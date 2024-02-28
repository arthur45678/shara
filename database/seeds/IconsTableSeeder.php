<?php

use Illuminate\Database\Seeder;
use App\Icon;
class IconsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $icons = [
        	['name' => 'ln-icon-Baby-Clothes2'],
        	['name' => 'ln-icon-Cocktail'],
			['name' => 'ln-icon-Car-2'],
			['name' => 'ln-icon-Taxi'],
			['name' => 'ln-icon-Wrench'],
			['name' => 'ln-icon-Nurse'],
			['name' => 'ln-icon-Magnifi-Glass2'],
			['name' => 'ln-icon-Chef-Hat2'],
			['name' => 'ln-icon-Bicycle-3'],
			['name' => 'ln-icon-Dog'],
			['name' => 'ln-icon-Pizza-Slice'],
			['name' => 'ln-icon-Box-Close'],
			['name' => 'ln-icon-Girl'],
			['name' => 'ln-icon-Broom'],
			['name' => 'ln-icon-Waiter'],
			['name' => 'ln-icon-Box-Open'],
			['name' => 'ln-icon-Screwdriver'],
			['name' => 'ln-icon-Libra-2'],
			['name' => 'ln-icon-Hand'],
			['name' => 'ln-icon-Truck'],
			['name' => 'ln-icon-Worker'],
			['name' => 'ln-icon-Claps'],
			['name' => 'ln-icon-weight-Lift'],
			['name' => 'ln-icon-Black-Cat'],
			['name' => 'ln-icon-Camera-5'],
			['name' => 'ln-icon-Dj'],
			['name' => 'ln-icon-Cloud-Laptop'],
			['name' => 'ln-icon-Cash-register2'],
			['name' => 'ln-icon-Glasses-3'],
			['name' => 'ln-icon-Code-Window'],
			['name' => 'ln-icon-Support'],
			['name' => 'ln-icon-Teacher'],
			['name' => 'ln-icon-Key-2'],
			['name' => 'ln-icon-Record2'],
			['name' => 'ln-icon-Waiter']

        ];

        foreach ($icons as $icon) {
			Icon::insert([
				$icon
			]);
		}
    }
}
