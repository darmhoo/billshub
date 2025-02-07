<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use Illuminate\Database\Seeder;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        app()[PermissionRegistrar::class]->forgetCachedPermissions();


        // create permissions
        Permission::create(['name' => 'fund wallet']);
        Permission::create(['name' => 'delete']);
        Permission::create(['name' => 'view transactions']);
        Permission::create(['name' => 'delete transactions']);
        Permission::create(['name' => 'edit transactions']);

        Permission::create(['name' => 'edit user']);
        Permission::create(['name' => 'add user']);

        $role1 = Role::create(['name' => 'admin']);
        $role2 = Role::create(['name'=> 'user']);
        $role3 = Role::create(['name'=> 'super-admin']);

        $user = User::where('name','admin')->first();
        $user->assignRole('super-admin');
    }
}
