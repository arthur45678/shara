<?php

use Illuminate\Database\Seeder;
use \App\Permission;
class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // \App\Permission::truncate();

        \App\Permission::create([
        		'name' => 'user.create'
        ]);

        \App\Permission::create([
        		'name' => 'user.update'
        ]);

        \App\Permission::create([
        		'name' => 'user.delete'
        ]);

        \App\Permission::create([
        		'name' => 'user.view'
        ]);

        \App\Permission::create([
        		'name' => 'company.create'
        ]);

        \App\Permission::create([
        		'name' => 'company.update'
        ]);

        \App\Permission::create([
        		'name' => 'company.delete'
        ]);

        \App\Permission::create([
        		'name' => 'company.view'
        ]);

        \App\Permission::create([
        		'name' => 'job.create'
        ]);

        \App\Permission::create([
        		'name' => 'job.update'
        ]);

        \App\Permission::create([
        		'name' => 'job.delete'
        ]);

        \App\Permission::create([
        		'name' => 'job.view'
        ]);

        \App\Permission::create([
                'name' => 'role.create'
        ]);

        \App\Permission::create([
                'name' => 'role.update'
        ]);

        \App\Permission::create([
                'name' => 'role.delete'
        ]);

        \App\Permission::create([
                'name' => 'role.view'
        ]);

        \App\Permission::create([
                'name' => 'industry.create'
        ]);

        \App\Permission::create([
                'name' => 'industry.update'
        ]);

        \App\Permission::create([
                'name' => 'industry.delete'
        ]);

        \App\Permission::create([
                'name' => 'industry.view'
        ]);

        \App\Permission::create([
                'name' => 'category.create'
        ]);

        \App\Permission::create([
                'name' => 'category.update'
        ]);

        \App\Permission::create([
                'name' => 'category.delete'
        ]);

        \App\Permission::create([
                'name' => 'category.view'
        ]);

        \App\Permission::create([
                'name' => 'country.create'
        ]);

        \App\Permission::create([
                'name' => 'country.update'
        ]);

        \App\Permission::create([
                'name' => 'country.delete'
        ]);

        \App\Permission::create([
                'name' => 'country.view'
        ]);

    }
}
