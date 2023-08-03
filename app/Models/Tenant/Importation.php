<?php

namespace App\Models\Tenant;

use App\Models\Tenant\Catalogs\DocumentType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Importation extends ModelTenant
{
    protected $fillable = [
        'import_order_task',
        'supplier_id',
        'document_type_id',
        'number_proforma',
        'amount_proforma',
        'date_periodo',
        'date_of_issue',
        'arrival_date',
        'sale_reference',
        'ref_customs_agent',
        'transport_type',
        'transport_code',
        'dam',
        'comments',
        'isc_dol',
        'igv_dol',
        'ipm_dol',
        'ad_valorem_dol',
        'dispatch_fee_dol',
        'perception_dol',
        'fine_dol',
        'utility',
        'exchange_rate'
    ];

    protected $with = ['supplier', 'invoices', 'uploaded_files'];

    public function invoices(): HasMany
    {
        return $this->hasMany(Purchase::class, 'import_order_task', 'import_order_task')->whereNotNull('import_order_task');
    }


    public function uploaded_files(): HasMany
    {
        return $this->hasMany(ImportationUploadedFile::class);
    }

    public function supplier(): HasOne
    {
        return $this->hasOne(Person::class, 'id', 'supplier_id');
    }

    public function document_type(): BelongsTo
    {
        return $this->belongsTo(DocumentType::class);
    }
}
