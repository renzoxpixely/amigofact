<?php

    namespace Modules\Finance\Models;

    use App\Models\Tenant\Bank;
    use App\Models\Tenant\Cash;
    use App\Models\Tenant\DocumentPayment;
    use App\Models\Tenant\ModelTenant;
    use App\Models\Tenant\PurchasePayment;
    use App\Models\Tenant\SaleNotePayment;
    use App\Models\Tenant\SoapType;
    use App\Models\Tenant\TransferAccountPayment;
    use App\Models\Tenant\User;
    use Carbon\Carbon;
    use Eloquent;
    use Exception;
    use Hyn\Tenancy\Traits\UsesTenantConnection;
    use Illuminate\Database\Eloquent\Builder;
    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Database\Eloquent\Relations\BelongsTo;
    use Illuminate\Database\Eloquent\Relations\MorphTo;
    use Illuminate\Support\HigherOrderCollectionProxy;
use Modules\Contract\Models\ContractPayment;
use Modules\Expense\Models\BankLoanPayment;
    use Modules\Expense\Models\ExpensePayment;
    use Modules\Pos\Models\CashTransaction;
    use Modules\Sale\Models\QuotationPayment;
    use Modules\Sale\Models\TechnicalServicePayment;

    /**
     * Modules\Finance\Models\GlobalPayment
     *
     * @property int                          $id
     * @property string                       $soap_type_id
     * @property int|null                     $destination_id
     * @property string                       $destination_type
     * @property int                          $payment_id
     * @property string                       $payment_type
     * @property int|null                     $user_id
     * @property Carbon|null                  $created_at
     * @property Carbon|null                  $updated_at
     * @property SoapType                     $soap_type
     * @property User|null                    $user
     * @property-read CashTransaction         $cas_transaction
     * @property-read ContractPayment         $con_payment
     * @property-read Model|Eloquent          $destination
     * @property-read DocumentPayment         $doc_payments
     * @property-read TransferAccountPayment  $transfers_accounts
     * @property-read ExpensePayment          $exp_payment
     * @property-read mixed                   $data_person
     * @property-read mixed                   $destination_description
     * @property-read mixed                   $instance_type
     * @property-read mixed                   $instance_type_description
     * @property-read mixed                   $type_movement
     * @property-read mixed                   $type_record
     * @property-read IncomePayment           $inc_payment
     * @property-read Model|Eloquent          $payment
     * @property-read PurchasePayment         $pur_payment
     * @property-read QuotationPayment        $quo_payment
     * @property-read SaleNotePayment         $sln_payments
     * @property-read TechnicalServicePayment $tec_serv_payment
     * @method static Builder|GlobalPayment newModelQuery()
     * @method static Builder|GlobalPayment newQuery()
     * @method static Builder|GlobalPayment query()
     * @method static Builder|GlobalPayment whereDefinePaymentType($payment_type)
     * @method static Builder|GlobalPayment whereFilterPaymentType($params)
     * @mixin ModelTenant
     */
    class GlobalPayment extends ModelTenant
    {

        use UsesTenantConnection;

        protected $fillable = [
            'soap_type_id',
            'destination_id',
            'destination_type',
            'payment_id',
            'payment_type',
            'user_id',
        ];

        protected $casts = [
            'destination_id' => 'int',
            'payment_id' => 'int',
            'user_id' => 'int'
        ];

        /**
         * @return BelongsTo
         */
        public function soap_type()
        {
            return $this->belongsTo(SoapType::class);
        }

        /**
         * @return MorphTo
         */
        public function destination()
        {
            return $this->morphTo();
        }

        /**
         * @return MorphTo
         */
        public function payment()
        {
            return $this->morphTo();
        }

        /**
         * @return mixed
         */
        public function doc_payments()
        {
            return $this->belongsTo(DocumentPayment::class, 'payment_id')
                ->wherePaymentType(DocumentPayment::class);
        }

        /**
         * Filtra las transferencias
         *
         * @return mixed
         */
        public function transfers_accounts()
        {
            return $this
                ->belongsTo(TransferAccountPayment::class, 'payment_id')
                ->wherePaymentType(TransferAccountPayment::class);

        }

        /**
         * @return mixed
         */
        public function exp_payment()
        {
            return $this->belongsTo(ExpensePayment::class, 'payment_id')
                ->wherePaymentType(ExpensePayment::class);
        }

        /**
         * @return mixed
         */
        public function sln_payments()
        {
            return $this->belongsTo(SaleNotePayment::class, 'payment_id')
                ->wherePaymentType(SaleNotePayment::class);
        }

        /**
         * @return mixed
         */
        public function pur_payment()
        {
            return $this->belongsTo(PurchasePayment::class, 'payment_id')
                ->wherePaymentType(PurchasePayment::class);
        }

        /**
         * @return mixed
         */
        public function quo_payment()
        {
            return $this->belongsTo(QuotationPayment::class, 'payment_id')
                ->wherePaymentType(QuotationPayment::class);
        }

        /**
         * @return mixed
         */
        public function con_payment()
        {
            return $this->belongsTo(ContractPayment::class, 'payment_id')
                ->wherePaymentType(ContractPayment::class);
        }

        /**
         * @return mixed
         */
        public function bank_loan_payment()
        {
            return $this->belongsTo(BankLoanPayment::class, 'payment_id')
                ->wherePaymentType(BankLoanPayment::class);
        }
        /**
         * @return mixed
         */
        public function inc_payment()
        {
            return $this->belongsTo(IncomePayment::class, 'payment_id')
                ->wherePaymentType(IncomePayment::class);
        }

        /**
         * @return mixed
         */
        public function cas_transaction()
        {
            return $this->belongsTo(CashTransaction::class, 'payment_id')
                ->wherePaymentType(CashTransaction::class);
        }

        /**
         * @return mixed
         */
        public function tec_serv_payment()
        {
            return $this->belongsTo(TechnicalServicePayment::class, 'payment_id')
                ->wherePaymentType(TechnicalServicePayment::class);
        }

        /**
         * @return HigherOrderCollectionProxy|mixed|string
         */
        public function getDestinationDescriptionAttribute()
        {
            if ($this->destination_type === Cash::class) return 'CAJA GENERAL';
            $destination = $this->destination;
            try {
                $bank_id = $destination->bank_id;
                $bank = Bank::find($bank_id);
                if ($bank !== null) {
                    try {
                        return $bank->description." ". $bank->cci;
                    } catch (Exception $e) {
                        // do nothing
                        return '';
                    }
                }
            } catch (Exception $e) {
                // do nothing
                return '';
            }
            return '';
            return $this->destination_type === Cash::class ? 'CAJA GENERAL' : "{$this->destination->bank->description} - {$this->destination->currency_type_id} - {$this->destination->description}";
        }

        /**
         * @return string[]
         */
        public function getDestinationWithCci(): array
        {
            $data = [
                'name' => '',
                'description' => '',
                'cci' => '',
            ];
            if ($this->destination_type === Cash::class) {
                $data['name'] = 'CAJA GENERAL';
                return $data;
            }
            $destination = $this->destination;
            try {
                $data['description'] = $destination->description;
                $data['cci'] = $destination->cci;
                $bank_id = $destination->bank_id;
                $bank = Bank::find($bank_id);
                if ($bank !== null) {
                    try {
                        $data['name'] = $bank->description;
                    } catch (Exception $e) {
                        // do nothing

                    }
                }
            } catch (Exception $e) {
                // do nothing
            }
            return $data;

        }

        /**
         * @return string
         */
        public function getTypeRecordAttribute()
        {
            return $this->destination_type === Cash::class ? 'cash' : 'bank_account';
        }

        /**
         * @return string
         */
        public function getInstanceTypeAttribute()
        {
            $instance_type = [
                DocumentPayment::class => 'document',
                SaleNotePayment::class => 'sale_note',
                PurchasePayment::class => 'purchase',
                ExpensePayment::class => 'expense',
                QuotationPayment::class => 'quotation',
                ContractPayment::class => 'contract',
                BankLoanPayment::class => 'bank_loan',
                IncomePayment::class => 'income',
                CashTransaction::class => 'cash_transaction',
                TechnicalServicePayment::class => 'technical_service',
                TransferAccountPayment ::class => 'transfer_account',
            ];

            return $instance_type[$this->payment_type];
        }

        public function getInstanceTypeDescriptionAttribute()
        {

            $description = null;

            switch ($this->instance_type) {
                case 'document':
                    $description = 'CPE';
                    break;
                case 'sale_note':
                    $description = 'NOTA DE VENTA';
                    break;
                case 'purchase':
                    $description = 'COMPRA';
                    break;
                case 'expense':
                    $description = 'GASTO';
                    break;
                case 'quotation':
                    $description = 'COTIZACIÓN';
                    break;
                case 'contract':
                    $description = 'CONTRATO';
                    break;
                case 'bank_loan':
                    $description = 'PRESTAMO BANCARIO';
                    break;
                case 'income':
                    $description = 'INGRESO';
                    break;
                case 'cash_transaction':
                    $description = 'INGRESO';
                    break;
                case 'technical_service':
                    $description = 'SERVICIO TÉCNICO';
                    break;
            }

            return $description;
        }


        public function getTypeMovementAttribute()
        {
            $type = null;

            switch ($this->instance_type) {

                case 'document':
                case 'sale_note':
                case 'quotation':
                case 'contract':
                case 'bank_loan':
                case 'income':
                case 'cash_transaction':
                case 'technical_service':
                    $type = 'input';
                    break;
                case 'purchase':
                case 'expense':
                    $type = 'output';
                    break;

            }

            return $type;

        }


        public function getDataPersonAttribute()
        {
            $person['name'] = null;
            $person['number'] = null;
            
            $record = $this->payment->associated_record_payment;

            switch ($this->instance_type) {

                case 'document':
                case 'sale_note':
                case 'quotation':
                case 'contract':
                case 'technical_service':
                    $person['name'] = $record->customer->name;
                    $person['number'] = $record->customer->number;
                    break;
                case 'purchase':
                case 'expense':
                    $person['name'] = $record->supplier->name;
                    $person['number'] = $record->supplier->number;
                    break;
                case 'income':
                    $person['name'] = $record->customer;
                    $person['number'] = '';
                case 'bank_loan':
                    // @todo Ajustar los datos de banco
                    $bank = $record->bank ?? '';
                    $person['name'] = $bank;//." ".__FILE__;
                    $person['number'] = "";// __LINE__;
                case 'cash_transaction':
                    $person['name'] = '-';
                    $person['number'] = '';
            }

            return (object)$person;
        }


        /**
         * @param Builder $query
         * @param         $params
         *
         * @return Builder
         */
        public function scopeWhereFilterPaymentType(Builder $query, $params)
        {

            /** DocumentPayment  */
            $query->whereHas('doc_payments', function ($q) use ($params) {
                if ($params->date_start) {
                    $q->where('date_of_payment', '>=', $params->date_start);
                }
                if ($params->date_end) {
                    $q->where('date_of_payment', '<=', $params->date_end);
                }
                // $q->whereBetween('date_of_payment', [$params->date_start, $params->date_end])
                $q->whereHas('associated_record_payment', function ($p) {
                    $p->whereStateTypeAccepted()->whereTypeUser();
                });
            });
            $query->OrWhereHas('exp_payment', function ($q) use ($params) {
                if ($params->date_start) {
                    $q->where('date_of_payment', '>=', $params->date_start);
                }
                if ($params->date_end) {
                    $q->where('date_of_payment', '<=', $params->date_end);
                }
                // $q->whereBetween('date_of_payment', [$params->date_start, $params->date_end])
                $q->whereHas('associated_record_payment', function ($p) {
                    $p->whereStateTypeAccepted()->whereTypeUser();
                });
            });
            /*SaleNotePayment*/
            $query->OrWhereHas('sln_payments', function ($q) use ($params) {
                if ($params->date_start) {
                    $q->where('date_of_payment', '>=', $params->date_start);
                }
                if ($params->date_end) {
                    $q->where('date_of_payment', '<=', $params->date_end);
                }
                // $q->whereBetween('date_of_payment', [$params->date_start, $params->date_end])
                $q->whereHas('associated_record_payment', function ($p) {
                    $p->whereStateTypeAccepted()->whereTypeUser()
                        ->whereNotChanged();
                });
            });
            /*PurchasePayment*/
            $query->OrWhereHas('pur_payment', function ($q) use ($params) {
                if ($params->date_start) {
                    $q->where('date_of_payment', '>=', $params->date_start);
                }
                if ($params->date_end) {
                    $q->where('date_of_payment', '<=', $params->date_end);
                }
                // $q->whereBetween('date_of_payment', [$params->date_start, $params->date_end])
                $q->whereHas('associated_record_payment', function ($p) {
                    $p->whereStateTypeAccepted()->whereTypeUser();
                });

            });
            /*QuotationPayment*/
            $query
                ->OrWhereHas('quo_payment', function ($q) use ($params) {
                    if ($params->date_start) {
                        $q->where('date_of_payment', '>=', $params->date_start);
                    }
                    if ($params->date_end) {
                        $q->where('date_of_payment', '<=', $params->date_end);
                    }
                    // $q->whereBetween('date_of_payment', [$params->date_start, $params->date_end])
                    $q->whereHas('associated_record_payment', function ($p) {
                        $p->whereStateTypeAccepted()->whereTypeUser()
                            ->whereNotChanged();
                    });

                });
            /*ContractPayment*/
            $query
                ->OrWhereHas('con_payment', function ($q) use ($params) {
                    if ($params->date_start) {
                        $q->where('date_of_payment', '>=', $params->date_start);
                    }
                    if ($params->date_end) {
                        $q->where('date_of_payment', '<=', $params->date_end);
                    }
                    // $q->whereBetween('date_of_payment', [$params->date_start, $params->date_end])
                    $q->whereHas('associated_record_payment', function ($p) {
                        $p->whereStateTypeAccepted()->whereTypeUser()
                            ->whereNotChanged();
                    });

                });
            /* IncomePayment */
            $query
                ->OrWhereHas('inc_payment', function ($q) use ($params) {
                    if ($params->date_start) {
                        $q->where('date_of_payment', '>=', $params->date_start);
                    }
                    if ($params->date_end) {
                        $q->where('date_of_payment', '<=', $params->date_end);
                    }
                    // $q->whereBetween('date_of_payment', [$params->date_start, $params->date_end])
                    $q->whereHas('associated_record_payment', function ($p) {
                        $p->whereStateTypeAccepted()->whereTypeUser();
                    });

                });
            /*CashTransaction*/
            $query
                ->OrWhereHas('cas_transaction', function ($q) use ($params) {
                    if ($params->date_start) {
                        $q->where('date', '>=', $params->date_start);
                    }
                    if ($params->date_end) {
                        $q->where('date', '<=', $params->date_end);
                    }

                    // $q->whereBetween('date', [$params->date_start, $params->date_end]);
                });
            /* TechnicalServicePayment */
            $query
                ->OrWhereHas('tec_serv_payment', function ($q) use ($params) {
                    if ($params->date_start) {
                        $q->where('date_of_payment', '>=', $params->date_start);
                    }
                    if ($params->date_end) {
                        $q->where('date_of_payment', '<=', $params->date_end);
                    }
                    // $q->whereBetween('date_of_payment', [$params->date_start, $params->date_end])
                    $q->whereHas('associated_record_payment', function ($p) {
                        $p->whereTypeUser();
                    });

                });
            /** Transferencias entre cuentas/caja */
            $query->OrWhereHas('transfers_accounts');

            return $query;
        }

        /**
         * @return BelongsTo
         */
        public function user()
        {
            return $this->belongsTo(User::class);
        }

        /**
         * @param Builder $query
         * @param         $payment_type
         *
         * @return Builder
         */
        public function scopeWhereDefinePaymentType(Builder $query, $payment_type)
        {

            if ($payment_type === IncomePayment::class) {
                return $query->whereIn('payment_type', [CashTransaction::class, $payment_type]);
            }

            return $query->wherePaymentType($payment_type);

        }

    }
