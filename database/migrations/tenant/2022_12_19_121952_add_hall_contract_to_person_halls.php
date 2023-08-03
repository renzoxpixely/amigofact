<?php

use App\Models\Tenant\Establishment;
use App\Models\Tenant\PersonHall;
use App\Models\Tenant\Warehouse;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AddHallContractToPersonHalls extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('ALTER TABLE `person_halls` CHANGE COLUMN `person_id` `person_id` INT(10) UNSIGNED NULL DEFAULT NULL AFTER `id`');

        $establishment = Establishment::find(1);
        $establishment_contract = Establishment::find(Establishment::ESTABLISHMENT_CONTRACT_ID);
        if($establishment && !$establishment_contract){
            $new = $establishment->toArray();            
            $new['id'] = Establishment::ESTABLISHMENT_CONTRACT_ID;
            $new['description'] = PersonHall::HALL_CONTRACT_NAME;
            $establishment_contract = Establishment::create($new);
            DB::table('establishments')->where('id', $establishment_contract->id)->update(['id' => Establishment::ESTABLISHMENT_CONTRACT_ID]); 
            $establishment_contract = Establishment::find(Establishment::ESTABLISHMENT_CONTRACT_ID);
        }
        if($establishment_contract){
            $warehouse_contract = Warehouse::find(Warehouse::WAREHOUSE_CONTRACT_ID);
            if(!$warehouse_contract){
                $warehouse_contract = Warehouse::create(['id'=> Warehouse::WAREHOUSE_CONTRACT_ID, 'description' => PersonHall::HALL_CONTRACT_NAME, 'establishment_id' => $establishment_contract->id]);
                DB::table('warehouses')->where('id', $warehouse_contract->id)->update(['id' => Warehouse::WAREHOUSE_CONTRACT_ID]); 
                $warehouse_contract = Warehouse::find(Warehouse::WAREHOUSE_CONTRACT_ID);
                /** */                
                $persona_hall = PersonHall::create(['id' => PersonHall::HALL_CONTRACT_ID, 'person_id' => null, 'name' => PersonHall::HALL_CONTRACT_NAME, 
                    'department_id' => '15', 'province_id' => '1501', 'district_id' => '150101', 'address' => '-', 'warehouse_id' => $warehouse_contract->id]);
                DB::table('person_halls')->where('id', $persona_hall->id)->update(['id' => PersonHall::HALL_CONTRACT_ID]); 
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
    }
}
