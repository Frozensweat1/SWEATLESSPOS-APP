<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WebsiteSetting extends Model
{
    protected $fillable = ['key', 'value', 'type'];
    protected $table = 'website_settings';
    public $timestamps = true;

    protected static ?array $memo = null;

    /**
     * Get setting value by key (all settings are loaded once per request).
     */
    public static function get(string $key, $default = null)
    {
        static::$memo ??= static::getAllSettings();

        return array_key_exists($key, static::$memo) && static::$memo[$key] !== null
            ? static::$memo[$key]
            : $default;
    }

    /**
     * CSS background value for hero sections: an uploaded image or a gradient fallback.
     */
    public static function backgroundCss(string $key, string $gradient = 'linear-gradient(135deg, #4f46e5, #7c3aed)'): string
    {
        $path = static::get($key);

        return $path ? 'url("' . asset('storage/' . ltrim($path, '/')) . '")' : $gradient;
    }

    /**
     * Get parsed value based on type
     */
    public function getValue()
    {
        return match ($this->type) {
            'boolean' => $this->value === '1' || $this->value === 'true',
            'json' => json_decode($this->value, true),
            'string' => (string) $this->value,
            default => $this->value,
        };
    }

    /**
     * Set value (automatically detects type)
     */
    public static function set(string $key, $value)
    {
        $type = 'string';
        $storedValue = $value;

        if (is_bool($value)) {
            $type = 'boolean';
            $storedValue = $value ? '1' : '0';
        } elseif (is_array($value)) {
            $type = 'json';
            $storedValue = json_encode($value);
        }

        static::$memo = null;

        return static::updateOrCreate(
            ['key' => $key],
            ['value' => $storedValue, 'type' => $type]
        );
    }

    /**
     * Get all settings as key-value pairs
     */
    public static function getAllSettings()
    {
        return static::all()->reduce(function ($carry, $item) {
            $carry[$item->key] = $item->getValue();
            return $carry;
        }, []);
    }
}
