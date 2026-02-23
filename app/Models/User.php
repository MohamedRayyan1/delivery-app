<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'name', 'phone', 'email', 'password',
        'role', 'city', 'fcm_token',
        'is_banned'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'password' => 'hashed',
        'is_banned' => 'boolean',
    ];

    // العلاقات
    public function addresses() {
        return $this->hasMany(UserAddress::class);
    }

    public function driverProfile() {
        return $this->hasOne(Driver::class);
    }

    public function customerProfile() {
        return $this->hasOne(CustomerProfile::class);
    }

    // Helper Method للتحقق من الرول بسهولة
    public function hasRole($role) {
        return $this->role === $role;
    }


public function favoriteRestaurants() {
    return $this->belongsToMany(Restaurant::class, 'favorite_restaurants', 'user_id', 'restaurant_id')
                ->withPivot('created_at');
}

public function favoriteItems() {
    return $this->belongsToMany(MenuItem::class, 'favorite_items', 'user_id', 'item_id')
                ->withPivot('created_at');
}
}
