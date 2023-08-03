<?php
namespace Modules\Dashboard\Traits;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Modules\Contract\Models\ContractItem;
use Modules\Contract\Models\ContractStateType;
use Modules\Contract\Models\ContractType;

trait ReportTrait
{

    private function indexOf($data, $contract, $item, $serie){
        foreach($data as $index => $row){
            if($row['contract'] == $contract && $row['item'] == $item && $row['serie'] == $serie) return $index;
        }
        return false;
    }

    private function indexOfSale($data, $sale_note){
        foreach($data as $index => $row){
            if($row['sale_note'] == $sale_note) return $index;
        }
        return false;
    }

    private function indexOfMonth($data, $date_of_issue){
        $date =  Carbon::parse($date_of_issue);
        if($data){
            foreach($data as $index => $row){
                if($date->format('M-Y') == $row['date']) return $index;
            }
        }
        return false;
    }

    private function getMonths($min_date, $max_date){
        $start =  Carbon::parse($min_date->format('Y-m-01'));
        $end =  Carbon::parse($max_date->format('Y-m-01'));
        $months = [$start->format('M-Y')];
        while(true){
            $next = $start->addMonth();
            if($next<$end){
                $months[] = $next->format('M-Y');
                $start = $next;
            }else{
                break;
            }
        }
        $months[] = $end->format('M-Y');
        return array_values(array_unique($months));

    }

    private function arrayMonths($months){
        $data = [];
        if($months){
            foreach($months as $month){
                $data[] = [
                    'date' => $month,
                    'total' => 0,
                    'total_payed' => 0,
                ];
            }
        }
        return $data;
    }

    private function getMonthsSubTitle($months){
        $months_subtitle = [];
        foreach($months as $row){
            $months_subtitle[] = 'Pagado';
            $months_subtitle[] = 'Pendiente';
        }
        return $months_subtitle;
    }

    public function report_stake($customer_id, $page = null){
        $contract_type = ContractType::where('name', ContractType::STAKE)->first();
        $data = [];
        $months = [];
        $total_records = 0;
        $page_size = config('tenant.items_per_page') ?? 20;
        if($contract_type){
            $conditions = "";
            if($customer_id){
                $conditions = " AND cc.customer_id = {$customer_id}";
            }
            $sql = "SELECT  
            CONCAT(cc.prefix,'-',cc.id) AS contract,
            cc.customer, ppt.description AS contact,
            it.description AS item, itlt.series AS serie,
            ph.name AS hall, dpt.description AS region, dtt.description AS city,
            CONCAT(sn.series,'-',sn.number) AS sale_note, sn.date_of_issue, sn.total,
            phi.mincetur,
            snp.date_of_payment, snp.payment,
            COALESCE(cil.activation_date, ci.activation_date) AS activation_date
            FROM contract_items ci 
            LEFT JOIN items it ON it.id = ci.item_id 
            LEFT JOIN contracts cc ON cc.id = ci.contract_id
            LEFT JOIN persons pp ON pp.id = cc.customer_id
            LEFT JOIN person_types ppt ON ppt.id = pp.person_type_id
            LEFT JOIN person_halls ph ON ph.id = ci.hall_id
            LEFT JOIN departments dpt ON dpt.id = ph.department_id
            LEFT JOIN districts dtt ON dtt.id = ph.district_id
            LEFT JOIN contract_item_lots cil ON ci.id = cil.contract_item_id
            LEFT JOIN stake_periods con ON cil.contract_item_id=con.contract_item_id AND cil.item_lot_id=con.item_lot_id
            LEFT JOIN sale_notes sn ON con.sale_note_id = sn.id
            LEFT JOIN sale_note_payments snp ON snp.sale_note_id = sn.id
            LEFT JOIN item_lots itlt ON itlt.id = cil.item_lot_id
            LEFT JOIN purchase_items phi ON itlt.item_loteable_type = 'App\\\\Models\\\\Tenant\\\\PurchaseItem' AND itlt.item_loteable_id = phi.id
            WHERE cc.contract_type_id = ?  {$conditions};";//AND cc.state_type_id = ?
            /**--WHERE cil.item_lot_id IS NOT NULL 
            --AND con.sale_note_id IS NOT NULL */
            $records = DB::connection('tenant')->select($sql, [$contract_type->id/*, ContractStateType::DELIVERED*/]);
    
            
            $min_date = null;$max_date = null;
            foreach($records as $idx => $row){
                $row = (array)$row;
            
                $sale_note = [
                    'sale_note' => $row['sale_note'],
                    'date_of_issue' => $row['date_of_issue'],
                    'total' => floatval($row['total']),
                    'total_payed' => floatval($row['payment']),
                ];
                $date_of_issue = Carbon::parse($sale_note['date_of_issue']);
                if($idx==0){
                    $min_date = $date_of_issue;
                    $max_date = $date_of_issue;
                }else{
                    if($date_of_issue<$min_date) $min_date = $date_of_issue;
                    if($date_of_issue>$max_date) $max_date = $date_of_issue;
                }

                $index = $this->indexOf($data, $row['contract'], $row['item'], $row['serie']);

                if($index !== false){
                    $index_sale = $this->indexOfSale($data[$index]['sale_notes'], $sale_note['sale_note']);
                    if($index_sale !== false){
                        $data[$index]['sale_notes'][$index_sale]['total_payed'] += $sale_note['total_payed'];
                    }else{
                        $data[$index]['sale_notes'][] = $sale_note;
                    }
                }else{

                    $customer = json_decode($row['customer'], true);
                    $mincetur = json_decode($row['mincetur'], true);

                    $customer = $customer ? $customer['name'] ?? null : null;
                    $mincetur = $mincetur &&  $mincetur['mincetur'] ? true : false;

                    $data[] = [
                        'contract' => $row['contract'],
                        'customer' => $customer,
                        'contact' => $row['contact'],
                        'item' => $row['item'],
                        'serie' => $row['serie'],
                        'hall' => $row['hall'],
                        'region' => $row['region'],
                        'city' => $row['city'],
                        'mincetur' => $mincetur,
                        'date_of_issue' => $sale_note['date_of_issue'],
                        'activation_date' => $row['activation_date'],
                        'invoice' => $sale_note['sale_note'],
                        'sale_notes' => [$sale_note]
                    ];
                }
            }
            $months_array=[];
            if($min_date && $max_date){
                $months = $this->getMonths($min_date, $max_date);
                $months_array = $this->arrayMonths($months);
            }

            foreach($data as $key => $row){
                $data[$key]['data'] =  $months_array;
                foreach($row['sale_notes'] as $sale_note){
                    $index = $this->indexOfMonth($data[$key]['data'], $sale_note['date_of_issue']);
                    if($index !== false){
                        $data[$key]['data'][$index]['total'] += $sale_note['total'];
                        $data[$key]['data'][$index]['total_payed'] += $sale_note['total_payed'];
                    }
                }
                unset($data[$key]['sale_notes']);
            }

            foreach($data as $key => $row){
                $result = [];
                foreach($row['data'] as $row2){
                    
                    $result[] = $row2['total_payed'];
                    $result[] = $row2['total'] - $row2['total_payed'];
                }
                $data[$key]['result'] = $result;
                unset($data[$key]['data']);
            }

            /**PAGE */
            $total_records = count($data);
            if($page!==null){
                $total_pages   = ceil($total_records / $page_size);
                if ($page > $total_pages) $page = $total_pages;
                if ($page < 1)  $page = 1;
                $offset = ($page - 1) * $page_size;
                $data = array_slice($data, $offset, $page_size);
            }
        }
        return ['data' => $data, 'months' => $months, 'months_subtitle' => $this->getMonthsSubTitle($months), 'page' => (int) $page, 'page_size' => (int) $page_size, 'total' => (int) $total_records];

    }

    public function report_sale($customer_id, $page = null){

        $contract_type = ContractType::where('name', ContractType::SALE)->first();
        $data = [];
        $months = [];
        $total_records = 0;
        $page_size = config('tenant.items_per_page') ?? 20;
        if($contract_type){
            $conditions = "";
            if($customer_id){
                $conditions = " AND cc.customer_id = {$customer_id}";
            }
            $sql = "SELECT  
            CONCAT(cc.prefix,'-',cc.id) AS contract,
            cc.customer, ppt.description AS contact,
            it.description AS item, itlt.series AS serie,
            ph.name AS hall, dpt.description AS region, dtt.description AS city,
            CONCAT(sn.series,'-',sn.number) AS sale_note, sn.date_of_issue, sn.total,
            phi.mincetur,
            snp.date_of_payment, snp.payment,
            COALESCE(cil.activation_date, ci.activation_date) AS activation_date
            FROM contract_items ci 
            LEFT JOIN items it ON it.id = ci.item_id 
            LEFT JOIN contracts cc ON cc.id = ci.contract_id
            LEFT JOIN persons pp ON pp.id = cc.customer_id
            LEFT JOIN person_types ppt ON ppt.id = pp.person_type_id
            LEFT JOIN person_halls ph ON ph.id = ci.hall_id
            LEFT JOIN departments dpt ON dpt.id = ph.department_id
            LEFT JOIN districts dtt ON dtt.id = ph.district_id
            LEFT JOIN contract_item_lots cil ON ci.id = cil.contract_item_id
            LEFT JOIN documents sn ON cc.document_id = sn.id
            LEFT JOIN document_payments snp ON snp.document_id = sn.id
            LEFT JOIN item_lots itlt ON itlt.id = cil.item_lot_id
            LEFT JOIN purchase_items phi ON itlt.item_loteable_type = 'App\\\\Models\\\\Tenant\\\\PurchaseItem' AND itlt.item_loteable_id = phi.id
            WHERE cc.contract_type_id = ?  {$conditions};";//AND cc.state_type_id = ?
            $records = DB::connection('tenant')->select($sql, [$contract_type->id/*, ContractStateType::DELIVERED*/]);
    
            
            $min_date = null;$max_date = null;
            foreach($records as $idx => $row){
                $row = (array)$row;
            
                $sale_note = [
                    'sale_note' => $row['sale_note'],
                    'date_of_issue' => $row['date_of_issue'],
                    'total' => floatval($row['total']),
                    'total_payed' => floatval($row['payment']),
                ];
                $date_of_issue = Carbon::parse($sale_note['date_of_issue']);
                if($idx==0){
                    $min_date = $date_of_issue;
                    $max_date = $date_of_issue;
                }else{
                    if($date_of_issue<$min_date) $min_date = $date_of_issue;
                    if($date_of_issue>$max_date) $max_date = $date_of_issue;
                }


                $customer = json_decode($row['customer'], true);
                $mincetur = json_decode($row['mincetur'], true);

                $customer = $customer ? $customer['name'] ?? null : null;
                $mincetur = $mincetur &&  $mincetur['mincetur'] ? true : false;

                $data[] = [
                    'contract' => $row['contract'],
                    'customer' => $customer,
                    'contact' => $row['contact'],
                    'item' => $row['item'],
                    'serie' => $row['serie'],
                    'hall' => $row['hall'],
                    'region' => $row['region'],
                    'city' => $row['city'],
                    'mincetur' => $mincetur,
                    'date_of_issue' => $sale_note['date_of_issue'],
                    'activation_date' => $row['activation_date'],
                    'invoice' => $sale_note['sale_note'],
                    'sale_note' => $sale_note
                ];
                
            }
            $months_array=[];
            if($min_date && $max_date){
                $months = $this->getMonths($min_date, $max_date);
                $months_array = $this->arrayMonths($months);
            }

            foreach($data as $key => $row){
                $data[$key]['data'] =  $months_array;
                $sale_note = $row['sale_note'];
                $index = $this->indexOfMonth($data[$key]['data'], $sale_note['date_of_issue']);
                if($index !== false){
                    $data[$key]['data'][$index]['total'] += $sale_note['total'];
                    $data[$key]['data'][$index]['total_payed'] += $sale_note['total_payed'];
                }
                unset($data[$key]['sale_note']);
            }

            foreach($data as $key => $row){
                $result = [];
                foreach($row['data'] as $row2){
                    $result[] = $row2['total_payed'];
                    $result[] = $row2['total'] - $row2['total_payed'];
                }
                $data[$key]['result'] = $result;
                unset($data[$key]['data']);
            }
            /**PAGE */
            $total_records = count($data);
            if($page!==null){
                $total_pages   = ceil($total_records / $page_size);
                if ($page > $total_pages) $page = $total_pages;
                if ($page < 1)  $page = 1;
                $offset = ($page - 1) * $page_size;
                $data = array_slice($data, $offset, $page_size);
            }
        }
        return ['data' => $data, 'months' => $months, 'months_subtitle' => $this->getMonthsSubTitle($months), 'page' => (int) $page, 'page_size' => (int) $page_size, 'total' => (int) $total_records];
    }

    public function report_rental($customer_id, $page = null){
        $contract_type = ContractType::where('name', ContractType::RENTAL)->first();
        $data = [];
        $months = [];
        $total_records = 0;
        $page_size = config('tenant.items_per_page') ?? 20;
        if($contract_type){
            $conditions = "";
            if($customer_id){
                $conditions = " AND cc.customer_id = {$customer_id}";
            }
            $sql = "SELECT  
            CONCAT(cc.prefix,'-',cc.id) AS contract,
            cc.customer, ppt.description AS contact,
            it.description AS item, itlt.series AS serie,
            ph.name AS hall, dpt.description AS region, dtt.description AS city,
            CONCAT(dd.series,'-',dd.number) AS sale_note, dd.date_of_issue, dd.total,
            phi.mincetur,
            snp.date_of_payment, snp.payment,
            COALESCE(cil.activation_date, ci.activation_date) AS activation_date
            FROM contract_items ci 
            LEFT JOIN items it ON it.id = ci.item_id 
            LEFT JOIN contracts cc ON cc.id = ci.contract_id
            LEFT JOIN persons pp ON pp.id = cc.customer_id
            LEFT JOIN person_types ppt ON ppt.id = pp.person_type_id
            LEFT JOIN person_halls ph ON ph.id = ci.hall_id
            LEFT JOIN departments dpt ON dpt.id = ph.department_id
            LEFT JOIN districts dtt ON dtt.id = ph.district_id
            LEFT JOIN contract_item_lots cil ON ci.id = cil.contract_item_id
            LEFT JOIN rental_periods con ON cil.contract_item_id=con.contract_item_id AND cil.item_lot_id=con.item_lot_id
            LEFT JOIN sale_notes sn ON con.sale_note_id = sn.id
            LEFT JOIN documents dd ON sn.document_id = dd.id
            LEFT JOIN document_payments snp ON snp.document_id = dd.id
            LEFT JOIN item_lots itlt ON itlt.id = cil.item_lot_id
            LEFT JOIN purchase_items phi ON itlt.item_loteable_type = 'App\\\\Models\\\\Tenant\\\\PurchaseItem' AND itlt.item_loteable_id = phi.id
           
            WHERE cc.contract_type_id = ?  {$conditions};";//AND cc.state_type_id = ?
            $records = DB::connection('tenant')->select($sql, [$contract_type->id/*, ContractStateType::DELIVERED*/]);
            /**--WHERE cil.item_lot_id IS NOT NULL 
            --AND sn.document_id IS NOT NULL */
            
            $min_date = null;$max_date = null;
            foreach($records as $idx => $row){
                $row = (array)$row;
            
                $sale_note = [
                    'sale_note' => $row['sale_note'],
                    'date_of_issue' => $row['date_of_issue'],
                    'total' => floatval($row['total']),
                    'total_payed' => floatval($row['payment']),
                ];
                $date_of_issue = Carbon::parse($sale_note['date_of_issue']);
                if($idx==0){
                    $min_date = $date_of_issue;
                    $max_date = $date_of_issue;
                }else{
                    if($date_of_issue<$min_date) $min_date = $date_of_issue;
                    if($date_of_issue>$max_date) $max_date = $date_of_issue;
                }

                $index = $this->indexOf($data, $row['contract'], $row['item'], $row['serie']);

                if($index !== false){
                    $index_sale = $this->indexOfSale($data[$index]['sale_notes'], $sale_note['sale_note']);
                    if($index_sale !== false){
                        $data[$index]['sale_notes'][$index_sale]['total_payed'] += $sale_note['total_payed'];
                    }else{
                        $data[$index]['sale_notes'][] = $sale_note;
                    }
                }else{

                    $customer = json_decode($row['customer'], true);
                    $mincetur = json_decode($row['mincetur'], true);

                    $customer = $customer ? $customer['name'] ?? null : null;
                    $mincetur = $mincetur &&  $mincetur['mincetur'] ? true : false;

                    $data[] = [
                        'contract' => $row['contract'],
                        'customer' => $customer,
                        'contact' => $row['contact'],
                        'item' => $row['item'],
                        'serie' => $row['serie'],
                        'hall' => $row['hall'],
                        'region' => $row['region'],
                        'city' => $row['city'],
                        'mincetur' => $mincetur,
                        'date_of_issue' => $sale_note['date_of_issue'],
                        'activation_date' => $row['activation_date'],
                        'sale_notes' => [$sale_note]
                    ];
                }
            }
            $months_array=[];
            if($min_date && $max_date){
                $months = $this->getMonths($min_date, $max_date);
                $months_array = $this->arrayMonths($months);
            }
            
            foreach($data as $key => $row){
                $data[$key]['data'] =  $months_array;
                $invoices = [];
                foreach($row['sale_notes'] as $sale_note){
                    $invoices[] = $sale_note['sale_note'];
                    $index = $this->indexOfMonth($data[$key]['data'], $sale_note['date_of_issue']);
                    if($index !== false){

                        $data[$key]['data'][$index]['total'] += $sale_note['total'];
                        $data[$key]['data'][$index]['total_payed'] += $sale_note['total_payed'];
                    }
                }
                $data[$key]['invoices'] = implode(",", $invoices);
                unset($data[$key]['sale_notes']);
            }

            foreach($data as $key => $row){
                $result = [];
                foreach($row['data'] as $row2){
                    $result[] = $row2['total_payed'];
                    $result[] = $row2['total'] - $row2['total_payed'];
                }
                $data[$key]['result'] = $result;
                unset($data[$key]['data']);
            }

            /**PAGE */
            $total_records = count($data);
            if($page!==null){
                $total_pages   = ceil($total_records / $page_size);
                if ($page > $total_pages) $page = $total_pages;
                if ($page < 1)  $page = 1;
                $offset = ($page - 1) * $page_size;
                $data = array_slice($data, $offset, $page_size);
            }
        }
        return ['data' => $data, 'months' => $months, 'months_subtitle' => $this->getMonthsSubTitle($months), 'page' => (int) $page, 'page_size' => (int) $page_size, 'total' => (int) $total_records];
    }
}