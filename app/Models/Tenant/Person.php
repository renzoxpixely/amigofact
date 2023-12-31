<?php

    namespace App\Models\Tenant;

    use App\Models\Tenant\Catalogs\AddressType;
    use App\Models\Tenant\Catalogs\Country;
    use App\Models\Tenant\Catalogs\Department;
    use App\Models\Tenant\Catalogs\District;
    use App\Models\Tenant\Catalogs\IdentityDocumentType;
    use App\Models\Tenant\Catalogs\Province;
    use Hyn\Tenancy\Traits\UsesTenantConnection;
    use Illuminate\Database\Eloquent\Builder;
    use Illuminate\Database\Eloquent\Collection;
    use Modules\DocumentaryProcedure\Models\DocumentaryFile;
    use Modules\Expense\Models\Expense;
    use Modules\Order\Models\OrderForm;
    use Modules\Order\Models\OrderNote;
    use Modules\Purchase\Models\FixedAssetPurchase;
    use Modules\Purchase\Models\PurchaseOrder;
    use Modules\Contract\Models\Contract;
    use Modules\Sale\Models\SaleOpportunity;
    use Modules\Sale\Models\TechnicalService;


    /**
     * App\Models\Tenant\Person
     *
     * @property-read AddressType                     $address_type
     * @property-read Collection|PersonAddress[]      $addresses
     * @property-read int|null                        $addresses_count
     * @property-read Collection|Contract[]           $contracts_where_customer
     * @property-read int|null                        $contracts_where_customer_count
     * @property-read Country                         $country
     * @property-read Department                      $department
     * @property-read Collection|Dispatch[]           $dispatches_where_customer
     * @property-read int|null                        $dispatches_where_customer_count
     * @property-read District                        $district
     * @property-read Collection|DocumentaryFile[]    $documentary_files
     * @property-read int|null                        $documentary_files_count
     * @property-read Collection|Document[]           $documents
     * @property-read int|null                        $documents_count
     * @property-read Collection|Document[]           $documents_where_customer
     * @property-read int|null                        $documents_where_customer_count
     * @property-read Collection|Expense[]            $expenses_where_supplier
     * @property-read int|null                        $expenses_where_supplier_count
     * @property-read Collection|FixedAssetPurchase[] $fixed_asset_purchases_where_customer
     * @property-read int|null                        $fixed_asset_purchases_where_customer_count
     * @property-read Collection|FixedAssetPurchase[] $fixed_asset_purchases_where_supplier
     * @property-read int|null                        $fixed_asset_purchases_where_supplier_count
     * @property-read mixed                           $address_full
     * @property mixed                                $contact
     * @property-read IdentityDocumentType            $identity_document_type
     * @property-read Collection|PersonAddress[]      $more_address
     * @property-read int|null                        $more_address_count
     * @property-read Collection|OrderForm[]          $order_forms_where_customer
     * @property-read int|null                        $order_forms_where_customer_count
     * @property-read Collection|OrderNote[]          $order_notes_where_customer
     * @property-read int|null                        $order_notes_where_customer_count
     * @property-read Collection|Perception[]         $perceptions_where_customer
     * @property-read int|null                        $perceptions_where_customer_count
     * @property-read Collection|PersonAddress[]      $person_addresses
     * @property int|null                             $parent_id
     * @property-read \App\Models\Tenant\Person       $parent_person
     * @property-read \App\Models\Tenant\Person       $children_person
     * @property-read int|null                        $person_addresses_count
     * @property-read PersonType                      $person_type
     * @property-read Province                        $province
     * @property-read Collection|PurchaseOrder[]      $purchase_orders_where_supplier
     * @property-read int|null                        $purchase_orders_where_supplier_count
     * @property-read Collection|PurchaseSettlement[] $purchase_settlements_where_supplier
     * @property-read int|null                        $purchase_settlements_where_supplier_count
     * @property-read Collection|Purchase[]           $purchases_where_customer
     * @property-read int|null                        $purchases_where_customer_count
     * @property-read Collection|Purchase[]           $purchases_where_supplier
     * @property-read int|null                        $purchases_where_supplier_count
     * @property-read Collection|Quotation[]          $quotations_where_customer
     * @property-read int|null                        $quotations_where_customer_count
     * @property-read Collection|Retention[]          $retentions_where_supplier
     * @property-read int|null                        $retentions_where_supplier_count
     * @property-read Collection|SaleNote[]           $sale_notes_where_customer
     * @property-read int|null                        $sale_notes_where_customer_count
     * @property-read Collection|SaleOpportunity[]    $sale_opportunities_where_customer
     * @property-read int|null                        $sale_opportunities_where_customer_count
     * @property-read Collection|TechnicalService[]   $technical_services_where_customer
     * @property-read int|null                        $technical_services_where_customer_count
     * @method static Builder|Person newModelQuery()
     * @method static Builder|Person newQuery()
     * @method static Builder|Person query()
     * @method static Builder|Person whereIsEnabled()
     * @method static Builder|Person whereType($type)
     * @mixin ModelTenant
     * @mixin \Eloquent
     */
    class Person extends ModelTenant
    {
        use UsesTenantConnection;

        protected $table = 'persons';
        protected $with = [
            'identity_document_type',
            'country',
            'department',
            'province',
            'district'
        ];
        protected $casts = [
            'perception_agent' => 'bool',
            'person_type_id' => 'int',
            'percentage_perception' => 'float',
            'enabled' => 'bool',
            'status' => 'int',
            'credit_days' => 'int',
            'parent_id' => 'int'
        ];
        protected $fillable = [
            'type',
            'identity_document_type_id',
            'number',
            'name',
            'trade_name',
            'internal_code',
            'country_id',
            'department_id',
            'province_id',
            'district_id',
            'address_type_id',
            'address',
            'condition',
            'state',
            'email',
            'telephone',
            'perception_agent',
            'person_type_id',
            'contact',
            'comment',
            'percentage_perception',
            'enabled',
            'website',
            'zone',
            'observation',
            'credit_days',
            'optional_email',
            'status',
            'parent_id',
            'edad',
            'user_id'
        ];

        // protected static function boot()
        // {
        //     parent::boot();

        //     static::addGlobalScope('active', function (Builder $builder) {
        //         $builder->where('status', 1);
        //     });
        // }

        /**
         * Devuelve un conjunto de hijos basado en parent_id
         *
         * @return \Illuminate\Database\Eloquent\Relations\HasMany
         */
        public function children_person()
        {
            return $this->hasMany(Person::class, 'parent_id');
        }
        /**
         * Devuelve el padre basado en parent_id
         *
         * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
         */
        public function parent_person()
        {
            return $this->belongsTo(Person::class, 'parent_id');

        }

        /**
         * @return \Illuminate\Database\Eloquent\Relations\HasMany
         */
        public function person_addresses()
        {
            return $this->hasMany(PersonAddress::class);
        }

        /**
         * @return \Illuminate\Database\Eloquent\Relations\HasMany
         */
        public function addresses()
        {
            return $this->hasMany(PersonAddress::class);
        }

        /**
         * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
         */
        public function identity_document_type()
        {
            return $this->belongsTo(IdentityDocumentType::class, 'identity_document_type_id');
        }

        /**
         * @return \Illuminate\Database\Eloquent\Relations\HasMany
         */
        public function documents()
        {
            return $this->hasMany(Document::class, 'customer_id');
        }

        /**
         * @return \Illuminate\Database\Eloquent\Relations\HasMany
         */
        public function documents_where_customer()
        {
            return $this->hasMany(Document::class, 'customer_id');
        }

        /**
         * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
         */
        public function country()
        {
            return $this->belongsTo(Country::class);
        }

        /**
         * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
         */
        public function department()
        {
            return $this->belongsTo(Department::class);
        }

        /**
         * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
         */
        public function province()
        {
            return $this->belongsTo(Province::class);
        }

        /**
         * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
         */
        public function district()
        {
            return $this->belongsTo(District::class);
        }

        /**
         * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
         */
        public function address_type()
        {
            return $this->belongsTo(AddressType::class);
        }

        /**
         * @param $query
         * @param $type
         *
         * @return mixed
         */
        public function scopeWhereType($query, $type)
        {
            return $query->where('type', $type);
        }

        public function getAddressFullAttribute()
        {
            $address = trim($this->address);
            $address = ($address === '-' || $address === '') ? '' : $address . ' ,';
            if ($address === '') {
                return '';
            }
            return "{$address} {$this->department->description} - {$this->province->description} - {$this->district->description}";
        }

        /**
         * @return \Illuminate\Database\Eloquent\Relations\HasMany
         */
        public function more_address()
        {
            return $this->hasMany(PersonAddress::class);
        }

        /**
         * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
         */
        public function person_type()
        {
            return $this->belongsTo(PersonType::class);
        }

        /**
         * @return \Illuminate\Database\Eloquent\Relations\HasMany
         */
        public function contracts_where_customer()
        {
            return $this->hasMany(Contract::class, 'customer_id');
        }

        /**
         * @return \Illuminate\Database\Eloquent\Relations\HasMany
         */
        public function dispatches_where_customer()
        {
            return $this->hasMany(Dispatch::class, 'customer_id');
        }

        /**
         * @return \Illuminate\Database\Eloquent\Relations\HasMany
         */
        public function documentary_files()
        {
            return $this->hasMany(DocumentaryFile::class);
        }

        /**
         * @param $query
         *
         * @return mixed
         */
        public function scopeWhereIsEnabled($query)
        {
            return $query->where('enabled', true);
        }

        /**
         * @return \Illuminate\Database\Eloquent\Relations\HasMany
         */
        public function expenses_where_supplier()
        {
            return $this->hasMany(Expense::class, 'supplier_id');
        }

        /**
         * @return \Illuminate\Database\Eloquent\Relations\HasMany
         */
        public function fixed_asset_purchases_where_customer()
        {
            return $this->hasMany(FixedAssetPurchase::class, 'customer_id');
        }

        /**
         * @return \Illuminate\Database\Eloquent\Relations\HasMany
         */
        public function fixed_asset_purchases_where_supplier()
        {
            return $this->hasMany(FixedAssetPurchase::class, 'supplier_id');
        }

        /**
         * @return \Illuminate\Database\Eloquent\Relations\HasMany
         */
        public function order_forms_where_customer()
        {
            return $this->hasMany(OrderForm::class, 'customer_id');
        }

        /**
         * @return \Illuminate\Database\Eloquent\Relations\HasMany
         */
        public function order_notes_where_customer()
        {
            return $this->hasMany(OrderNote::class, 'customer_id');
        }

        /**
         * @return \Illuminate\Database\Eloquent\Relations\HasMany
         */
        public function perceptions_where_customer()
        {
            return $this->hasMany(Perception::class, 'customer_id');
        }


        /**
         * @return \Illuminate\Database\Eloquent\Relations\HasMany
         */
        public function purchase_orders_where_supplier()
        {
            return $this->hasMany(PurchaseOrder::class, 'supplier_id');
        }

        /**
         * @return \Illuminate\Database\Eloquent\Relations\HasMany
         */
        public function purchase_settlements_where_supplier()
        {
            return $this->hasMany(PurchaseSettlement::class, 'supplier_id');
        }

        /**
         * @return \Illuminate\Database\Eloquent\Relations\HasMany
         */
        public function purchases_where_customer()
        {
            return $this->hasMany(Purchase::class, 'customer_id');
        }

        /**
         * @return \Illuminate\Database\Eloquent\Relations\HasMany
         */
        public function purchases_where_supplier()
        {
            return $this->hasMany(Purchase::class, 'supplier_id');
        }

        /**
         * @return \Illuminate\Database\Eloquent\Relations\HasMany
         */
        public function quotations_where_customer()
        {
            return $this->hasMany(Quotation::class, 'customer_id');
        }

        /**
         * @return \Illuminate\Database\Eloquent\Relations\HasMany
         */
        public function retentions_where_supplier()
        {
            return $this->hasMany(Retention::class, 'supplier_id');
        }

        /**
         * @return \Illuminate\Database\Eloquent\Relations\HasMany
         */
        public function sale_notes_where_customer()
        {
            return $this->hasMany(SaleNote::class, 'customer_id');
        }

        /**
         * @return \Illuminate\Database\Eloquent\Relations\HasMany
         */
        public function sale_opportunities_where_customer()
        {
            return $this->hasMany(SaleOpportunity::class, 'customer_id');
        }

        /**
         * @return \Illuminate\Database\Eloquent\Relations\HasMany
         */
        public function technical_services_where_customer()
        {
            return $this->hasMany(TechnicalService::class, 'customer_id');
        }

        public function getContactAttribute($value)
        {
            return (is_null($value)) ? null : (object)json_decode($value);
        }

        public function setContactAttribute($value)
        {
            $this->attributes['contact'] = (is_null($value)) ? null : json_encode($value);
        }

        /**
         * Retorna un standar de nomenclatura para el modelo
         *
         * @param bool $withFullAddress
         * @param bool $childrens
         *
         * @return array
         */
        public function getCollectionData($withFullAddress = false, $childrens = false)
        {

            $addresses = $this->addresses;
            if ($withFullAddress == true) {
                $addresses = collect($addresses)->transform(function ($row) {
                    return $row->getCollectionData();
                });
            }
            $person_halls = $this->halls;
            $person_halls = collect($person_halls)->transform(function ($row) {
                return $row->getCollectionData();
            });
			
			$user = $this->user;
			if($user) $user = ['name' => $user->name, 'email' => $user->email];

            $person_type_descripton = '';
            if ($this->person_type !== null) {
                $person_type_descripton = $this->person_type->description;
            }
            $optional_mail = $this->getOptionalEmailArray();
            $optional_mail_send = [];
            if ( !empty($this->email)) {
                $optional_mail_send[] = $this->email;
            }
            $total_optional_mail = count($optional_mail);
            for ($i = 0; $i < $total_optional_mail; $i++) {
                $temp = trim($optional_mail[$i]['email']);
                if ( !empty($temp) && $temp != $this->email) {
                    $optional_mail_send[] = $temp;
                }
            }
            $data = [
                'id' => $this->id,
                'description' => $this->number . ' - ' . $this->name,
                'name' => $this->name,
                'number' => $this->number,
                'identity_document_type_id' => $this->identity_document_type_id,
                'identity_document_type_code' => $this->identity_document_type->code,
                'address' => $this->address,
                'internal_code' => $this->internal_code,
                'observation' => $this->observation,
                'zone' => $this->zone,
                'website' => $this->website,
                'document_type' => $this->identity_document_type->description,
                'enabled' => (bool)$this->enabled,
                'created_at' => $this->created_at->format('Y-m-d H:i:s'),
                'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
                'type' => $this->type,
                'trade_name' => $this->trade_name,
                'country_id' => $this->country_id,
                'department_id' => $this->department_id,
                'province_id' => $this->province_id,
                'district_id' => $this->district_id,
                'telephone' => $this->telephone,
                'email' => $this->email,
                'perception_agent' => (bool)$this->perception_agent,
                'percentage_perception' => $this->percentage_perception,
                'state' => $this->state,
                'condition' => $this->condition,
                'person_type_id' => $this->person_type_id,
                'person_type' => $person_type_descripton,
                'contact' => $this->contact,
                'comment' => $this->comment,
                'addresses' => $addresses,
                'parent_id' => $this->parent_id,
                'credit_days' => (int)$this->credit_days,
                'optional_email' => $optional_mail,
                'optional_email_send' => implode(',', $optional_mail_send),
                'childrens' => [],
                'edad'=>$this->edad,
                'person_halls' => $person_halls,
                'halls' => $person_halls,
				'user' => $user,
				'user_id' => $this->user_id
            ];
            if($childrens == true){
                $child = $this->children_person->transform(function($row){
                    return $row->getCollectionData();
                });
                $data['childrens'] = $child;
                $parent = null;
                if($this->parent_person) {
                    $parent = $this->parent_person->getCollectionData();
                }

                $data['parent'] = $parent;

            }

            return $data;
        }

        /**
         * @return array
         */
        public function getOptionalEmailArray(): array
        {
            $data = unserialize($this->optional_email);
            if ($data === false) {
                $data = [];
            }

            return $data;
        }

        /**
         * @return string
         */
        public function getObservation(): string
        {
            return $this->observation;
        }

        /**
         * @param string $observation
         *
         * @return Person
         */
        public function setObservation(string $observation): Person
        {
            $this->observation = $observation;
            return $this;
        }

        /**
         * @return string
         */
        public function getZone(): string
        {
            return $this->zone;
        }

        /**
         * @param string $zone
         *
         * @return Person
         */
        public function setZone(string $zone): Person
        {
            $this->zone = $zone;
            return $this;
        }

        /**
         * @return string
         */
        public function getWebsite(): string
        {
            return $this->website;
        }

        /**
         * @param string $website
         *
         * @return Person
         */
        public function setWebsite(string $website): Person
        {
            $this->website = $website;
            return $this;
        }

        /**
         * @return string|null
         */
        public function getOptionalEmail(): ?string
        {
            return $this->optional_email;
        }

        /**
         * @param string|null $optional_email
         *
         * @return Person
         */
        public function setOptionalEmail(?string $optional_email): Person
        {
            $this->optional_email = $optional_email;
            return $this;
        }

        /**
         * @param array $optional_email_array
         *
         * @return Person
         */
        public function setOptionalEmailArray(array $optional_email_array = []): Person
        {
            $this->optional_email = serialize($optional_email_array);
            return $this;
        }

        /**
         * @return int|null
         */
        public function getParentId(): ?int
        {
            return (int)$this->parent_id;
        }

        /**
         * @param int|null $parent_id
         *
         * @return Person
         */
        public function setParentId(?int $parent_id): Person
        {
            $this->parent_id = (int)$parent_id;
            return $this;
        }

        /**
         * @return HasMany
         */
        public function halls()
        {
            return $this->hasMany(PersonHall::class);
        }

        /**
         * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
         */
        public function user()
        {
            return $this->belongsTo(User::class);
        }
    }
