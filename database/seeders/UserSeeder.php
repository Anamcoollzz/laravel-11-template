<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use App\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();

        $roles = Role::all();
        $rolesArray = $roles->pluck('name')->toArray();

        User::truncate();

        $users = config('stisla.users');
        foreach ($users as $user) {
            $userObj = User::create([
                'name'                 => $user['name'],
                'email'                => $user['email'],
                'email_verified_at'    => $user['email_verified_at'],
                'password'             => bcrypt($user['password']),
                'is_locked'            => $user['is_locked'] ?? 0,
                'phone_number'         => $user['phone_number'] ?? null,
                'birth_date'           => $user['birth_date'] ?? null,
                'address'              => $user['address'] ?? null,
                'last_password_change' => date('Y-m-d H:i:s'),
                'created_by_id'        => null,
                'last_updated_by_id'   => null
            ]);
            foreach ($user['roles'] as $role)
                if (in_array($role, $rolesArray))
                    $userObj->assignRole($role);
        }
    }
}
