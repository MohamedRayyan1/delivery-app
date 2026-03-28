<?php

namespace App\Repositories\Eloquent;

use App\Models\User;
use App\Models\Driver;
use App\Models\DriverDocument;

class DriverRegistrationRepository
{
    public function createUser(array $data)
    {
        return User::create($data);
    }

    public function createDriver(array $data)
    {
        return Driver::create($data);
    }

    public function createDocument(array $data)
    {
        return DriverDocument::create($data);
    }

    
}
