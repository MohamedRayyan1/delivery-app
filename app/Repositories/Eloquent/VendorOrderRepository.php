<?php

namespace App\Repositories\Eloquent;

use App\Models\DeliveryRequest;
use App\Models\Order;

class VendorOrderRepository
{
    public function getRestaurantOrders(int $restaurantId, ?string $status, ?string $search, int $perPage)
    {
        return Order::select('id', 'user_id', 'status', 'grand_total', 'created_at')
            ->with([
                'user:id,name,phone',
                'items' => function ($query) {
                    $query->select('id', 'order_id', 'item_id', 'quantity')->with('Item:id,name');
                }
            ])
            ->where('restaurant_id', $restaurantId)
            ->when($status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->when($search, function ($query, $search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery->where('id', 'like', "%{$search}%")
                        ->orWhereHas('user', function ($userQuery) use ($search) {
                            $userQuery->where('name', 'like', "%{$search}%");
                        });
                });
            })
            ->orderBy('created_at', 'desc')
            ->cursorPaginate($perPage);
    }

    public function findByIdAndRestaurant(int $orderId, int $restaurantId)
    {
        return Order::where('id', $orderId)
            ->where('restaurant_id', $restaurantId)
            ->firstOrFail();
    }

    public function updateStatus(int $orderId, string $status)
    {
        return Order::where('id', $orderId)->update(['status' => $status]);
    }


    // App\Repositories\Eloquent\VendorOrderRepository.php

    public function findPreparingOrder(int $orderId, int $restaurantId)
    {
        return Order::where('id', $orderId)
            ->where('restaurant_id', $restaurantId)
            ->where('status', 'preparing')
            ->first();
    }

    public function createDeliveryRequest(array $data)
    {
        return DeliveryRequest::create($data);
    }
}
