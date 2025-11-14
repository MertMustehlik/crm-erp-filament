<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        if (!Schema::hasTable('roles') || Role::query()->exists() || Permission::query()->exists()) {
            return;
        }

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
                'customer view', // tüm müşterileri görebilir
                'customer view own', //sadece kendisine atanan müşterilerini görebilir
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
