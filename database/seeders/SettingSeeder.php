<?php

namespace Database\Seeders;

use App\Models\Setting;
use App\Repositories\SettingRepository;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();

        DB::table('settings')->truncate();
        if (config('stisla.use_setting') === '1') {
            $settings = config('stisla.settings');
        } else {
            $settings = config('stisla.settings2');
        }
        // $settings = config('stisla.settings2');

        $encrypts = SettingRepository::getEncryptedKeys();
        foreach ($settings as $setting) {
            if (($setting['is_url'] ?? false) === true) {
                if (in_array($setting['key'], $encrypts)) {
                    Setting::create([
                        'key'   => $setting['key'],
                        'value' => encrypt(url($setting['value']))
                    ]);
                } else {
                    Setting::create([
                        'key'   => $setting['key'],
                        'value' => url($setting['value'])
                    ]);
                }
            } else {
                if (in_array($setting['key'], $encrypts)) {
                    Setting::create([
                        'key'   => $setting['key'],
                        'value' => encrypt($setting['value']),
                    ]);
                } else {
                    Setting::create([
                        'key'   => $setting['key'],
                        'value' => $setting['value']
                    ]);
                }
            }
        }
    }
}
