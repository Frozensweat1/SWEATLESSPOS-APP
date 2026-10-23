<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use RuntimeException;

class DatabaseMaintenanceService
{
    public const DEFAULT_STORE_ID = 1;

    // Configuration and access-control tables are never wiped.
    private const PRESERVED_TABLES = [
        'migrations',
        'roles',
        'permissions',
        'role_has_permissions',
        'model_has_permissions',
        'settings',
        'website_settings',
        'website_pages',
        'sessions',
        'cache',
        'cache_locks',
    ];

    // Tables with rows tied to users; only rows of retained users survive.
    private const USER_SCOPED_TABLES = [
        'model_has_roles' => ['model_id', true],
        'personal_access_tokens' => ['tokenable_id', true],
        'personal_settings' => ['user_id', false],
    ];

    public function backupDirectory(): string
    {
        $dir = storage_path('app/backups');
        File::ensureDirectoryExists($dir);

        return $dir;
    }

    public function backups(): array
    {
        return collect(File::files($this->backupDirectory()))
            ->filter(fn ($f) => in_array($f->getExtension(), ['sql', 'sqlite']))
            ->sortByDesc(fn ($f) => $f->getMTime())
            ->map(fn ($f) => [
                'name' => $f->getFilename(),
                'size' => $f->getSize(),
                'created_at' => $f->getMTime(),
            ])
            ->values()
            ->all();
    }

    public function backupPath(string $name): ?string
    {
        $name = basename($name);
        $path = $this->backupDirectory() . DIRECTORY_SEPARATOR . $name;

        return preg_match('/^[\w.\-]+\.(sql|sqlite)$/', $name) && is_file($path) ? $path : null;
    }

    public function deleteBackup(string $name): bool
    {
        $path = $this->backupPath($name);

        return $path ? File::delete($path) : false;
    }

    public function createBackup(string $prefix = 'backup'): string
    {
        $driver = DB::connection()->getDriverName();
        $stamp = now()->format('Y-m-d_His');
        $dir = $this->backupDirectory();

        if ($driver === 'sqlite') {
            $path = "{$dir}/{$prefix}_{$stamp}.sqlite";
            DB::statement('VACUUM INTO ' . DB::getPdo()->quote($path));

            return basename($path);
        }

        if (in_array($driver, ['mysql', 'mariadb'], true)) {
            $path = "{$dir}/{$prefix}_{$stamp}.sql";
            $this->dumpMysql($path);

            return basename($path);
        }

        throw new RuntimeException("Backups are not supported for the [{$driver}] database driver.");
    }

    private function dumpMysql(string $path): void
    {
        $pdo = DB::getPdo();
        $handle = fopen($path, 'wb');
        if (! $handle) {
            throw new RuntimeException('Unable to write the backup file.');
        }

        try {
            fwrite($handle, "-- Sweatless POS backup " . now()->toDateTimeString() . "\nSET FOREIGN_KEY_CHECKS=0;\nSET NAMES utf8mb4;\n\n");

            foreach (DB::select('SHOW FULL TABLES WHERE Table_type = "BASE TABLE"') as $row) {
                $table = array_values((array) $row)[0];
                $quoted = '`' . str_replace('`', '``', $table) . '`';
                $create = array_values((array) DB::select("SHOW CREATE TABLE {$quoted}")[0])[1];

                fwrite($handle, "DROP TABLE IF EXISTS {$quoted};\n{$create};\n\n");

                DB::table($table)->orderByRaw('1')->chunk(500, function ($rows) use ($handle, $pdo, $quoted) {
                    foreach ($rows as $r) {
                        $values = array_map(
                            fn ($v) => $v === null ? 'NULL' : $pdo->quote((string) $v),
                            array_values((array) $r)
                        );
                        fwrite($handle, "INSERT INTO {$quoted} VALUES (" . implode(',', $values) . ");\n");
                    }
                });

                fwrite($handle, "\n");
            }

            fwrite($handle, "SET FOREIGN_KEY_CHECKS=1;\n");
        } catch (\Throwable $e) {
            fclose($handle);
            File::delete($path);
            throw $e;
        }

        fclose($handle);
    }

    /**
     * Wipes all business data, keeping super-admin users, the default store,
     * roles/permissions and system/website configuration.
     *
     * @return array{tables: int, users_removed: int, stores_removed: int}
     */
    public function truncateData(): array
    {
        $keepUserIds = DB::table('model_has_roles')
            ->join('roles', 'roles.id', '=', 'model_has_roles.role_id')
            ->where('roles.name', 'super-admin')
            ->where('model_has_roles.model_type', \App\Models\User::class)
            ->pluck('model_has_roles.model_id')
            ->all();

        if (empty($keepUserIds)) {
            throw new RuntimeException('No super-admin user found; refusing to truncate.');
        }

        if (! DB::table('stores')->where('id', self::DEFAULT_STORE_ID)->exists()) {
            throw new RuntimeException('Default store not found; refusing to truncate.');
        }

        $skip = array_merge(
            self::PRESERVED_TABLES,
            array_keys(self::USER_SCOPED_TABLES),
            ['users', 'stores']
        );

        $result = ['tables' => 0, 'users_removed' => 0, 'stores_removed' => 0];

        Schema::disableForeignKeyConstraints();
        try {
            foreach (Schema::getTableListing() as $table) {
                $table = str_contains($table, '.') ? substr($table, strrpos($table, '.') + 1) : $table;
                if (in_array($table, $skip, true)) {
                    continue;
                }
                DB::table($table)->truncate();
                $result['tables']++;
            }

            foreach (self::USER_SCOPED_TABLES as $table => [$column, $_]) {
                if (Schema::hasTable($table) && Schema::hasColumn($table, $column)) {
                    DB::table($table)->whereNotIn($column, $keepUserIds)->delete();
                }
            }

            $result['users_removed'] = DB::table('users')->whereNotIn('id', $keepUserIds)->delete();
            $result['stores_removed'] = DB::table('stores')->where('id', '!=', self::DEFAULT_STORE_ID)->delete();

            DB::table('users')->whereIn('id', $keepUserIds)->update(['store_id' => self::DEFAULT_STORE_ID]);
        } finally {
            Schema::enableForeignKeyConstraints();
        }

        return $result;
    }
}
