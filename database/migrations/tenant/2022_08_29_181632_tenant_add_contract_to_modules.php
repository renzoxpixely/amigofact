<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class TenantAddContractToModules extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        $count = DB::table('modules')->select()->where('value', 'contracts')->get()->count();
        $user_id = 1;
        if($count == 0){
            /**ADD MODULE */
            $module_id = DB::table('modules')->insertGetId([
                'value' => 'contracts',
                'description' => 'Contratos',
                'order_menu' => 2
            ]);
            DB::table('module_user')->insert(['module_id' => $module_id, 'user_id' => $user_id]);
            /**ADD MODULE LEVELS */
            $module_levels = [
                ['value' => 'contracts', 'description' => 'Listado','module_id' => $module_id],
                ['value' => 'production_orders', 'description' => 'Ordenes de Producción','module_id' => $module_id],
                ['value' => 'contract_types', 'description' => 'Tipos de Contrato','module_id' => $module_id]
            ];
            foreach($module_levels as $module_level){
                $module_level_id = DB::table('module_levels')->insertGetId($module_level);
                DB::table('module_level_user')->insert(['module_level_id' => $module_level_id, 'user_id' => $user_id]);
            }
        }
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $module = DB::table('modules')->select('id')->where('value', 'contracts')->first();
        if($module){
            DB::table('module_user')->where('module_id', $module->id)->delete();
            $module_levels = DB::table('module_levels')->select('id')->where('module_id', $module->id)->get();
            foreach($module_levels as $module_level){
                DB::table('module_level_user')->where('module_level_id', $module_level->id)->delete();
                DB::table('module_levels')->where('id', $module_level->id)->delete();
            }
            DB::table('modules')->where('id', $module->id)->delete();
        }
    }
}
