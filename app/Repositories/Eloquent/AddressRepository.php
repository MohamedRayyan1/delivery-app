<?php

namespace App\Repositories\Eloquent;

use App\Models\UserAddress;
use App\Repositories\Contracts\AddressRepositoryInterface;

class AddressRepository implements AddressRepositoryInterface
{
    public function getUserAddresses(int $userId)
    {
        return UserAddress::where('user_id', $userId)->get();
    }

    public function findUserAddress(int $addressId, int $userId)
    {
        return UserAddress::where('id', $addressId)->where('user_id', $userId)->first();
    }

    public function createAddress(array $data)
    {
        return UserAddress::create($data);
    }

    public function updateAddress(int $addressId, int $userId, array $data)
    {
        $address = $this->findUserAddress($addressId, $userId);

        if ($address) {
            $address->update($data);
            return $address;
        }

        return null;
    }

    public function deleteAddress(int $addressId, int $userId)
    {
        $address = $this->findUserAddress($addressId, $userId);

        if ($address) {
            return $address->delete();
        }

        return false;
    }

    public function resetDefaultAddress(int $userId)
    {
        UserAddress::where('user_id', $userId)->update(['is_default' => false]);
    }
}
