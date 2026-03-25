<?php

namespace App\Services\Customer;

use App\Repositories\Eloquent\CustomerSearchRepository;

class CustomerSearchService
{
    protected $repository;

    public function __construct(CustomerSearchRepository $repository)
    {
        $this->repository = $repository;
    }

    public function searchMeals(string $keyword)
    {
        return $this->repository->searchItemsGroupedByRestaurant($keyword);
    }
}
