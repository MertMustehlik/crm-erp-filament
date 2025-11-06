<?php

namespace Database\Seeders;

use App\Models\User;
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
