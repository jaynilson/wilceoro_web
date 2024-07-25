<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FleetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $id = 0;
        for ($i=0; $i < 100 ; $i++) { 
        
        $random = rand(null,3);
        $random2 = rand(0,7);
 
        $locations=[
            "ROYAL PALM",
            "JUPITER",
            "TREASURE COAST",
            "MIAMI",
            "COCOA",
            "DAYTONA",
            "SARASOTA",
            "6TH STREET"
        ];

    
         DB::table('fleet')->insert([
        'n' =>  $id+1,
        'model' =>  ($random==2)?"Hyundai Accent":(($random==3)?"Indetruck":"Indetruck"),
        'licence_plate' => 'A36855'.strval($id+1),
        'year' => '2011',
        'yard_location' => $locations[$random2],
        'department' => 'YARD',
        'status' => 'true',
        'picture' =>($random==2)?"trucktest.png":(($random==3)?"trailertest.png":"equipmenttest.png"),
        'type' => ($random==2)?"trucks_cars":(($random==3)?"trailers":"equipment"),
        'created_at'=>now(),
        'updated_at'=>now(),
        ]);
        $id = DB::getPdo()->lastInsertId();
        }
     
    }
}

