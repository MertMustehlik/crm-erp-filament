<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $role = Role::firstOrCreate(['name' => 'Super Admin']);
        $user = User::first();
        $user->assignRole($role);

        $permissionList = [
            'user' => [
                'user view',
                'user create',
                'user edit',
                'user delete',
            ],
            'role' => [
                'role view',
                'role create',
                'role edit',
                'role delete',
            ],
            'customer' => [
                'customer view',
                'customer create',
                'customer edit',
                'customer delete',
            ],
            'product' => [
                'product view',
                'product create',
                'product edit',
                'product delete',
            ],
        ];

        foreach ($permissionList as $groupName => $permissions) {
            foreach ($permissions as $permission) {
                Permission::create([
                    'name' => $permission,
                    'guard_name' => 'web',
                    'group_name' => $groupName,
                ]);
            }
        }
    }
}
