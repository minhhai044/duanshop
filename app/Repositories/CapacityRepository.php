<?php

namespace App\Repositories;

use App\Models\Capacity;

class CapacityRepository
{
    protected $capacity;
    public function __construct(
        Capacity $capacity
    ) {
        $this->capacity = $capacity;
    }
    public function get()
    {
        return $this->capacity->get();
    }
    public function create($data)
    {
        return $this->capacity->create($data);
    }
    public function find($id)
    {
        return $this->capacity->find($id);
    }
    public function update($id, $data)
    {
        $update = $this->capacity->find($id);
        $update->update($data);
        return $update;
    }
    public function delete($id)
    {
        $delete = $this->capacity->find($id);
        $delete->delete();
        return $delete;
    }
    public function pluck($column, $key)
    {
        return $this->capacity->pluck($column, $key)->all();
    }
}
