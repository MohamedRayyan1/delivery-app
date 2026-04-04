<?php

namespace App\Services\Customer;

use App\Repositories\Eloquent\CustomerCartRepository;
use Illuminate\Support\Facades\DB;
use Exception;

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
            $basePrice = $cartItem->Item->discount_price ?? $cartItem->Item->price;

            $extrasPrice = 0;
            foreach ($cartItem->extras as $extra) {
                $extrasPrice += $extra->price;
            }

            $itemTotal = ($basePrice + $extrasPrice) * $cartItem->quantity;
            $subtotal += $itemTotal;

            $cartItem->calculated_item_price = $basePrice + $extrasPrice;
            $cartItem->calculated_total_price = $itemTotal;
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
                throw new Exception('لا يمكن إضافة وجبات من مطعم مختلف. يرجى تفريغ السلة أولاً.');
            }

            if ($cart->restaurant_id === null) {
                $this->repository->updateCartRestaurant($cart->id, $data['restaurant_id']);
            }

            $extrasIds = $data['extras_ids'] ?? [];

            $existingItem = $this->repository->findIdenticalItemInCart($cart->id, $data['item_id'], $extrasIds);

            if ($existingItem) {
                $newQuantity = $existingItem->quantity + $data['quantity'];
                $notes = $data['notes'] ?? $existingItem->notes;
                $this->repository->updateItemQuantity($existingItem->id, $newQuantity, $notes);
            } else {
                $cartItemData = [
                    'cart_id' => $cart->id,
                    'item_id' => $data['item_id'],
                    'quantity' => $data['quantity'],
                    'notes' => $data['notes'] ?? null,
                ];
                $newItem = $this->repository->addItemToCart($cartItemData);

                if (!empty($extrasIds)) {
                    $this->repository->syncItemExtras($newItem->id, $extrasIds);
                }
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

    public function decrementItemQuantity(int $userId, int $cartItemId)
    {
        return DB::transaction(function () use ($userId, $cartItemId) {
            $cartItem = $this->repository->getCartItemById($cartItemId);

            if (!$cartItem) {
                throw new Exception('الوجبة غير موجودة في السلة.');
            }

            $cart = $this->repository->firstOrCreateCart($userId);
            if ($cartItem->cart_id !== $cart->id) {
                throw new Exception('هذه الوجبة لا تنتمي لسلتك.');
            }

            if ($cartItem->quantity > 1) {
                $newQuantity = $cartItem->quantity - 1;
                $this->repository->updateItemQuantity($cartItem->id, $newQuantity, $cartItem->notes);
                return $this->getCart($userId);
            }

            return $this->removeItem($userId, $cartItemId);
        });
    }
}
