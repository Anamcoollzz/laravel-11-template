<?php

namespace Database\Seeders;

use App\Models\Setting;
use App\Repositories\SettingRepository;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        DB::table('settings')->truncate();
        $settings = config('stisla.settings');
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
