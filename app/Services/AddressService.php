<?php

namespace App\Services;

use App\Repositories\Contracts\AddressRepositoryInterface;
use Illuminate\Support\Facades\DB;

class AddressService
{
    private AddressRepositoryInterface $addressRepo;

    public function __construct(AddressRepositoryInterface $addressRepo)
    {
        $this->addressRepo = $addressRepo;
    }

    public function getUserAddresses(int $userId)
    {
        return $this->addressRepo->getUserAddresses($userId);
    }

    public function createAddress(int $userId, array $data)
    {
        $data['user_id'] = $userId;

        return DB::transaction(function () use ($userId, $data) {
            if (isset($data['is_default']) && $data['is_default']) {
                $this->addressRepo->resetDefaultAddress($userId);
            }

            return $this->addressRepo->createAddress($data);
        });
    }

    public function updateAddress(int $addressId, int $userId, array $data)
    {
        return DB::transaction(function () use ($addressId, $userId, $data) {
            if (isset($data['is_default']) && $data['is_default']) {
                $this->addressRepo->resetDefaultAddress($userId);
            }

            return $this->addressRepo->updateAddress($addressId, $userId, $data);
        });
    }

    public function deleteAddress(int $addressId, int $userId)
    {
        return $this->addressRepo->deleteAddress($addressId, $userId);
    }

    public function setDefault(int $addressId, int $userId)
    {
        return DB::transaction(function () use ($addressId, $userId) {
            $this->addressRepo->resetDefaultAddress($userId);
            return $this->addressRepo->updateAddress($addressId, $userId, ['is_default' => true]);
        });
    }
}
