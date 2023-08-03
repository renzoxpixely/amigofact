<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;

class ImportationUploadedFile extends ModelTenant
{
    protected $fillable = [
        'filename',
        'importation_id'
    ];
}
