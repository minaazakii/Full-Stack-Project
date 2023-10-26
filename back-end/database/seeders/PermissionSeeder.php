<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            //User Permissions
            ['user-index', 'user', 'View All Users'],
            ['user-show', 'user', 'View Single User'],
            ['user-edit', 'user', 'Edit User'],
            ['user-delete', 'user', 'Delete User'],

            //Category Permissions
            ['category-index', 'category', 'View All Categories'],
            ['category-show', 'category', 'View Single Category'],
            ['category-edit', 'category', 'Edit Category'],
            ['category-delete', 'category', 'Delete Category '],

            //Product Permissions
            ['product-index', 'product', 'View All Categories'],
            ['product-show', 'product', 'View Single Category'],
            ['product-edit', 'product', 'Edit Category'],
            ['product-delete', 'product', 'Delete Categories'],

            //Order Permissions
            ['order-index', 'order', 'View All Orders'],
            ['order-show', 'order', 'View Single Order'],
            ['order-edit', 'order', 'Edit Order'],
            ['order-delete', 'order', 'Delete Order'],
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission[0],
            ], [
                'category' => $permission[1],
                'display' => $permission[2]
            ]);
        }
    }
}
