<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'key',
        'category',
        'type',
        'value',
        'description'
    ];

    /**
     * Get a setting value by key and category
     */
    public static function getValue($key, $category = 'general', $default = null)
    {
        $setting = self::where('key', $key)
                      ->where('category', $category)
                      ->first();
        
        return $setting ? $setting->value : $default;
    }

    /**
     * Set a setting value
     */
    public static function setValue($key, $value, $category = 'general', $type = 'text', $description = null)
    {
        return self::updateOrCreate(
            ['key' => $key, 'category' => $category],
            [
                'value' => $value,
                'type' => $type,
                'description' => $description
            ]
        );
    }

    /**
     * Get all settings for a category
     */
    public static function getByCategory($category)
    {
        return self::where('category', $category)->get();
    }
}
