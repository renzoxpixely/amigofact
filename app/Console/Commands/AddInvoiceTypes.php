<?php

namespace App\Console\Commands;

use App\Models\Tenant\Purchase;

use Illuminate\Support\Facades\DB;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class AddInvoiceTypes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:invoiceType';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Give purchase items the correct invoice type';

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
            $this->info('Starting with the update');

            DB::connection('tenant')->beginTransaction();
                $invoices = Purchase::all();

                foreach ($invoices as $invoice) {
                    foreach ($invoice->items as $item) {
                        if ($item->item->description === 'FLETE') {
                            $invoice->update(['invoice_type' => Purchase::INVOICE_TYPE_LADING]);
                        } else if ($item->item->description === 'SEGURO') {
                            $invoice->update(['invoice_type' => Purchase::INVOICE_TYPE_INSURANCE]);
                        }
                    }
                }

                $this->info('Invoice records updated successfully!');

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
