<?php
// database/seeders/WalletTransactionSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WalletTransactionSeeder extends Seeder
{
    public function run(): void
    {
        // 40 عملية محفظة - مرتبط مع users + orders
        $userIds = DB::table('users')->pluck('id');
        $orderIds = DB::table('orders')->pluck('id');
        $types = ['deposit', 'order_payment', 'withdrawal', 'refund'];

        for ($i = 1; $i <= 40; $i++) {
            $userId = $userIds->random();
            $orderId = rand(0, 1) ? $orderIds->random() : null;

            DB::table('wallet_transactions')->insert([
                'user_id' => $userId,
                'order_id' => $orderId,
                'type' => $types[array_rand($types)],
                'amount' => rand(-15000, 25000),
                'balance_after' => rand(500, 45000),
                'description' => $orderId ? "دفع طلب رقم #{$orderId}" : 'شحن رصيد',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
