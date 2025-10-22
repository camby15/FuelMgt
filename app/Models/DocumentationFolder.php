<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Session;
use App\Models\CompanyProfile;
use App\Models\Employee;
use App\Models\User;

class DocumentationFolder extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'documentation_folders';

    protected $fillable = [
        'company_id',
        'name',
        'description',
        'parent_id',
        'created_by',
        'created_by_type',
        'access_level'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Validation rules
    public static function rules()
    {
        return [
            'access_level' => 'required|in:public,department,role,private',
            'created_by_type' => 'required|in:user,employee',
        ];
    }

    // Access levels
    const ACCESS_PUBLIC = 'public';
    const ACCESS_DEPARTMENT = 'department';
    const ACCESS_ROLE = 'role';
    const ACCESS_PRIVATE = 'private';

    // Created by types
    const CREATED_BY_USER = 'user';
    const CREATED_BY_EMPLOYEE = 'employee';

    // Relationships
    public function company()
    {
        return $this->belongsTo(CompanyProfile::class, 'company_id');
    }

    public function creator()
    {
        // Dynamic relationship based on created_by_type
        if ($this->created_by_type === 'employee') {
            return $this->belongsTo(Employee::class, 'created_by');
        } else {
            return $this->belongsTo(User::class, 'created_by');
        }
    }

    // Helper method to get creator name
    public function getCreatorNameAttribute()
    {
        if ($this->created_by_type === 'employee') {
            return $this->creator ? $this->creator->first_name . ' ' . $this->creator->last_name : 'Unknown Employee';
        } else {
            return $this->creator ? $this->creator->name : 'Unknown User';
        }
    }

    public function parent()
    {
        return $this->belongsTo(DocumentationFolder::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(DocumentationFolder::class, 'parent_id');
    }

    public function documents()
    {
        return $this->hasMany(Documentation::class, 'folder_id');
    }

    // Scopes
    public function scopeForCompany($query, $companyId)
    {
        return $query->where('company_id', $companyId);
    }

    public function scopeRootFolders($query)
    {
        return $query->whereNull('parent_id');
    }

    public function scopeWithDocumentCount($query)
    {
        return $query->withCount('documents');
    }

    // Helper methods
    public function getFullPathAttribute()
    {
        $path = collect([$this->name]);
        $parent = $this->parent;
        
        while ($parent) {
            $path->prepend($parent->name);
            $parent = $parent->parent;
        }
        
        return $path->implode(' / ');
    }

    public function canAccess($user = null)
    {
        if (!$user) {
            $user = auth()->user();
        }

        switch ($this->access_level) {
            case self::ACCESS_PUBLIC:
                return true;
            case self::ACCESS_DEPARTMENT:
                return $user && $user->department_id === auth()->user()->department_id;
            case self::ACCESS_ROLE:
                return $user && $user->hasRole('admin') || $user->hasRole('hr_manager');
            case self::ACCESS_PRIVATE:
                return $user && $user->id === $this->created_by;
            default:
                return false;
        }
    }

    public static function getAccessLevels()
    {
        return [
            self::ACCESS_PUBLIC => 'Public',
            self::ACCESS_DEPARTMENT => 'Department',
            self::ACCESS_ROLE => 'Role-based',
            self::ACCESS_PRIVATE => 'Private'
        ];
    }
}
