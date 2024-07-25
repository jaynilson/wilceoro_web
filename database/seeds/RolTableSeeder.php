<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class RolTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $rols = ['super_admin','tools_admin','fleet_admin','employee','mechanic' ];

        // $this->call(UsersTableSeeder::class);
        foreach ($rols as $key => $rol){
            $exist = DB::table('rol')->where("name",$rol)->first();

            if(!$exist){
                DB::table('rol')->insert([
                    'name'=> $rol,
                    'created_at' => Carbon::now()->format('Y-m-d H:i:s')//change to spain
                    ]);

            }

        }
 
    }
}
