<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleAndPermissionSeeder extends Seeder
{
    public function run()
    {
        Permission::create(['name' => 'create-users']);
        Permission::create(['name' => 'edit-users']);
        Permission::create(['name' => 'delete-users']);

        Permission::create(['name' => 'create-tasks']);
        Permission::create(['name' => 'edit-tasks']);
        Permission::create(['name' => 'delete-tasks']);

        $adminRole = Role::create(['name' => 'Admin']);
        $userRole = Role::create(['name' => 'User']);

        $adminRole->givePermissionTo([
            'create-users',
            'edit-users',
            'delete-users',
            'create-blog-posts',
            'edit-blog-posts',
            'delete-blog-posts',
        ]);

        $userRole->givePermissionTo([
            'create-tasks',
            'edit-tasks',
            'delete-tasks',
        ]);
    }
}
