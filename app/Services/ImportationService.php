<?php

namespace App\Services;

use App\Models\Tenant\Importation;
use App\Models\Tenant\Purchase;
use Carbon\Carbon;

class ImportationService
{
    /** @var Importation $importation  **/
    public $importation = null;

    public function getParentReport(int $importation_id): array
    {
        $report_invoices = [];

        $this->importation = Importation::findOrFail($importation_id);

        $total_weight_cost = $this->getTotalWeightCostDol();
        $total_value = max($this->getTotalValueDol(), 1);
        $total_cost = $this->getTotalCost();
        $total_lading = $this->getTotalLadingDol();
        $total_insurance = $this->getTotalInsuranceDol();

        foreach ($this->importation->invoices as $invoice) {
            if ($invoice->invoice_type) {
                continue;
            }

           foreach ($invoice->items as $purchase_item) {

               $exchange_rate = $invoice->exchange_rate_sale;
               $item_cost_dol = $invoice->currency_type_id === Purchase::CURRENCY_USD ? $purchase_item->total_value : $purchase_item->total_value * $exchange_rate;
               $ad_valorem_pct = ($this->importation->ad_valorem_dol > 1 && $purchase_item->prorated_by_ad_valorem) ? round(($item_cost_dol / $this->importation->ad_valorem_dol) * 100, 2) : 0;
               $weight_pct = round(($purchase_item->weight / max($this->getTotalWeight(), 1)) * 100, 2);
               $cost_pct = round(($item_cost_dol / $total_value) * 100, 2);
               $item_quantity = max(intval($purchase_item->quantity), 1);

               $ad_valorem_dol = round(($ad_valorem_pct * $this->importation->ad_valorem_dol) / 100, 2);
               $lading_dol = round(($total_lading * $weight_pct) / 100, 2);
               $insurance_dol = round(($total_insurance * $cost_pct) / 100, 2);
               $import_cost_dol = round((($weight_pct * $total_weight_cost) + ($cost_pct * $total_cost)) / 100 - $lading_dol - $insurance_dol, 2);
               $total_cost_dol = round($ad_valorem_dol + $lading_dol + $insurance_dol + $import_cost_dol + $purchase_item->total_value, 2);

               $report_invoices[] = [
                   'id' => $purchase_item->id,
                   'import_order_task' => $this->importation->import_order_task,
                   'invoice' => $invoice->number,
                   'date' => Carbon::parse($invoice->date_of_issue)->format('Y-m-d'),
                   'part_number' => $purchase_item->matrix_code,
                   'name' => $purchase_item->item->name,
                   'description' => $purchase_item->item->description,
                   'category' => $purchase_item->item->category ?: '',
                   'quantity' => $item_quantity,
                   'unit_price' => round($purchase_item->unit_value, 2),
                   'amount_usd' => round($item_cost_dol,2),
                   'exchange_rate' => $invoice->exchange_rate_sale,
                   'amount_sol' => round($item_cost_dol * $exchange_rate, 2),
                   'afecto_ad_valorem' => $purchase_item->prorated_by_ad_valorem ? 'Sí' : 'No',
                   'weight' => $purchase_item->weight,
                   'ad_valorem_pct' => $ad_valorem_pct . "%",
                   'weight_pct' => $weight_pct . "%",
                   'cost_pct' => $cost_pct . "%",
                   'ad_valorem_dol' =>  $ad_valorem_dol,
                   'lading_dol' => $lading_dol,
                   'insurance_dol' => $insurance_dol,
                   'import_cost_dol' => $import_cost_dol,
                   'total_cost_dol' => $total_cost_dol,
                   'unit_cost' => $total_cost_dol / $item_quantity,
                   'ad_valorem_sol' => round($ad_valorem_dol * $this->importation->exchange_rate, 2),
                   'lading_sol' => round($lading_dol * $exchange_rate, 2),
                   'insurance_sol' => round($insurance_dol * $exchange_rate, 2),
                   'import_cost_sol' => round($import_cost_dol * $exchange_rate, 2),
                   'total_cost_sol' => round($total_cost_dol * $exchange_rate, 2),
                   'unit_cost_sol' => round(($total_cost_dol / $item_quantity) * $exchange_rate, 2)
            ];
           }
        }

        return $report_invoices;
    }

    public function getTotalWeight(): float
    {
        $weight_items_total = [];

        foreach ($this->importation->invoices as $invoice) {
            foreach ($invoice->items as $item) {
                $weight_items_total[] = $item->weight;
            }
        }

        return round(array_reduce($weight_items_total, function($carry, $item) {
            return $carry += $item;
        }), 2);
    }

    public function getTotalValueDol(): float
    {
        $cost_items_total = [];

        foreach ($this->importation->invoices as $invoice) {
            foreach ($invoice->items as $item) {
                // if the invoice is part of the import costs ignore it
                if ($invoice->invoice_type !== null) {
                    continue;
                }

                if ($invoice->currency_type_id === Purchase::CURRENCY_USD) {
                    $cost_items_total[] = $item->total_value;
                } else {
                    $cost_items_total[] = $item->total_value * $invoice->exchange_rate_sale;
                }
            }
        }

        return round(array_reduce($cost_items_total, function($carry, $item) {
            return $carry += $item;
        }), 2);
    }

    public function getTotalWeightCostDol(): float
    {
        $weight_cost_total = [];

        foreach ($this->importation->invoices as $invoice) {
            foreach ($invoice->items_prorated_by_weight as $item) {
                if ($invoice->currency_type_id === Purchase::CURRENCY_USD) {
                    $weight_cost_total[] = $item->total_value;
                } else {
                    $weight_cost_total[] = $item->total_value * $invoice->exchange_rate_sale;
                }

            }
        }

        return round(array_reduce($weight_cost_total, function($carry, $item) {
            return $carry += $item;
        }), 2);
    }

    public function getTotalLadingDol(): float
    {
        $ladingInvoices = $this->importation->invoices->where('invoice_type', 'lading')->all();
        $totalLading = array_reduce($ladingInvoices, function($carry, $invoice) {
            if ($invoice->currency_type_id === Purchase::CURRENCY_USD) {
                return $carry += $invoice['total_value'];
            } else {
                return $carry += ($invoice['total_value'] * $invoice->exchange_rate_sale);
            }

        });

        return round($totalLading, 2);
    }

    public function getTotalInsuranceDol(): float
    {
        $insuranceInvoices = $this->importation->invoices->where('invoice_type', 'insurance')->all();
        $totalInsurance = array_reduce($insuranceInvoices, function($carry, $invoice) {
            if ($invoice->currency_type_id === Purchase::CURRENCY_USD) {
                return $carry += $invoice['total_value'];
            } else {
                return $carry += ($invoice['total_value'] * $invoice->exchange_rate_sale);
            }
        });

        return round($totalInsurance, 2);
    }

    public function getTotalCost(): float
    {
        $total_cost = [];

        foreach ($this->importation->invoices as $invoice) {
            foreach ($invoice->items_prorated_by_cost as $item) {
                if ($invoice->currency_type_id === Purchase::CURRENCY_USD) {
                    $total_cost[] = $item->total_value;
                } else {
                    $total_cost[] = $item->total_value * $invoice->exchange_rate_sale;
                }
            }
        }

        return round(array_reduce($total_cost, function($carry, $item) {
            return $carry += $item;
        }), 2);
    }

}
