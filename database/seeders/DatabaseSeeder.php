<?php
// database/seeders/DatabaseSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // الاستدعاء من مكان واحد + الترتيب الصحيح للعلاقات (الجداول الأساسية أولاً)
        $this->call([
            UserSeeder::class,
            GovernorateSeeder::class,
            CustomerProfileSeeder::class,
            DriverSeeder::class,           // ← أولاً
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
            DeliveryRequestSeeder::class,
            WalletTransactionSeeder::class,
            ReviewSeeder::class,
        ]);
    }
}
