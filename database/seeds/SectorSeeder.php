<?php

use Illuminate\Database\Seeder;

class SectorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $countries = [
            ['id' => 1, 'name' => 'gr'],
            ['id' => 2, 'name' => 'fr'],
            ['id' => 3, 'name' => 'en'],
            ['id' => 4, 'name' => 'de'],
        ];

        \App\Sector::insert($countries);

        $countryTranslations = [
            ['sector_id' => 1, 'locale' => 'el', 'name' => 'Ελλάδα'],
            ['sector_id' => 1, 'locale' => 'fr', 'name' => 'Grèce'],
            ['sector_id' => 1, 'locale' => 'en', 'name' => 'Greece'],
            ['sector_id' => 1, 'locale' => 'de', 'name' => 'Griechenland'],
            ['sector_id' => 2, 'locale' => 'en', 'name' => 'France'],
        ];

        \App\SectorTranslation::insert($countryTranslations);
    }
}
