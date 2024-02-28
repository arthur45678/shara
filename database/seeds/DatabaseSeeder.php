<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // \App\User::truncate();
        // Cartalyst\Sentinel\Roles\EloquentRole::truncate();
        // \App\Permission::truncate();
        // \App\Language::truncate();
        Model::unguard();

        // $this->call(RoleTableSeeder::class);
        // $this->call(UsersTableSeeder::class);
        // $this->call(PermissionsTableSeeder::class);
        // $this->call(LanguagesTabelSeeder::class);
        // $this->call(CountriesLatLonSeeder::class);
        // $this->call(CountriesCapitalSeeder::class);
        // $this->call(NewCountriesCapitalSeeder::class);
         //$this->call(IconsTableSeeder::class);
         $this->call(NativesSeeder::class);
        // $this->call(SectorSeeder::class);

        
        Model::reguard();
    }
}
