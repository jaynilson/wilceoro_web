<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);

        $this->truncateTables(['rol','user','fleet']);
        $this->call(RolTableSeeder::class);
        $this->call(AdminUserSeeder::class);
        $this->call(FleetSeeder::class);
    }

    protected function truncateTables(array $tables){
        DB::statement('SET FOREIGN_KEY_CHECKS = 0;');
        foreach ($tables as $key => $table) 
        DB::table($table)->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS = 1;');
    
    }
}
