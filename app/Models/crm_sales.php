<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class crm_sales extends Model
{
    use HasFactory, softDeletes;
    protected $table = 'crm_sales';
    
    protected $fillable = [
        'user_id',
        'company_id',
        'deal_name',
        "category_id",

        "sales_commission",
        "deal_company",
        'deal_value',
        'sales_manager_id',
        'stage',
        'expected_close_date',
        'probability',
        'description',
        'deleted',
    ];
}
