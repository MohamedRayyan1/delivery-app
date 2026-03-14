<?php

namespace App\Services\Customer;

use App\Repositories\Eloquent\CustomerHomeRepository;
use Illuminate\Support\Facades\Cache;

class CustomerHomeService
{
    protected $repository;

    public function __construct(CustomerHomeRepository $repository)
    {
        $this->repository = $repository;
    }

    // لم نعد نستخدم home كـ API موحد، لكن نُبقي الخدمة مؤقتاً في حال استُخدمت داخلياً لاحقاً
}

