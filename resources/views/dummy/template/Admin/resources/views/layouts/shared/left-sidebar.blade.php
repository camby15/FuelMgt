<!-- ========== Left Sidebar Start ========== -->
<div class="leftside-menu">
    <!-- Brand Logo Light -->
    <a href="{{ route('any', 'index') }}" class="logo logo-light">
        <span class="logo-lg">
            <img src="/images/logo_white.png" alt="logo" />
        </span>
        <span class="logo-sm">
            <img src="/images/logo_white_sm.png" alt="small logo" />
        </span>
    </a>

    <!-- Brand Logo Dark -->
    <a href="{{ route('any', 'index') }}" class="logo logo-dark">
        <span class="logo-lg">
            <img src="/images/logo-white.png" alt="logo" />
        </span>
        <span class="logo-sm">
            <img src="/images/logo_white_sm.png" alt="small logo" />
        </span>
    </a>

    <!-- Sidebar Hover Menu Toggle Button -->
    <div class="button-sm-hover" data-bs-toggle="tooltip" data-bs-placement="right" title="Show Full Sidebar">
        <i class="ri-checkbox-blank-circle-line align-middle"></i>
    </div>

    <!-- Full Sidebar Menu Close Button -->
    <div class="button-close-fullsidebar">
        <i class="ri-close-fill align-middle"></i>
    </div>

    <!-- Sidebar -left -->
    <div class="h-100" id="leftside-menu-container" data-simplebar>
        <!-- Leftbar User -->
        <div class="leftbar-user">
            <a href="{{ route('second', ['pages', 'profile']) }}">
                <img src="/images/users/avatar-1.jpg" alt="user-image" height="42" class="rounded-circle shadow-sm" />
                <span class="leftbar-user-name mt-2">Roach Appiah</span>
            </a>
        </div>

        <!--- Sidemenu -->
        <ul class="side-nav">
            <li class="side-nav-title">Navigation</li>

            <li class="side-nav-item">
                <a
                    data-bs-toggle="collapse"
                    href="#sidebarDashboards"
                    aria-expanded="false"
                    aria-controls="sidebarDashboards"
                    class="side-nav-link">
                    <i class="ri-home-4-line"></i>
                    <span class="badge bg-success float-end">2</span>
                    <span>Dashboards</span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="sidebarDashboards">
                    <ul class="side-nav-second-level">
                        <li>
                            <a href="{{ route('any', 'analytics') }}">Analytics</a>
                        </li>
                        <li>
                            <a href="{{ route('any', 'index') }}">Ecommerce</a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="side-nav-item">
                <a
                    data-bs-toggle="collapse"
                    href="#userManagement"
                    aria-expanded="false"
                    aria-controls="userManagement"
                    class="side-nav-link">
                    <i class="ri-user-settings-line"></i>
                    <span>User Management</span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="userManagement">
                    <ul class="side-nav-second-level">
                        <li><a href="{{ route('company-sub-users.index') }}">Users</a></li>
                        <li><a href="{{ route('company.user-profiles.index') }}">User Profiles</a></li>
                        <li><a href="{{ route('company.partners.index') }}">Partners</a></li>
                        <li>
                            <a href="{{ route('any', 'company/user-management/user-category') }}">User Category</a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="side-nav-item">
                <a href="{{ route('any', 'company/manage-departments') }}" class="side-nav-link">     
                    <i class="ri-building-line"></i>
                    <span>Manage Department</span>
                </a>
            </li>

            <li class="side-nav-item">
                <a href="{{ route('any', 'company/manage-branches') }}" class="side-nav-link">
                    <i class="ri-git-branch-line"></i>
                    <span>Manage Branches</span>
                </a>
            </li>

            <li class="side-nav-item">
                <a
                    data-bs-toggle="collapse"
                    href="#documentManagement"
                    aria-expanded="false"
                    aria-controls="documentManagement"
                    class="side-nav-link">
                    <i class="ri-file-list-3-line"></i>
                    <span>Document Management</span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="documentManagement">
                    <ul class="side-nav-second-level">
                        <li>
                            <a href="{{ route('any', 'company/Document-management/document-types') }}">
                                Document Types
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('any', 'company/Document-management/doc-classification') }}">
                                Document Classification
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('any', 'company/Document-management/doc-workflow') }}">
                                Document WorkFlow
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('any', 'company/Document-management/generate-doc') }}">
                                Document Generation
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="side-nav-item">
                <a
                    data-bs-toggle="collapse"
                    href="#targets"
                    aria-expanded="false"
                    aria-controls="targets"
                    class="side-nav-link">
                    <i class="ri-flag-2-line"></i>
                    <span>Targets</span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="targets">
                    <ul class="side-nav-second-level">
                        <li><a href="{{ route('any', 'company-target') }}">Company Target</a></li>
                        <li><a href="{{ route('any', 'individual-target') }}">Individual Target</a></li>
                    </ul>
                </div>
            </li>

            <li class="side-nav-item">
                <a
                    data-bs-toggle="collapse"
                    href="#finance"
                    aria-expanded="false"
                    aria-controls="finance"
                    class="side-nav-link">
                    <i class="ri-money-dollar-circle-line"></i>
                    <span>Finance</span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="finance">
                    <ul class="side-nav-second-level">
                        <li><a href="{{ route('any', 'company/Finance/manage-currency') }}">Manage Currency</a></li>
                        <li><a href="{{ route('any', 'company/Finance/account-types') }}">Create Account Type</a></li>
                        <li>
                            <a href="{{ route('any', 'company/Finance/main-accounts') }}">Main Account Management</a>
                        </li>
                        <li>
                            <a href="{{ route('any', 'company/Finance/sub-accounts') }}">Sub Account Management</a>
                        </li>
                        <li><a href="{{ route('any', 'company/Finance/account-mapping') }}">Account Mapping</a></li>
                        <li>
                            <a href="{{ route('any', 'company/Finance/account-categories') }}">Account Categories</a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="side-nav-item">
                <a href="{{ route('any', 'menu-management') }}" class="side-nav-link">
                    <i class="ri-menu-2-fill"></i>
                    <span>Menu Management</span>
                </a>
            </li>

            <li class="side-nav-item">
                <a href="{{ route('any', 'company-profile') }}" class="side-nav-link">
                    <i class="ri-building-4-line"></i>
                    <span>Company Profile</span>
                </a>
            </li>

            <li class="side-nav-item">
                <a href="{{ route('any', 'company/emailtemplate') }}" class="side-nav-link">
                    <i class="ri-mail-settings-line"></i>
                    <span>Email Template</span>
                </a>
            </li>

            <li class="side-nav-item">
                <a
                    data-bs-toggle="collapse"
                    href="#productManagement"
                    aria-expanded="false"
                    aria-controls="productManagement"
                    class="side-nav-link">
                    <i class="ri-shopping-bag-3-line"></i>
                    <span>Product Management</span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="productManagement">
                    <ul class="side-nav-second-level">
                        <li><a href="{{ route('any', 'product-configuration') }}">Product Configuration</a></li>
                    </ul>
                </div>
            </li>

            <li class="side-nav-item">
                <a
                    data-bs-toggle="collapse"
                    href="#notificationManagement"
                    aria-expanded="false"
                    aria-controls="notificationManagement"
                    class="side-nav-link">
                    <i class="ri-notification-4-line"></i>
                    <span>Notification Management</span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="notificationManagement">
                    <ul class="side-nav-second-level">
                        <li><a href="{{ route('any', 'birthday-notifications') }}">Birthday Notifications</a></li>
                        <li><a href="{{ route('any', 'renewal-notification') }}">Renewal Notification</a></li>
                    </ul>
                </div>
            </li>

            <li class="side-nav-item">
                <a
                    data-bs-toggle="collapse"
                    href="#inventory"
                    aria-expanded="false"
                    aria-controls="inventory"
                    class="side-nav-link">
                    <i class="ri-store-2-line"></i>
                    <span>Inventory</span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="inventory">
                    <ul class="side-nav-second-level">
                        <li>
                            <a href="{{ route('any', 'company/Inventory/managercategories') }}">Manage Categories</a>
                        </li>
                        <li><a href="{{ route('any', 'company/Inventory/suppliers') }}">Suppliers</a></li>
                    </ul>
                </div>
            </li>

            <li class="side-nav-item">
                <a
                    data-bs-toggle="collapse"
                    href="#digitalMarketing"
                    aria-expanded="false"
                    aria-controls="digitalMarketing"
                    class="side-nav-link">
                    <i class="ri-advertisement-line"></i>
                    <span>Digital Marketing</span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="digitalMarketing">
                    <ul class="side-nav-second-level">
                        <li><a href="{{ route('any', 'newsletter-templates') }}">Newsletter Templates</a></li>
                        <li><a href="{{ route('any', 'subscribers') }}">Subscribers</a></li>
                    </ul>
                </div>
            </li>

            <li class="side-nav-item">
                <a
                    data-bs-toggle="collapse"
                    href="#customer"
                    aria-expanded="false"
                    aria-controls="customer"
                    class="side-nav-link">
                    <i class="ri-user-voice-line"></i>
                    <span>Customer</span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="customer">
                    <ul class="side-nav-second-level">
                        <li><a href="{{ route('any', 'search-customer') }}">Search Customer</a></li>
                        <li><a href="{{ route('any', 'lead-management') }}">Lead Management</a></li>
                    </ul>
                </div>
            </li>

            <li class="side-nav-item">
                <a
                    data-bs-toggle="collapse"
                    href="#productRequest"
                    aria-expanded="false"
                    aria-controls="productRequest"
                    class="side-nav-link">
                    <i class="ri-product-hunt-line"></i>
                    <span>Product Request</span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="productRequest">
                    <ul class="side-nav-second-level">
                        <li><a href="{{ route('any', 'search-request') }}">Search Request</a></li>
                        <li><a href="{{ route('any', 'my-request') }}">My Request</a></li>
                        <li><a href="{{ route('any', 'new-request') }}">New Request</a></li>
                        <li><a href="{{ route('any', 'request-report') }}">Report</a></li>
                    </ul>
                </div>
            </li>

            <li class="side-nav-item">
                <a
                    data-bs-toggle="collapse"
                    href="#payment"
                    aria-expanded="false"
                    aria-controls="payment"
                    class="side-nav-link">
                    <i class="ri-secure-payment-line"></i>
                    <span>Payment</span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="payment">
                    <ul class="side-nav-second-level">
                        <li><a href="{{ route('any', 'search-payment') }}">Search Payment</a></li>
                        <li><a href="{{ route('any', 'paying') }}">Paying</a></li>
                        <li><a href="{{ route('any', 'payout') }}">Payout</a></li>
                    </ul>
                </div>
            </li>

            <li class="side-nav-item">
                <a href="{{ route('any', 'id-verification') }}" class="side-nav-link">
                    <i class="ri-shield-user-line"></i>
                    <span>ID Verification</span>
                </a>
            </li>

            <li class="side-nav-item">
                <a
                    data-bs-toggle="collapse"
                    href="#inventoryManagement"
                    aria-expanded="false"
                    aria-controls="inventoryManagement"
                    class="side-nav-link">
                    <i class="ri-archive-line"></i>
                    <span>Inventory Management</span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="inventoryManagement">
                    <ul class="side-nav-second-level">
                        <li><a href="{{ route('any', 'requisition') }}">Requisition</a></li>
                        <li><a href="{{ route('any', 'procurement') }}">Procurement</a></li>
                        <li><a href="{{ route('any', 'inventory-report') }}">Report</a></li>
                    </ul>
                </div>
            </li>

            <li class="side-nav-item">
                <a
                    data-bs-toggle="collapse"
                    href="#humanResource"
                    aria-expanded="false"
                    aria-controls="humanResource"
                    class="side-nav-link">
                    <i class="ri-team-line"></i>
                    <span>Human Resource</span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="humanResource">
                    <ul class="side-nav-second-level">
                        <li><a href="{{ route('any', 'company-setup') }}">Company Setup</a></li>
                        <li><a href="{{ route('any', 'employee') }}">Employee</a></li>
                    </ul>
                </div>
            </li>
        </ul>
        <!--- End Sidemenu -->

        <div class="clearfix"></div>
    </div>
</div>
<!-- ========== Left Sidebar End ========== -->
