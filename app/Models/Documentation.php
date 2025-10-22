<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Documentation extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'hr_documentations';

    protected $fillable = [
        'company_id',
        'title',
        'description',
        'category',
        'file_path',
        'file_name',
        'file_type',
        'file_size',
        'tags',
        'access_level',
        'folder_id',
        'status',
        'uploaded_by',
        'approved_by',
        'approved_at'
    ];

    protected $casts = [
        'approved_at' => 'datetime',
        'file_size' => 'integer',
    ];

    // Document categories
    const CATEGORY_POLICY = 'policy';
    const CATEGORY_PROCEDURE = 'procedure';
    const CATEGORY_FORM = 'form';
    const CATEGORY_TEMPLATE = 'template';
    const CATEGORY_CONTRACT = 'contract';
    const CATEGORY_OTHER = 'other';

    // Access levels
    const ACCESS_PUBLIC = 'public';
    const ACCESS_DEPARTMENT = 'department';
    const ACCESS_ROLE = 'role';
    const ACCESS_PRIVATE = 'private';

    // Document statuses
    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function folder()
    {
        return $this->belongsTo(DocumentationFolder::class);
    }

    // Scopes
    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('file_type', $type);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByAccessLevel($query, $accessLevel)
    {
        return $query->where('access_level', $accessLevel);
    }

    public function scopeForCompany($query, $companyId)
    {
        return $query->where('company_id', $companyId);
    }

    // Get category label
    public function getCategoryLabelAttribute()
    {
        $categories = [
            self::CATEGORY_POLICY => 'Policy',
            self::CATEGORY_PROCEDURE => 'Procedure',
            self::CATEGORY_FORM => 'Form',
            self::CATEGORY_TEMPLATE => 'Template',
            self::CATEGORY_CONTRACT => 'Contract',
            self::CATEGORY_OTHER => 'Other'
        ];
        return $categories[$this->category] ?? $this->category;
    }

    // Get category badge class
    public function getCategoryBadgeClassAttribute()
    {
        $classes = [
            self::CATEGORY_POLICY => 'bg-primary bg-opacity-10 text-primary',
            self::CATEGORY_PROCEDURE => 'bg-info bg-opacity-10 text-info',
            self::CATEGORY_FORM => 'bg-success bg-opacity-10 text-success',
            self::CATEGORY_TEMPLATE => 'bg-warning bg-opacity-10 text-warning',
            self::CATEGORY_CONTRACT => 'bg-danger bg-opacity-10 text-danger',
            self::CATEGORY_OTHER => 'bg-secondary bg-opacity-10 text-secondary'
        ];
        return $classes[$this->category] ?? 'bg-secondary bg-opacity-10 text-secondary';
    }

    // Get access level label
    public function getAccessLevelLabelAttribute()
    {
        $levels = [
            self::ACCESS_PUBLIC => 'Public',
            self::ACCESS_DEPARTMENT => 'Department',
            self::ACCESS_ROLE => 'Role-based',
            self::ACCESS_PRIVATE => 'Private'
        ];
        return $levels[$this->access_level] ?? $this->access_level;
    }

    // Get access level badge class
    public function getAccessLevelBadgeClassAttribute()
    {
        $classes = [
            self::ACCESS_PUBLIC => 'bg-success bg-opacity-10 text-success',
            self::ACCESS_DEPARTMENT => 'bg-info bg-opacity-10 text-info',
            self::ACCESS_ROLE => 'bg-warning bg-opacity-10 text-warning',
            self::ACCESS_PRIVATE => 'bg-danger bg-opacity-10 text-danger'
        ];
        return $classes[$this->access_level] ?? 'bg-secondary bg-opacity-10 text-secondary';
    }

    // Get status label
    public function getStatusLabelAttribute()
    {
        $statuses = [
            self::STATUS_PENDING => 'Pending',
            self::STATUS_APPROVED => 'Approved',
            self::STATUS_REJECTED => 'Rejected'
        ];
        return $statuses[$this->status] ?? $this->status;
    }

    // Get status badge class
    public function getStatusBadgeClassAttribute()
    {
        $classes = [
            self::STATUS_PENDING => 'bg-warning bg-opacity-10 text-warning',
            self::STATUS_APPROVED => 'bg-success bg-opacity-10 text-success',
            self::STATUS_REJECTED => 'bg-danger bg-opacity-10 text-danger'
        ];
        return $classes[$this->status] ?? 'bg-secondary bg-opacity-10 text-secondary';
    }

    // Get file size in human readable format
    public function getFileSizeHumanAttribute()
    {
        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }

    // Get file icon class
    public function getFileIconClassAttribute()
    {
        $icons = [
            'pdf' => 'fas fa-file-pdf text-danger',
            'doc' => 'fas fa-file-word text-primary',
            'docx' => 'fas fa-file-word text-primary',
            'xls' => 'fas fa-file-excel text-success',
            'xlsx' => 'fas fa-file-excel text-success',
            'ppt' => 'fas fa-file-powerpoint text-warning',
            'pptx' => 'fas fa-file-powerpoint text-warning',
            'txt' => 'fas fa-file-alt text-secondary',
            'jpg' => 'fas fa-file-image text-info',
            'jpeg' => 'fas fa-file-image text-info',
            'png' => 'fas fa-file-image text-info',
            'gif' => 'fas fa-file-image text-info'
        ];
        
        return $icons[$this->file_type] ?? 'fas fa-file text-secondary';
    }
}
