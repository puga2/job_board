<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //   // Create permissions
        // Permission::create(['name'=>'post job']);
        // Permission::create(['name'=>'approve job']);
        // Permission::create(['name'=>'delete job']);

        // //  Create roles and assign Permissions
        // $employer = Role::create(['name'=>'employer']);
        // $employer->givePermissionTo('post job');

        // $admin = Role::create(['name'=>'admin']);
        // $admin->givePermissionTo(['approve job','delete job']);

        // // Assign roles to a user
        // $user = User::find(1);
        // $user->assignRole($admin);
    }
}
