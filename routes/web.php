<?php

use Illuminate\Support\Facades\Schema;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\CRM\TicketController;
use App\Models\TicketAttachment;
use Illuminate\Http\Request;
use App\Http\Controllers\RoutingController;
use App\Http\Controllers\IndividualUserController;
use App\Http\Controllers\CompanyUserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ContractFormController;
use App\Http\Controllers\LockedScreenController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\Users\CompanySubUserController;
use App\Http\Controllers\Users\CompanyUserProfileController;
use App\Http\Controllers\Users\CompanyPartnersController;
use App\Http\Controllers\Users\CompanyUserCategoriesController;
use App\Http\Controllers\ProjectManagement\HomeConnectionCustomersController;
use App\Http\Controllers\ProjectManagement\PmReportController;
use App\Http\Controllers\Categories\DepartmentCategories;
use App\Http\Controllers\Newsletters\CompanyNewsLetterController;
use App\Http\Controllers\CRM\CustomerManagment;
use App\Http\Controllers\CRM\EmailManagement;
use App\Http\Controllers\CRM\CampaignController;
use App\Http\Controllers\CRM\CustomerContactController;
use App\Http\Controllers\CRM\ActivityController;
use App\Models\Campaign;
use App\Http\Controllers\CRM\ContractController;
use App\Models\Contract;
use App\Http\Controllers\DemoRequestController;

use App\Http\Controllers\LoyaltyProgramController;
use App\Http\Controllers\CustomerTierController;
use App\Http\Controllers\RewardController;
use App\Http\Controllers\RedemptionController;
use App\Http\Controllers\ReferralController;
use App\Http\Controllers\HR\EmployeeController;
use App\Http\Controllers\HR\LeaveController;
use App\Http\Controllers\HR\TrainingController;
use App\Http\Controllers\HR\PerformanceController;
use App\Http\Controllers\HR\DocumentationController;
use App\Http\Controllers\HR\JobsController;
use App\Http\Controllers\HR\AttendanceController;
use App\Http\Controllers\HR\PayrollController;

use App\Http\Controllers\WareHouse\POController;
use App\Http\Controllers\WareHouse\POApprovalController;
use App\Http\Controllers\WareHouse\SupplierController;
use App\Http\Controllers\WareHouse\QualityInspectionController;
use App\Http\Controllers\WareHouse\POReceivingController;
use App\Http\Controllers\WareHouse\CentralStoreController;



// Load authentication routes
require __DIR__ . '/auth.php';

// Super Admin Routes
Route::prefix('super-admin')->name('superAdmin.')->group(function () {
    Route::get('/login', [\App\Http\Controllers\SuperUserLoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [\App\Http\Controllers\SuperUserLoginController::class, 'login']);
    Route::get('/dashboard', [\App\Http\Controllers\SuperUserLoginController::class, 'showDashboard'])->name('dashboard')->middleware('auth:super_admin');
    Route::post('/logout', [\App\Http\Controllers\SuperUserLoginController::class, 'logout'])->name('logout');
});

// Super Admin Section
Route::prefix('superadmin')->name('superadmin.')->middleware(['auth:super_admin', 'superadmin'])->group(function () {

    // Superusers Management
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [App\Http\Controllers\SuperAdmin\SuperuserController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\SuperAdmin\SuperuserController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\SuperAdmin\SuperuserController::class, 'store'])->name('store');
        Route::get('/{user}/edit', [App\Http\Controllers\SuperAdmin\SuperuserController::class, 'edit'])->name('edit');
        Route::put('/{user}', [App\Http\Controllers\SuperAdmin\SuperuserController::class, 'update'])->name('update');
        Route::delete('/{user}', [App\Http\Controllers\SuperAdmin\SuperuserController::class, 'destroy'])->name('destroy');
        Route::post('/{user}/toggle-status', [App\Http\Controllers\SuperAdmin\SuperuserController::class, 'toggleStatus'])->name('toggle-status');
        Route::post('/export', [App\Http\Controllers\SuperAdmin\SuperuserController::class, 'export'])->name('export');
        Route::post('/import', [App\Http\Controllers\SuperAdmin\SuperuserController::class, 'import'])->name('import');
        Route::get('/download-template', [App\Http\Controllers\SuperAdmin\SuperuserController::class, 'downloadTemplate'])->name('download-template');
        Route::put('/{user}/update-role', [App\Http\Controllers\SuperAdmin\SuperuserController::class, 'updateRole'])->name('update-role');
    });

    // Role Management
    Route::prefix('roles')->name('roles.')->group(function () {
        Route::get('/', [App\Http\Controllers\SuperAdmin\SuperRoleController::class, 'index'])->name('index');
        Route::post('/', [App\Http\Controllers\SuperAdmin\SuperRoleController::class, 'store'])->name('store');
        Route::get('/{role}', [App\Http\Controllers\SuperAdmin\SuperRoleController::class, 'show'])->name('show');
        Route::put('/{role}', [App\Http\Controllers\SuperAdmin\SuperRoleController::class, 'update'])->name('update');
        Route::delete('/{role}', [App\Http\Controllers\SuperAdmin\SuperRoleController::class, 'destroy'])->name('destroy');
        Route::get('/{role}/permissions', [App\Http\Controllers\SuperAdmin\SuperRoleController::class, 'permissions'])->name('permissions');
        Route::post('/{role}/sync-permissions', [App\Http\Controllers\SuperAdmin\SuperRoleController::class, 'syncPermissions'])->name('sync-permissions');
    });

    Route::get('/settings', function () {
        return view('superAdmin.settings');
    })->name('settings');

    Route::get('/audit', function () {
        return view('superAdmin.audit');
    })->name('audit');

    Route::get('/agents', function () {
        return view('superAdmin.agents');
    })->name('agents');

    Route::get('/agentdash', function () {
        return view('superAdmin.agentdash');
    })->name('agentdash');
});

// Public Routes - Accessible without authentication
// -----------------------------------------------
// Welcome/Landing page route
Route::get('/', [WelcomeController::class, 'index'])->name('root');

// Route to grant access when "Get Started" is clicked
// This sets the access_granted session and allows navigation to auth pages
Route::get('/start', [WelcomeController::class, 'grantAccess'])->name('start');

// Route to handle the demo request form 
Route::post('/request-demo', [DemoRequestController::class, 'store'])->name('demo.request');

// Route to handle the external contract form 
Route::get('external/client-contractForm', [ContractFormController::class, 'showContractForm'])->name('client.contract.form');
Route::post('external/client-form/submit', [ContractFormController::class, 'submitContract'])->name('client.form.submit');

// Route to handle the external signature form for company users
Route::get('external/client-signatureForm/{email}/{id}', [ContractFormController::class, 'showSignatureForm'])->name('client.signature.form');
Route::post('external/client-signature/submit', [ContractFormController::class, 'submitSignatureForm'])->name('client.signature.Form.submit');

// External Job Application Routes
Route::get('job', [JobsController::class, 'showExternalApplicationForm'])->name('external.job.application.form');
Route::post('external/job-application/submit', [JobsController::class, 'submitExternalApplication'])->name('external.job.application.submit');

// Job Applications API Routes
Route::prefix('company/hr/jobs')->name('hr.jobs.')->middleware(['auth.company_or_sub_user', 'company.session'])->group(function () {
    Route::post('/applications', [JobsController::class, 'getApplications'])->name('applications');
    Route::post('/applications/{id}', [JobsController::class, 'getApplicationDetails'])->name('application.details');
    Route::post('/applications/{id}/reject', [JobsController::class, 'rejectApplication'])->name('application.reject');

    // Onboarding Routes
    Route::post('/onboarding', [JobsController::class, 'getOnboardingData'])->name('onboarding.data');
    Route::post('/onboarding/create', [JobsController::class, 'createOnboarding'])->name('onboarding.create');
    Route::post('/onboarding/{id}/update-status', [JobsController::class, 'updateOnboardingStatus'])->name('onboarding.update-status');
    Route::delete('/onboarding/{id}', [JobsController::class, 'deleteOnboarding'])->name('onboarding.delete');
});

// Authentication and Registration Routes
// ------------------------------------
// Routes for handling user registration
Route::post('/register/individual', [IndividualUserController::class, 'register']);
Route::post('/register/company', [CompanyUserController::class, 'register'])->name('register.company.store');

// Routes for OTP and token verification
Route::post('/request-otp', [AuthController::class, 'requestOtp'])->name('auth.otp.request');
Route::get('/resend-otp', [AuthController::class, 'resendOtp'])->name('auth.otp.resend');
Route::post('/auth/verify-token', [AuthController::class, 'verifyToken'])->name('auth.verify.token');
Route::post('/verify-otp', [AuthController::class, 'verifyOtp'])->name('auth.otp.verify');
Route::post('/sub-user-logout', [AuthController::class, 'subUserLogout'])->name('auth.sub_user.logout');

// Debug route to check authentication state
Route::get('/debug-auth', function() {
    return response()->json([
        'regular_user_check' => auth()->check(),
        'regular_user_id' => auth()->id(),
        'sub_user_check' => auth()->guard('sub_user')->check(),
        'sub_user_id' => auth()->guard('sub_user')->id(),
        'session_sub_user_id' => session('sub_user_id'),
        'session_company_id' => session('selected_company_id'),
        'all_session_data' => session()->all()
    ]);
})->name('debug.auth');

// Debug route to check categories
Route::get('/debug-categories', function() {
    $companyId = session('selected_company_id');
    
    $allCategories = \App\Models\Category::count();
    $companyScopedCategories = $companyId ? \App\Models\Category::where('company_id', $companyId)->count() : 0;
    $sampleCategories = \App\Models\Category::take(3)->get(['id', 'name', 'company_id', 'status']);
    
    return response()->json([
        'session_company_id' => $companyId,
        'all_categories_count' => $allCategories,
        'company_scoped_count' => $companyScopedCategories,
        'sample_categories' => $sampleCategories,
        'auth_status' => [
            'regular_user' => auth()->check(),
            'sub_user' => auth()->guard('sub_user')->check(),
            'company_sub_user' => auth()->guard('company_sub_user')->check()
        ]
    ]);
})->name('debug.categories');

// Lock Screen Routes (accessible by both regular users and sub-users)
// ----------------
Route::post('/lock-screen', [LockedScreenController::class, 'verifyPin'])->name('verify-pin')->middleware('auth.company_or_sub_user');
Route::get('/lock-screen', [LockedScreenController::class, 'showLockScreen'])->name('lock-screen')->middleware('auth.company_or_sub_user');
Route::get('/lock-screen/logout', [LockedScreenController::class, 'logout'])->name('lock.logout')->middleware('auth.company_or_sub_user');


Route::get('ticket/all', [TicketController::class, 'tickets']);

// ------ Super admin login link 
Route::get('/stak/admin', function () {
    return view('stak.admin');
})->name('admin.login');

// Route to set admin-selected currency
Route::post('/set-currency', function (Request $req) {
    session(['currency' => $req->currency]);
    return back();
})->name('setCurrency');

// Protected Routes - Require proper navigation through "Get Started"
// --------------------------------------------------------------
Route::group(['middleware' => ['prevent.direct.access']], function () {
    // Authentication view routes - Protected from direct URL access
    Route::get('/auth', function () {
        return view('authentications.auth');
    })->name('auth.auth');
    Route::get('/auth/company', function () {
        return view('authentications.company');
    })->name('auth.company');
    Route::get('/auth/individual', function () {
        return view('authentications.individual');
    })->name('auth.individual');
    Route::get('/auth/login', function () {
        return view('authentications.login');
    })->name('auth.login');
    Route::get('/auth/token', function () {
        return view('authentications.token');
    })->name('auth.token');
});



// Company Sub Users Management Routes
Route::prefix('company')->name('company-sub-users.')->group(function () {
    Route::get('/users', [CompanySubUserController::class, 'index'])->name('index');
    Route::get('/users/create', [CompanySubUserController::class, 'create'])->name('create');
    Route::post('/users', [CompanySubUserController::class, 'store'])->name('store');
    Route::get('/users/{companySubUser}/edit', [CompanySubUserController::class, 'edit'])->name('edit');
    Route::put('/users/{companySubUser}', [CompanySubUserController::class, 'update'])->name('update');
    Route::delete('/users/{companySubUser}', [CompanySubUserController::class, 'destroy'])->name('destroy');
    Route::put('/users/{companySubUser}/toggle-lock', [CompanySubUserController::class, 'toggleLock'])->name('toggle-lock');
    Route::post('/users/{companySubUser}/reset-password', [CompanySubUserController::class, 'resetPassword'])->name('reset-password');
    Route::get('/users/{companySubUser}/get-pin', [CompanySubUserController::class, 'getPin'])->name('get-pin');
    Route::post('/users/{companySubUser}/send-sms', [CompanySubUserController::class, 'sendSms'])->name('send-sms');
    Route::get('sub-users/data', [CompanySubUserController::class, 'getData'])->name('data');
    Route::post('/users/bulk-upload', [CompanySubUserController::class, 'bulkUpload'])->name('bulk-upload');
    Route::get('/users/download-template', [CompanySubUserController::class, 'downloadTemplate'])->name('download-template');
    Route::post('/users/{companySubUser}/send-email', [CompanySubUserController::class, 'sendEmail'])->name('send-email');
});


// User Profile Management Routes
Route::prefix('company')->name('company.')->group(function () {
    // Sub-user profile management routes (more specific routes first)
    Route::get('user-profiles/sub-users', [CompanyUserProfileController::class, 'getSubUsers'])->name('user-profiles.sub-users');
    Route::post('user-profiles/sub-users/{userId}/assign', [CompanyUserProfileController::class, 'assignProfile'])->name('user-profiles.assign-profile');
    Route::delete('user-profiles/sub-users/{userId}/remove', [CompanyUserProfileController::class, 'removeProfile'])->name('user-profiles.remove-profile');
    Route::get('user-profiles/sub-users/{userId}/menu-access', [CompanyUserProfileController::class, 'getUserMenuAccess'])->name('user-profiles.menu-access');

    // Basic CRUD routes (less specific routes later)
    Route::get('user-profiles', [CompanyUserProfileController::class, 'index'])->name('user-profiles.index');
    Route::post('user-profiles', [CompanyUserProfileController::class, 'store'])->name('user-profiles.store');
    Route::get('user-profiles/{profile}/edit', [CompanyUserProfileController::class, 'edit'])->name('user-profiles.edit');
    Route::put('user-profiles/{profile}', [CompanyUserProfileController::class, 'update'])->name('user-profiles.update');
    Route::delete('user-profiles/{profile}', [CompanyUserProfileController::class, 'destroy'])->name('user-profiles.destroy');

    // Menu access routes for profiles
    Route::get('user-profiles/{profile}/menu-access', [CompanyUserProfileController::class, 'getMenuAccess'])->name('user-profiles.get-menu-access');
    Route::post('user-profiles/{profile}/menu-access', [CompanyUserProfileController::class, 'updateMenuAccess'])->name('user-profiles.update-menu-access');


});

// Partner Management Routes
Route::prefix('company')->name('company.')->group(function () {
    Route::prefix('partners')->name('partners.')->group(function () {
        Route::get('/', [CompanyPartnersController::class, 'index'])->name('index');
        Route::get('/create', [CompanyPartnersController::class, 'create'])->name('create');
        Route::post('/', [CompanyPartnersController::class, 'store'])->name('store');
        // --- Bulk Upload
        Route::get('/download-template', [CompanyPartnersController::class, 'downloadTemplate'])->name('partner-download-template');
        Route::post('/bulk-upload', [CompanyPartnersController::class, 'bulkUpload'])->name('partner-bulk-upload');

        Route::get('/{id}', [CompanyPartnersController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [CompanyPartnersController::class, 'edit'])->name('edit');
        Route::put('/{id}', [CompanyPartnersController::class, 'update'])->name('update');
        Route::put('/{id}/status', [CompanyPartnersController::class, 'updateStatus'])->name('update-status');
        Route::delete('/{id}', [CompanyPartnersController::class, 'destroy'])->name('destroy');
    });
});


// User Categories Management Routes
Route::prefix('company')->name('company-categories.')->group(function () {
    Route::get('/categories', [CompanyUserCategoriesController::class, 'index'])->name('index');
    Route::post('/categories', [CompanyUserCategoriesController::class, 'store'])->name('store');
    Route::put('/categories/{category}', [CompanyUserCategoriesController::class, 'update'])->name('update');
    Route::delete('/categories/{category}', [CompanyUserCategoriesController::class, 'destroy'])->name('destroy');
    Route::post('/categories/bulk-upload', [CompanyUserCategoriesController::class, 'bulkUpload'])->name('bulk-upload');
    Route::get('/categories/download-template', [CompanyUserCategoriesController::class, 'downloadTemplate'])->name('download-template');
    Route::put('/categories/{category}/toggle-status', [CompanyUserCategoriesController::class, 'toggleStatus'])->name('toggle-status');
});

// Department Categories Management Routes
Route::prefix('company')->middleware(['auth.company_or_sub_user', 'company.session'])->name('department-categories.')->group(function () {
    Route::prefix('Categories')->group(function () {
        // Main CRUD routes (index and store first)
        Route::get('/departments', [App\Http\Controllers\Categories\DepartmentCategories::class, 'index'])->name('index');
        Route::get('/departments/data', [App\Http\Controllers\Categories\DepartmentCategories::class, 'getDepartmentsData'])->name('data');
        Route::post('/departments', [App\Http\Controllers\Categories\DepartmentCategories::class, 'store'])->name('store');
        
        // Statistics and utility routes (before {id} routes)
        Route::get('/departments/stats/overview', [App\Http\Controllers\Categories\DepartmentCategories::class, 'getStats'])->name('stats');
        
        // Import/Export routes (before {id} routes)
        Route::post('/departments/import', [App\Http\Controllers\Categories\DepartmentCategories::class, 'import'])->name('import');
        Route::get('/departments/export', [App\Http\Controllers\Categories\DepartmentCategories::class, 'export'])->name('export');
        Route::get('/departments/template/download', [App\Http\Controllers\Categories\DepartmentCategories::class, 'downloadTemplate'])->name('template.download');
        
        
        // CRUD routes with {id} parameter (must come AFTER specific routes)
        Route::get('/departments/{id}', [App\Http\Controllers\Categories\DepartmentCategories::class, 'show'])->name('show');
        Route::put('/departments/{id}', [App\Http\Controllers\Categories\DepartmentCategories::class, 'update'])->name('update');
        Route::delete('/departments/{id}', [App\Http\Controllers\Categories\DepartmentCategories::class, 'destroy'])->name('destroy');
        
        // Sub-department management routes
        Route::post('/departments/{id}/sub-departments', [App\Http\Controllers\Categories\DepartmentCategories::class, 'addSubDepartment'])->name('sub-departments.add');
        Route::delete('/departments/{id}/sub-departments', [App\Http\Controllers\Categories\DepartmentCategories::class, 'removeSubDepartment'])->name('sub-departments.remove');
        
        // Sort order management
        Route::put('/departments/sort-order', [App\Http\Controllers\Categories\DepartmentCategories::class, 'updateSortOrder'])->name('sort-order.update');
    });
});

// Categories Management Routes
Route::prefix('company')->middleware(['auth.company_or_sub_user', 'company.session'])->name('categories.')->group(function () {
    Route::prefix('Categories')->group(function () {
        // Main CRUD routes (index and store first)
        Route::get('/categories', [App\Http\Controllers\Categories\CategoriesManagement::class, 'index'])->name('index');
        Route::get('/categories/data', [App\Http\Controllers\Categories\CategoriesManagement::class, 'getCategoriesData'])->name('data');
        Route::post('/categories', [App\Http\Controllers\Categories\CategoriesManagement::class, 'store'])->name('store');
        
        // Statistics and utility routes (before {id} routes)
        Route::get('/categories/stats/overview', [App\Http\Controllers\Categories\CategoriesManagement::class, 'getStats'])->name('stats');
        
        // Import/Export routes (before {id} routes)
        Route::post('/categories/import', [App\Http\Controllers\Categories\CategoriesManagement::class, 'import'])->name('import');
        Route::get('/categories/export', [App\Http\Controllers\Categories\CategoriesManagement::class, 'export'])->name('export');
        Route::get('/categories/template/download', [App\Http\Controllers\Categories\CategoriesManagement::class, 'downloadTemplate'])->name('template.download');
        
        
        // CRUD routes with {id} parameter (must come AFTER specific routes)
        Route::get('/categories/{id}', [App\Http\Controllers\Categories\CategoriesManagement::class, 'show'])->name('show');
        Route::put('/categories/{id}', [App\Http\Controllers\Categories\CategoriesManagement::class, 'update'])->name('update');
        Route::delete('/categories/{id}', [App\Http\Controllers\Categories\CategoriesManagement::class, 'destroy'])->name('destroy');
        
        // Sub-category management routes
        Route::post('/categories/{id}/sub-categories', [App\Http\Controllers\Categories\CategoriesManagement::class, 'addSubCategory'])->name('sub-categories.add');
        Route::delete('/categories/{id}/sub-categories', [App\Http\Controllers\Categories\CategoriesManagement::class, 'removeSubCategory'])->name('sub-categories.remove');
        
        // Sort order management
        Route::put('/categories/sort-order', [App\Http\Controllers\Categories\CategoriesManagement::class, 'updateSortOrder'])->name('sort-order.update');
    });
});

// Business Sectors Management Routes
Route::prefix('company')->middleware(['auth.company_or_sub_user', 'company.session'])->name('business-sectors.')->group(function () {
    Route::prefix('Categories')->group(function () {
        // Main CRUD routes (index and store first)
        Route::get('/business-sectors', [App\Http\Controllers\Categories\BusinessSector::class, 'index'])->name('index');
        Route::get('/business-sectors/data', [App\Http\Controllers\Categories\BusinessSector::class, 'getData'])->name('data');
        Route::post('/business-sectors', [App\Http\Controllers\Categories\BusinessSector::class, 'store'])->name('store');
        
        // Statistics and utility routes (before {id} routes)
        Route::get('/business-sectors/stats', [App\Http\Controllers\Categories\BusinessSector::class, 'getStats'])->name('stats');
        
        // Import/Export routes (before {id} routes)
        Route::post('/business-sectors/import', [App\Http\Controllers\Categories\BusinessSector::class, 'import'])->name('import');
        Route::get('/business-sectors/export', [App\Http\Controllers\Categories\BusinessSector::class, 'export'])->name('export');
        Route::get('/business-sectors/template/download', [App\Http\Controllers\Categories\BusinessSector::class, 'downloadTemplate'])->name('template.download');
        
        // CRUD routes with {id} parameter (must come AFTER specific routes)
        Route::get('/business-sectors/{id}', [App\Http\Controllers\Categories\BusinessSector::class, 'show'])->name('show');
        Route::put('/business-sectors/{id}', [App\Http\Controllers\Categories\BusinessSector::class, 'update'])->name('update');
        Route::delete('/business-sectors/{id}', [App\Http\Controllers\Categories\BusinessSector::class, 'destroy'])->name('destroy');
        
        // Sort order management
        Route::put('/business-sectors/sort-order', [App\Http\Controllers\Categories\BusinessSector::class, 'updateSortOrder'])->name('sort-order.update');
    });
});

// Workforce & Fleet Management Routes (Team Members)
Route::prefix('company')->middleware(['auth.company_or_sub_user', 'company.session'])->group(function () {
    Route::prefix('MasterTracker')->group(function () {
        // Main workforce-fleet page (GET only)
        Route::get('/workforce-fleet', [App\Http\Controllers\MasterTracker\TeamMemberController::class, 'index'])->name('workforce-fleet.index');
        
        // Team Members CRUD routes
        Route::prefix('team-members')->name('team-members.')->group(function () {
            // Main CRUD routes (index and store first)
            Route::get('/', [App\Http\Controllers\MasterTracker\TeamMemberController::class, 'index'])->name('index');
            Route::get('/data', [App\Http\Controllers\MasterTracker\TeamMemberController::class, 'getData'])->name('data');
            Route::post('/', [App\Http\Controllers\MasterTracker\TeamMemberController::class, 'store'])->name('store');
            
            // Statistics and utility routes (before {id} routes)
            Route::get('/stats', [App\Http\Controllers\MasterTracker\TeamMemberController::class, 'getStats'])->name('stats');
            
            // Import/Export routes (before {id} routes)
            Route::post('/import', [App\Http\Controllers\MasterTracker\TeamMemberController::class, 'import'])->name('import');
            Route::get('/export', [App\Http\Controllers\MasterTracker\TeamMemberController::class, 'export'])->name('export');
            Route::get('/template/download', [App\Http\Controllers\MasterTracker\TeamMemberController::class, 'downloadTemplate'])->name('template.download');
            
            // Sort order management
            Route::put('/sort-order', [App\Http\Controllers\MasterTracker\TeamMemberController::class, 'updateSortOrder'])->name('sort-order.update');
            
            // CRUD routes with {id} parameter (must come AFTER specific routes)
            Route::get('/{id}', [App\Http\Controllers\MasterTracker\TeamMemberController::class, 'show'])->name('show');
            Route::put('/{id}', [App\Http\Controllers\MasterTracker\TeamMemberController::class, 'update'])->name('update');
            Route::delete('/{id}', [App\Http\Controllers\MasterTracker\TeamMemberController::class, 'destroy'])->name('destroy');
        });

        // Drivers CRUD routes
        Route::prefix('drivers')->name('drivers.')->group(function () {
            // Main CRUD routes (index and store first)
            Route::get('/', [App\Http\Controllers\MasterTracker\DriverMemberController::class, 'index'])->name('index');
            Route::get('/data', [App\Http\Controllers\MasterTracker\DriverMemberController::class, 'getData'])->name('data');
            Route::post('/', [App\Http\Controllers\MasterTracker\DriverMemberController::class, 'store'])->name('store');
            
            // Statistics and utility routes (before {id} routes)
            Route::get('/stats', [App\Http\Controllers\MasterTracker\DriverMemberController::class, 'getStats'])->name('stats');
            
            // Import/Export routes (before {id} routes)
            Route::post('/import', [App\Http\Controllers\MasterTracker\DriverMemberController::class, 'import'])->name('import');
            Route::get('/export', [App\Http\Controllers\MasterTracker\DriverMemberController::class, 'export'])->name('export');
            Route::get('/template/download', [App\Http\Controllers\MasterTracker\DriverMemberController::class, 'downloadTemplate'])->name('template.download');
            
            // CRUD routes with {id} parameter (must come AFTER specific routes)
            Route::get('/{id}', [App\Http\Controllers\MasterTracker\DriverMemberController::class, 'show'])->name('show');
            Route::put('/{id}', [App\Http\Controllers\MasterTracker\DriverMemberController::class, 'update'])->name('update');
            Route::delete('/{id}', [App\Http\Controllers\MasterTracker\DriverMemberController::class, 'destroy'])->name('destroy');
        });

        // Vehicles CRUD routes
        Route::prefix('vehicles')->name('vehicles.')->group(function () {
            // Main CRUD routes (index and store first)
            Route::get('/', [App\Http\Controllers\MasterTracker\VehicleManagementController::class, 'index'])->name('index');
            Route::get('/data', [App\Http\Controllers\MasterTracker\VehicleManagementController::class, 'getData'])->name('data');
            Route::post('/', [App\Http\Controllers\MasterTracker\VehicleManagementController::class, 'store'])->name('store');
            
            // Statistics and utility routes (before {id} routes)
            Route::get('/stats', [App\Http\Controllers\MasterTracker\VehicleManagementController::class, 'getStats'])->name('stats');
            
            // Import/Export routes (before {id} routes)
            Route::post('/import', [App\Http\Controllers\MasterTracker\VehicleManagementController::class, 'import'])->name('import');
            Route::get('/export', [App\Http\Controllers\MasterTracker\VehicleManagementController::class, 'export'])->name('export');
            Route::get('/template/download', [App\Http\Controllers\MasterTracker\VehicleManagementController::class, 'downloadTemplate'])->name('template.download');
            
            // CRUD routes with {id} parameter (must come AFTER specific routes)
            Route::get('/{id}', [App\Http\Controllers\MasterTracker\VehicleManagementController::class, 'show'])->name('show');
            Route::put('/{id}', [App\Http\Controllers\MasterTracker\VehicleManagementController::class, 'update'])->name('update');
            Route::delete('/{id}', [App\Http\Controllers\MasterTracker\VehicleManagementController::class, 'destroy'])->name('destroy');
        });

        // Team Pairing CRUD routes
        Route::prefix('team-pairing')->name('team-pairing.')->group(function () {
            // Main CRUD routes (index and store first)
            Route::get('/', [App\Http\Controllers\MasterTracker\TeamParingController::class, 'index'])->name('index');
            Route::get('/data', [App\Http\Controllers\MasterTracker\TeamParingController::class, 'getData'])->name('data');
            Route::post('/', [App\Http\Controllers\MasterTracker\TeamParingController::class, 'store'])->name('store');
            
            // Statistics and utility routes (before {id} routes)
            Route::get('/stats', [App\Http\Controllers\MasterTracker\TeamParingController::class, 'getStats'])->name('stats');
            Route::get('/members', [App\Http\Controllers\MasterTracker\TeamParingController::class, 'getTeamMembers'])->name('members');
            Route::get('/vehicles', [App\Http\Controllers\MasterTracker\TeamParingController::class, 'getVehicles'])->name('vehicles');
            Route::get('/drivers', [App\Http\Controllers\MasterTracker\TeamParingController::class, 'getDrivers'])->name('drivers');
            
            // Bulk operations and export routes (before {id} routes)
            Route::post('/bulk-allocation', [App\Http\Controllers\MasterTracker\TeamParingController::class, 'bulkAllocation'])->name('bulk-allocation');
            Route::get('/export', [App\Http\Controllers\MasterTracker\TeamParingController::class, 'export'])->name('export');
            Route::get('/report', [App\Http\Controllers\MasterTracker\TeamParingController::class, 'report'])->name('report');
            
            // CRUD routes with {id} parameter (must come AFTER specific routes)
            Route::get('/{team_paring}', [App\Http\Controllers\MasterTracker\TeamParingController::class, 'show'])->name('show');
            Route::get('/{team_paring}/edit', [App\Http\Controllers\MasterTracker\TeamParingController::class, 'edit'])->name('edit');
            Route::put('/{team_paring}', [App\Http\Controllers\MasterTracker\TeamParingController::class, 'update'])->name('update');
            Route::delete('/{team_paring}', [App\Http\Controllers\MasterTracker\TeamParingController::class, 'destroy'])->name('destroy');
        });

        // Team Roaster Routes
        Route::prefix('team-roaster')->name('team-roaster.')->group(function () {
            Route::get('/', [App\Http\Controllers\MasterTracker\TeamRoasterController::class, 'index'])->name('index');
            Route::post('/', [App\Http\Controllers\MasterTracker\TeamRoasterController::class, 'store'])->name('store');
            Route::get('/teams', [App\Http\Controllers\MasterTracker\TeamRoasterController::class, 'getTeams'])->name('teams');
            Route::get('/stats', [App\Http\Controllers\MasterTracker\TeamRoasterController::class, 'getStats'])->name('stats');
            Route::get('/calendar-events', [App\Http\Controllers\MasterTracker\TeamRoasterController::class, 'getCalendarEvents'])->name('calendar-events');
            Route::get('/debug', [App\Http\Controllers\MasterTracker\TeamRoasterController::class, 'debugEnvironment'])->name('debug');
            Route::get('/{teamRoster}', [App\Http\Controllers\MasterTracker\TeamRoasterController::class, 'show'])->name('show');
            Route::get('/{teamRoster}/edit', [App\Http\Controllers\MasterTracker\TeamRoasterController::class, 'edit'])->name('edit');
            Route::put('/{teamRoster}', [App\Http\Controllers\MasterTracker\TeamRoasterController::class, 'update'])->name('update');
            Route::post('/{teamRoster}/update', [App\Http\Controllers\MasterTracker\TeamRoasterController::class, 'update'])->name('update.post');
            Route::delete('/{teamRoster}', [App\Http\Controllers\MasterTracker\TeamRoasterController::class, 'destroy'])->name('destroy');
            Route::post('/{teamRoster}/delete', [App\Http\Controllers\MasterTracker\TeamRoasterController::class, 'destroy'])->name('destroy.post');
            Route::put('/{teamRoster}/status', [App\Http\Controllers\MasterTracker\TeamRoasterController::class, 'updateStatus'])->name('update-status');
        });

        // Workforce Fleet Management Route (using TeamMemberController for overall page)
        Route::get('/workforce-fleet', [App\Http\Controllers\MasterTracker\TeamMemberController::class, 'index'])->name('workforce-fleet');
    });
});

// Project Management Map Data Routes
Route::prefix('company')->middleware(['auth.company_or_sub_user', 'company.session'])->group(function () {
    // Site Assignment Routes
    Route::prefix('site-assignments')->name('site-assignments.')->group(function () {
        Route::get('/', [\App\Http\Controllers\ProjectManagement\SiteAssignmentController::class, 'index'])->name('index');
        Route::get('/create', [\App\Http\Controllers\ProjectManagement\SiteAssignmentController::class, 'create'])->name('create');
        Route::post('/', [\App\Http\Controllers\ProjectManagement\SiteAssignmentController::class, 'store'])->name('store');
        Route::post('/bulk-store', [\App\Http\Controllers\ProjectManagement\SiteAssignmentController::class, 'bulkStore'])->name('bulk-store');
        Route::get('/{assignment}', [\App\Http\Controllers\ProjectManagement\SiteAssignmentController::class, 'show'])->name('show');
        Route::get('/{assignment}/edit', [\App\Http\Controllers\ProjectManagement\SiteAssignmentController::class, 'edit'])->name('edit');
        Route::put('/{assignment}', [\App\Http\Controllers\ProjectManagement\SiteAssignmentController::class, 'update'])->name('update');
        Route::delete('/{assignment}', [\App\Http\Controllers\ProjectManagement\SiteAssignmentController::class, 'destroy'])->name('destroy');
        
        // Additional routes for site assignment actions
        Route::post('/{assignment}/report-issue', [\App\Http\Controllers\ProjectManagement\SiteAssignmentController::class, 'reportIssue'])->name('report-issue');
        Route::post('/{assignment}/resolve-issue/{issueId}', [\App\Http\Controllers\ProjectManagement\SiteAssignmentController::class, 'resolveIssue'])->name('resolve-issue');
    });
    
    // Home Connection Site Assignment Routes (with company prefix)
    Route::prefix('home-connection/site-assignments')->name('company.home-connection.site-assignments.')->group(function () {
        Route::get('/{assignment}', [\App\Http\Controllers\ProjectManagement\SiteAssignmentController::class, 'show'])->name('show');
        Route::put('/{assignment}', [\App\Http\Controllers\ProjectManagement\SiteAssignmentController::class, 'update'])->name('update');
        Route::delete('/{assignment}', [\App\Http\Controllers\ProjectManagement\SiteAssignmentController::class, 'destroy'])->name('destroy');
    });

    // Home Connection Team Roster Routes
    Route::prefix('home-connection/team-rosters')->name('home-connection.team-rosters.')->group(function () {
        Route::get('/', [\App\Http\Controllers\ProjectManagement\HomeConnectionTeamRosterController::class, 'index'])->name('index');
        Route::post('/', [\App\Http\Controllers\ProjectManagement\HomeConnectionTeamRosterController::class, 'store'])->name('store');
        Route::get('/{id}', [\App\Http\Controllers\ProjectManagement\HomeConnectionTeamRosterController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [\App\Http\Controllers\ProjectManagement\HomeConnectionTeamRosterController::class, 'edit'])->name('edit');
        Route::put('/{id}', [\App\Http\Controllers\ProjectManagement\HomeConnectionTeamRosterController::class, 'update'])->name('update');
        Route::delete('/{id}', [\App\Http\Controllers\ProjectManagement\HomeConnectionTeamRosterController::class, 'destroy'])->name('destroy');
        Route::post('/export', [\App\Http\Controllers\ProjectManagement\HomeConnectionTeamRosterController::class, 'export'])->name('export');
    });

    Route::prefix('project-management')->group(function () {
        Route::get('/map-data', [App\Http\Controllers\ProjectManagement\MapDataController::class, 'index'])->name('project-management.map-data');
    });
});

// Customer Management Routes
Route::prefix('company')->name('company.')->group(function () {
    Route::prefix('customers')->name('customers.')->group(function () {
        Route::get('/', [CustomerManagment::class, 'index'])->name('index');
        Route::post('/all', [CustomerManagment::class, 'showall'])->name('showall');
        Route::post('/', [CustomerManagment::class, 'store'])->name('store');
        Route::post('/individual', [CustomerManagment::class, 'storeIndividual'])->name('store.individual');
        Route::get('/{id}/edit', [CustomerManagment::class, 'edit'])->name('edit');
        Route::get('/{id}/edit-individual', [CustomerManagment::class, 'edit'])->name('edit.individual');
        Route::put('/{id}', [CustomerManagment::class, 'update'])->name('update');
        Route::delete('/{id}', [CustomerManagment::class, 'destroy'])->name('destroy');
        Route::get('/download-template', [CustomerManagment::class, 'downloadTemplate'])->name('download-template');
        Route::post('/bulk-upload', [CustomerManagment::class, 'bulkUpload'])->name('bulk-upload');
        Route::get('/{customer}/restore', [CustomerManagment::class, 'restore'])->name('restore');
        Route::get('/data', [CustomerManagment::class, 'getData'])->name('data');
        Route::get('/search', [CustomerManagment::class, 'search'])->name('search');
        Route::get('/{customer}', [CustomerManagment::class, 'show'])->name('show');
    });
});
// CRM Leads Management Routes
Route::prefix('company')->name('company.')->group(function () {
    Route::prefix('leads')->name('leads.')->group(function () {
        Route::post('/all', [App\Http\Controllers\CRM\CrmLeadsController::class, 'index'])->name('index');
        Route::post('/', [App\Http\Controllers\CRM\CrmLeadsController::class, 'store'])->name('store');
        Route::get('/{id}', [App\Http\Controllers\CRM\CrmLeadsController::class, 'show'])->name('show');
        Route::put('/{id}', [App\Http\Controllers\CRM\CrmLeadsController::class, 'update'])->name('update');
        Route::get('/{id}/edit', [App\Http\Controllers\CRM\CrmLeadsController::class, 'edit'])->name('edit');
        Route::delete('/{id}', [App\Http\Controllers\CRM\CrmLeadsController::class, 'destroy'])->name('destroy');
        Route::put('/{id}/status', [App\Http\Controllers\CRM\CrmLeadsController::class, 'updateStatus'])->name('status');
        Route::post('/bulk-upload', [App\Http\Controllers\CRM\CrmLeadsController::class, 'bulkUpload'])->name('bulk-upload');
        Route::get('/download-template', [App\Http\Controllers\CRM\CrmLeadsController::class, 'downloadTemplate'])->name('download-template');
        // Additional Lead Actions
        Route::post('/export', [App\Http\Controllers\CRM\CrmLeadsController::class, 'export'])->name('export');
        Route::post('/schedule-appointment', [App\Http\Controllers\CRM\CrmLeadsController::class, 'scheduleAppointment'])->name('schedule-appointment');
        Route::get('/appointments/all', [App\Http\Controllers\CRM\CrmLeadsController::class, 'getAppointments'])->name('appointments.all');
        Route::post('/convert-to-opportunity', [App\Http\Controllers\CRM\CrmLeadsController::class, 'convertToOpportunity'])->name('convert-to-opportunity');
    });
});

// Main Home Connection Route - Must be before catch-all routes
Route::get('/company/ProjectManagement/homeConnection', function(Request $request) {
    $companyId = Session::get('selected_company_id') ?? 1;
    
    // Get business unit filter from request
    $businessUnit = $request->get('business_unit');
    
    // Get team filter from request
    $teamId = $request->get('team_id');
    
    // Build query with optional business unit filter
    $query = App\Models\ProjectManagement\HomeConnectionCustomer::where('company_id', $companyId);
    
    if ($businessUnit && in_array($businessUnit, ['GESL', 'LINFRA'])) {
        $query->where('business_unit', $businessUnit);
    }
    
    $customers = $query->orderBy('created_at', 'desc')
        ->paginate(5); // 5 customers per page

    // Debug: Check if customers are fetched
    Log::info('Main route - Customers fetched: ' . $customers->count());

    // Get site assignment data with pagination and optional team filter
    $assignmentsQuery = App\Models\ProjectManagement\SiteAssignment::where('company_id', $companyId)
        ->whereNotIn('status', ['completed', 'cancelled'])
        ->with(['customer', 'team.teamMembers', 'assignedBy', 'resolvedBy']);
    
    // Apply team filter if specified
    if ($teamId && $teamId !== 'all') {
        $assignmentsQuery->where('team_id', $teamId);
    }
    
    $assignments = $assignmentsQuery->orderBy('created_at', 'desc')
        ->paginate(5); // 5 assignments per page

    // Get sites (customers) for dropdown - using home_connection_customers
    $sites = App\Models\ProjectManagement\HomeConnectionCustomer::where('company_id', $companyId)
        ->select('id', 'customer_name as name', 'location as address', 'contact_number as phone')
        ->orderBy('customer_name')
        ->get();

    // Get teams for dropdown
    $teams = App\Models\TeamParing::where('company_id', $companyId)
        ->with(['teamMembers'])
        ->select('id', 'team_name', 'team_code', 'team_status', 'team_location')
        ->orderBy('team_name')
        ->get();

    // Get sites (customers) for dropdown - using home_connection_customers
    $sites = App\Models\ProjectManagement\HomeConnectionCustomer::where('company_id', $companyId)
        ->select('id', 'customer_name as name', 'location as address', 'contact_number as phone')
        ->orderBy('customer_name')
        ->get();

    // Get teams for dropdown
    $teams = App\Models\TeamParing::where('company_id', $companyId)
        ->with(['teamMembers'])
        ->select('id', 'team_name', 'team_code', 'team_status', 'team_location')
        ->orderBy('team_name')
        ->get();

    // Get assignments with issues
    $issues = App\Models\ProjectManagement\SiteAssignment::where('company_id', $companyId)
        ->where('has_issue', true)
        ->where('issue_status', '!=', App\Models\ProjectManagement\SiteAssignment::ISSUE_STATUS_RESOLVED)
        ->with(['customer', 'team', 'assignedBy'])
        ->orderBy('issue_reported_at', 'desc')
        ->get();

    // Get assignment history
    $history = App\Models\ProjectManagement\SiteAssignment::where('company_id', $companyId)
        ->where('status', App\Models\ProjectManagement\SiteAssignment::STATUS_COMPLETED)
        ->with(['customer', 'team'])
        ->orderBy('completed_date', 'desc')
        ->limit(20)
        ->get();

    // Calculate statistics
    $totalConnectionsQuery = App\Models\ProjectManagement\HomeConnectionCustomer::where('company_id', $companyId);
    if ($businessUnit && in_array($businessUnit, ['GESL', 'LINFRA'])) {
        $totalConnectionsQuery->where('business_unit', $businessUnit);
    }
    $totalConnections = $totalConnectionsQuery->count();
    
    // Active teams count (includes active, deployed, and maintenance - excludes only inactive)
    $activeTeams = App\Models\TeamParing::where('company_id', $companyId)
        ->whereIn('team_status', ['active', 'deployed', 'maintenance'])
        ->count();
    
    // Teams on field (teams with active assignments)
    $teamsOnField = App\Models\ProjectManagement\SiteAssignment::where('company_id', $companyId)
        ->whereIn('status', ['pending', 'in_progress'])
        ->distinct('team_id')
        ->count('team_id');
    
    // Pending requests count
    $pendingRequestsQuery = App\Models\ProjectManagement\HomeConnectionCustomer::where('company_id', $companyId)
        ->where('status', 'Pending');
    if ($businessUnit && in_array($businessUnit, ['GESL', 'LINFRA'])) {
        $pendingRequestsQuery->where('business_unit', $businessUnit);
    }
    $pendingRequests = $pendingRequestsQuery->count();
    
    // New pending requests today
    $newPendingToday = App\Models\ProjectManagement\HomeConnectionCustomer::where('company_id', $companyId)
        ->where('status', 'Pending')
        ->whereDate('created_at', today())
        ->count();
    
    // Calculate satisfaction rate (completed assignments without issues)
    $completedAssignments = App\Models\ProjectManagement\SiteAssignment::where('company_id', $companyId)
        ->where('status', App\Models\ProjectManagement\SiteAssignment::STATUS_COMPLETED)
        ->count();
    
    $completedWithoutIssues = App\Models\ProjectManagement\SiteAssignment::where('company_id', $companyId)
        ->where('status', App\Models\ProjectManagement\SiteAssignment::STATUS_COMPLETED)
        ->where(function($query) {
            $query->where('has_issue', false)
                  ->orWhereNull('has_issue');
        })
        ->count();
    
    $satisfactionRate = $completedAssignments > 0 
        ? round(($completedWithoutIssues / $completedAssignments) * 100) 
        : 0;
    
    // Calculate growth percentage (compare with last month)
    $lastMonthConnections = App\Models\ProjectManagement\HomeConnectionCustomer::where('company_id', $companyId)
        ->whereMonth('created_at', now()->subMonth()->month)
        ->whereYear('created_at', now()->subMonth()->year)
        ->count();
    
    $currentMonthConnections = App\Models\ProjectManagement\HomeConnectionCustomer::where('company_id', $companyId)
        ->whereMonth('created_at', now()->month)
        ->whereYear('created_at', now()->year)
        ->count();
    
    $growthPercentage = $lastMonthConnections > 0 
        ? round((($currentMonthConnections - $lastMonthConnections) / $lastMonthConnections) * 100, 1) 
        : 0;

    return view('company.ProjectManagement.homeConnection', [
        'customers' => $customers,
        'businessUnit' => $businessUnit,
        'teamId' => $teamId,
        'activeTab' => 'customers',
        'assignments' => $assignments,
        'sites' => $sites,
        'teams' => $teams,
        'issues' => $issues,
        'history' => $history,
        'companyId' => $companyId,
        // Statistics
        'totalConnections' => $totalConnections,
        'activeTeams' => $activeTeams,
        'teamsOnField' => $teamsOnField,
        'pendingRequests' => $pendingRequests,
        'newPendingToday' => $newPendingToday,
        'satisfactionRate' => $satisfactionRate,
        'growthPercentage' => $growthPercentage,
    ]);
})->name('project-management.home-connection');

// Home Connection Roster Export Route
Route::post('/company/ProjectManagement/homeConnection/export-rosters', [\App\Http\Controllers\ProjectManagement\SiteAssignmentController::class, 'export'])
    ->name('home-connection.export-rosters')
    ->middleware(['auth', 'company.session']);

// Home Connection Customers Routes - Must be before catch-all routes
Route::prefix('company/home-connections/customers')->name('project-management.customers.')->middleware(['auth', 'company.session'])->group(function () {
    Route::get('/', [App\Http\Controllers\ProjectManagement\HomeConnectionCustomersController::class, 'index'])->name('index');
    Route::post('/', [App\Http\Controllers\ProjectManagement\HomeConnectionCustomersController::class, 'store'])->name('store');
    Route::post('/bulk-upload', [App\Http\Controllers\ProjectManagement\HomeConnectionCustomersController::class, 'bulkUpload'])->name('bulk-upload');
    Route::get('/download-template', [App\Http\Controllers\ProjectManagement\HomeConnectionCustomersController::class, 'downloadTemplate'])->name('download-template');
    Route::match(['GET', 'POST'], '/export', [App\Http\Controllers\ProjectManagement\HomeConnectionCustomersController::class, 'export'])->name('export');
    Route::post('/export-assignments', [App\Http\Controllers\ProjectManagement\HomeConnectionCustomersController::class, 'exportAssignments'])->name('export-assignments');
    Route::get('/{customer}', [App\Http\Controllers\ProjectManagement\HomeConnectionCustomersController::class, 'show'])->name('show');
    Route::put('/{customer}', [App\Http\Controllers\ProjectManagement\HomeConnectionCustomersController::class, 'update'])->name('update');
    Route::delete('/{customer}', [App\Http\Controllers\ProjectManagement\HomeConnectionCustomersController::class, 'destroy'])->name('destroy');
    Route::post('/{customer}/schedule-appointment', [App\Http\Controllers\ProjectManagement\HomeConnectionCustomersController::class, 'scheduleAppointment'])->name('schedule-appointment');
    Route::put('/{customer}/status', [App\Http\Controllers\ProjectManagement\HomeConnectionCustomersController::class, 'updateStatus'])->name('update-status');
    Route::post('/{customer}/send-sms', [App\Http\Controllers\ProjectManagement\HomeConnectionCustomersController::class, 'sendSms'])->name('send-sms');
});
// CRM Dashboard Route
Route::prefix('company')->name('company.')->group(function () {
    Route::get('CRM/crm', [App\Http\Controllers\CRM\CrmDashboardController::class, 'index'])->name('crm.dashboard');

    // Customer Contacts Routes
    Route::post('customers/{id}/contacts', [CustomerContactController::class, 'store'])->name('customers.contacts.store');
    Route::put('customers/{id}/contacts/{contactId}', [CustomerContactController::class, 'update'])->name('customers.contacts.update');
    Route::delete('customers/{id}/contacts/{contactId}', [CustomerContactController::class, 'destroy'])->name('customers.contacts.destroy');
    Route::get('customers/{id}/contacts', [CustomerContactController::class, 'getContacts'])->name('customers.contacts.get');
});

// CRM Opportunity Routes
Route::prefix('company')->name('company.')->group(function () {
    Route::prefix('opportunities')->name('opportunities.')->group(function () {
        Route::post('/all', [App\Http\Controllers\CRM\OpportunityController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\CRM\OpportunityController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\CRM\OpportunityController::class, 'store'])->name('store');
        Route::get('/{id}', [App\Http\Controllers\CRM\OpportunityController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [App\Http\Controllers\CRM\OpportunityController::class, 'edit'])->name('edit');
        Route::put('/{id}', [App\Http\Controllers\CRM\OpportunityController::class, 'update'])->name('update');
        Route::delete('/{id}', [App\Http\Controllers\CRM\OpportunityController::class, 'destroy'])->name('destroy');
        Route::put('/{id}/status', [App\Http\Controllers\CRM\OpportunityController::class, 'updateStatus'])->name('status');
        Route::post('/export', [App\Http\Controllers\CRM\OpportunityController::class, 'export'])->name('export');
    });
});

// Campaign routes
Route::prefix('company')->name('company.')->group(function () {
    Route::prefix('campaigns')->name('campaigns.')->group(function () {
        Route::post('/all', [CampaignController::class, 'index'])->name('index'); // List campaigns
        Route::get('/create', [CampaignController::class, 'create'])->name('create'); // Show form
        Route::post('/store', [CampaignController::class, 'store'])->name('store'); // Handle form submission
        Route::get('/{id}/edit', [CampaignController::class, 'edit'])->name('edit'); // Edit form
        Route::put('/campaigns/{campaign}', [CampaignController::class, 'update'])->name('update');
        Route::post('/campaigns/store/{campaign}', [CampaignController::class, 'storeNotes'])->name('store.notes');
        Route::delete('/{campaign}', [CampaignController::class, 'destroy'])->name('destroy');
        // Route::get('/filter', [CampaignController::class, 'filter'])->name('search');
        Route::get('/{campaign}/view', [CampaignController::class, 'view'])->name('view');
        Route::post('/export', [CampaignController::class, 'export'])->name('export');
    });
});

// Email Management Routes
Route::prefix('company')->middleware(['auth.company_or_sub_user', 'company.session'])->group(function () {
    Route::prefix('email')->group(function () {
        Route::get('/sent', [EmailManagement::class, 'getSentEmails'])->name('company.email.sent');
        Route::get('/deleted', [EmailManagement::class, 'getDeletedEmails'])->name('company.email.deleted');
        Route::get('/drafts', [EmailManagement::class, 'getDraftEmails'])->name('company.email.drafts');
        Route::get('/drafts/{id}', [EmailManagement::class, 'getDraft'])->name('company.email.draft.get');
        Route::delete('/drafts/{id}', [EmailManagement::class, 'deleteDraft'])->name('company.email.draft.delete');
        Route::post('/save-draft', [EmailManagement::class, 'saveDraft'])->name('company.email.draft.save');
        Route::post('/send', [EmailManagement::class, 'sendEmail'])->name('company.email.send');
        Route::post('/delete', [EmailManagement::class, 'deleteEmail'])->name('company.email.delete');
        Route::post('/restore', [EmailManagement::class, 'restoreEmail'])->name('company.email.restore');
    });
});

// CRM Activity Routes
Route::prefix('company')->name('company.')->group(function () {
    Route::prefix('crm')->name('crm.')->group(function () {
        Route::post('/activities/all', [ActivityController::class, 'index'])->name('activities.index');
        Route::get('/activities/create', [ActivityController::class, 'create'])->name('activities.create');
        Route::post('/activities', [ActivityController::class, 'store'])->name('activities.store');
        Route::get('/activities/{activity}', [ActivityController::class, 'show'])->name('activities.show');
        Route::put('/activities/{activity}', [ActivityController::class, 'update'])->name('activities.update');
        Route::delete('/activities/{activity}', [ActivityController::class, 'destroy'])->name('activities.destroy');
        Route::get('/activities/refresh', [ActivityController::class, 'refresh'])->name('activities.refresh');
        Route::post('/activities/{activity}/complete', [ActivityController::class, 'complete'])->name('activities.complete');
        Route::post('/activities/sub_users_contact', [ActivityController::class, 'sub_users_contact'])->name('activities.sub_users_contact');


        Route::POST('/activities/statistics', [ActivityController::class, 'getActivityStatistics'])->name('activities.statistics');
    });
});

// CRM Contract Management Routes
Route::prefix('company')->name('company.')->group(function () {
    Route::prefix('CRM/contract')->name('contract.')->group(function () {
        Route::get('/', [ContractController::class, 'index'])->name('index');
        Route::post('/', [ContractController::class, 'store'])->name('store');
        Route::get('/{id}/show', [ContractController::class, 'show'])->name('show');
        Route::match(['put', 'post'], '/edit/{id}', [ContractController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [ContractController::class, 'destroy'])->name('destroy');
        Route::get('/{id}/download', [ContractController::class, 'download'])->name('download');
        Route::post('/send-for-signature/{id}', [ContractController::class, 'sendForSignature'])->name('send-for-signature');
        Route::get('/filter', [ContractController::class, 'filter'])->name('filter');
        Route::post('/{id}/comment', [ContractController::class, 'addComment'])->name('comment.add');
        Route::post('/{id}/reminder', [ContractController::class, 'addReminder'])->name('reminder.add');
        Route::get('/{id}/audit-trail', [ContractController::class, 'getAuditTrail'])->name('audit-trail');
    });
});

Route::post('company/send-contract', [ContractController::class, 'sendContract'])->name('contract.send');
Route::post('company/send-signature', [ContractController::class, 'sendSignature'])->name('signature.send');

// CRM Activity Routes
Route::prefix('crm')->middleware(['auth.company_or_sub_user', 'company.session'])->group(function () {
    Route::get('/activities', [ActivityController::class, 'index'])->name('crm.activities.index');
    Route::post('/activities', [ActivityController::class, 'store'])->name('crm.activities.store');
    Route::put('/activities/{id}', [ActivityController::class, 'update'])->name('crm.activities.update');
    Route::delete('/activities/{id}', [ActivityController::class, 'destroy'])->name('crm.activities.destroy');
    Route::post('/activities/{id}/complete', [ActivityController::class, 'complete'])->name('crm.activities.complete');
    Route::post('/activities/create', [ActivityController::class, 'store'])->name('crm.activities.create');
});

// Newsletter Management Routes
Route::prefix('company/newsletters')->name('company-newsletters.')->group(function () {
    Route::get('/', [CompanyNewsLetterController::class, 'index'])->name('index');
    Route::post('/', [CompanyNewsLetterController::class, 'store'])->name('store');
    Route::get('/{companyNewsLetter}/edit', [CompanyNewsLetterController::class, 'edit'])->name('edit');
    Route::put('/{companyNewsLetter}', [CompanyNewsLetterController::class, 'update'])->name('update');
    Route::delete('/{companyNewsLetter}', [CompanyNewsLetterController::class, 'destroy'])->name('destroy');
});

// -- Routes for individual & company dashboards views
Route::get('/company/index', [CompanyUserController::class, 'index'])->name('dash.company');
Route::get('/individual/index', [IndividualUserController::class, 'index'])->name('dash.individual');

// Individual User Routes
Route::prefix('individual')->name('individual.')->group(function () {
    Route::get('/', [IndividualUserController::class, 'index'])->name('dashboard');
});

// Project Management Export Route - Must be before catch-all routes
Route::get('company/projects/export-csv', [App\Http\Controllers\ProjectManagement\ProjectController::class, 'export'])
    ->name('company.projects.export')
    ->middleware(['auth', 'company.session']);
    // Project Management Routes
Route::prefix('company')->middleware(['auth', 'company.session'])->name('company.')->group(function () {
    // Main Project Management Dashboard
    Route::get('/projects', [App\Http\Controllers\ProjectManagement\ProjectManagementController::class, 'index'])->name('projects.dashboard');
    
    Route::prefix('projects')->name('projects.')->group(function () {
        Route::post('/all', [App\Http\Controllers\ProjectManagement\ProjectController::class, 'index'])->name('index');
        Route::post('/', [App\Http\Controllers\ProjectManagement\ProjectController::class, 'store'])->name('store');
        Route::get('/{id}', [App\Http\Controllers\ProjectManagement\ProjectController::class, 'show'])->name('show');
        Route::put('/{id}', [App\Http\Controllers\ProjectManagement\ProjectController::class, 'update'])->name('update');
        Route::get('/{id}/edit', [App\Http\Controllers\ProjectManagement\ProjectController::class, 'edit'])->name('edit');
        Route::delete('/{id}', [App\Http\Controllers\ProjectManagement\ProjectController::class, 'destroy'])->name('destroy');
        Route::put('/{id}/status', [App\Http\Controllers\ProjectManagement\ProjectController::class, 'updateStatus'])->name('status');
       
            
        Route::get('/managers', [App\Http\Controllers\ProjectManagement\ProjectController::class, 'getManagers'])->name('managers');
    });

    // Task Management Routes
    Route::prefix('tasks')->name('tasks.')->group(function () {
        Route::post('/', [App\Http\Controllers\ProjectManagement\TaskController::class, 'store'])->name('store');
        Route::get('/{id}', [App\Http\Controllers\ProjectManagement\TaskController::class, 'show'])->name('show');
        Route::put('/{id}', [App\Http\Controllers\ProjectManagement\TaskController::class, 'update'])->name('update');
        Route::delete('/{id}', [App\Http\Controllers\ProjectManagement\TaskController::class, 'destroy'])->name('destroy');
        Route::put('/{id}/status', [App\Http\Controllers\ProjectManagement\TaskController::class, 'updateStatus'])->name('status');
        
        // Additional Task Actions
        Route::get('/projects', [App\Http\Controllers\ProjectManagement\TaskController::class, 'getProjects'])->name('projects');
        Route::get('/teams', [App\Http\Controllers\ProjectManagement\TaskController::class, 'getTeams'])->name('teams');
    });

    // Project Management Reports & Analytics Routes
    Route::prefix('pmreports')->name('pmreports.')->group(function () {
        // Main reports dashboard
        Route::get('/', [App\Http\Controllers\ProjectManagement\PmReportController::class, 'index'])->name('index');
        
        // AJAX endpoints for analytics data
        Route::get('/analytics', [App\Http\Controllers\ProjectManagement\PmReportController::class, 'getAnalytics'])->name('analytics');
        Route::get('/priority-tasks', [App\Http\Controllers\ProjectManagement\PmReportController::class, 'getPriorityTasks'])->name('priority-tasks');
        
        // Report generation and export
        Route::post('/generate', [App\Http\Controllers\ProjectManagement\PmReportController::class, 'generateReport'])->name('generate');
        Route::post('/export', [App\Http\Controllers\ProjectManagement\PmReportController::class, 'exportData'])->name('export');
    });
});

// Management Routes - Must be before catch-all routes
Route::prefix('company')->middleware(['auth.company_or_sub_user', 'company.session'])->group(function () {
    Route::get('/Management/purchase-order-approval', function() {
        return view('company.Management.purchase-order-approval');
    })->name('management.purchase-order-approval');
    
    Route::get('/Management/requisition-approval', function() {
        return view('company.Management.requisition-approval');
    })->name('management.requisition-approval');
});

// Catch-all routes - Keep these last
Route::get('{first}/{second}/{third}', [RoutingController::class, 'thirdLevel'])->name('third');
Route::get('{first}/{second}', [RoutingController::class, 'secondLevel'])->name('second');
Route::get('{any}', [RoutingController::class, 'root'])->name('any');

// CRM Sales Routes
Route::prefix('company')->name('company.')->group(function () {
    Route::prefix('sales')->name('sales.')->group(function () {
        Route::post('/salescategories', [App\Http\Controllers\CRM\CrmSalesController::class, 'saleCategory'])->name('saleCategory');
        Route::POST('/statistics', [App\Http\Controllers\CRM\CrmSalesController::class, 'getDashboardStats'])->name('statistics');
        Route::post('/all', [App\Http\Controllers\CRM\CrmSalesController::class, 'index'])->name('index');
        Route::post('/dealexport', [App\Http\Controllers\CRM\CrmSalesController::class, 'export'])->name('dealexport');
        Route::post('/sale_categories', [App\Http\Controllers\CRM\CrmSalesController::class, 'getSalesCategory'])->name('getSalesCategory');
        Route::get('/create', [App\Http\Controllers\CRM\CrmSalesController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\CRM\CrmSalesController::class, 'store'])->name('store');
        Route::post('/{id}', [App\Http\Controllers\CRM\CrmSalesController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [App\Http\Controllers\CRM\CrmSalesController::class, 'edit'])->name('edit');
        Route::put('/{id}', [App\Http\Controllers\CRM\CrmSalesController::class, 'update'])->name('update');
        Route::delete('/{id}', [App\Http\Controllers\CRM\CrmSalesController::class, 'destroy'])->name('destroy');
        Route::put('/{id}/status', [App\Http\Controllers\CRM\CrmSalesController::class, 'updateStatus'])->name('status');
    });
});

Route::prefix('company')->name('company.')->group(function () {
    Route::prefix('support')->name('support.')->group(function () {
        Route::post('/show', [App\Http\Controllers\CRM\TicketController::class, 'show'])->name('show');
        Route::post('/all', [App\Http\Controllers\CRM\TicketController::class, 'index'])->name('index'); // List all tickets
        Route::post('/', [App\Http\Controllers\CRM\TicketController::class, 'store'])->name('store'); // Create a ticket
        Route::put('close/{id}', [App\Http\Controllers\CRM\TicketController::class, 'close'])->name('destroy'); // Soft delete ticket
    });
});

Route::get('/referral/process/{token}', [ReferralController::class, 'processReferral'])
    ->name('referral.process');

Route::prefix('company')->group(function () {
    // Loyalty Programs
    Route::prefix('loyalty-programs')->group(function () {
        // Stats and Reports
        Route::post('/stats', [LoyaltyProgramController::class, 'stats']);
        Route::post('/segmentation', [LoyaltyProgramController::class, 'segmentation']);
        Route::post('/import', [LoyaltyProgramController::class, 'importCustomers']);
        Route::post('/report', [LoyaltyProgramController::class, 'generateReport']);


        Route::post('/all', [LoyaltyProgramController::class, 'index']);
        Route::post('/', [LoyaltyProgramController::class, 'store']);
        Route::post('/{id}', [LoyaltyProgramController::class, 'show']);
        Route::put('/{id}', [LoyaltyProgramController::class, 'update']);
        Route::delete('/{id}', [LoyaltyProgramController::class, 'destroy']);



        // Tiers
        Route::prefix('{programId}/tiers')->group(function () {
            Route::post('/all', [CustomerTierController::class, 'index']);
            Route::post('/', [CustomerTierController::class, 'store']);
            Route::get('/{tierId}', [CustomerTierController::class, 'show']);
            Route::put('/{tierId}', [CustomerTierController::class, 'update']);
            Route::delete('/{tierId}', [CustomerTierController::class, 'destroy']);
        });

        // Rewards
        Route::prefix('{programId}/rewards')->group(function () {
            Route::post('/all', [RewardController::class, 'index']);
            Route::post('/', [RewardController::class, 'store']);
            Route::get('/{rewardId}', [RewardController::class, 'show']);
            Route::put('/{rewardId}', [RewardController::class, 'update']);
            Route::delete('/{rewardId}', [RewardController::class, 'destroy']);
            Route::post('/getrewards', [RewardController::class, 'getRewards']);
        });

        // Redemptions
        Route::prefix('{programId}/redemptions')->group(function () {
            Route::post('/all', [RedemptionController::class, 'index']);
            Route::post('/', [RedemptionController::class, 'store']);
            Route::get('/{redemptionId}', [RedemptionController::class, 'show']);
            Route::put('/{redemptionId}', [RedemptionController::class, 'update']);
            Route::delete('/{redemptionId}', [RedemptionController::class, 'destroy']);
            Route::post('/customers', [RedemptionController::class, 'getCustomers']); // Fetch customers only

        });

        // Referrals
        Route::prefix('{programId}/referrals')->group(function () {
            Route::post('/all', [ReferralController::class, 'index']);
            Route::post('/', [ReferralController::class, 'store']);
            Route::get('/{referralId}', [ReferralController::class, 'show']);
            Route::put('/{referralId}', [ReferralController::class, 'update']);
            Route::delete('/{referralId}', [ReferralController::class, 'destroy']);
        });
    });
});

Route::prefix('company/hr/employees')->name('hr.employees.')->group(function () {

    // Specific routes must come before parameter routes
    Route::post('/available-staff-ids', [EmployeeController::class, 'getAvailableStaffIds'])->name('available-staff-ids');

    Route::post('/store', [EmployeeController::class, 'store'])->name('store');
    Route::get('/all', [EmployeeController::class, 'index'])->name('index');
    Route::post('/all', [EmployeeController::class, 'getEmployees'])->name('all');
    Route::get('/create', [EmployeeController::class, 'create'])->name('create');

    // Import helpers MUST come before parameter routes
    Route::get('/import/template', [EmployeeController::class, 'downloadImportTemplate'])->name('import.template');
    Route::post('/import', [EmployeeController::class, 'import'])->name('import');

    Route::post('/{employee}', [EmployeeController::class, 'show'])->name('show');
    Route::get('/{employee}/edit', [EmployeeController::class, 'edit'])->name('edit');
    Route::put('/{employee}', [EmployeeController::class, 'update'])->name('update');
    Route::delete('/{employee}', [EmployeeController::class, 'destroy'])->name('destroy');
    Route::post('/{employee}/send-message', [EmployeeController::class, 'sendMessage'])->name('send-message');
    Route::get('/export', [EmployeeController::class, 'export'])->name('export');

    // (moved above)
});




Route::prefix('company/hr/jobs')->name('jobs.')->group(function () {
    Route::post('/all', [JobsController::class, 'all'])->name('all');
    Route::post('/status-counts', [JobsController::class, 'getStatusCounts'])->name('statusCounts');

    Route::post('/store', [JobsController::class, 'store'])->name('store');
    Route::post('/showByToken', [JobsController::class, 'showByToken'])->name('showByToken');
    Route::post('/update', [JobsController::class, 'update'])->name('update');
    Route::post('/delete', [JobsController::class, 'delete'])->name('delete');
});




Route::prefix('company/hr/attendance')->name('attendance.')->group(function () {
    Route::post('/index', [AttendanceController::class, 'index'])->name('index');
    Route::post('/stats', [AttendanceController::class, 'getStats'])->name('stats');
    Route::post('/', [AttendanceController::class, 'store'])->name('store');
    Route::post('/bulk', [AttendanceController::class, 'bulkUpdate'])->name('bulk');
    Route::put('/{id}', [AttendanceController::class, 'update'])->name('update');
    Route::post('/history/{employeeId}', [AttendanceController::class, 'history'])->name('history');
    Route::post('/employees', [AttendanceController::class, 'getEmployees'])->name('employees');
    Route::post('/import', [AttendanceController::class, 'import'])->name('import');
});


Route::prefix('company/hr/payroll')->name('payroll.')->group(function () {
    Route::post('/stats', [PayrollController::class, 'stats'])->name('stats');
    Route::post('/all', [PayrollController::class, 'index'])->name('index');
    Route::post('/debug-per-page', [PayrollController::class, 'debugPerPage'])->name('debug-per-page');
    Route::post('/employees', [PayrollController::class, 'getEmployees'])->name('employees');
    Route::post('/departments', [PayrollController::class, 'getDepartments'])->name('departments');
    Route::post('/run', [PayrollController::class, 'runPayroll'])->name('run');
    Route::post('/', [PayrollController::class, 'store'])->name('store');
    Route::post('/import', [PayrollController::class, 'import'])->name('payroll.import');
    Route::post('/{payroll}', [PayrollController::class, 'show'])->name('show');
    Route::put('/{payroll}', [PayrollController::class, 'update'])->name('update');
    Route::post('/process', [PayrollController::class, 'process'])->name('process');


    Route::delete('/{payroll}', [PayrollController::class, 'destroy'])->name('destroy');
    Route::post('/export/excel', [PayrollController::class, 'exportExcel'])->name('export.excel');
    Route::post('/export/pdf', [PayrollController::class, 'exportPdf'])->name('export.pdf');
    Route::post('/export/csv', [PayrollController::class, 'exportCsv'])->name('export.csv');
});

// Leave Management Routes
Route::prefix('company/hr/leaves')->name('leaves.')->group(function () {
    Route::post('/', [LeaveController::class, 'index'])->name('index');
    Route::post('/store', [LeaveController::class, 'store'])->name('store');
    Route::post('/stats', [LeaveController::class, 'getStats'])->name('stats');
    Route::post('/calendar-events', [LeaveController::class, 'getCalendarEvents'])->name('calendar.events');
    Route::post('/balance', [LeaveController::class, 'getLeaveBalance'])->name('balance');
    Route::post('/{id}', [LeaveController::class, 'show'])->name('show');
    Route::put('/{id}', [LeaveController::class, 'update'])->name('update');
    Route::delete('/{id}', [LeaveController::class, 'destroy'])->name('destroy');
    Route::post('/{id}/approve', [LeaveController::class, 'approve'])->name('approve');
    Route::post('/{id}/reject', [LeaveController::class, 'reject'])->name('reject');
    Route::post('/{id}/cancel', [LeaveController::class, 'cancel'])->name('cancel');
});

// Training Management Routes
Route::prefix('company/hr/training')->name('training.')->group(function () {
    Route::post('/', [TrainingController::class, 'index'])->name('index');
    Route::post('/test', [TrainingController::class, 'test'])->name('test');
    Route::post('/store', [TrainingController::class, 'store'])->name('store');
    Route::post('/stats', [TrainingController::class, 'getStats'])->name('stats');
    Route::post('/{id}', [TrainingController::class, 'show'])->name('show');
    Route::put('/{id}', [TrainingController::class, 'update'])->name('update');
    Route::delete('/{id}', [TrainingController::class, 'destroy'])->name('destroy');
});

// Performance Management Routes (temporarily without middleware for testing)
Route::prefix('company/hr/performance')->name('performance.')->group(function () {
    Route::post('/', [PerformanceController::class, 'index'])->name('index');
    Route::post('/store', [PerformanceController::class, 'store'])->name('store');
    Route::post('/stats', [PerformanceController::class, 'getStats'])->name('stats');
    Route::get('/search-employees', [PerformanceController::class, 'searchEmployees'])->name('search-employees');
    Route::post('/{id}', [PerformanceController::class, 'show'])->name('show');
    Route::put('/{id}', [PerformanceController::class, 'update'])->name('update');
    Route::delete('/{id}', [PerformanceController::class, 'destroy'])->name('destroy');
});

// Staff Self-Service Portal Routes
Route::prefix('company/hr/staff')->name('staff.')->group(function () {
    Route::get('/', [App\Http\Controllers\HR\StaffController::class, 'index'])->name('index');
    Route::post('/personal-info', [App\Http\Controllers\HR\StaffController::class, 'updatePersonalInfo'])->name('personal-info.update');
    Route::post('/profile-picture', [App\Http\Controllers\HR\StaffController::class, 'uploadProfilePicture'])->name('profile-picture.upload');
    Route::post('/expense-claims', [App\Http\Controllers\HR\StaffController::class, 'getExpenseClaims'])->name('expense-claims');
    Route::post('/training-data', [App\Http\Controllers\HR\StaffController::class, 'getTrainingData'])->name('training-data');
    Route::post('/documents', [App\Http\Controllers\HR\StaffController::class, 'getDocuments'])->name('documents');
});

// Debug route for testing performance API
Route::get('/debug-performance-api', function() {
    try {
        session(['selected_company_id' => 1]);

        $controller = new \App\Http\Controllers\HR\PerformanceController();
        $request = new \Illuminate\Http\Request();

        $statsResponse = $controller->getStats($request);
        $statsData = json_decode($statsResponse->getContent(), true);

        $indexResponse = $controller->index($request);
        $indexData = json_decode($indexResponse->getContent(), true);

        return response()->json([
            'success' => true,
            'stats' => $statsData,
            'index_count' => count($indexData['data'] ?? []),
            'message' => 'Performance API debug test completed'
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
    }
});

// Documentation Management Routes
Route::prefix('company/hr/documentation')->name('documentation.')->group(function () {
    // Folder routes (must come before generic {id} routes)
    Route::post('/folders', [DocumentationController::class, 'getFolders'])->name('folders');
    Route::post('/folders/create', [DocumentationController::class, 'createFolder'])->name('folders.create');
    Route::put('/folders/{id}', [DocumentationController::class, 'updateFolder'])->name('folders.update');
    Route::delete('/folders/{id}', [DocumentationController::class, 'deleteFolder'])->name('folders.delete');
    
    // Document routes
    Route::post('/', [DocumentationController::class, 'index'])->name('index');
    Route::post('/store', [DocumentationController::class, 'store'])->name('store');
    Route::post('/stats', [DocumentationController::class, 'getStats'])->name('stats');
    Route::post('/{id}', [DocumentationController::class, 'show'])->name('show');
    Route::put('/{id}', [DocumentationController::class, 'update'])->name('update');
    Route::delete('/{id}', [DocumentationController::class, 'destroy'])->name('destroy');
    Route::get('/{id}/download', [DocumentationController::class, 'download'])->name('download');
    Route::post('/{id}/status', [DocumentationController::class, 'updateStatus'])->name('updateStatus');
});

// Debug route for testing folders API
Route::post('/debug-folders', function() {
    return response()->json([
        'success' => true,
        'message' => 'Debug route working',
        'timestamp' => now()->toISOString()
    ]);
});

// Test route for documentation controller
Route::post('/test-documentation-controller', [App\Http\Controllers\HR\DocumentationController::class, 'getFolders']);

Route::prefix('company/warehouse/purchasing_order')->name('purchasing_order.')->group(function () {
    // Purchase Order Routes

    Route::post('/all', [POController::class, 'index'])->name('po.index'); // paginated list
    Route::post('/', [POController::class, 'store'])->name('po.store');

    Route::post('/suppliers/all', [POController::class, 'supplierIndex'])->name('supplier.index');
    Route::post('/suppliers', [POController::class, 'supplierStore'])->name('supplier.store');
    Route::post('/generatePONumber', [POController::class, 'generatePONumber'])->name('generate.po.number');
    Route::post('/validate-batch-number', [POController::class, 'validateBatchNumber'])->name('validate.batch.number');
    
    // Tax configuration routes
    Route::get('/tax-configurations', [POController::class, 'getTaxConfigurations'])->name('tax.configurations');
    Route::post('/calculate-tax', [POController::class, 'calculateTax'])->name('calculate.tax');

    // Import routes
    Route::get('/download-template', [App\Http\Controllers\WareHouse\POImportController::class, 'downloadTemplate'])->name('download.template');
    Route::post('/import', [App\Http\Controllers\WareHouse\POImportController::class, 'processImport'])->name('import');

    Route::post('/{id}', [POController::class, 'show'])->name('po.show');
    Route::put('/{po_number}', [POController::class, 'update'])->name('po.update');
    Route::delete('/{id}', [POController::class, 'destroy'])->name('po.destroy');

    // Supplier Routes

    Route::get('/suppliers/{id}', [POController::class, 'supplierShow'])->name('supplier.show');
    Route::put('/suppliers/{id}', [POController::class, 'supplierUpdate'])->name('supplier.update');
    Route::delete('/suppliers/{id}', [POController::class, 'supplierDestroy'])->name('supplier.destroy');
});

Route::prefix('company')->middleware(['auth', 'company.session'])->group(function () {
    // PO Approval Routes
    Route::prefix('warehouse/po-approval')->name('po_approval.')->group(function () {
        Route::post('/pending', [POApprovalController::class, 'getPendingApprovals'])->name('pending');
        Route::post('/all-requisitions', [POApprovalController::class, 'getAllRequisitions'])->name('all_requisitions');
        Route::post('/details/{id}', [POApprovalController::class, 'getApprovalDetails'])->name('details');
        Route::post('/approve/{id}', [POApprovalController::class, 'approve'])->name('approve');
        Route::post('/reject/{id}', [POApprovalController::class, 'reject'])->name('reject');
        Route::post('/revert/{id}', [POApprovalController::class, 'revert'])->name('revert');
        Route::post('/bulk-approve', [POApprovalController::class, 'bulkApprove'])->name('bulk_approve');
        Route::get('/export', [POApprovalController::class, 'exportApprovals'])->name('export');
        Route::post('/statistics', [POApprovalController::class, 'getProcurementStatistics'])->name('statistics');
        
        // Invoice upload routes
        Route::post('/upload-invoice/{id}', [POApprovalController::class, 'uploadInvoice'])->name('upload_invoice');
        Route::delete('/delete-invoice/{poId}/{invoiceId}', [POApprovalController::class, 'deleteInvoice'])->name('delete_invoice');
        Route::get('/download-invoice/{poId}/{invoiceId}', [POApprovalController::class, 'downloadInvoice'])->name('download_invoice');
        Route::post('/batch-invoice-status', [POApprovalController::class, 'batchInvoiceStatus'])->name('batch_invoice_status');
        
        // Test route for debugging
        Route::get('/test-upload/{id}', [POApprovalController::class, 'testUpload'])->name('test_upload');
    });
    Route::prefix('warehouse/suppliers')->name('suppliers.')->group(function () {
    Route::post('/all', [SupplierController::class, 'index'])->name('index');
        Route::post('/search', [SupplierController::class, 'search'])->name('search');
        Route::post('/export', [SupplierController::class, 'export'])->name('export');
        Route::get('/filter-data', [SupplierController::class, 'getFilterData'])->name('filter-data');
    Route::post('/', [SupplierController::class, 'store'])->name('store');
    
    // Test route for debugging
    Route::get('/test', function() {
        return response()->json([
            'success' => true,
            'message' => 'Supplier route is working',
            'auth' => auth()->check(),
            'company_id' => session('selected_company_id')
        ]);
    })->name('test');
    
    // Test purchase orders data
    Route::get('/test-purchase-orders', function() {
        $companyId = session('selected_company_id');
        $suppliers = \App\Models\Wh_Supplier::where('company_id', $companyId)
            ->with(['purchaseOrders' => function($q) {
                $q->latest()->take(1);
            }])
            ->get();
        
        return response()->json([
            'suppliers' => $suppliers->map(function($supplier) {
                return [
                    'id' => $supplier->id,
                    'company_name' => $supplier->company_name,
                    'purchase_orders_count' => $supplier->purchaseOrders->count(),
                    'purchase_orders' => $supplier->purchaseOrders->map(function($order) {
                        return [
                            'id' => $order->id,
                            'po_number' => $order->po_number,
                            'total_value' => $order->total_value,
                            'created_at' => $order->created_at,
                            'supplier_id' => $order->supplier_id
                        ];
                    })
                ];
            })
        ]);
    })->name('test-purchase-orders');
    
    // Simple test route to check purchase orders
    Route::get('/check-orders', function() {
        $orders = \App\Models\Wh_PurchaseOrder::with('supplier')->get();
        $data = [];
        foreach($orders as $order) {
            $data[] = [
                'po_number' => $order->po_number,
                'supplier_name' => $order->supplier->company_name,
                'supplier_id' => $order->supplier_id,
                'total_value' => $order->total_value,
                'created_at' => $order->created_at
            ];
        }
        return response()->json($data);
    })->name('check-orders');
    
    // Rating endpoints - must come before {id} routes
    Route::post('/rate', [SupplierController::class, 'rateSupplier'])->name('rate');
    Route::get('/{id}/ratings', [SupplierController::class, 'getSupplierRatings'])->name('ratings');
    
    Route::post('/{id}', [SupplierController::class, 'show'])->name('show');
    Route::put('/{id}', [SupplierController::class, 'update'])->name('update');
    Route::delete('/{id}', [SupplierController::class, 'destroy'])->name('destroy');
});

    Route::prefix('warehouse/quality-inspections')->name('quality-inspections.')->group(function () {
    // Main CRUD operations
    Route::post('/all', [QualityInspectionController::class, 'index'])->name('index');

    
    // Special endpoints
    Route::post('/suppliers-with-pending-pos', [QualityInspectionController::class, 'getSuppliersWithPendingPOs'])->name('suppliers-with-pending-pos');
    Route::post('/generate-batch-number', [QualityInspectionController::class, 'generateBatchNumber'])->name('generate-batch-number');
    Route::post('/categories', [QualityInspectionController::class, 'getCategories'])->name('categories');
    
    Route::post('/{supplierId}/pending-pos', [QualityInspectionController::class, 'getPendingPOsForSupplier'])->name('pending-pos');
    Route::post('/{poId}/uninspected-items', [QualityInspectionController::class, 'getUninspectedItems'])->name('uninspected-items');
    // Route::post('/{id}/update-status', [QualityInspectionController::class, 'updateStatus'])->name('update-status');
    
Route::post('/{id}/edit', [QualityInspectionController::class, 'edit'])->name('edit');
        Route::post('/', [QualityInspectionController::class, 'store'])->name('store');
    Route::post('/{id}', [QualityInspectionController::class, 'show'])->name('show');
    Route::put('/{id}', [QualityInspectionController::class, 'update'])->name('update');
    Route::delete('/{id}', [QualityInspectionController::class, 'destroy'])->name('destroy');
});

    // Central Store Routes
    Route::prefix('warehouse/central-store')->name('central-store.')->group(function () {
    Route::get('/', [App\Http\Controllers\WareHouse\CentralStoreController::class, 'index'])->name('index');
    Route::post('/page-data', [App\Http\Controllers\WareHouse\CentralStoreController::class, 'getPageData'])->name('page-data');
    Route::post('/suppliers-with-approved-pos', [App\Http\Controllers\WareHouse\CentralStoreController::class, 'getSuppliersWithApprovedPOs'])->name('suppliers-with-approved-pos');
    Route::post('/{supplierId}/approved-pos', [App\Http\Controllers\WareHouse\CentralStoreController::class, 'getApprovedPOsForSupplier'])->name('approved-pos');
    Route::post('/{poId}/approved-items', [App\Http\Controllers\WareHouse\CentralStoreController::class, 'getApprovedItemsFromPO'])->name('approved-items');
    Route::post('/add-item', [App\Http\Controllers\WareHouse\CentralStoreController::class, 'addNewItem'])->name('add-item');
    Route::post('/items', [App\Http\Controllers\WareHouse\CentralStoreController::class, 'getAllItems'])->name('items');
    Route::post('/{itemId}/complete', [App\Http\Controllers\WareHouse\CentralStoreController::class, 'markItemCompleted'])->name('complete');
    Route::post('/items/{itemId}/update', [App\Http\Controllers\WareHouse\CentralStoreController::class, 'updateItem'])->name('update-item');
    
    // Debug route for testing
            Route::get('/test-update-route', function() {
            return response()->json([
                'success' => true,
                'message' => 'Update route is accessible',
                'auth_check' => auth()->check(),
                'user' => auth()->user() ? auth()->user()->id : null,
                'company_id' => session('selected_company_id'),
                'route_exists' => true
            ]);
        })->name('test-update-route');
        
        Route::get('/test-images', function() {
            $items = \App\Models\CentralStore::whereNotNull('images')->get(['id', 'item_name', 'images']);
            return response()->json([
                'items_with_images' => $items->map(function($item) {
                    return [
                        'id' => $item->id,
                        'item_name' => $item->item_name,
                        'images' => $item->images
                    ];
                })
            ]);
        })->name('test-images');
    Route::post('/statistics', [App\Http\Controllers\WareHouse\CentralStoreController::class, 'getStatistics'])->name('statistics');
    Route::post('/check-sku-uniqueness', [App\Http\Controllers\WareHouse\CentralStoreController::class, 'checkSkuUniqueness'])->name('check-sku-uniqueness');
    Route::post('/check-barcode-uniqueness', [App\Http\Controllers\WareHouse\CentralStoreController::class, 'checkBarcodeUniqueness'])->name('check-barcode-uniqueness');
    Route::post('/available-items-for-requisition', [App\Http\Controllers\WareHouse\CentralStoreController::class, 'getAvailableItemsForRequisition'])->name('available-items-for-requisition');
    
    // Debug routes for testing
    Route::get('/debug/suppliers', function() {
        $companyId = session('selected_company_id');
        $suppliers = \App\Models\Wh_Supplier::where('company_id', $companyId)->get();
        return response()->json([
            'company_id' => $companyId,
            'suppliers' => $suppliers->map(function($s) {
                return ['id' => $s->id, 'name' => $s->company_name];
            })
        ]);
    });
    
    Route::get('/debug/pos/{supplierId}', function($supplierId) {
        $companyId = session('selected_company_id');
        $pos = \App\Models\Wh_PurchaseOrder::where('supplier_id', $supplierId)
            ->where('company_id', $companyId)
            ->get();
        return response()->json([
            'supplier_id' => $supplierId,
            'pos' => $pos->map(function($po) {
                $items = is_string($po->items) ? json_decode($po->items, true) : $po->items;
                return [
                    'id' => $po->id,
                    'po_number' => $po->po_number,
                    'status' => $po->status,
                    'items_count' => is_array($items) ? count($items) : 0
                ];
            })
        ]);
    });
});

    Route::prefix('warehouse/receivings')->group(function() {
        Route::post('/pending-pos', [POReceivingController::class, 'getPendingPOs']);
        Route::post('/po-items/{po}', [POReceivingController::class, 'getPOItems']);
        Route::post('/', [POReceivingController::class, 'store']);
        Route::post('/return-goods', [POReceivingController::class, 'returnGoods']);
        Route::post('/update-po-status', [POReceivingController::class, 'updatePOStatus']);
        Route::post('/goods-receipts', [POReceivingController::class, 'getGoodsReceipts']);
        Route::post('/company-info', [POReceivingController::class, 'getCompanyInfo']);
        Route::post('/supplier-returns', [POReceivingController::class, 'getSupplierReturns']);
        Route::post('/submit-return', [POReceivingController::class, 'submitReturn']);
        Route::post('/po-receive-numbers', [POReceivingController::class, 'getPOReceiveNumbers']);
        Route::post('/reference-items', [POReceivingController::class, 'getReferenceItems']);
        Route::post('/current-user', [POReceivingController::class, 'getCurrentUser']);
        
        // Supplier return routes
        Route::post('/supplier-references', [POReceivingController::class, 'getSupplierReferences']);
        Route::post('/receiving-items/{id}', [POReceivingController::class, 'getReceivingItems']);
        Route::post('/all-suppliers', [POReceivingController::class, 'getAllSuppliers']);
        
        // Return management routes
        Route::post('/return-details/{id}', [POReceivingController::class, 'getReturnDetails']);
        Route::post('/process-return/{id}', [POReceivingController::class, 'processReturn']);
        
        // Additional routes for table data
        Route::post('/purchase-orders', [POReceivingController::class, 'getPurchaseOrders']);
        
        // GRN details and print routes - Changed to POST for AJAX compatibility
        Route::post('/grn-details/{id}', [POReceivingController::class, 'getGRNDetails']);
        Route::post('/print-grn/{id}', [POReceivingController::class, 'printGRN']);
        
        // PO details route - Changed to POST for AJAX compatibility
        Route::post('/po-details/{id}', [POReceivingController::class, 'getPODetails']);
        
        // Debug route
        Route::get('/debug-session', function() {
            return response()->json([
                'session_company_id' => session('selected_company_id'),
                'auth_check' => auth()->check(),
                'user' => auth()->user() ? [
                    'id' => auth()->user()->id,
                    'class' => get_class(auth()->user()),
                    'company_id' => auth()->user()->company_id ?? 'not_set'
                ] : null
            ]);
        });
        
        // Debug route for GRN data
        Route::get('/debug-grn-data', [POReceivingController::class, 'debugGRNData']);
        
        // Debug route for database data
        Route::get('/debug-data', function() {
            $companyId = session('selected_company_id') ?? 1;
            
            $purchaseOrders = \App\Models\Wh_PurchaseOrder::where('company_id', $companyId)->count();
            $receivings = \App\Models\POReceiving::where('company_id', $companyId)->count();
            $supplierReturns = \App\Models\SupplierReturn::where('company_id', $companyId)->count();
            
            // Get detailed receiving data
            $receivingDetails = \App\Models\POReceiving::where('company_id', $companyId)
                ->with(['purchaseOrder.supplier', 'user'])
                ->get()
                ->map(function($receiving) {
                    return [
                        'id' => $receiving->id,
                        'receiving_number' => $receiving->receiving_number,
                        'status' => $receiving->status,
                        'purchase_order_id' => $receiving->purchase_order_id,
                        'purchase_order_exists' => $receiving->purchaseOrder ? 'Yes' : 'No',
                        'po_number' => $receiving->purchaseOrder ? $receiving->purchaseOrder->po_number : 'N/A',
                        'supplier_exists' => $receiving->purchaseOrder && $receiving->purchaseOrder->supplier ? 'Yes' : 'No',
                        'supplier_name' => $receiving->purchaseOrder && $receiving->purchaseOrder->supplier ? $receiving->purchaseOrder->supplier->company_name : 'N/A',
                        'user_exists' => $receiving->user ? 'Yes' : 'No',
                        'user_name' => $receiving->user ? ($receiving->user->name ?? $receiving->user->fullname ?? 'N/A') : 'N/A',
                        'created_at' => $receiving->created_at
                    ];
                });
            
            return response()->json([
                'company_id' => $companyId,
                'purchase_orders_count' => $purchaseOrders,
                'receivings_count' => $receivings,
                'supplier_returns_count' => $supplierReturns,
                'receiving_details' => $receivingDetails
            ]);
        });
    });
});

// Test route for email functionality
Route::get('/test-email', function () {
    try {
        // Create a test return notification job
        \App\Jobs\SendReturnNotificationEmail::dispatch(
            \App\Models\SupplierReturn::first(),
            \App\Models\Wh_Supplier::first(),
            'test@example.com',
            'Test Company',
            '<tr><td>Test Item</td><td>1</td><td>GH₵ 100.00</td><td>GH₵ 100.00</td></tr>',
            100.00
        );
        
        return response()->json([
            'success' => true,
            'message' => 'Test email job dispatched successfully. Check the queue and logs.'
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage()
        ]);
    }
});
Route::get('/company/index', [CompanyUserController::class, 'index'])->name('dash.company');

Route::get('/individual/index', [IndividualUserController::class, 'index'])->name('dash.individual');




// Individual User Routes

Route::prefix('individual')->name('individual.')->group(function () {

    Route::get('/', [IndividualUserController::class, 'index'])->name('dashboard');

});



// Catch-all routes - Keep these last

Route::get('{first}/{second}/{third}', [RoutingController::class, 'thirdLevel'])->name('third');

Route::get('{first}/{second}', [RoutingController::class, 'secondLevel'])->name('second');

Route::get('{any}', [RoutingController::class, 'root'])->name('any');



// CRM Sales Routes

Route::prefix('company')->name('company.')->group(function () {

    Route::prefix('sales')->name('sales.')->group(function () {

        Route::post('/salescategories', [App\Http\Controllers\CRM\CrmSalesController::class, 'saleCategory'])->name('saleCategory');

        Route::POST('/statistics', [App\Http\Controllers\CRM\CrmSalesController::class, 'getDashboardStats'])->name('statistics');

        Route::post('/all', [App\Http\Controllers\CRM\CrmSalesController::class, 'index'])->name('index');

        Route::post('/dealexport', [App\Http\Controllers\CRM\CrmSalesController::class, 'export'])->name('dealexport');

        Route::post('/sale_categories', [App\Http\Controllers\CRM\CrmSalesController::class, 'getSalesCategory'])->name('getSalesCategory');

        Route::get('/create', [App\Http\Controllers\CRM\CrmSalesController::class, 'create'])->name('create');

        Route::post('/', [App\Http\Controllers\CRM\CrmSalesController::class, 'store'])->name('store');

        Route::post('/{id}', [App\Http\Controllers\CRM\CrmSalesController::class, 'show'])->name('show');

        Route::get('/{id}/edit', [App\Http\Controllers\CRM\CrmSalesController::class, 'edit'])->name('edit');

        Route::put('/{id}', [App\Http\Controllers\CRM\CrmSalesController::class, 'update'])->name('update');

        Route::delete('/{id}', [App\Http\Controllers\CRM\CrmSalesController::class, 'destroy'])->name('destroy');

        Route::put('/{id}/status', [App\Http\Controllers\CRM\CrmSalesController::class, 'updateStatus'])->name('status');

    });

});



Route::prefix('company')->name('company.')->group(function () {

    Route::prefix('support')->name('support.')->group(function () {

        Route::post('/show', [App\Http\Controllers\CRM\TicketController::class, 'show'])->name('show');

        Route::post('/all', [App\Http\Controllers\CRM\TicketController::class, 'index'])->name('index'); // List all tickets

        Route::post('/', [App\Http\Controllers\CRM\TicketController::class, 'store'])->name('store'); // Create a ticket

        Route::put('close/{id}', [App\Http\Controllers\CRM\TicketController::class, 'close'])->name('destroy'); // Soft delete ticket

    });

});



Route::get('/referral/process/{token}', [ReferralController::class, 'processReferral'])

    ->name('referral.process');



Route::prefix('company')->group(function () {

    // Loyalty Programs

    Route::prefix('loyalty-programs')->group(function () {

        // Stats and Reports

        Route::post('/stats', [LoyaltyProgramController::class, 'stats']);

        Route::post('/segmentation', [LoyaltyProgramController::class, 'segmentation']);

        Route::post('/import', [LoyaltyProgramController::class, 'importCustomers']);

        Route::post('/report', [LoyaltyProgramController::class, 'generateReport']);





        Route::post('/all', [LoyaltyProgramController::class, 'index']);

        Route::post('/', [LoyaltyProgramController::class, 'store']);

        Route::post('/{id}', [LoyaltyProgramController::class, 'show']);

        Route::put('/{id}', [LoyaltyProgramController::class, 'update']);

        Route::delete('/{id}', [LoyaltyProgramController::class, 'destroy']);







        // Tiers

        Route::prefix('{programId}/tiers')->group(function () {

            Route::post('/all', [CustomerTierController::class, 'index']);

            Route::post('/', [CustomerTierController::class, 'store']);

            Route::get('/{tierId}', [CustomerTierController::class, 'show']);

            Route::put('/{tierId}', [CustomerTierController::class, 'update']);

            Route::delete('/{tierId}', [CustomerTierController::class, 'destroy']);

        });



        // Rewards

        Route::prefix('{programId}/rewards')->group(function () {

            Route::post('/all', [RewardController::class, 'index']);

            Route::post('/', [RewardController::class, 'store']);

            Route::get('/{rewardId}', [RewardController::class, 'show']);

            Route::put('/{rewardId}', [RewardController::class, 'update']);

            Route::delete('/{rewardId}', [RewardController::class, 'destroy']);

            Route::post('/getrewards', [RewardController::class, 'getRewards']);

        });



        // Redemptions

        Route::prefix('{programId}/redemptions')->group(function () {

            Route::post('/all', [RedemptionController::class, 'index']);

            Route::post('/', [RedemptionController::class, 'store']);

            Route::get('/{redemptionId}', [RedemptionController::class, 'show']);

            Route::put('/{redemptionId}', [RedemptionController::class, 'update']);

            Route::delete('/{redemptionId}', [RedemptionController::class, 'destroy']);

            Route::post('/customers', [RedemptionController::class, 'getCustomers']); // Fetch customers only



        });



        // Referrals

        Route::prefix('{programId}/referrals')->group(function () {

            Route::post('/all', [ReferralController::class, 'index']);

            Route::post('/', [ReferralController::class, 'store']);

            Route::get('/{referralId}', [ReferralController::class, 'show']);

            Route::put('/{referralId}', [ReferralController::class, 'update']);

            Route::delete('/{referralId}', [ReferralController::class, 'destroy']);

        });

    });

});



Route::prefix('company/hr/employees')->name('hr.employees.')->group(function () {

    // Specific routes must come before parameter routes
    Route::post('/available-staff-ids', [EmployeeController::class, 'getAvailableStaffIds'])->name('available-staff-ids');

    Route::post('/store', [EmployeeController::class, 'store'])->name('store');

    Route::get('/all', [EmployeeController::class, 'index'])->name('index');

    Route::post('/all', [EmployeeController::class, 'getEmployees'])->name('all');

    Route::get('/create', [EmployeeController::class, 'create'])->name('create');

    // Import helpers MUST be before parameterized routes
    Route::get('/import/template', [EmployeeController::class, 'downloadImportTemplate'])->name('import.template');
    Route::post('/import', [EmployeeController::class, 'import'])->name('import');

    Route::post('/{employee}', [EmployeeController::class, 'show'])->name('show');

    Route::get('/{employee}/edit', [EmployeeController::class, 'edit'])->name('edit');

    Route::put('/{employee}', [EmployeeController::class, 'update'])->name('update');

    Route::delete('/{employee}', [EmployeeController::class, 'destroy'])->name('destroy');

    Route::post('/{employee}/send-message', [EmployeeController::class, 'sendMessage'])->name('send-message');

    Route::get('/export', [EmployeeController::class, 'export'])->name('export');

});










Route::prefix('company/hr/jobs')->name('jobs.')->group(function () {

    Route::post('/all', [JobsController::class, 'all'])->name('all');

    Route::post('/status-counts', [JobsController::class, 'getStatusCounts'])->name('statusCounts');



    Route::post('/store', [JobsController::class, 'store'])->name('store');

    Route::post('/showByToken', [JobsController::class, 'showByToken'])->name('showByToken');

    Route::post('/update', [JobsController::class, 'update'])->name('update');

    Route::post('/delete', [JobsController::class, 'delete'])->name('delete');

});









Route::prefix('company/hr/attendance')->name('attendance.')->group(function () {

    Route::post('/index', [AttendanceController::class, 'index'])->name('index');

    Route::post('/stats', [AttendanceController::class, 'getStats'])->name('stats');

    Route::post('/', [AttendanceController::class, 'store'])->name('store');

    Route::post('/bulk', [AttendanceController::class, 'bulkUpdate'])->name('bulk');

    Route::put('/{id}', [AttendanceController::class, 'update'])->name('update');

    Route::post('/history/{employeeId}', [AttendanceController::class, 'history'])->name('history');

    Route::post('/employees', [AttendanceController::class, 'getEmployees'])->name('employees');

    Route::post('/import', [AttendanceController::class, 'import'])->name('import');

});





Route::prefix('company/hr/payroll')->name('payroll.')->group(function () {

    Route::post('/stats', [PayrollController::class, 'stats'])->name('stats');

    Route::post('/all', [PayrollController::class, 'index'])->name('index');

    Route::post('/employees', [PayrollController::class, 'getEmployees'])->name('employees');

    Route::post('/', [PayrollController::class, 'store'])->name('store');

    Route::post('/import', [PayrollController::class, 'import'])->name('payroll.import');

    Route::post('/{payroll}', [PayrollController::class, 'show'])->name('show');

    Route::put('/{payroll}', [PayrollController::class, 'update'])->name('update');

    Route::post('/process', [PayrollController::class, 'process'])->name('process');





    Route::delete('/{payroll}', [PayrollController::class, 'destroy'])->name('destroy');

    Route::post('/export/excel', [PayrollController::class, 'exportExcel'])->name('export.excel');

    Route::post('/export/pdf', [PayrollController::class, 'exportPdf'])->name('export.pdf');

    Route::post('/export/csv', [PayrollController::class, 'exportCsv'])->name('export.csv');

});



Route::prefix('company/warehouse/purchasing_order')->name('purchasing_order.')->group(function () {

    // Purchase Order Routes



    Route::post('/all', [POController::class, 'index'])->name('po.index'); // paginated list

    Route::post('/', [POController::class, 'store'])->name('po.store');



    Route::post('/suppliers/all', [POController::class, 'supplierIndex'])->name('supplier.index');

    Route::post('/suppliers', [POController::class, 'supplierStore'])->name('supplier.store');

    Route::post('/generatePONumber', [POController::class, 'generatePONumber'])->name('generate.po.number');





    Route::post('/{id}', [POController::class, 'show'])->name('po.show');

    Route::put('/{po_number}', [POController::class, 'update'])->name('po.update');

    Route::delete('/{id}', [POController::class, 'destroy'])->name('po.destroy');



    // Supplier Routes



    Route::get('/suppliers/{id}', [POController::class, 'supplierShow'])->name('supplier.show');

    Route::put('/suppliers/{id}', [POController::class, 'supplierUpdate'])->name('supplier.update');

    Route::delete('/suppliers/{id}', [POController::class, 'supplierDestroy'])->name('supplier.destroy');

});



Route::prefix('company')->middleware(['auth', 'company.session'])->group(function () {
    // PO Approval Routes
    Route::prefix('warehouse/po-approval')->name('po_approval.')->group(function () {
        Route::post('/pending', [POApprovalController::class, 'getPendingApprovals'])->name('pending');
        Route::post('/all-requisitions', [POApprovalController::class, 'getAllRequisitions'])->name('all_requisitions');
        Route::post('/details/{id}', [POApprovalController::class, 'getApprovalDetails'])->name('details');
        Route::post('/approve/{id}', [POApprovalController::class, 'approve'])->name('approve');
        Route::post('/reject/{id}', [POApprovalController::class, 'reject'])->name('reject');
        Route::post('/revert/{id}', [POApprovalController::class, 'revert'])->name('revert');
        Route::post('/bulk-approve', [POApprovalController::class, 'bulkApprove'])->name('bulk_approve');
        Route::get('/export', [POApprovalController::class, 'exportApprovals'])->name('export');
        Route::post('/statistics', [POApprovalController::class, 'getProcurementStatistics'])->name('statistics');
        
        // Invoice upload routes
        Route::post('/upload-invoice/{id}', [POApprovalController::class, 'uploadInvoice'])->name('upload_invoice');
        Route::delete('/delete-invoice/{poId}/{invoiceId}', [POApprovalController::class, 'deleteInvoice'])->name('delete_invoice');
        Route::get('/download-invoice/{poId}/{invoiceId}', [POApprovalController::class, 'downloadInvoice'])->name('download_invoice');
        Route::post('/batch-invoice-status', [POApprovalController::class, 'batchInvoiceStatus'])->name('batch_invoice_status');
        
        // Test route for debugging
        Route::get('/test-upload/{id}', [POApprovalController::class, 'testUpload'])->name('test_upload');
    });
    Route::prefix('warehouse/suppliers')->name('suppliers.')->group(function () {

    Route::post('/all', [SupplierController::class, 'index'])->name('index');

        Route::post('/search', [SupplierController::class, 'search'])->name('search');

        Route::post('/export', [SupplierController::class, 'export'])->name('export');
        Route::get('/filter-data', [SupplierController::class, 'getFilterData'])->name('filter-data');
    Route::post('/', [SupplierController::class, 'store'])->name('store');

    
    // Test route for debugging
    Route::get('/test', function() {
        return response()->json([
            'success' => true,
            'message' => 'Supplier route is working',
            'auth' => auth()->check(),
            'company_id' => session('selected_company_id')
        ]);
    })->name('test');
    
    // Test purchase orders data
    Route::get('/test-purchase-orders', function() {
        $companyId = session('selected_company_id');
        $suppliers = \App\Models\Wh_Supplier::where('company_id', $companyId)
            ->with(['purchaseOrders' => function($q) {
                $q->latest()->take(1);
            }])
            ->get();
        
        return response()->json([
            'suppliers' => $suppliers->map(function($supplier) {
                return [
                    'id' => $supplier->id,
                    'company_name' => $supplier->company_name,
                    'purchase_orders_count' => $supplier->purchaseOrders->count(),
                    'purchase_orders' => $supplier->purchaseOrders->map(function($order) {
                        return [
                            'id' => $order->id,
                            'po_number' => $order->po_number,
                            'total_value' => $order->total_value,
                            'created_at' => $order->created_at,
                            'supplier_id' => $order->supplier_id
                        ];
                    })
                ];
            })
        ]);
    })->name('test-purchase-orders');
    
    // Simple test route to check purchase orders
    Route::get('/check-orders', function() {
        $orders = \App\Models\Wh_PurchaseOrder::with('supplier')->get();
        $data = [];
        foreach($orders as $order) {
            $data[] = [
                'po_number' => $order->po_number,
                'supplier_name' => $order->supplier->company_name,
                'supplier_id' => $order->supplier_id,
                'total_value' => $order->total_value,
                'created_at' => $order->created_at
            ];
        }
        return response()->json($data);
    })->name('check-orders');
    
    // Rating endpoints - must come before {id} routes
    Route::post('/rate', [SupplierController::class, 'rateSupplier'])->name('rate');
    Route::get('/{id}/ratings', [SupplierController::class, 'getSupplierRatings'])->name('ratings');
    
    Route::post('/{id}', [SupplierController::class, 'show'])->name('show');

    Route::put('/{id}', [SupplierController::class, 'update'])->name('update');

    Route::delete('/{id}', [SupplierController::class, 'destroy'])->name('destroy');

});



    Route::prefix('warehouse/quality-inspections')->name('quality-inspections.')->group(function () {

    // Main CRUD operations

    Route::post('/all', [QualityInspectionController::class, 'index'])->name('index');



    

    // Special endpoints

    Route::post('/suppliers-with-pending-pos', [QualityInspectionController::class, 'getSuppliersWithPendingPOs'])->name('suppliers-with-pending-pos');

    Route::post('/generate-batch-number', [QualityInspectionController::class, 'generateBatchNumber'])->name('generate-batch-number');

    

    Route::post('/{supplierId}/pending-pos', [QualityInspectionController::class, 'getPendingPOsForSupplier'])->name('pending-pos');

    Route::post('/{poId}/uninspected-items', [QualityInspectionController::class, 'getUninspectedItems'])->name('uninspected-items');

    // Route::post('/{id}/update-status', [QualityInspectionController::class, 'updateStatus'])->name('update-status');

    

Route::post('/{id}/edit', [QualityInspectionController::class, 'edit'])->name('edit');

        Route::post('/', [QualityInspectionController::class, 'store'])->name('store');

    Route::post('/{id}', [QualityInspectionController::class, 'show'])->name('show');

    Route::put('/{id}', [QualityInspectionController::class, 'update'])->name('update');

    Route::delete('/{id}', [QualityInspectionController::class, 'destroy'])->name('destroy');

});



    Route::prefix('warehouse/receivings')->group(function() {

        Route::post('/pending-pos', [POReceivingController::class, 'getPendingPOs']);

        Route::post('/po-items/{po}', [POReceivingController::class, 'getPOItems']);

        Route::post('/', [POReceivingController::class, 'store']);

        Route::post('/return-goods', [POReceivingController::class, 'returnGoods']);

        Route::post('/update-po-status', [POReceivingController::class, 'updatePOStatus']);

        Route::post('/goods-receipts', [POReceivingController::class, 'getGoodsReceipts']);

        Route::post('/company-info', [POReceivingController::class, 'getCompanyInfo']);

        Route::post('/supplier-returns', [POReceivingController::class, 'getSupplierReturns']);

        Route::post('/submit-return', [POReceivingController::class, 'submitReturn']);

        Route::post('/po-receive-numbers', [POReceivingController::class, 'getPOReceiveNumbers']);

        Route::post('/reference-items', [POReceivingController::class, 'getReferenceItems']);

        Route::post('/current-user', [POReceivingController::class, 'getCurrentUser']);

        
        // Supplier return routes
        Route::post('/supplier-references', [POReceivingController::class, 'getSupplierReferences']);
        Route::post('/receiving-items/{id}', [POReceivingController::class, 'getReceivingItems']);
        Route::post('/all-suppliers', [POReceivingController::class, 'getAllSuppliers']);
        

        // Return management routes

        Route::post('/return-details/{id}', [POReceivingController::class, 'getReturnDetails']);

        Route::post('/process-return/{id}', [POReceivingController::class, 'processReturn']);

        

        // Additional routes for table data

        Route::post('/purchase-orders', [POReceivingController::class, 'getPurchaseOrders']);

        

        // GRN details and print routes - Changed to POST for AJAX compatibility

        Route::post('/grn-details/{id}', [POReceivingController::class, 'getGRNDetails']);

        Route::post('/print-grn/{id}', [POReceivingController::class, 'printGRN']);

        

        // PO details route - Changed to POST for AJAX compatibility

        Route::post('/po-details/{id}', [POReceivingController::class, 'getPODetails']);

        

        // Debug route

        Route::get('/debug-session', function() {

            return response()->json([

                'session_company_id' => session('selected_company_id'),

                'auth_check' => auth()->check(),

                'user' => auth()->user() ? [

                    'id' => auth()->user()->id,

                    'class' => get_class(auth()->user()),

                    'company_id' => auth()->user()->company_id ?? 'not_set'

                ] : null

            ]);

        });

        

        // Debug route for GRN data

        Route::get('/debug-grn-data', [POReceivingController::class, 'debugGRNData']);

        

        // Debug route for database data

        Route::get('/debug-data', function() {

            $companyId = session('selected_company_id') ?? 1;

            

            $purchaseOrders = \App\Models\Wh_PurchaseOrder::where('company_id', $companyId)->count();

            $receivings = \App\Models\POReceiving::where('company_id', $companyId)->count();

            $supplierReturns = \App\Models\SupplierReturn::where('company_id', $companyId)->count();

            

            // Get detailed receiving data

            $receivingDetails = \App\Models\POReceiving::where('company_id', $companyId)

                ->with(['purchaseOrder.supplier', 'user'])

                ->get()

                ->map(function($receiving) {

                    return [

                        'id' => $receiving->id,

                        'receiving_number' => $receiving->receiving_number,

                        'status' => $receiving->status,

                        'purchase_order_id' => $receiving->purchase_order_id,

                        'purchase_order_exists' => $receiving->purchaseOrder ? 'Yes' : 'No',

                        'po_number' => $receiving->purchaseOrder ? $receiving->purchaseOrder->po_number : 'N/A',

                        'supplier_exists' => $receiving->purchaseOrder && $receiving->purchaseOrder->supplier ? 'Yes' : 'No',

                        'supplier_name' => $receiving->purchaseOrder && $receiving->purchaseOrder->supplier ? $receiving->purchaseOrder->supplier->company_name : 'N/A',

                        'user_exists' => $receiving->user ? 'Yes' : 'No',

                        'user_name' => $receiving->user ? ($receiving->user->name ?? $receiving->user->fullname ?? 'N/A') : 'N/A',

                        'created_at' => $receiving->created_at

                    ];

                });

            

            return response()->json([

                'company_id' => $companyId,

                'purchase_orders_count' => $purchaseOrders,

                'receivings_count' => $receivings,

                'supplier_returns_count' => $supplierReturns,

                'receiving_details' => $receivingDetails

            ]);

        });

    });

});









Route::prefix('company/warehouse/receivings')->group(function() {
    Route::post('/pending-pos', [POReceivingController::class, 'getPendingPOs']);
    Route::post('/po-items/{po}', [POReceivingController::class, 'getPOItems']);
    Route::post('/', [POReceivingController::class, 'store']);
});


// Requisition Routes
Route::prefix('company/warehouse/requisitions')->name('requisitions.')->group(function () {
    Route::post('/all', [App\Http\Controllers\WareHouse\RequisitionController::class, 'index'])->name('index');
    Route::post('/', [App\Http\Controllers\WareHouse\RequisitionController::class, 'store'])->name('store');
    Route::post('/show/{id}', [App\Http\Controllers\WareHouse\RequisitionController::class, 'show'])->name('show');
    Route::put('/{id}', [App\Http\Controllers\WareHouse\RequisitionController::class, 'update'])->name('update');
    Route::post('/{id}/approve', [App\Http\Controllers\WareHouse\RequisitionController::class, 'approve'])->name('approve');
    Route::delete('/{id}', [App\Http\Controllers\WareHouse\RequisitionController::class, 'destroy'])->name('destroy');
    Route::post('/warehouse-departments', [App\Http\Controllers\WareHouse\RequisitionController::class, 'getWarehouseDepartments'])->name('warehouse-departments');
    Route::post('/all-departments', [App\Http\Controllers\WareHouse\RequisitionController::class, 'getAllDepartments'])->name('all-departments');
    Route::post('/debug-departments', [App\Http\Controllers\WareHouse\RequisitionController::class, 'debugDepartments'])->name('debug-departments');
    Route::post('/users', [App\Http\Controllers\WareHouse\RequisitionController::class, 'getUsers'])->name('users');
    Route::post('/statistics', [App\Http\Controllers\WareHouse\RequisitionController::class, 'getStatistics'])->name('statistics');
    Route::post('/project-managers', [App\Http\Controllers\WareHouse\RequisitionController::class, 'getProjectManagers'])->name('project-managers');
    Route::post('/team-leaders', [App\Http\Controllers\WareHouse\RequisitionController::class, 'getTeamLeaders'])->name('team-leaders');
});

// Management Approval Routes
Route::prefix('company/management/requisitions')->name('management.requisitions.')->group(function () {
    Route::get('/', [App\Http\Controllers\Management\ManagementRequisitionController::class, 'index'])->name('index');
    Route::post('/all', [App\Http\Controllers\Management\ManagementRequisitionController::class, 'getRequisitions'])->name('all');
    Route::post('/check-team-members', [App\Http\Controllers\Management\ManagementRequisitionController::class, 'checkTeamMembers'])->name('check-team-members');
    Route::post('/{id}', [App\Http\Controllers\Management\ManagementRequisitionController::class, 'show'])->name('show');
    Route::post('/{id}/approve', [App\Http\Controllers\Management\ManagementRequisitionController::class, 'approve'])->name('approve');
    Route::post('/{id}/reject', [App\Http\Controllers\Management\ManagementRequisitionController::class, 'reject'])->name('reject');
    Route::post('/{id}/update', [App\Http\Controllers\Management\ManagementRequisitionController::class, 'update'])->name('update');
    Route::post('/bulk-approve', [App\Http\Controllers\Management\ManagementRequisitionController::class, 'bulkApprove'])->name('bulk-approve');
});

// Routes for loading project managers and team leaders
Route::prefix('company/warehouse/requisitions')->name('warehouse.requisitions.')->group(function () {
    Route::post('/project-managers', [App\Http\Controllers\WareHouse\RequisitionController::class, 'getProjectManagers'])->name('project-managers');
    Route::post('/team-leaders', [App\Http\Controllers\WareHouse\RequisitionController::class, 'getTeamLeaders'])->name('team-leaders');
});

// Test route for items API
Route::get('/test-items-api', function() {
    try {
        // Set a test company_id in session
        session(['selected_company_id' => 1]);
        
        // Check if CentralStore model exists
        $modelExists = class_exists('\App\Models\CentralStore');
        
        if (!$modelExists) {
            return response()->json([
                'success' => false,
                'error' => 'CentralStore model does not exist'
            ]);
        }
        
        // First, let's check if there are any items in the central store
        $totalItems = \App\Models\CentralStore::where('company_id', 1)->count();
        $completedItems = \App\Models\CentralStore::where('company_id', 1)->where('status', 'completed')->count();
        $availableItems = \App\Models\CentralStore::where('company_id', 1)->where('status', 'completed')->where('quantity', '>', 0)->count();
        
        // Get some sample items
        $sampleItems = \App\Models\CentralStore::where('company_id', 1)->limit(3)->get();
        
        return response()->json([
            'success' => true,
            'model_exists' => $modelExists,
            'database_stats' => [
                'total_items' => $totalItems,
                'completed_items' => $completedItems,
                'available_items' => $availableItems
            ],
            'sample_items' => $sampleItems,
            'message' => 'Items API test completed'
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
    }
});

// Test route for project managers API
Route::get('/test-project-managers-api', function() {
    try {
        // Set a test company_id in session
        session(['selected_company_id' => 1]);
        
        // Test the project managers controller
        $controller = new \App\Http\Controllers\WareHouse\RequisitionController();
        $request = new \Illuminate\Http\Request();
        
        $response = $controller->getProjectManagers();
        $data = $response->getData(true);
        
        return response()->json([
            'success' => true,
            'project_managers_count' => count($data['data']),
            'project_managers_data' => $data['data'],
            'debug_info' => $data['debug'] ?? null,
            'message' => 'Project Managers API test completed'
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
    }
});

// Outbound Operations Routes
Route::prefix('company/warehouse/outbound')->name('warehouse.outbound.')->group(function () {
    Route::post('/approved-requisitions', [App\Http\Controllers\WareHouse\OutboundController::class, 'getApprovedRequisitions'])->name('approved-requisitions');
    Route::post('/issue-requisition/{id}', [App\Http\Controllers\WareHouse\OutboundController::class, 'issueRequisition'])->name('issue-requisition');
    Route::post('/create-waybill/{id}', [App\Http\Controllers\WareHouse\OutboundController::class, 'createWaybill'])->name('create-waybill');
    Route::post('/issued-requisitions', [App\Http\Controllers\WareHouse\OutboundController::class, 'getIssuedRequisitionsForWaybill'])->name('issued-requisitions');
    Route::post('/departments', [App\Http\Controllers\WareHouse\OutboundController::class, 'getDepartmentsForWaybill'])->name('departments');
    Route::post('/reference-details', [App\Http\Controllers\WareHouse\OutboundController::class, 'getReferenceDetails'])->name('reference-details');
});

// Waybill routes
Route::prefix('company/warehouse/waybills')->name('warehouse.waybills.')->group(function () {
    Route::post('/', [App\Http\Controllers\WareHouse\WaybillController::class, 'getWaybills'])->name('list');
    Route::post('/create', [App\Http\Controllers\WareHouse\WaybillController::class, 'createWaybill'])->name('create');
    Route::get('/{id}', [App\Http\Controllers\WareHouse\WaybillController::class, 'getWaybillDetails'])->name('details');
    Route::put('/{id}', [App\Http\Controllers\WareHouse\WaybillController::class, 'updateWaybill'])->name('update');
    Route::put('/{id}/status', [App\Http\Controllers\WareHouse\WaybillController::class, 'updateWaybillStatus'])->name('status');
    Route::post('/{id}/add-items', [App\Http\Controllers\WareHouse\WaybillController::class, 'addItemsToWaybill'])->name('add-items');
    Route::delete('/{id}', [App\Http\Controllers\WareHouse\WaybillController::class, 'deleteWaybill'])->name('delete');
});

        // Warehouse Dashboard Routes
        Route::prefix('company/warehouse/dashboard')->name('warehouse.dashboard.')->group(function () {
            Route::post('/statistics', [App\Http\Controllers\WareHouse\WarehouseDashboardController::class, 'getStatistics'])->name('statistics');
            Route::post('/debug', [App\Http\Controllers\WareHouse\WarehouseDashboardController::class, 'debug'])->name('debug');
            Route::post('/test', [App\Http\Controllers\WareHouse\WarehouseDashboardController::class, 'test'])->name('test');
        });

        // Warehouse Report Routes
Route::prefix('company/warehouse/reports')->name('warehouse.reports.')->group(function () {
    Route::post('/inventory-analytics', [App\Http\Controllers\WareHouse\WarehouseReportController::class, 'getInventoryAnalytics'])->name('inventory_analytics');
    Route::post('/chart-data', [App\Http\Controllers\WareHouse\WarehouseReportController::class, 'getChartData'])->name('chart_data');
    Route::post('/generate-report', [App\Http\Controllers\WareHouse\WarehouseReportController::class, 'generateReport'])->name('generate_report');

    // Detailed report data routes
    Route::post('/inventory-details', [App\Http\Controllers\WareHouse\WarehouseReportController::class, 'getInventoryDetails'])->name('inventory_details');
    Route::post('/procurement-details', [App\Http\Controllers\WareHouse\WarehouseReportController::class, 'getProcurementDetails'])->name('procurement_details');
    Route::post('/requisition-details', [App\Http\Controllers\WareHouse\WarehouseReportController::class, 'getRequisitionDetails'])->name('requisition_details');
    Route::post('/batch-details', [App\Http\Controllers\WareHouse\WarehouseReportController::class, 'getBatchDetails'])->name('batch_details');
    Route::post('/supplier-details', [App\Http\Controllers\WareHouse\WarehouseReportController::class, 'getSupplierDetails'])->name('supplier_details');
    Route::post('/pending-approvals', [App\Http\Controllers\WareHouse\WarehouseReportController::class, 'getPendingApprovals'])->name('pending_approvals');
    Route::post('/reorder-pos', [App\Http\Controllers\WareHouse\WarehouseReportController::class, 'getReorderPOs'])->name('reorder_pos');
    Route::post('/central-store/reorder/{itemId}', [App\Http\Controllers\WareHouse\WarehouseReportController::class, 'reorderItem'])->name('reorder_item');
    Route::post('/financial-details', [App\Http\Controllers\WareHouse\WarehouseReportController::class, 'getFinancialDetails'])->name('financial_details');
});

// Warehouse Suppliers Routes
Route::prefix('company/warehouse/suppliers')->name('warehouse.suppliers.')->group(function () {
    Route::post('/all', [App\Http\Controllers\WareHouse\SupplierController::class, 'index'])->name('index');
    Route::post('/search', [App\Http\Controllers\WareHouse\SupplierController::class, 'search'])->name('search');
    Route::post('/export', [App\Http\Controllers\WareHouse\SupplierController::class, 'export'])->name('export');
    Route::get('/filter-data', [App\Http\Controllers\WareHouse\SupplierController::class, 'getFilterData'])->name('filter-data');
    Route::post('/', [App\Http\Controllers\WareHouse\SupplierController::class, 'store'])->name('store');
    Route::post('/{id}', [App\Http\Controllers\WareHouse\SupplierController::class, 'show'])->name('show');
    Route::put('/{id}', [App\Http\Controllers\WareHouse\SupplierController::class, 'update'])->name('update');
    Route::delete('/{id}', [App\Http\Controllers\WareHouse\SupplierController::class, 'destroy'])->name('destroy');
    Route::post('/rate', [App\Http\Controllers\WareHouse\SupplierController::class, 'rateSupplier'])->name('rate');
    Route::get('/{id}/ratings', [App\Http\Controllers\WareHouse\SupplierController::class, 'getSupplierRatings'])->name('ratings');
});

// Debug route to check requisitions and team members
Route::get('/debug-requisitions', function() {
    try {
        $result = [];
        
        // Check requisitions
        $requisitions = \App\Models\Requisition::with('teamLeader')->get();
        $result['total_requisitions'] = $requisitions->count();
        
        $requisitionsData = [];
        foreach($requisitions as $req) {
            $requisitionsData[] = [
                'id' => $req->id,
                'title' => $req->title,
                'team_leader_id' => $req->team_leader_id,
                'team_leader_name' => $req->teamLeader ? $req->teamLeader->full_name : 'Not found',
                'project_manager_id' => $req->project_manager_id
            ];
        }
        $result['requisitions'] = $requisitionsData;
        
        // Check team members
        $teamMembers = \App\Models\TeamMember::all();
        $result['total_team_members'] = $teamMembers->count();
        
        $teamMembersData = [];
        foreach($teamMembers as $member) {
            $teamMembersData[] = [
                'id' => $member->id,
                'full_name' => $member->full_name,
                'position' => $member->position,
                'company_id' => $member->company_id
            ];
        }
        $result['team_members'] = $teamMembersData;
        
        // Check team pairings
        $teamPairings = \App\Models\TeamParing::with('teamMembers', 'teamLead')->get();
        $result['total_team_pairings'] = $teamPairings->count();
        
        $teamPairingsData = [];
        foreach($teamPairings as $pairing) {
            $teamPairingsData[] = [
                'id' => $pairing->id,
                'team_name' => $pairing->team_name,
                'team_lead_id' => $pairing->team_lead,
                'team_lead_name' => $pairing->teamLead ? $pairing->teamLead->full_name : 'Not found',
                'team_members_count' => $pairing->teamMembers->count(),
                'company_id' => $pairing->company_id
            ];
        }
        $result['team_pairings'] = $teamPairingsData;
        
        return response()->json($result);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()]);
    }
});

// Management - Purchase Order Approval Routes
Route::prefix('company/management')->name('management.')->middleware(['auth.company_or_sub_user', 'company.session'])->group(function () {
    Route::prefix('purchase-order-approval')->name('po_approval.')->group(function () {
        Route::post('/', [App\Http\Controllers\Management\PurchaseOrderApprovalController::class, 'index'])->name('index');
        Route::post('/data', [App\Http\Controllers\Management\PurchaseOrderApprovalController::class, 'index'])->name('data');
        Route::post('/{id}/approve', [App\Http\Controllers\Management\PurchaseOrderApprovalController::class, 'approve'])->name('approve');
        Route::post('/{id}/reject', [App\Http\Controllers\Management\PurchaseOrderApprovalController::class, 'reject'])->name('reject');
        Route::post('/bulk-approve', [App\Http\Controllers\Management\PurchaseOrderApprovalController::class, 'bulkApprove'])->name('bulk_approve');
    });
});




