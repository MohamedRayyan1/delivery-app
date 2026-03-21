<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Faker\Factory as Faker;

class IntegratedDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('ar_SA');
        $now = Carbon::now();

        // 1. تنظيف الجداول لمنع التكرار (ترتيب الحذف مهم لتجنب أخطاء المفاتيح الأجنبية)
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('driver_daily_stats')->truncate();
        DB::table('reviews')->truncate();
        DB::table('orders')->truncate();
        DB::table('user_addresses')->truncate();
        DB::table('drivers')->truncate();
        // نفترض وجود جدول مطاعم لأن جدول الطلبات والتقييمات يعتمد عليه
        if (DB::getSchemaBuilder()->hasTable('restaurants')) {
            DB::table('restaurants')->truncate();
        }
        DB::table('users')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 2. إنشاء المطاعم ومديريها (لضمان تكامل المفاتيح الأجنبية)
     // 2. إنشاء المطاعم ومديريها (لضمان تكامل المفاتيح الأجنبية)
        $restaurantIds = [];
        if (DB::getSchemaBuilder()->hasTable('restaurants')) {
            $governorates = ['الرياض', 'مكة المكرمة', 'المنطقة الشرقية', 'القصيم'];

            for ($i = 1; $i <= 5; $i++) {
                $selectedGov = $faker->randomElement($governorates);

                // أ. إنشاء حساب مدير للمطعم
                $managerId = DB::table('users')->insertGetId([
                    'name' => 'مدير مطعم ' . $faker->firstName,
                    'phone' => '05' . $faker->unique()->randomNumber(8, true),
                    'email' => 'manager'.$i.'_'.uniqid().'@example.com',
                    'password' => Hash::make('password123'),
                    'role' => 'restaurant_manager', //
                    'city' => $faker->city, //
                    'is_banned' => false, //
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);

                // ب. إنشاء المطعم مع كافة الحقول الإلزامية
                $restaurantIds[] = DB::table('restaurants')->insertGetId([
                    'name' => 'مطعم ' . $faker->company,
                    'manager_user_id' => $managerId,
                    'governorate' => $selectedGov, // حل الخطأ الحالي
                    'city' => $faker->city,         // لتجنب خطأ مشابه مستقبلاً
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }

        // 3. إنشاء العملاء (Customers) وعناوينهم
        $customerData = [];
        for ($i = 1; $i <= 10; $i++) {
            $userId = DB::table('users')->insertGetId([
                'name' => $faker->name,
                'phone' => '05' . $faker->unique()->randomNumber(8, true),
                'email' => $faker->unique()->safeEmail,
                'password' => Hash::make('password123'),
                'role' => 'customer',
                'city' => 'الرياض',
                'is_banned' => false,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            $addressId = DB::table('user_addresses')->insertGetId([
                'user_id' => $userId,
                'label' => 'المنزل',
                'street' => $faker->streetName,
                'lat' => $faker->latitude(24.5, 24.9), // إحداثيات الرياض تقريباً
                'lng' => $faker->longitude(46.5, 46.9),
                'is_default' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            $customerData[] = ['user_id' => $userId, 'address_id' => $addressId];
        }

        // 4. إنشاء السائقين (Drivers) وملفاتهم
        $driverIds = [];
        for ($i = 1; $i <= 5; $i++) {
            $userId = DB::table('users')->insertGetId([
                'name' => 'السائق ' . $faker->firstName,
                'phone' => '05' . $faker->unique()->randomNumber(8, true),
                'email' => $faker->unique()->safeEmail,
                'password' => Hash::make('password123'),
                'role' => 'driver',
                'city' => 'الرياض',
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            $driverId = DB::table('drivers')->insertGetId([
                'user_id' => $userId,
                'is_online' => true,
                'account_status' => 'active',
                'vehicle_type' => $faker->randomElement(['car', 'motorcycle']),
                'vehicle_plate_number' => $faker->bothify('???-####'),
                'current_lat' => $faker->latitude(24.5, 24.9),
                'current_lng' => $faker->longitude(46.5, 46.9),
                'total_earnings' => 0, // سيتم تحديثه لاحقاً
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            $driverIds[] = $driverId;
        }

        // 5. إنشاء 50 طلب (Orders) وتقييماتها وإحصائياتها بدقة عالية
        $driverStatsTracker = []; // لتتبع الأرقام الدقيقة للإحصائيات اليومية

        for ($i = 1; $i <= 50; $i++) {
            $customer = $faker->randomElement($customerData);
            $driverId = $faker->randomElement($driverIds);
            $restaurantId = $faker->randomElement($restaurantIds);

            // حسابات مالية دقيقة للطلب
            $subtotal = $faker->randomFloat(2, 30, 200);
            $deliveryFee = $faker->randomFloat(2, 10, 25);
            $discount = 0;
            $grandTotal = ($subtotal + $deliveryFee) - $discount;

            $date = Carbon::now()->subDays(rand(0, 5)); // طلبات خلال آخر 5 أيام
            $dateString = $date->format('Y-m-d');

            // إدخال الطلب
            $orderId = DB::table('orders')->insertGetId([
                'user_id' => $customer['user_id'],
                'restaurant_id' => $restaurantId,
                'driver_id' => $driverId,
                'address_id' => $customer['address_id'],
                'status' => 'delivered', // نفترض أن الـ 50 طلب مكتملة لبناء الإحصائيات
                'payment_method' => $faker->randomElement(['cash', 'card']),
                'payment_status' => 'paid',
                'subtotal' => $subtotal,
                'delivery_fee' => $deliveryFee,
                'discount_amount' => $discount,
                'grand_total' => $grandTotal,
                'applied_restaurant_commission' => 15.00,
                'applied_driver_share' => 100.00,
                'picked_up_at' => (clone $date)->addMinutes(15),
                'delivered_at' => (clone $date)->addMinutes(45),
                'paid_at' => (clone $date)->addMinutes(45),
                'created_at' => $date,
                'updated_at' => (clone $date)->addMinutes(45),
            ]);

            // إدخال التقييم المرتبط بالطلب حصرياً
            $driverRating = $faker->randomFloat(1, 3.5, 5.0); // تقييم من 3.5 إلى 5
            DB::table('reviews')->insert([
                'user_id' => $customer['user_id'],
                'order_id' => $orderId,
                'restaurant_id' => $restaurantId,
                'driver_id' => $driverId,
                'restaurant_rating' => $faker->randomFloat(1, 3, 5),
                'driver_rating' => $driverRating,
                'comment' => $faker->realText(50),
                'created_at' => $date,
                'updated_at' => $date,
            ]);

            // تجميع وتحديث بيانات الإحصائيات اليومية للسائق محلياً (في المصفوفة)
            if (!isset($driverStatsTracker[$driverId][$dateString])) {
                $driverStatsTracker[$driverId][$dateString] = [
                    'earnings' => 0,
                    'completed_orders' => 0,
                    'rating_sum' => 0,
                    'rating_count' => 0,
                ];
            }

            // نضيف أرباح التوصيل للسائق (حسب نسبة السائق وهي هنا 100%)
            $driverStatsTracker[$driverId][$dateString]['earnings'] += $deliveryFee;
            $driverStatsTracker[$driverId][$dateString]['completed_orders'] += 1;
            $driverStatsTracker[$driverId][$dateString]['rating_sum'] += $driverRating;
            $driverStatsTracker[$driverId][$dateString]['rating_count'] += 1;
        }

        // 6. حقن الإحصائيات اليومية (Driver Daily Stats) في قاعدة البيانات
        $driverTotalEarningsTracker = [];

        foreach ($driverStatsTracker as $driverId => $dates) {
            if (!isset($driverTotalEarningsTracker[$driverId])) {
                $driverTotalEarningsTracker[$driverId] = 0;
            }

            foreach ($dates as $dateStr => $stats) {
                DB::table('driver_daily_stats')->insert([
                    'driver_id' => $driverId,
                    'stat_date' => $dateStr,
                    'earnings' => $stats['earnings'],
                    'completed_orders' => $stats['completed_orders'],
                    'rating_sum' => $stats['rating_sum'],
                    'rating_count' => $stats['rating_count'],
                    'created_at' => Carbon::parse($dateStr),
                    'updated_at' => Carbon::parse($dateStr),
                ]);

                // تجميع إجمالي الأرباح لتحديث جدول السائقين الأساسي
                $driverTotalEarningsTracker[$driverId] += $stats['earnings'];
            }
        }

        // 7. تحديث إجمالي أرباح السائقين النهائي في جدول (drivers)
        foreach ($driverTotalEarningsTracker as $driverId => $totalEarnings) {
            DB::table('drivers')
                ->where('id', $driverId)
                ->update(['total_earnings' => $totalEarnings]);
        }
    }
}
