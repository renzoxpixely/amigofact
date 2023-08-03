<?php

use App\Models\Tenant\Establishment;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class TenantInsertEstablishmentClientToEstablishments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        $establishment = Establishment::find(1);
        $establishment_customer = Establishment::find(Establishment::ESTABLISHMENT_CUSTOMER_ID);
        if($establishment && !$establishment_customer){
            $new = $establishment->toArray();            
            $new['id'] = Establishment::ESTABLISHMENT_CUSTOMER_ID;
            $new['description'] = "Establecimiento de Clientes";
            $new_establishment = new Establishment();
            $new_establishment->fill($new);
            $new_establishment->save();
            DB::table('establishments')->where('id', $new_establishment->id)->update(['id' => Establishment::ESTABLISHMENT_CUSTOMER_ID]); 
        }
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //DB::table('establishments')->where('id', Establishment::ESTABLISHMENT_CUSTOMER_ID)->delete(); 
    }
}
