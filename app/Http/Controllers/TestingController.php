<?php

namespace App\Http\Controllers;

use App\Services\EmailService;
use Illuminate\Support\Str;

class TestingController extends Controller
{
    public function datatable()
    {
        return view('testing.datatable');
    }

    public function sendEmail()
    {
        (new EmailService)->testing('hairulanam21@gmail.com', Str::random(20));
    }

    public function modal()
    {
        return view('testing.modal');
    }

    public function test()
    {
        // $content = file_get_contents(database_path('seeders/data/menus.json'));
        // $content = file_get_contents(database_path('seeders/data/permissions.json'));
        // $content = file_get_contents(database_path('seeders/data/roles.json'));
        // $content = file_get_contents(database_path('seeders/data/settings.json'));
        // $content = file_get_contents(database_path('seeders/data/users.json'));
        $content = file_get_contents(database_path('seeders/data/settings2.json'));
        $content = str_replace(":", " =>", $content);
        $content = str_replace('"', "'", $content);
        $content = str_replace("}", "]", $content);
        $content = str_replace("{", "[", $content);
        return '<pre>' . $content . '</pre>';
    }
}
