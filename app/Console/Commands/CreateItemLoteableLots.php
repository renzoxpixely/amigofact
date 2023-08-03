<?php

namespace App\Console\Commands;

use App\Models\Tenant\Item;
use App\Models\Tenant\PurchaseItem;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Modules\Item\Models\ItemLot;

class CreateItemLoteableLots extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:itemLots';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creating the item lots for the corresponding purchases';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try {
            $this->info('Starting with creating the lots');

            DB::connection('tenant')->beginTransaction();
                $item_lots = ItemLot::all();
                
                foreach ($item_lots as $item_lot) {
                    if ($item_lot->item_loteable_type === PurchaseItem::class) {
                        $product = Item::findOrFail($item_lot->item_id);

                    $product->lots()->create([
                        'date' => $item_lot->enters_warehouse_at ?: $item_lot->date,
                        'series' => $item_lot->series,
                        'game_id' => $item_lot->game_id ?? null,
                        'mincetur_code' => $item_lot->mincetur_code ?? null,
                        'item_id' => $item_lot->item_id,
                        'warehouse_id' => $item_lot->warehouse_id,
                        'has_sale' => $item_lot->has_sale,
                        'state' => $item_lot->state ?? null
                    ]);  
                    }
                }
                

                $this->info('Item lots created successfully!');

            DB::connection('tenant')->commit();
                return 0;
        } catch(\Throwable $exception) {
            DB::connection('tenant')->rollBack();
            Log::error($exception);
            $this->error('Updating invoice records failed please check logs');

            return 1;
        }
    }
}
