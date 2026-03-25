<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use App\Services\Customer\CustomerSectionService;
use App\Http\Resources\Customer\CustomerMenuSectionResource;
use App\Http\Resources\Customer\CustomerSectionRestaurantsResource;
use App\Http\Resources\Customer\CustomerSectionItemResource;

class CustomerSectionController extends Controller
{
    protected $service;

    public function __construct(CustomerSectionService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $sections = $this->service->listSections();

        return $this->successResponse(
            CustomerMenuSectionResource::collection($sections)
        );
    }

    public function restaurants(int $sectionId)
    {
        $data = $this->service->getSectionRestaurants($sectionId);

        return $this->successResponse([
            'section' => new CustomerMenuSectionResource($data['section']),
            'items' => CustomerSectionItemResource::collection($data['items']),
            'restaurants' => CustomerSectionRestaurantsResource::collection($data['restaurants']),
        ]);
    }
}
