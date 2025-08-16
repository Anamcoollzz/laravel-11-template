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
