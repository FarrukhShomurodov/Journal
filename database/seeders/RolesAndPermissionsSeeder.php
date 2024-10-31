<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        Permission::create(['name' => 'manage resources']);
        Permission::create(['name' => 'full access']);

        $adminRole = Role::create(['name' => 'admin']);
        $modertorRole = Role::create(['name' => 'moderator']);

        $adminRole->givePermissionTo([
            'full access',
            'manage resources'
        ]);

        $modertorRole->givePermissionTo([
            'manage resources'
        ]);

        $user = User::query()->find(1)->first();
        $user->assignRole('admin');
    }
}
