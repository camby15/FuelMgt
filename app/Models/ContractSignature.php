<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContractSignature extends Model
{
    use HasFactory;

    protected $table = 'contract_signatures';

    protected $fillable = [
        'contract_id',
        'signer_name',
        'signer_email',
        'signature_image_path',
        'signed_at',
        'ip_address',
        'status',
        'rejection_reason'
    ];

    protected $casts = [
        'signed_at' => 'datetime',
    ];
}
