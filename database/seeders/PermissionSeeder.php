<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $modules = [
            // Master
            ['guard_name' => 'web', 'model' => 'States'],
            ['guard_name' => 'web', 'model' => 'Districts'],
            ['guard_name' => 'web', 'model' => 'Pincodes'],
            ['guard_name' => 'web', 'model' => 'Cities'],
            ['guard_name' => 'web', 'model' => 'Stages'],
            ['guard_name' => 'web', 'model' => 'Tax'],
            ['guard_name' => 'web', 'model' => 'Unit of Measurement'],
            ['guard_name' => 'web', 'model' => 'Roles and Permissions', 'SplPermission' => 1],
            ['guard_name' => 'web', 'model' => 'Users', 'SplPermission' => 1],
            ['guard_name' => 'web', 'model' => 'Customers', 'SplPermission' => 1],
            ['guard_name' => 'web', 'model' => 'Vendors', 'SplPermission' => 1],
            ['guard_name' => 'web', 'model' => 'Lead Source', 'SplPermission' => 1],
            ['guard_name' => 'web', 'model' => 'Lead', 'SplPermission' => 1],

            ['guard_name' => 'web', 'model' => 'Product Category'],
            ['guard_name' => 'web', 'model' => 'Product'],
            ['guard_name' => 'web', 'model' => 'Warehouse'],

            // Manage Projects
            ['guard_name' => 'web', 'model' => 'Project Specifications'],
            ['guard_name' => 'web', 'model' => 'Amenities'],
            ['guard_name' => 'web', 'model' => 'Sites'],
            ['guard_name' => 'web', 'model' => 'Projects'],
            ['guard_name' => 'web', 'model' => 'Project Tasks'],
        ];

        $updatedModules = [];

        foreach ($modules as $module) {
            $options = ['Create', 'Edit', 'View', 'Delete', 'Restore', 'Excel', 'PDF', 'CSV', 'Copy', 'Print'];

            if (array_key_exists('SplPermission', $module) && $module['SplPermission']) {
                $options[] = 'Special';
                unset($module['SplPermission']);
            }
            foreach ($options as $option) {
                if (!Permission::whereName($option . ' ' . $module['model'])->exists()) {
                    $permission = $module;
                    $permission['name'] = $option . ' ' . $module['model'];
//                    $updatedModules[] = $permission;
                    Permission::create($permission);
                }
            }
        }

//        $dbPermission = Permission::all()->pluck('name');
//        $collectionPermission = collect($updatedModules)->pluck('name');

//        $differenceArray = array_diff($dbPermission->toArray(), $collectionPermission->toArray());
//        Permission::whereIn('name', $differenceArray)->delete();

        $modulesIds = Permission::all()->pluck('id');
        foreach (SYSTEM_ROLES as $role) {
            $role = Role::firstOrCreate(['name' => $role, 'guard_name' => 'web']);
            $role->givePermissionTo($modulesIds);
        }
        $super_admin_id = Role::where('name', SUPER_ADMIN_ROLE_NAME)->first()->id;
        $supervisor_id = Role::where('name', SITE_SUPERVISOR_ROLE_NAME)->first()->id;
        $users = [
            [
                "name" => 'Vinith Kumar',
                "email" => 'vinithkumarpropluslogics@gmail.com',
                "password" => Hash::make('proplus1234$'),
                "role_id" => $super_admin_id,
            ],
            [
                "name" => 'Naveen',
                "email" => 'navinproplus222@gmail.com',
                "password" => Hash::make('proplus1234$'),
                "role_id" => $super_admin_id,
            ],
            [
                "name" => 'Anand',
                "email" => 'anand@propluslogics.com',
                "password" => Hash::make('proplus1234$'),
                "role_id" => $super_admin_id,
            ]
        ];

        foreach ($users as $userData) {
            $user = User::create($userData);
            $user->assignRole($super_admin_id);
        }

        $supervisors = [
            [
                "name" => 'Supervisor 1',
                "email" => 'vinithkumarpropluslogics+s1@gmail.com',
                "password" => Hash::make('proplus1234$'),
                "role_id" => $supervisor_id,
            ],
            [
                "name" => 'Supervisor 2',
                "email" => 'vinithkumarpropluslogics+s2@gmail.com',
                "password" => Hash::make('proplus1234$'),
                "role_id" => $supervisor_id,
            ],
            [
                "name" => 'Supervisor 3',
                "email" => 'vinithkumarpropluslogics+s3@gmail.com',
                "password" => Hash::make('proplus1234$'),
                "role_id" => $supervisor_id,
            ]
        ];

        foreach ($supervisors as $supervisorData) {
            $supervisor = User::create($supervisorData);
            $supervisor->assignRole($supervisor_id);
        }
    }
}
