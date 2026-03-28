<?php
// database/seeders/DriverDocumentSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DriverDocumentSeeder extends Seeder
{
    public function run(): void
    {
        // ~20 وثيقة (3-4 وثائق لكل سائق)
        $driverIds = DB::table('drivers')->pluck('id');

        $documentTypes = [
            'license'          => 'رخصة القيادة',
            'vehicle_registration' => 'رخصة المركبة',
            'id_card'          => 'البطاقة الشخصية',
            'insurance'        => 'تأمين المركبة',
            'criminal_record'  => 'شهادة عدم محكومية',
        ];

        foreach ($driverIds as $driverId) {
            // 3-4 وثائق لكل سائق
            $numDocs = rand(3, 4);
            $types = array_keys($documentTypes);
            shuffle($types);

            for ($i = 0; $i < $numDocs; $i++) {
                DB::table('driver_documents')->insert([
                    'driver_id' => $driverId,
                    'document_type' => $types[$i],
                    'file_path' => "documents/driver_{$driverId}/" . $types[$i] . ".pdf",
                    'status' => rand(0, 1) ? 'approved' : 'pending',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
