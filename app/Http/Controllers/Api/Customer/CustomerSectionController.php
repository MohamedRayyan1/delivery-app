<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use App\Http\Resources\Customer\CustomerMenuSectionResource;
use App\Http\Resources\Customer\CustomerSectionRestaurantsResource;
use App\Services\Customer\CustomerSectionService;

class CustomerSectionController extends Controller
{
    protected CustomerSectionService $service;

    public function __construct(CustomerSectionService $service)
    {
        $this->service = $service;
    }

    /**
     * API: جلب جميع أقسام المنيو (menu_sections)
     */
    public function index()
    {
        $sections = $this->service->listSections();

        return $this->successResponse(
            CustomerMenuSectionResource::collection($sections)
        );
    }

    /**
     * API: جلب مطاعم القسم (عادي + الأكثر طلباً) بناء على menu_section_id
     */
    public function restaurants(int $sectionId)
    {
        $data = $this->service->getSectionRestaurants($sectionId);

        return $this->successResponse([
            'section' => new CustomerMenuSectionResource($data['section']),
            'restaurants' => CustomerSectionRestaurantsResource::collection($data['restaurants']),
            'popular_restaurants' => CustomerSectionRestaurantsResource::collection($data['popular_restaurants']),
        ]);
    }
}

