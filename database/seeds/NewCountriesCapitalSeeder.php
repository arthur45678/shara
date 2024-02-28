<?php

use Illuminate\Database\Seeder;

class NewCountriesCapitalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $json = File::get(public_path()."/../database/data/convertcsv.json");
        $data = json_decode($json, true);
        foreach($data as $value) {
        	DB::table('countries')->where('abbreviation', $value['Country code'])->update([
	            'latitude' => $value['Latitude'],
	            'longitude' => $value['Longitude']
	         ]);
        }
    }
}
