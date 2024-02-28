<?php

use Illuminate\Database\Seeder;
use Cartalyst\Sentinel\Roles\EloquentRole;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		// Cartalyst\Sentinel\Roles\EloquentRole::truncate();

        Cartalyst\Sentinel\Roles\EloquentRole::create([
        	'slug' => 'superadmin',
        	'name' => 'SuperAdmin',
        	'permissions' => [
        		'user.create' => true,
                'user.view' => true,
                'user.update' => true,
                'user.delete' => true,
                'company.create' => true,
                'company.view' => true,
                'company.update' => true,
                'company.delete' => true,
                'job.create' => true,
                'job.view' => true,
                'job.update' => true,
                'job.delete' => true,
                'role.create' => true,
                'role.view' => true,
                'role.update' => true,
                'role.delete' => true,
                'industry.create' => true,
                'industry.view' => true,
                'industry.update' => true,
                'industry.delete' => true,
                'category.create' => true,
                'category.view' => true,
                'category.update' => true,
                'category.delete' => true,
                'country.create' => true,
                'country.view' => true,
                'country.update' => true,
                'country.delete' => true,
                'city.create' => true,
                'city.view' => true,
                'city.update' => true,
                'city.delete' => true
        	]
        ]);

        Cartalyst\Sentinel\Roles\EloquentRole::create([
            'slug' => 'admin',
            'name' => 'Admin',
            'permissions' => [
                'company.create' => true,
                'company.view' => true,
                'company.update' => true,
                'company.delete' => true,
                'job.create' => true,
                'job.view' => true,
                'job.update' => true,
                'job.delete' => true,
                'role.create' => true,
                'role.view' => true,
                'role.update' => true,
                'role.delete' => true,
                'industry.create' => true,
                'industry.view' => true,
                'industry.update' => true,
                'industry.delete' => true,
                'category.create' => true,
                'category.view' => true,
                'category.update' => true,
                'category.delete' => true,
                'country.create' => true,
                'country.view' => true,
                'country.update' => true,
                'country.delete' => true,
                'city.create' => true,
                'city.view' => true,
                'city.update' => true,
                'city.delete' => true
            ]
        ]);
    }
}
