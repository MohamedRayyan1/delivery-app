<?php
// database/seeders/RestaurantSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RestaurantSeeder extends Seeder
{
    public function run(): void
    {
        // جلب جميع الـ IDs للمستخدمين الذين لديهم رتبة vendor
        // ونقوم بعمل shuffle (ترتيب عشوائي) للمصفوفة لضمان توزيع مختلف في كل مرة
        $managerIds = DB::table('users')
            ->where('role', 'vendor')
            ->pluck('id')
            ->shuffle();

        // التأكد من أننا لن ننشئ مطاعم أكثر من عدد المديرين المتاحين
        // لتجنب خطأ الـ Unique Constraint
        $count = min(20, $managerIds->count());

        for ($i = 0; $i < $count; $i++) {
            // نأخذ المعرف بالترتيب من المصفوفة المشوشة (ضمان عدم التكرار)
            $managerId = $managerIds[$i];

            DB::table('restaurants')->insert([
                'manager_user_id' => $managerId,
                'name' => "مطعم " . ($i + 1) . " السوري",
                'governorate' => 'دمشق',
                'city' => 'دمشق',
                'status' => 'active',
                'logo' => null,
                'cover_image' => null,
                'description' => 'أشهى الأكلات السورية التقليدية مع خدمة توصيل سريعة',
                'rating' => rand(38, 49) / 10,
                'delivery_cost' => rand(800, 2500),
                'min_order_price' => rand(1500, 6000),
                'delivery_time' => rand(20, 45) . ' دقيقة',
                'is_featured' => (bool) rand(0, 1),
                'lat' => 33.5138 + (rand(-40, 40) / 1000),
                'lng' => 36.2765 + (rand(-40, 40) / 1000),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
