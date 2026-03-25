<?php

namespace App\Services\Admin;

use App\Repositories\Eloquent\AdminGiftRepository;

class AdminGiftService
{
    protected $repository;

    public function __construct(AdminGiftRepository $repository)
    {
        $this->repository = $repository;
    }

    public function listGifts()
    {
        return $this->repository->getAllGifts();
    }

    public function storeGift(array $data)
    {
        return $this->repository->createGift($data);
    }

    public function updateGift(int $id, array $data)
    {
        return $this->repository->updateGift($id, $data);
    }

    public function deleteGift(int $id)
    {
        return $this->repository->deleteGift($id);
    }
}
