<?php

namespace App\Services;

use App\Models\Color;
use Illuminate\Support\Facades\Cache;

/**
 * Class ColorService
 * @package App\Services
 */
class ColorService
{
    /**
     * Get all colors
     */
    public function getColor()
    {
        return Color::orderBy('created_at', 'desc')->get();
    }

    /**
     * Create a new color
     */
    public function createColor($data)
    {
        // Auto generate slug if empty
        if (empty($data['slug'])) {
            $data['slug'] = generateSlug($data['color_name']);
        }
        
        // Set default is_active
        $data['is_active'] = $data['is_active'] ?? true;
        
        $color = Color::create($data);
        Cache::forget('colors');
        return $color;
    }

    /**
     * Find color by ID
     */
    public function findIdColor($id)
    {
        return Color::findOrFail($id);
    }

    /**
     * Update color
     */
    public function updateColor($id, $data)
    {
        $color = Color::findOrFail($id);
        
        // Auto generate slug if empty
        if (empty($data['slug'])) {
            $data['slug'] = generateSlug($data['color_name']);
        }
        
        // Set default is_active
        $data['is_active'] = $data['is_active'] ?? $color->is_active;
        
        $color->update($data);
        Cache::forget('colors');
        return $color;
    }

    /**
     * Get colors for dropdown/select options
     */
    public function pluckColor($column, $key)
    {
        return Color::where('is_active', true)->pluck($column, $key);
    }

    /**
     * Get active colors
     */
    public function getActiveColors()
    {
        return Cache::remember('active_colors', 3600, function () {
            return Color::where('is_active', true)
                ->orderBy('color_name')
                ->get();
        });
    }

    /**
     * Toggle color status
     */
    public function toggleStatus($id)
    {
        $color = Color::findOrFail($id);
        $color->update(['is_active' => !$color->is_active]);
        Cache::forget('colors');
        Cache::forget('active_colors');
        return $color;
    }
}
