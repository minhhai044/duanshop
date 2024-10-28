<?php

namespace App\Repositories;

use App\Models\Color;

class ColorRepository
{
    protected $color;
    public function __construct(
        Color $color
    ) {
        $this->color = $color;
    }
    public function get()
    {
        return $this->color->get();
    }
    public function find($id)
    {
        return $this->color->find($id);
    }
    public function create($data)
    {
        return $this->color->create($data);
    }
    public function update($id, $data)
    {
        $update = $this->color->find($id);
        $update->update($data);
        return $update;
    }
    public function delete($id)
    {
        $delete = $this->color->find($id);
        $delete->delete();
        return $delete;
    }
    public function pluck($column, $key)
    {
        return $this->color->pluck($column, $key)->all();
    }
}
