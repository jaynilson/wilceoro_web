<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class RecordCategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = ['Belt Drive','Body & Lamp Assembly','Brake & Wheel Hub','Cooling System','Drivetrain', 'Electrical', "Electrical-Bulb & Socket", "Electrical-Connector", "Electrical-Switch & Reply", "Engine", "Exhaust & Emission", "Fuel & Air", "Heat & Air Conditioning", "Ignition", "Interior", "Steering", "Suspension", "Transmission-Automatic", "Transmission-Manual", "Wiper & Washer" ];

        foreach ($categories as $key => $category){
            $exist = DB::table('record_category')->where("name",$category)->first();

            if(!$exist){
                DB::table('record_category')->insert([
                    'name'=> $category,
                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
                ]);

            }

        }
 
    }
}
