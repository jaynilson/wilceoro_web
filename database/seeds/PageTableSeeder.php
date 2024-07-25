<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class PageTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $pages = [
            ['id' => 1, 'name' => 'Users List', 'module' => 'User'],
            ['id' => 2, 'name' => 'User Overview', 'module' => 'User'],
            ['id' => 3, 'name' => 'Vehicles List', 'module' => 'Vehicle'],
            ['id' => 4, 'name' => 'Vehicle Overview', 'module' => 'Vehicle'],
            ['id' => 5, 'name' => 'Vehicle Manual Record', 'module' => 'Vehicle'],
            ['id' => 6, 'name' => 'Vehicle Manual Aggsin Inventory', 'module' => 'Vehicle'],
            ['id' => 7, 'name' => 'Vehicle Documents', 'module' => 'Vehicle'],
            ['id' => 8, 'name' => 'Vehicle Custom Fields Setting', 'module' => 'Vehicle'],
            ['id' => 9, 'name' => 'Inspection Items', 'module' => 'Vehicle'],
            ['id' => 10, 'name' => 'Request Category', 'module' => 'Vehicle'],
            ['id' => 11, 'name' => 'Service List', 'module' => 'Service'],
            ['id' => 12, 'name' => 'Service Record', 'module' => 'Service'],
            ['id' => 13, 'name' => 'Inventory', 'module' => 'Inventory'],
            ['id' => 14, 'name' => 'Global Finance (Inventory price, service cost, etc)', 'module' => 'Finance'],
            ['id' => 15, 'name' => 'Rental', 'module' => 'Rental'],
            ['id' => 16, 'name' => 'Report', 'module' => 'Report'],
            ['id' => 17, 'name' => 'Reminder', 'module' => 'Reminder'],
        ];
        foreach ($pages as $page){
            $exist = DB::table('page')->find($page['id']);
            if($exist){
                $exist->name = $page['name'];
                $exist->module = $page['module'];
                $exist->save();
            }else{
                $page['created_at'] = Carbon::now()->format('Y-m-d H:i:s');
                $page['updated_at'] = Carbon::now()->format('Y-m-d H:i:s');
                DB::table('page')->insert($page);
            }
        }
    }
}
