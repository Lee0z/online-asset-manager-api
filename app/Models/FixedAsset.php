<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FixedAsset extends Model
{
    use HasFactory;

    protected $fillable = [
        'asset_name',
        'kst_symbol',
        'initial_value',
        'current_value',
        'acquisition_document_type',
        'depreciation_rate',
        'status',
        'acquisition_date',
        'commissioning_date',
        'liquidation_date',
        'liquidation_reason',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}