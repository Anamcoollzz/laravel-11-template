<?php

namespace Database\Seeders;

use App\Models\Bank;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class BankSeeder extends Seeder
{
    private function generate()
    {
        DB::table('banks')->truncate();
        DB::table('bank_deposits')->truncate();
        $sql = "INSERT INTO `banks` (`id`, `name`, `bank_type`, `created_by_id`, `last_updated_by_id`, `created_at`, `updated_at`) VALUES
                                    (1, 'ALADIN SYARIAH', 'Syariah', 1, NULL, NULL, NULL),
                                    (2, 'ALLO BANK', 'Konvensional', 1, NULL, NULL, NULL),
                                    (3, 'AMAR BANK', 'Konvensional', 1, NULL, NULL, NULL),
                                    (4, 'BANK SAQU', 'Konvensional', 1, NULL, NULL, NULL),
                                    (5, 'BCA', 'Konvensional', 1, NULL, NULL, NULL),
                                    (6, 'BINA DIGITAL', 'Konvensional', 1, NULL, NULL, NULL),
                                    (7, 'BLU', 'Konvensional', 1, NULL, NULL, NULL),
                                    (8, 'BSI', 'Syariah', 1, NULL, NULL, NULL),
                                    (9, 'CIMB', 'Konvensional', 1, NULL, NULL, NULL),
                                    (10, 'CIMB SYARIAH', 'Syariah', 1, NULL, NULL, NULL),
                                    (11, 'FINETIKS', 'Konvensional', 1, NULL, NULL, NULL),
                                    (12, 'JAGO SYARIAH', 'Syariah', 1, NULL, NULL, NULL),
                                    (13, 'KROM BANK', 'Konvensional', 1, NULL, NULL, NULL),
                                    (14, 'MEGA', 'Konvensional', 1, NULL, NULL, NULL),
                                    (15, 'MEGA SYARIAH', 'Syariah', 1, NULL, NULL, NULL),
                                    (16, 'NEOCOMMERCE', 'Konvensional', 1, NULL, NULL, NULL),
                                    (17, 'SEABANK', 'Konvensional', 1, NULL, NULL, NULL),
                                    (18, 'SUPERBANK', 'Konvensional', 1, NULL, NULL, NULL);


                                    INSERT INTO `bank_deposits` (`id`, `bank_id`, `per_anum`, `amount`, `tax_percentage`, `tax`, `estimation`, `time_period`, `due_date`, `status`, `realization`, `difference`, `created_by_id`, `last_updated_by_id`, `created_at`, `updated_at`) VALUES
                                    (1, 1, 9, 34999000, 20, 52498.5, 209994, '1 Bulan', '2025-09-07', 'Aktif', NULL, NULL, NULL, 1, '2025-08-16 15:03:18', '2025-08-16 16:37:27'),
                                    (2, 13, 7.25, 10000000, 20, 12083.333333333, 48333.333333333, '1 Bulan', '2025-09-06', 'Aktif', NULL, NULL, NULL, 1, '2025-08-16 15:20:43', '2025-08-16 16:36:51'),
                                    (3, 4, 6.8, 5000000, 20, 5666.6666666667, 22666.666666667, '1 Bulan', '2025-09-03', 'Aktif', NULL, NULL, NULL, 1, '2025-08-16 15:22:34', '2025-08-16 16:35:49'),
                                    (4, 4, 6.5, 10000000, 20, 10833.333333333, 43333.333333333, '1 Bulan', '2025-09-03', 'Aktif', NULL, NULL, NULL, 1, '2025-08-16 15:23:08', '2025-08-16 16:35:31'),
                                    (5, 18, 6, 1250000, 20, 625, 2500, '14 Hari', '2025-08-20', 'Aktif', NULL, NULL, NULL, 1, '2025-08-16 15:26:12', '2025-08-16 16:35:07'),
                                    (6, 18, 6, 750000, 20, 187.5, 750, '7 Hari', '2025-08-13', 'Aktif', 863, -113, NULL, 1, '2025-08-16 15:26:42', '2025-08-16 16:34:30'),
                                    (7, 4, 5, 3703400, 20, 3086.1666666667, 12344.666666667, '1 Bulan', '2025-09-05', 'Aktif', NULL, NULL, NULL, 1, '2025-08-16 15:27:18', '2025-08-16 16:32:44'),
                                    (8, 11, 5, 2500000, 20, 2083.3333333333, 8333.3333333333, '1 Bulan', '2025-09-16', 'Aktif', NULL, NULL, NULL, NULL, '2025-08-16 15:28:23', '2025-08-16 15:28:23'),
                                    (9, 17, 4.75, 10000000, 20, 7916.6666666667, 31666.666666667, '1 Bulan', '2025-09-04', 'Aktif', NULL, NULL, NULL, 1, '2025-08-16 15:29:38', '2025-08-16 16:32:04'),
                                    (10, 17, 4.5, 10000000, 20, 7500, 30000, '1 Bulan', '2025-09-03', 'Aktif', NULL, NULL, NULL, 1, '2025-08-16 15:29:56', '2025-08-16 16:31:46'),
                                    (11, 4, 4.25, 1000000, 20, 708.33333333333, 2833.3333333333, '1 Bulan', '2025-09-16', 'Aktif', NULL, NULL, NULL, NULL, '2025-08-16 15:30:52', '2025-08-16 15:30:52'),
                                    (12, 12, 4, 30000000, 20, 20000, 80000, '1 Bulan', '2025-09-03', 'Aktif', NULL, NULL, NULL, 1, '2025-08-16 15:31:18', '2025-08-16 16:27:23'),
                                    (13, 15, 3.99, 20000000, 20, 13300, 53200, '1 Bulan', '2025-09-08', 'Aktif', NULL, NULL, NULL, 1, '2025-08-16 15:31:42', '2025-08-16 16:26:08'),
                                    (14, 8, 3, 60000000, 20, 30000, 120000, '1 Bulan', '2025-09-03', 'Aktif', NULL, NULL, NULL, 1, '2025-08-16 15:32:10', '2025-08-16 16:10:21'),
                                    (15, 10, 3, 8000000, 20, 4000, 16000, '1 Bulan', '2025-09-06', 'Aktif', NULL, NULL, NULL, 1, '2025-08-16 15:32:26', '2025-08-16 16:09:14');
                                    ";
        DB::unprepared($sql);
        // $banks = Bank::orderBy('name')->select(['name', 'bank_type', 'created_by_id'])->get()->toJson();
        // DB::table('banks')->truncate();
        // DB::table('banks')->insert(json_decode($banks, true));
        // dd($banks);
    }
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->generate();
        die;
        $data         = [];
        $faker        = \Faker\Factory::create('id_ID');
        // $options      = array_values(get_options());
        // $radioOptions = array_values(get_options(4));
        // $now          = now();
        $banks = [
            'ALADIN SYARIAH',
            'KROM BANK',
            'BANK SAQU BUSPOSITO',
            'BANK SAQU 6.8 PERSEN',
            'ALLO 3 BLN',
            'BANK SAQU 6.5 PERSEN',
            'NEO',
            'SUPERBANK 14 HARI',
            'SUPERBANK 7 HARI',
            'AMAR BANK',
            'ALLO',
            'NEO 7 HARI',
            'BANK SAQU 5 PERSEN',
            'FINETIKS BUNGA',
            'BINA DIGITAL',
            'SEABANK 4.75',
            'SEABANK 4.5',
            'BANK SAQU 4.25 PERSEN',
            'JAGO SYARIAH >= 50 JT',
            'BANK SAQU',
            'JAGO SYARIAH >= 1 JT',
            'MEGA SYARIAH',
            'BLU',
            'MEGA',
            'CIMBS 3 BLN',
            'CIMB 3 BLN',
            'BCA',
            'BSI',
            'CIMBS',
            // 'MEGA SYARIAH',
            'CIMB'
        ];

        $types = [
            'SYARIAH',
            'KONVENSIONAL',
            'KONVENSIONAL',
            'KONVENSIONAL',
            'KONVENSIONAL',
            'KONVENSIONAL',
            'KONVENSIONAL',
            'KONVENSIONAL',
            'KONVENSIONAL',
            'KONVENSIONAL',
            'KONVENSIONAL',
            'KONVENSIONAL',
            'KONVENSIONAL',
            'KONVENSIONAL',
            'SYARIAH',
            'KONVENSIONAL',
            'SYARIAH',
            'SYARIAH',
            'KONVENSIONAL',
            'KONVENSIONAL',
            'SYARIAH',
            'KONVENSIONAL',
            'KONVENSIONAL',
            'SYARIAH',
            'SYARIAH',
            // 'SYARIAH',
            'KONVENSIONAL'
        ];

        foreach ($banks as $i => $bank_name) {
            // $selectMultiple = [];
            // foreach (range(1, Arr::random(range(1, 3))) as $j) {
            //     array_push($selectMultiple, $options[$j - 1]);
            // }
            // $checkbox = [];
            // foreach (range(1, Arr::random(range(1, 3))) as $j) {
            //     array_push($checkbox, $options[$j - 1]);
            // }
            // $checkbox2 = [];
            // foreach (range(1, Arr::random(range(1, 3))) as $j) {
            //     array_push($checkbox2, $options[$j - 1]);
            // }
            array_push($data, [
                // 'text'               => Str::random(10),
                // 'email'              => $email = $faker->email,
                // 'number'             => $faker->numberBetween(1, 1000),
                // 'currency'           => $faker->numberBetween(1, 10000),
                // 'currency_idr'       => $faker->numberBetween(1000, 10000000),
                // 'select'             => Arr::random($options),
                // 'select2'            => Arr::random($options),
                // 'select2_multiple'   => json_encode($selectMultiple),
                // 'textarea'           => $faker->text(100),
                // 'radio'              => Arr::random($radioOptions),
                // 'checkbox'           => json_encode($checkbox),
                // 'checkbox2'          => json_encode($checkbox2),
                // 'tags'               => implode(',', $checkbox2),
                // 'file'               => 'https://picsum.photos/200/300?random=' . $i,
                // 'image'              => 'https://picsum.photos/200/300?random=' . $i,
                // 'date'               => $faker->date('Y-m-d'),
                // 'time'               => $faker->date('H:i:s'),
                // 'color'              => $faker->hexColor,
                // 'summernote_simple'  => $faker->text(100),
                // 'summernote'         => $faker->randomHtml,
                // 'barcode'            => Str::random(10),
                // 'qr_code'            => $faker->ean13,
                'name' => $bank_name,
                'bank_type' => isset($types[$i]) ? ucwords(strtolower($types[$i])) : 'Konvensional',
                'created_at'         => $faker->dateTimeBetween('-1 month', 'now'),
                'updated_at'         => $faker->dateTimeBetween('-1 month', 'now'),
                // 'created_by_id'      => Arr::random([null, 1]),
                'created_by_id'      => 1,
                // 'last_updated_by_id' => Arr::random([null, 1]),
                // 'last_updated_by_id' => 1,
            ]);
        }
        foreach (collect($data)->chunk(20) as $chunkData) {
            Bank::insert($chunkData->toArray());
        }
    }
}
