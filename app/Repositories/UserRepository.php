<?php

namespace App\Repositories;

use App\Models\ActivityLog;
use App\Models\PermissionGroup;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Spatie\Permission\Models\Permission;
use App\Models\Role;

class UserRepository extends Repository
{

    /**
     * constructor method
     *
     * @return void
     */
    public function __construct()
    {
        $this->model = new User();
    }

    /**
     * get user id login
     *
     * @return int
     */
    public function getUserIdLogin()
    {
        return auth()->id() ?? auth('api')->id();
    }

    /**
     * set and get user login
     *
     * @return User
     */
    public function login(User $user)
    {
        auth()->login($user, request()->filled('remember'));
        $user->update(['last_login' => now()]);
        logLogin();
        return $user;
    }

    /**
     * find user by email
     *
     * @param string $email
     * @return User
     */
    public function findByEmail(string $email)
    {
        return $this->model->where('email', $email)->first();
    }

    /**
     * find user by twitter id
     *
     * @param string $twitterId
     * @return User
     */
    public function findByTwitterId(string $twitterId)
    {
        return $this->model->where('twitter_id', $twitterId)->first();
    }

    /**
     * find user by email token
     *
     * @param string $emailToken
     * @return User
     */
    public function findByEmailToken(string $emailToken)
    {
        return $this->model->where('email_token', $emailToken)->first();
    }

    /**
     * update profile by user login
     *
     * @param array $data
     * @return int
     */
    public function updateProfile(array $data)
    {
        $userId = $this->getUserIdLogin();
        $this->model->where('id', $userId)->update($data);
        return $this->find($userId);
    }

    /**
     * get users data
     *
     * @return Collection
     */
    public function getUsers()
    {
        $users = $this->model->with(['roles', 'createdBy', 'lastUpdatedBy'])->latest()->get();
        return $users;
    }

    /**
     * get user as option dropdown
     *
     * @return array
     */
    public function getUserOptions()
    {
        return $this->getUsers()
            ->pluck('name', 'id')
            ->toArray();
    }

    /**
     * get user data as pagination
     *
     * @param integer $perPage
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getPaginateUsers($perPage = 20)
    {
        $users = $this->model->with(['roles'])->latest()->paginate($perPage);
        return $users;
    }

    /**
     * get all role data
     *
     * @return Collection
     */
    public function getRoles()
    {
        $roles = Role::with(['permissions'])->withCount(['permissions'])->latest()->get();
        return $roles;
    }

    /**
     * get role as option dropdown
     *
     * @return array
     */
    public function getRoleOptions()
    {
        return $this->getRoles()
            ->pluck('name', 'id')
            ->toArray();
    }

    /**
     * find permission
     *
     * @param integer $permissionId
     * @return Permission
     */
    public function findPermission(int $permissionId)
    {
        return Permission::where('id', $permissionId)->first();
    }

    /**
     * find permission group
     *
     * @param integer $groupId
     * @return PermissionGroup
     */
    public function findPermissionGroup(int $groupId)
    {
        return PermissionGroup::where('id', $groupId)->first();
    }

    /**
     * delete permission
     *
     * @param integer $permissionId
     * @return Permission
     */
    public function deletePermission(int $permissionId)
    {
        return Permission::where('id', $permissionId)->delete();
    }

    /**
     * delete permission group by id
     *
     * @param integer $groupId
     * @return Permission
     */
    public function deletePermissionGroup(int $groupId)
    {
        return PermissionGroup::where('id', $groupId)->delete();
    }

    /**
     * update permission data
     *
     * @param integer $permissionId
     * @param array $data
     * @return Permission
     */
    public function updatePermission(int $permissionId, array $data)
    {
        Permission::where('id', $permissionId)->update($data);
        return $this->findPermission($permissionId);
    }

    /**
     * update permission group data
     *
     * @param integer $groupId
     * @param array $data
     * @return PermissionGroup
     */
    public function updatePermissionGroup(int $groupId, array $data)
    {
        PermissionGroup::where('id', $groupId)->update($data);
        return $this->findPermissionGroup($groupId);
    }

    /**
     * get all permission data
     *
     * @return Collection
     */
    public function getPermissions()
    {
        return Permission::all();
    }

    /**
     * get all permission join group data
     *
     * @return Collection
     */
    public function getPermissionJoinGroups()
    {
        $permissions = Permission::select(['permissions.*', 'permission_groups.group_name'])
            ->join('permission_groups', 'permissions.permission_group_id', '=', 'permission_groups.id')->get();
        return $permissions;
    }

    /**
     * get all permission join group data latest
     *
     * @return Collection
     */
    public function getLatestPermissionJoinGroups()
    {
        $permissions = Permission::select(['permissions.*', 'permission_groups.group_name'])
            ->join('permission_groups', 'permissions.permission_group_id', '=', 'permission_groups.id')
            ->latest()
            ->get();
        return $permissions;
    }

    /**
     * get permission group data latest
     *
     * @return Collection
     */
    public function getLatestPermissionGroups()
    {
        return PermissionGroup::latest()->get();
    }

    /**
     * get permission as option dropdown
     *
     * @return array
     */
    public function getPermissionGroupOptions()
    {
        return PermissionGroup::pluck('group_name', 'id')->toArray();
    }

    /**
     * create permission data
     *
     * @param array $data
     * @return Permission
     */
    public function createPermission(array $data)
    {
        return Permission::create($data);
    }

    /**
     * create permission group data
     *
     * @param array $data
     * @return PermissionGroup
     */
    public function createPermissionGroup(array $data)
    {
        return PermissionGroup::create($data);
    }

    /**
     * findRole
     *
     * @param integer $roleId
     * @return Role
     */
    public function findRole(int $roleId)
    {
        return Role::where('id', $roleId)->with(['permissions'])->first();
    }

    /**
     * create role data
     *
     * @param string $roleName
     * @param array $data
     * @return Role
     */
    public function createRole(string $roleName, array $data)
    {
        $role = Role::create([
            'name'       => $roleName,
            'guard_name' => 'web'
        ]);
        if (isset($data['permissions'])) {
            $permissions = Permission::whereIn('name', $data['permissions'])->get();
            $role->syncPermissions($permissions);
        }
        return $role;
    }

    /**
     * update role data
     *
     * @param int $roleId
     * @param array $data
     * @return int
     */
    public function updateRole(int $roleId, array $data)
    {
        $role = Role::find($roleId);
        $role->update($data);
        if ($role && isset($data['permissions'])) {
            $permissions = Permission::whereIn('name', $data['permissions'])->get();
            $role->syncPermissions($permissions);
            return $role;
        }
    }

    /**
     * delete role data
     *
     * @param int $roleId
     * @return int
     */
    public function deleteRole(int $roleId)
    {
        return Role::where('id', $roleId)->delete();
    }

    /**
     * get all user where role owner boarding house data
     *
     * @return Collection
     */
    public function getOwnerOptions()
    {
        $owners = $this->model->role('pemilik kos')->get();
        return $owners->pluck('name', 'id')->toArray();
    }

    /**
     * get permission group with child
     *
     * @return Collection
     */
    public function getPermissionGroupWithChild()
    {
        return PermissionGroup::with(['permissions'])->get();
    }

    /**
     * getLogActivitiesPaginate
     *
     * @param integer $perPage
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getLogActivitiesPaginate($perPage = 20)
    {
        return ActivityLog::query()
            ->where('user_id', $this->getUserIdLogin())
            ->latest()
            ->paginate($perPage);
    }

    /**
     * assign role
     *
     * @param User $user
     * @param string $role
     * @return User
     */
    public function assignRole(User $user, string $role)
    {
        return $user->assignRole($role);
    }

    /**
     * sync roles
     *
     * @param User $user
     * @param array $role
     * @return User
     */
    public function syncRoles(User $user, array $roles)
    {
        $roles = Role::whereIn('name', $roles)->get();
        return $user->syncRoles($roles);
    }

    /**
     * sync roles by id
     *
     * @param User $user
     * @param array $role
     * @return User
     */
    public function syncRolesByID(User $user, array $roles)
    {
        $roles = Role::whereIn('id', $roles)->get();
        return $user->syncRoles($roles);
    }
}
