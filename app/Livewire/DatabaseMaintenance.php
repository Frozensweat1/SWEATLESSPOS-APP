<?php

namespace App\Livewire;

use App\Services\DatabaseMaintenanceService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Component;

class DatabaseMaintenance extends Component
{
    public string $confirmText = '';
    public string $password = '';
    public bool $backupFirst = true;

    private function authorizeAction(): void
    {
        abort_unless(Auth::user()?->hasRole('super-admin'), 403);
    }

    private function notify(string $title, string $text, bool $success = true): void
    {
        $alert = LivewireAlert::title($title)->text($text);
        ($success ? $alert->success() : $alert->error())
            ->toast()->position('top-end')->show();
    }

    public function createBackup(DatabaseMaintenanceService $service): void
    {
        $this->authorizeAction();

        try {
            $name = $service->createBackup();
            $this->notify('Backup created', $name);
        } catch (\Throwable $e) {
            report($e);
            $this->notify('Backup failed', $e->getMessage(), false);
        }
    }

    public function deleteBackup(string $name, DatabaseMaintenanceService $service): void
    {
        $this->authorizeAction();

        $service->deleteBackup($name)
            ? $this->notify('Deleted', 'Backup removed.')
            : $this->notify('Not found', 'Backup file does not exist.', false);
    }

    public function truncateData(DatabaseMaintenanceService $service): void
    {
        $this->authorizeAction();

        $this->validate([
            'confirmText' => ['required', 'in:DELETE ALL DATA'],
            'password' => ['required'],
        ], [
            'confirmText.in' => 'Type DELETE ALL DATA exactly to confirm.',
        ]);

        if (! Hash::check($this->password, Auth::user()->password)) {
            $this->addError('password', 'Incorrect password.');
            return;
        }

        try {
            $backup = $this->backupFirst ? $service->createBackup('pre-truncate') : null;
            $result = $service->truncateData();

            logger()->warning('Database truncated', ['by' => Auth::id(), 'backup' => $backup] + $result);

            $this->reset(['confirmText', 'password']);
            $this->notify(
                'Data cleared',
                "{$result['tables']} tables emptied, {$result['users_removed']} users and {$result['stores_removed']} stores removed."
                    . ($backup ? " Backup: {$backup}" : '')
            );
        } catch (\Throwable $e) {
            report($e);
            $this->notify('Truncate failed', $e->getMessage(), false);
        }
    }

    public function render(DatabaseMaintenanceService $service)
    {
        return view('livewire.database-maintenance', ['backups' => $service->backups()]);
    }
}
