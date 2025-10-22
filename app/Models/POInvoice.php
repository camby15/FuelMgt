<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class POInvoice extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'po_invoices';

    protected $fillable = [
        'purchase_order_id',
        'file_name',
        'original_name',
        'file_path',
        'file_type',
        'file_size',
        'notes',
        'uploaded_by',
        'company_id',
    ];

    protected $casts = [
        'file_size' => 'integer',
    ];

    public function purchaseOrder()
    {
        return $this->belongsTo(Wh_PurchaseOrder::class, 'purchase_order_id');
    }

    public function uploadedBy()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function company()
    {
        return $this->belongsTo(CompanyProfile::class, 'company_id');
    }

    // Helper method to get file size in human readable format
    public function getFileSizeAttribute($value)
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $size = $value;
        $unit = 0;

        while ($size >= 1024 && $unit < count($units) - 1) {
            $size /= 1024;
            $unit++;
        }

        return round($size, 2) . ' ' . $units[$unit];
    }

    // Helper method to get file extension
    public function getFileExtensionAttribute()
    {
        return pathinfo($this->original_name, PATHINFO_EXTENSION);
    }

    // Helper method to check if file is an image
    public function getIsImageAttribute()
    {
        $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'];
        return in_array(strtolower($this->file_extension), $imageExtensions);
    }

    // Helper method to check if file is a PDF
    public function getIsPdfAttribute()
    {
        return strtolower($this->file_extension) === 'pdf';
    }
}
