<?php

namespace Database\Seeders;

use App\Models\PermissionGroup;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();



        Role::truncate();
        $roles = json_decode(file_get_contents(database_path('seeders/data/roles.json')), true);
        foreach ($roles as $role) {
            $roleObj = Role::create([
                'name' => $role,
            ]);
            if ($role === 'superadmin') {
                $roleObj->is_locked = 1;
                $roleObj->save();
            }
        }

        $roles = Role::all();
        $rolesArray = $roles->pluck('name')->toArray();

        Permission::truncate();

        // default permissions
        $permissions = json_decode(file_get_contents(database_path('seeders/data/permissions.json')), true);
        foreach ($permissions as $permission) {
            $group = PermissionGroup::updateOrCreate([
                'group_name' => $permission['group']
            ]);
            $perm = Permission::create([
                'name'                => $permission['name'],
                'permission_group_id' => $group->id
            ]);
            foreach ($permission['roles'] as $role) {
                if (in_array($role, $rolesArray))
                    $perm->assignRole($role);
            }
        }

        // per module generated permission
        $path = database_path('seeders/data/permission-modules');
        if (file_exists($path)) {
            $files = getFileNamesFromDir($path);
            foreach ($files as $file) {
                $permissions = json_decode(file_get_contents(database_path('seeders/data/permission-modules/' . $file)), true);
                foreach ($permissions as $permission) {
                    $group = PermissionGroup::updateOrCreate([
                        'group_name' => $permission['group']
                    ]);
                    $perm = Permission::create([
                        'name'                => $permission['name'],
                        'permission_group_id' => $group->id
                    ]);
                    foreach ($permission['roles'] as $role)
                        if (in_array($role, $rolesArray))
                            $perm->assignRole($role);
                }
            }
        }
    }
}
