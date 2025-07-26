<?php

namespace Database\Seeders;

use App\Models\CrudExample;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class CrudExampleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data         = [];
        $faker        = \Faker\Factory::create('id_ID');
        $options      = array_values(get_options());
        $radioOptions = array_values(get_options(4));
        $now          = now();
        foreach (range(1, 25) as $i) {
            $selectMultiple = [];
            foreach (range(1, Arr::random(range(1, 3))) as $j) {
                array_push($selectMultiple, $options[$j - 1]);
            }
            $checkbox = [];
            foreach (range(1, Arr::random(range(1, 3))) as $j) {
                array_push($checkbox, $options[$j - 1]);
            }
            $checkbox2 = [];
            foreach (range(1, Arr::random(range(1, 3))) as $j) {
                array_push($checkbox2, $options[$j - 1]);
            }
            array_push($data, [
                'text'               => Str::random(10),
                'email'              => $faker->email,
                'number'             => $faker->numberBetween(1, 1000),
                'currency'           => $faker->numberBetween(1, 10000),
                'currency_idr'       => $faker->numberBetween(1000, 10000000),
                'select'             => Arr::random($options),
                'select2'            => Arr::random($options),
                'select2_multiple'   => json_encode($selectMultiple),
                'textarea'           => $faker->text(100),
                'radio'              => Arr::random($radioOptions),
                'checkbox'           => json_encode($checkbox),
                'checkbox2'          => json_encode($checkbox2),
                'tags'               => implode(',', $checkbox2),
                'file'               => $faker->imageUrl,
                'image'              => $faker->imageUrl,
                'date'               => $faker->date('Y-m-d'),
                'time'               => $faker->date('H:i:s'),
                'color'              => $faker->hexColor,
                'summernote_simple'  => $faker->text(100),
                'summernote'         => $faker->randomHtml,
                'barcode'            => Str::random(10),
                'qr_code'            => $faker->ean13,
                'created_at'         => $now,
                'updated_at'         => $now,
                // 'created_by_id'      => Arr::random([null, 1]),
                'created_by_id'      => 1,
                'last_updated_by_id' => 1,
            ]);
        }
        foreach (collect($data)->chunk(20) as $chunkData) {
            CrudExample::insert($chunkData->toArray());
        }
    }
}
