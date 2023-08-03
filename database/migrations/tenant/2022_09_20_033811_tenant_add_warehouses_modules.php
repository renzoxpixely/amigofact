<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class TenantAddWarehousesModules extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /**ADD MODULE LEVELS */
        $module_levels = [
            ['value' => 'users_warehouses', 'description' => 'Almacenes','module_id' => 14]
        ];
        foreach($module_levels as $module_level){
            $module_level_id = DB::table('module_levels')->insertGetId($module_level);
            DB::table('module_level_user')->insert(['module_level_id' => $module_level_id, 'user_id' => 1]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $module_levels = DB::table('module_levels')->select('id')->where('module_id', 14)->where('description', 'Almacenes')->get();
        foreach($module_levels as $module_level){
            DB::table('module_level_user')->where('module_level_id', $module_level->id)->delete();
            DB::table('module_levels')->where('id', $module_level->id)->delete();
        }
    }
}
