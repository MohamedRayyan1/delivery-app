<?php

namespace App\Services\Customer;

use App\Repositories\Eloquent\CustomerCartRepository;
use Illuminate\Support\Facades\DB;

class CustomerCartService
{
    protected $repository;

    public function __construct(CustomerCartRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getCart(int $userId)
    {
        $cart = $this->repository->getCartWithDetails($userId);

        if (!$cart || $cart->items->isEmpty()) {
            return null;
        }

        $subtotal = 0;
        foreach ($cart->items as $cartItem) {
            $price = $cartItem->Item->discount_price ?? $cartItem->Item->price;
            $subtotal += ($price * $cartItem->quantity);
        }

        $deliveryFee = $cart->restaurant->delivery_cost ?? 0;
        $grandTotal = $subtotal + $deliveryFee;

        $cart->calculated_subtotal = $subtotal;
        $cart->calculated_delivery_fee = $deliveryFee;
        $cart->calculated_grand_total = $grandTotal;
        return $cart;
    }

    public function addItem(int $userId, array $data)
    {
        return DB::transaction(function () use ($userId, $data) {
            $cart = $this->repository->firstOrCreateCart($userId);

            if ($cart->restaurant_id !== null && $cart->restaurant_id !== (int)$data['restaurant_id']) {
                throw new \Exception('لا يمكن إضافة وجبات من مطعم مختلف. يرجى تفريغ السلة أولاً.');
            }

            if ($cart->restaurant_id === null) {
                $this->repository->updateCartRestaurant($cart->id, $data['restaurant_id']);
            }

            $existingItem = $this->repository->findItemInCart($cart->id, $data['item_id']);
            if ($existingItem) {
                $newQuantity = $existingItem->quantity + $data['quantity'];
                $notes = $data['notes'] ?? $existingItem->notes;
                $this->repository->updateItemQuantity($existingItem->id, $newQuantity, $notes);
            } else {
                $data['cart_id'] = $cart->id;
                $this->repository->addItemToCart($data);
            }

            return $this->getCart($userId);
        });
    }

    public function removeItem(int $userId, int $cartItemId)
    {
        return DB::transaction(function () use ($userId, $cartItemId) {
            $this->repository->removeItem($cartItemId);

            $cart = $this->repository->getCartWithDetails($userId);
            if ($cart && $cart->items->isEmpty()) {
                $this->repository->updateCartRestaurant($cart->id, null);
            }

            return $this->getCart($userId);
        });
    }

    public function clearCart(int $userId)
    {
        return DB::transaction(function () use ($userId) {
            $cart = $this->repository->getCartWithDetails($userId);
            if ($cart) {
                $this->repository->clearCart($cart->id);
                $this->repository->updateCartRestaurant($cart->id, null);
            }
            return null;
        });
    }
}
