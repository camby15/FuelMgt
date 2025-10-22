<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CrmSalesCategory extends Model
{
    use HasFactory;
    protected $table = 'crm_sales_category'; // Define the table name

    protected $fillable = ['name']; // Allow mass assignment for 'name'
}
