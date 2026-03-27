<?php
// database/seeders/SystemSettingSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SystemSettingSeeder extends Seeder
{
    public function run(): void
    {
        // 10 إعدادات نظام
        $settings = [
            ['key' => 'tax_percentage', 'value' => '10', 'description' => 'نسبة الضريبة المضافة'],
            ['key' => 'delivery_commission', 'value' => '15', 'description' => 'عمولة التوصيل للسائق'],
            ['key' => 'min_delivery_time', 'value' => '20', 'description' => 'أقل وقت توصيل'],
            ['key' => 'app_name', 'value' => 'دليلي', 'description' => 'اسم التطبيق'],
            ['key' => 'wallet_enabled', 'value' => 'true', 'description' => 'تفعيل المحفظة'],
            ['key' => 'max_order_items', 'value' => '20', 'description' => 'أقصى عدد عناصر في الطلب'],
            ['key' => 'support_email', 'value' => 'support@dalili.app', 'description' => 'البريد الدعم'],
            ['key' => 'currency', 'value' => 'SYP', 'description' => 'العملة'],
            ['key' => 'featured_restaurants_limit', 'value' => '8', 'description' => 'عدد المطاعم المميزة'],
            ['key' => 'review_reminder_hours', 'value' => '48', 'description' => 'ساعات تذكير التقييم'],
        ];

        foreach ($settings as $setting) {
            DB::table('system_settings')->insert([
                'key' => $setting['key'],
                'value' => $setting['value'],
                'description' => $setting['description'],
                'updated_at' => now(),
            ]);
        }
    }
}
