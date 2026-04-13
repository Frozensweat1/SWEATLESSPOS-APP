<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WebsiteSetting extends Model
{
    protected $fillable = ['key', 'value', 'type'];
    protected $table = 'website_settings';
    public $timestamps = true;

    /**
     * Get setting value by key
     */
    public static function get(string $key, $default = null)
    {
        $setting = static::where('key', $key)->first();
        return $setting ? $setting->getValue() : $default;
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
