<?php

use Illuminate\Database\Seeder;

class CountriesLatLonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $json = File::get(public_path()."/../database/data/countries_info.json");
        $data = json_decode($json, true);
        foreach($data['Results'] as $key => $value) {
        	DB::table('countries')->where('abbreviation', $key)->update([
	            'latitude' => $value['Capital']['GeoPt'][0],
	            'longitude' => $value['Capital']['GeoPt'][1]
	         ]);
        }
    }
}
