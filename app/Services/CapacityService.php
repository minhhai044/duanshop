<?php

namespace App\Services;

use App\Repositories\CapacityRepository;

class CapacityService
{
    protected $capacityRepository;
    public function __construct(
        CapacityRepository $capacityRepository
    ) {
        $this->capacityRepository = $capacityRepository;
    }
    public function getCapacity()
    {
        return $this->capacityRepository->get();
    }
    public function createCapacity($data)
    {
        return $this->capacityRepository->create($data);
    }
    public function findIdCapacity($id)
    {
        return $this->capacityRepository->find($id);
    }
    public function updateCapacity($id, $data)
    {
        return $this->capacityRepository->update($id, $data);
    }
    public function deleteCapacity($id)
    {
        return $this->capacityRepository->delete($id);
    }
    public function pluckCapacity($column,$key){
        return $this->capacityRepository->pluck($column,$key);
    }
}
