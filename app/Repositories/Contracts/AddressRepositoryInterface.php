<?php

namespace App\Repositories\Contracts;

interface AddressRepositoryInterface
{
    public function getUserAddresses(int $userId);
    public function findUserAddress(int $addressId, int $userId);
    public function createAddress(array $data);
    public function updateAddress(int $addressId, int $userId, array $data);
    public function deleteAddress(int $addressId, int $userId);
    public function resetDefaultAddress(int $userId);
}
