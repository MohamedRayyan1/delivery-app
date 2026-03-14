<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserAddress;
use App\Models\MenuSection;
use App\Models\Restaurant;
use App\Models\SubMenuSection;
use App\Models\MenuItem;
use App\Models\Ad;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
        'name' => 'Admin User',
        'phone' => '0912345678', // رقم ثابت للتجربة
        'email' => 'admin@example.com',
        'role' => 'admin',
        'password' => Hash::make('admin123'), // كلمة سر ثابتة
    ]);

        // 1. إنشاء 10 مستخدمين مع بروفايلاتهم (عناوينهم)
        $users = User::factory(9)->create([
            'password' => Hash::make('password'),
        ])->each(function ($user) {
            UserAddress::create([
                'user_id' => $user->id,
                'label' => 'المنزل',
                'street' => 'شارع ' . $user->name,
                'details' => 'بجانب المسجد الكبير',
                'floor' => 'الطابق ' . rand(1, 5),
                'phone' => '09' . rand(11111111, 99999999),
                'lat' => 33.5138,
                'lng' => 36.2765,
                'is_default' => true,
            ]);
        });

        // 2. إنشاء 7 أقسام رئيسية (Menu Sections)
        $sections = collect(['مشاوي', 'بيتزا', 'شاورما', 'برجر', 'مأكولات بحرية', 'حلويات', 'عصائر'])->map(function ($name) {
            return MenuSection::create([
                'name' => $name,
                'image' => 'sections/default.png',
            ]);
        });

        // 3. إنشاء 20 مطعم
        // سنفترض أن أول مستخدم هو المدير لتبسيط العملية
        $manager = $users->first();

        for ($i = 1; $i <= 20; $i++) {
            $restaurant = Restaurant::create([
                'manager_user_id' => $manager->id,
                'name' => "مطعم البركة $i",
                'governorate' => 'دمشق',
                'city' => 'المزة',
                'status' => 'active',
                'logo' => 'logos/rest.png',
                'cover_image' => 'covers/rest.png',
                'description' => "أفضل الوجبات السريعة في المنطقة رقم $i",
                'rating' => rand(3, 5),
                'delivery_cost' => rand(1000, 5000),
                'min_order_price' => 10000,
                'delivery_time' => '30-45 دقيقة',
                'is_featured' => rand(0, 1),
            ]);

            // 4. ربط المطعم بأقسام عشوائية (Many-to-Many)
            // كل مطعم ينتمي لـ 1-3 أقسام رئيسية
            $restaurant->menuSections()->attach(
                $sections->random(rand(1, 3))->pluck('id')->toArray()
            );

            // 5. إنشاء 5 أقسام فرعية (SubMenu Sections) لكل مطعم
            for ($j = 1; $j <= 5; $j++) {
                $subSection = SubMenuSection::create([
                    'restaurant_id' => $restaurant->id,
                    'name' => "قسم فرعي $j",
                    'image' => 'sub_sections/default.png',
                ]);

                // 6. إنشاء 5 وجبات (Menu Items) لكل قسم فرعي
                for ($k = 1; $k <= 5; $k++) {
                    MenuItem::create([
                        'sub_section_id' => $subSection->id,
                        'name' => "وجبة شهية $k",
                        'description' => "مكونات الوجبة رقم $k من القسم $j",
                        'price' => rand(15000, 50000),
                        'discount_price' => rand(10000, 14000),
                        'image' => 'items/food.png',
                        'is_featured' => rand(0, 1),
                        'is_available' => true,
                    ]);
                }
            }

            // 7. إنشاء 5 إعلانات مرتبطة ببعض المطاعم
            if ($i <= 5) {
                Ad::create([
                    'restaurant_id' => $restaurant->id,
                    'image' => "ads/ad_$i.png",
                    'title' => "عرض خاص من مطعم $i",
                    'content' => "خصم 50% على كافة الطلبات",
                    'status' => 'active',
                    'cost' => 500.00,
                    'start_date' => now(),
                    'end_date' => now()->addDays(10),
                    'is_active' => true,
                ]);
            }
        }
    }
}
