<?php

namespace App\Services;

use App\Repositories\ColorRepository;

class ColorService
{
    protected $colorRepository;
    public function __construct(
        ColorRepository $colorRepository
    ) {
        $this->colorRepository = $colorRepository;
    }
    public function getColor()
    {
        return $this->colorRepository->get();
    }
    public function findIdColor($id)
    {
        return $this->colorRepository->find($id);
    }
    public function createColor($data)
    {
        return $this->colorRepository->create($data);
    }
    public function updateColor($id, $data)
    {
        return $this->colorRepository->update($id, $data);
    }
    public function deleteColor($id)
    {
        return $this->colorRepository->delete($id);
    }
    public function pluckColor($column, $key)
    {
        return $this->colorRepository->pluck($column, $key);
    }
}
