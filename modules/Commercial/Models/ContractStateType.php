<?php

namespace Modules\Commercial\Models;

use App\Models\Tenant\ModelTenant;

class ContractStateType extends ModelTenant
{
    public $incrementing = false;
    public $timestamps = false;

    public const REGISTERED = '01';
    public const DELIVERED = '05';
    public const FINALIZED_TO_BREACH = '06';
    public const FINALIZED = '07';
    public const REFUSED = '09';
    public const INCOMPLETE_DELIVERY = '04';
}