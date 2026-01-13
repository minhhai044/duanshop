<?php

namespace App\Services;

use App\Models\Capacity;
use Illuminate\Support\Facades\Cache;

/**
 * Class CapacityService
 * @package App\Services
 */
class CapacityService
{
    /**
     * Get all capacities
     */
    public function getCapacity()
    {
        return Capacity::orderBy('created_at', 'desc')->get();
    }

    /**
     * Create a new capacity
     */
    public function createCapacity($data)
    {
        // Auto generate slug if empty
        if (empty($data['slug'])) {
            $data['slug'] = generateSlug($data['cap_name']);
        }
        
        // Set default is_active
        $data['is_active'] = $data['is_active'] ?? true;
        
        $capacity = Capacity::create($data);
        Cache::forget('capacities');
        return $capacity;
    }

    /**
     * Find capacity by ID
     */
    public function findIdCapacity($id)
    {
        return Capacity::findOrFail($id);
    }

    /**
     * Update capacity
     */
    public function updateCapacity($id, $data)
    {
        $capacity = Capacity::findOrFail($id);
        
        // Auto generate slug if empty
        if (empty($data['slug'])) {
            $data['slug'] = generateSlug($data['cap_name']);
        }
        
        // Set default is_active
        $data['is_active'] = $data['is_active'] ?? $capacity->is_active;
        
        $capacity->update($data);
        Cache::forget('capacities');
        return $capacity;
    }

    /**
     * Get capacities for dropdown/select options
     */
    public function pluckCapacity($column, $key)
    {
        return Capacity::where('is_active', true)->pluck($column, $key);
    }

    /**
     * Get active capacities
     */
    public function getActiveCapacities()
    {
        return Cache::remember('active_capacities', 3600, function () {
            return Capacity::where('is_active', true)
                ->orderBy('cap_name')
                ->get();
        });
    }

    /**
     * Toggle capacity status
     */
    public function toggleStatus($id)
    {
        $capacity = Capacity::findOrFail($id);
        $capacity->update(['is_active' => !$capacity->is_active]);
        Cache::forget('capacities');
        Cache::forget('active_capacities');
        return $capacity;
    }
}
