<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $role = Role::create(['name' => 'Super Admin']);

        $permissionList = [
            'user' => [
                'user list',
                'user edit',
                'user delete',
            ],
            'role' => [
                'role list',
                'role edit',
                'role delete',
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
