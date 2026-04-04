<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            GovernorateSeeder::class,
            CustomerProfileSeeder::class,
            DriverSeeder::class,
            DriverDocumentSeeder::class,
            UserAddressSeeder::class,
            RestaurantSeeder::class,
            MenuSectionSeeder::class,
            MenuSectionRestaurantSeeder::class,
            SubMenuSectionSeeder::class,
            MenuItemSeeder::class,
            ItemExtraSeeder::class,
            CartSeeder::class,
            CouponSeeder::class,
            SystemSettingSeeder::class,
            GiftSeeder::class,
            SupportContactSeeder::class,
            AdSeeder::class,
            FavoriteSeeder::class,
            OrderSeeder::class,
            OrderItemSeeder::class,
            OrderItemExtraSeeder::class,   // ← الجديد
            DeliveryRequestSeeder::class,
            WalletTransactionSeeder::class,
            ReviewSeeder::class,
        ]);
    }
}
