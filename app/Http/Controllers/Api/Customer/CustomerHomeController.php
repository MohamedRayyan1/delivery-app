<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use App\Services\Customer\CustomerHomeService;
use App\Http\Resources\Customer\CustomerAdResource;
use App\Http\Resources\Customer\CustomerHomeSectionResource;
use App\Http\Resources\Customer\CustomerHomeItemResource;
use Illuminate\Http\Request;

class CustomerHomeController extends Controller
{
    // لم نعد نستخدم هذا الكنترولر في الراوتات بعد استبدال Home بـ APIات الأقسام
}
