<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyInfo extends Model
{
    use HasFactory;

    protected $table = 'company_info';

    protected $fillable = [
        'company_name',
        'company_tax_number',
        'company_street',
        'company_street_number',
        'company_zip_code',
        'company_city',
        'company_state',
        'company_country',
    ];
}
