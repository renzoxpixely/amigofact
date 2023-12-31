<?php

namespace App\Models\Tenant;

use App\Models\Tenant\Catalogs\IdentityDocumentType;

/**
 * Class Company
 *
 * @package App\Models\Tenant
 * @mixin  ModelTenant
 */
class Company extends ModelTenant
{
    protected $with = ['identity_document_type'];
    protected $fillable = [
        'user_id',
        'identity_document_type_id',
        'number',
        'name',
        'trade_name',
        'soap_send_id',
        'soap_type_id',
        'soap_username',
        'soap_password',
        'soap_url',
        'certificate',
        'certificate_due',
        'logo',
        'detraction_account',
        'operation_amazonia',
        'img_firm',
        'cod_digemid',
        'integrated_query_client_id',
        'integrated_query_client_secret',
        'num_aut_manifiesto_pasajero'
    ];

    /**
     * @return mixed
     */
    public function getCodDigemid() {
        return $this->cod_digemid;
    }

    /**
     * @param mixed $cod_digemid
     *
     * @return Company
     */
    public function setCodDigemid($cod_digemid) {
        $this->cod_digemid = $cod_digemid;
        return $this;
    }

    public function identity_document_type()
    {
        return $this->belongsTo(IdentityDocumentType::class, 'identity_document_type_id');
    }

    public static function active()
    {
        return Company::first();
    }

    /**
     * @return string|null
     */
    public function getLogo(): ?string
    {
        return $this->logo;
    }

    /**
     * @param string|null $logo
     *
     * @return Company
     */
    public function setLogo(?string $logo): Company
    {
        $this->logo = $logo;
        return $this;
    }


}
