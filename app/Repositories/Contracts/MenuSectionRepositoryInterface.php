<?php

namespace App\Repositories\Contracts;

use Illuminate\Support\Collection;

interface MenuSectionRepositoryInterface
{
    public function all(): Collection;

    public function findById(int $id);
}

