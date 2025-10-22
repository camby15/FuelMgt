<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HrEmploymentDocuments extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'resume',
        'cover_letter',
        'educational_certificate',
        'other_documents',
        'document_notes',
        'documents_complete',
        'documents_verified',
    ];

    protected $casts = [
        'other_documents' => 'array',
        'documents_complete' => 'boolean',
        'documents_verified' => 'boolean',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}