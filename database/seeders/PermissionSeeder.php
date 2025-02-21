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
            ['guard_name' => 'web', 'model' => 'Tax'],
            ['guard_name' => 'web', 'model' => 'Unit of Measurement'],
            ['guard_name' => 'web', 'model' => 'Roles and Permissions', 'SplPermission' => 1],
            ['guard_name' => 'web', 'model' => 'Customers', 'SplPermission' => 1],
            ['guard_name' => 'web', 'model' => 'Vendors', 'SplPermission' => 1],

            ['guard_name' => 'web', 'model' => 'Product Category'],
            ['guard_name' => 'web', 'model' => 'Product'],
            ['guard_name' => 'web', 'model' => 'Warehouse'],

            // Manage Projects
            ['guard_name' => 'web', 'model' => 'Project Specifications'],
            ['guard_name' => 'web', 'model' => 'Amenities'],
            ['guard_name' => 'web', 'model' => 'Projects'],
        ];

        $updatedModules = [];

        foreach ($modules as $module) {
            $options = ['Create', 'Edit', 'View', 'Delete', 'Restore', 'Excel', 'PDF', 'CSV', 'Copy', 'Print'];

            if(array_key_exists('SplPermission', $module) && $module['SplPermission']){
                $options[]='Special';
                unset($module['SplPermission']);
            }
            foreach ($options as $option) {
                if (!Permission::whereName($option.' '.$module['model'])->exists()) {
                    $permission = $module;
                    $permission['name'] = $option.' '.$module['model'];
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

        if (!Role::whereName('Super Admin')->exists()) {
            $role = Role::create(['name' => 'Super Admin']);
            $role->givePermissionTo($modulesIds);
            $users = [
                [
                    "name" => 'Vinith Kumar',
                    "email" => 'vinithkumarpropluslogics@gmail.com',
                    "password" => Hash::make('proplus1234$'),
                ],
                [
                    "name" => 'Naveen',
                    "email" => 'navinproplus222@gmail.com',
                    "password" => Hash::make('proplus1234$'),
                ],
                [
                    "name" => 'Anand',
                    "email" => 'anand@propluslogics.com',
                    "password" => Hash::make('proplus1234$'),
                ]
            ];

            foreach ($users as $userData) {
                $user = User::create($userData);
                $user->assignRole($role->id);
            }
        } else {
            $role = Role::whereName('Super Admin')->first();
            $role->syncPermissions($modulesIds);
        }
    }
}
