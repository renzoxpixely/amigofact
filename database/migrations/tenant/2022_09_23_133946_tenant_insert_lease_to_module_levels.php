<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class TenantInsertLeaseToModuleLevels extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {   
        $user_id = 1;
        $module = DB::table('modules')->select()->where('value', 'contracts')->first();
        $count = DB::table('module_levels')->select()->where('value', 'lease_app')->get()->count();
        if($count == 0 && $module){
            $module_level_id = DB::table('module_levels')->insertGetId(['value' => 'lease_app', 'description' => 'Arrendamiento','module_id' => $module->id]);
            DB::table('module_level_user')->insert(['module_level_id' => $module_level_id, 'user_id' => $user_id]);
        }
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $module_level = DB::table('module_levels')->where('value', 'lease_app')->first();
        if($module_level){
            DB::table('module_level_user')->where('module_level_id', $module_level->id)->delete();
            DB::table('module_levels')->where('id', $module_level->id)->delete();
        }
    }
}
