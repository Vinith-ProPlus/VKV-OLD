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
        $permissions = [
            // Master
            ['name' => 'States', 'guard_name' => 'web', 'model' => 'Master'],
            ['name' => 'Cities', 'guard_name' => 'web', 'model' => 'Master'],
            ['name' => 'Roles-and-Permissions', 'guard_name' => 'web', 'model' => 'Master'],

            // Product Category
            ['name' => 'Create Product Category', 'guard_name' => 'web', 'model' => 'Product Category'],
            ['name' => 'View Product Category', 'guard_name' => 'web', 'model' => 'Product Category'],
            ['name' => 'Edit Product Category', 'guard_name' => 'web', 'model' => 'Product Category'],
            ['name' => 'Delete Product Category', 'guard_name' => 'web', 'model' => 'Product Category'],
            ['name' => 'Import Product Category', 'guard_name' => 'web', 'model' => 'Product Category'],

            // Product
            ['name' => 'Create Product', 'guard_name' => 'web', 'model' => 'Product'],
            ['name' => 'View Product', 'guard_name' => 'web', 'model' => 'Product'],
            ['name' => 'Edit Product', 'guard_name' => 'web', 'model' => 'Product'],
            ['name' => 'Delete Product', 'guard_name' => 'web', 'model' => 'Product'],
            ['name' => 'Import Product', 'guard_name' => 'web', 'model' => 'Product'],
        ];

        foreach ($permissions as $permission) {
            if (!Permission::whereName($permission['name'])->exists()) {
                Permission::create($permission);
            }
        }

        $dbPermission = Permission::all()->pluck('name');
        $collectionPermission = collect($permissions)->pluck('name');

        $differenceArray = array_diff($dbPermission->toArray(), $collectionPermission->toArray());
        Permission::whereIn('name', $differenceArray)->delete();

        $permissionsIds = Permission::all()->pluck('id');

        if (!Role::whereName('Super Admin')->exists()) {
            $role = Role::create(['name' => 'Super Admin']);
            $role->givePermissionTo($permissionsIds);
            $users = [
                [
                    "name" => 'Vinith Kumar',
                    "email" => 'vinithkumarpropluslogics@gmail.com',
                    "password" => Hash::make('proplus1234$'),
                ],
                [
                    "name" => 'Naveen',
                    "email" => 'naveenproplus222@gmail.com',
                    "password" => Hash::make('proplus1234$'),
                ]
            ];

            foreach ($users as $userData) {
                $user = User::create($userData);
                $user->assignRole($role->id);
            }
        } else {
            $role = Role::whereName('Super Admin')->first();
            $role->syncPermissions($permissionsIds);
        }
    }
}
