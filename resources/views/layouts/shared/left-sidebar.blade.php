<!-- ========== Left Sidebar Start ========== -->
<div class="leftside-menu">
    <!-- Brand Logo Light -->
    <style>
        .logo img {
            max-height: 100px;  /* Increased from default size */
            width: auto;
        }
        .logo-sm img {
            max-height: 100px;  /* Slightly smaller for mobile */
        }
    </style>
    <a href="{{ route('any', 'index') }}" class="logo logo-light">
        <span class="logo-lg">
            <img src="/images/yash.png" alt="logo" />
        </span>
        <span class="logo-sm">
            <img src="/images/yash.png" alt="small logo" />
        </span>
    </a>

    <!-- Brand Logo Dark -->
    <a href="{{ route('any', 'index') }}" class="logo logo-dark">
        <span class="logo-lg">
            <img src="/images/yash.png" alt="logo" />
        </span>
        <span class="logo-sm">
            <img src="/images/yash.png" alt="small logo" />
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
                <span class="leftbar-user-name mt-2">Hamid</span>
            </a>
        </div>

        <!--- Sidemenu -->
        <ul class="side-nav">
            <li class="side-nav-title">Navigation</li>


<!-- 
            <li class="side-nav-item">
                <a data-bs-toggle="collapse"
                   href="#superAdminMenu"
                   aria-expanded="false"
                   aria-controls="superAdminMenu"
                   class="side-nav-link">
                   <i class="ri-command-line"></i>
                    <span>Super Admin</span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="superAdminMenu">
                    <ul class="side-nav-second-level">
                        <li>
                            <a href="{{ route('any','superAdmin/dashboard') }}">Dashboard</a>
                        </li>
                        <li>
                            <a href="{{ route('any','superAdmin/superusers') }}">SuperUsers</a>
                        </li>
                        <li>
                            <a href="{{ route('any','superadmin.roles.index') }}">Roles & Permissions</a>
                        </li>
                        <li>
                            <a href="{{ route('any','superAdmin/settings') }}">Site Settings</a>
                        </li>
                        <li>
                            <a href="{{ route('any','superAdmin/audit') }}">Audit Logs</a>
                        </li>
                        <li>
                            <a href="{{ route('any','superAdmin/agents')}}">Agents Management</a>
                        </li>
                        <li>
                            <a href="{{ route('any','superAdmin/agentdash')}}">Agents Centre</a>
                        </li>
                    </ul>
                </div>
            </li>
-->
            @if($hasMenuAccess('dashboard'))
            <li class="side-nav-item">
                <a href="{{ route('any', 'company/FuelManagement/dashboard') }}" class="side-nav-link">
                    <i class="ri-home-4-line"></i>
                    <span>Dashboards</span>
                </a>
            </li>
            @endif
           @if($hasSubmenuAccess('administration'))
           <li class="side-nav-item">
                <a data-bs-toggle="collapse" 
                   href="#Administration" 
                   aria-expanded="false" 
                   aria-controls="Administration" 
                   class="side-nav-link">
                    <i class="ri-admin-line"></i>
                    <span class="badge bg-success float-end"></span>
                    <span>Administration</span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="Administration">
                    <ul class="side-nav-second-level">
                        @if($hasSubmenuAccess('user_management'))
                        <li>
                            <a data-bs-toggle="collapse" 
                               href="#userManagement" 
                               aria-expanded="false" 
                               aria-controls="userManagement">
                                <i class="ri-user-settings-line"></i>
                                <span>User Management</span>
                                <span class="menu-arrow"></span>
                            </a>
                            <div class="collapse" id="userManagement">
                                <ul class="side-nav-third-level">
                                    @if($hasMenuAccess('users'))
                                    <li><a href="{{ route('company-sub-users.index') }}">Users</a></li>
                                    @endif
                                    @if($hasMenuAccess('user_profiles'))
                                    <li><a href="{{ route('company.user-profiles.index') }}">User Profiles</a></li>
                                    @endif
                                    @if($hasMenuAccess('partners'))
                                    <li><a href="{{ route('company.partners.index') }}">Partners</a></li>
                                    @endif
                                    @if($hasMenuAccess('user_category'))
                                    <li><a href="{{ route('company-categories.index') }}">User Category</a></li>
                                    @endif
                                </ul>
                            </div>
                        </li>
                        @endif
                        
                        @if($hasSubmenuAccess('category_management'))
                        <li>
                            <a data-bs-toggle="collapse" 
                               href="#categoryManagement" 
                               aria-expanded="false" 
                               aria-controls="categoryManagement">
                                <i class="ri-folder-3-line"></i>
                                <span>Category Management</span>
                                <span class="menu-arrow"></span>
                            </a>
                            <div class="collapse" id="categoryManagement">
                                <ul class="side-nav-third-level">
                                    @if($hasMenuAccess('manage_categories'))
                                    <li><a href="{{ route('categories.index') }}">Manage Categories</a></li>
                                    @endif
                                    <!--
                                    @if($hasMenuAccess('category_settings'))
                                    <li><a href="{{ route('any', 'company/Categories/category-settings') }}">Category Settings</a></li>
                                    @endif
                                    -->
                                </ul>
                            </div>
                        </li>
                        @endif


                        @if($hasSubmenuAccess('email_management'))
                        <li>
                            <a data-bs-toggle="collapse" 
                               href="#emailManagement" 
                               aria-expanded="false" 
                               aria-controls="emailManagement">
                                <i class="ri-mail-settings-line"></i>
                                <span>Email Management</span>
                                <span class="menu-arrow"></span>
                            </a>
                            <div class="collapse" id="emailManagement">
                                <ul class="side-nav-third-level">
                                    @if($hasMenuAccess('email_templates'))
                                    <li><a href="{{ route('any', 'company/Administration/email-templates') }}">Email Templates</a></li>
                                    @endif
                                    @if($hasMenuAccess('mailing_lists'))
                                    <li><a href="{{ route('any', 'company/Administration/mailing-lists') }}">Mailing Lists</a></li>
                                    @endif
                                    @if($hasMenuAccess('email_campaigns'))
                                    <li><a href="{{ route('any', 'company/Administration/email-campaigns') }}">Email Campaigns</a></li>
                                    @endif
                                    @if($hasMenuAccess('email_analytics'))
                                    <li><a href="{{ route('any', 'company/Administration/email-analytics') }}">Email Analytics</a></li>
                                    @endif
                                </ul>
                            </div>
                        </li>
                        @endif
                        
                       <!-- <li>
                            <a href="{{ route('any', 'company/manage-departments') }}">
                                <i class="ri-building-line"></i>
                                <span>Manage Department</span>
                            </a>
                        </li>
                        <li> 
                            <a href="{{ route('any', 'company/manage-branches') }}">
                                <i class="ri-git-branch-line"></i>
                                <span>Manage Branches</span>
                            </a>
                        </li>
                        <li>
                            <a data-bs-toggle="collapse" 
                               href="#documentManagement" 
                               aria-expanded="false" 
                               aria-controls="documentManagement">
                                <i class="ri-file-list-3-line"></i>
                                <span>Document Management</span>
                                <span class="menu-arrow"></span>
                            </a>
                            <div class="collapse" id="documentManagement">
                                <ul class="side-nav-third-level">
                                    <li><a href="{{ route('any', 'company/Document-management/document-types') }}">Document Types</a></li>
                                    <li><a href="{{ route('any', 'company/Document-management/doc-classification') }}">Document Classification</a></li>
                                    <li><a href="{{ route('any', 'company/Document-management/doc-workflow') }}">Document WorkFlow</a></li>
                                    <li><a href="{{ route('any', 'company/Document-management/generatedoc') }}">Document Generation</a></li>
                                </ul>
                            </div>
                        </li> -->
                      <!--  <li>
                            <a data-bs-toggle="collapse" 
                               href="#targets" 
                               aria-expanded="false" 
                               aria-controls="targets">
                                <i class="ri-flag-2-line"></i>
                                <span>Targets</span>
                                <span class="menu-arrow"></span>
                            </a>
                            <div class="collapse" id="targets">
                                <ul class="side-nav-third-level">
                                    <li><a href="{{ route('any', 'company/Targets/ctarget') }}">Company Target</a></li>
                                    <li><a href="{{ route('any', 'company/Targets/individual-target') }}">Individual Target</a></li>
                                </ul>
                            </div>
                        </li>
                        <li>
                            <a data-bs-toggle="collapse" 
                               href="#finance" 
                               aria-expanded="false" 
                               aria-controls="finance">
                                <i class="ri-money-dollar-circle-line"></i>
                                <span>Finance</span>
                                <span class="menu-arrow"></span>
                            </a>
                            <div class="collapse" id="finance">
                                <ul class="side-nav-third-level">
                                    <li><a href="{{ route('any', 'company/Finance/managecurency') }}">Manage Currency</a></li>
                                    <li><a href="{{ route('any', 'company/Finance/account-types') }}">Create Account Type</a></li>
                                    <li><a href="{{ route('any', 'company/Finance/main-accounts') }}">Main Account Management</a></li>
                                    <li><a href="{{ route('any', 'company/Finance/sub-accounts') }}">Sub Account Management</a></li>
                                    <li><a href="{{ route('any', 'company/Finance/account-mapping') }}">Account Mapping</a></li>
                                    <li><a href="{{ route('any', 'company/Finance/account-categories') }}">Account Categories</a></li>
                                </ul>
                            </div>
                        </li>
                        <li>
                            <a href="{{ route('any', 'company/MenuManager/menumanager') }}">
                                <i class="ri-menu-2-fill"></i>
                                <span>Menu Management</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('any', 'company/CompanyProfile/company-profile') }}">
                                <i class="ri-building-4-line"></i>
                                <span>Company Profile</span>
                            </a>
                        </li>
                        <li>
                            <a data-bs-toggle="collapse" 
                               href="#notificationManagement" 
                               aria-expanded="false" 
                               aria-controls="notificationManagement">
                                <i class="ri-notification-4-line"></i>
                                <span>Notification Management</span>
                                <span class="menu-arrow"></span>
                            </a>
                            <div class="collapse" id="notificationManagement">
                                <ul class="side-nav-third-level">
                                    <li><a href="{{ route('any', 'birthday-notifications') }}">Birthday Notifications</a></li>
                                    <li><a href="{{ route('any', 'renewal-notification') }}">Renewal Notification</a></li>
                                </ul>
                            </div>
                        </li>
                        <li>
                            <a data-bs-toggle="collapse" 
                               href="#inventory" 
                               aria-expanded="false" 
                               aria-controls="inventory">
                                <i class="ri-store-2-line"></i>
                                <span>Inventory</span>
                                <span class="menu-arrow"></span>
                            </a>
                            <div class="collapse" id="inventory">
                                <ul class="side-nav-third-level">
                                    <li><a href="{{ route('any', 'company/Inventory/managercategories') }}">Manage Categories</a></li>
                                    <li><a href="{{ route('any', 'company/Inventory/suppliers') }}">Suppliers</a></li>
                                </ul>
                            </div>
                        </li>
                        <li>
                            <a data-bs-toggle="collapse" 
                               href="#digitalMarketing" 
                               aria-expanded="false" 
                               aria-controls="digitalMarketing">
                                <i class="ri-advertisement-line"></i>
                                <span>Digital Marketing</span>
                                <span class="menu-arrow"></span>
                            </a>
                            <div class="collapse" id="digitalMarketing">
                                <ul class="side-nav-third-level">
                                    <li><a href="{{ route('company-newsletters.index') }}">Newsletter Templates</a></li>
                                    <li><a href="{{ route('any', 'company/digital-marketing/subscribers') }}">Subscribers</a></li>
                                </ul>
                            </div>
                        </li> -->
                    </ul>
                </div>
            </li> 
            @endif
            <!-- Fuel Sales Management System -->
            @if($hasSubmenuAccess('fuel_management'))
            <li class="side-nav-item">
                <a data-bs-toggle="collapse"
                   href="#fuelManagement"
                   aria-expanded="false"
                   aria-controls="fuelManagement"
                   class="side-nav-link">
                    <i class="ri-gas-station-line"></i>
                    <span>Fuel Sales Management</span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="fuelManagement">
                    <ul class="side-nav-second-level">
                        <!--@if($hasMenuAccess('fuel_dashboard'))
                        <li>
                            <a href="{{ route('any', 'company/FuelManagement/dashboard') }}">
                                <i class="ri-dashboard-line"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>
                        @endif -->
                        
                        @if($hasMenuAccess('station_management'))
                        <li>
                            <a href="{{ route('any', 'company/FuelManagement/allstations') }}">
                                <i class="ri-building-4-line"></i>
                                <span>Stations</span>
                            </a>
                        </li>
                        @endif
                        
                        @if($hasMenuAccess('stock_management'))
                        <li>
                            
                        </li>
                        @endif
                        
                        @if($hasMenuAccess('sales_management'))
                        <li>
                            <a href="{{ route('any', 'company/FuelManagement/sales') }}">
                                <i class="ri-money-dollar-circle-line"></i>
                                <span>Sales</span>
                            </a>
                        </li>
                        @endif
                        @if($hasMenuAccess('station_managers'))
                        <li>
                            <a href="{{ route('any', 'company/FuelManagement/stationmanager') }}">
                                <i class="ri-user-line"></i>
                                <span>Station Managers</span>
                            </a>
                        </li>
                        @endif
                    </ul>
                </div>
            </li>
            <li class="side-nav-item">
                <a data-bs-toggle="collapse"
                    href="#stockActivity"
                    aria-expanded="false"
                    aria-controls="stockActivity"
                    class="side-nav-link">
                    <i class="ri-stack-line"></i>
                    <span>Stock Activity</span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="stockActivity">
                    <ul class="side-nav-second-level">
                        @if($hasMenuAccess('stock_received'))
                        <li>
                            <a href="{{ route('any', 'company/FuelManagement/stock') }}">
                                <i class="ri-stack-line"></i>
                                <span>Stock Received</span>
                            </a>
                        </li>
                        @endif
                        @if($hasMenuAccess('stock_dispatched'))
                        <li>
                            <a href="{{ route('any', 'company/FuelManagement/DispatchStock') }}">
                                <i class="ri-truck-line"></i>
                                <span>Stock Dispatched</span>
                            </a>
                        </li>
                        @endif
                        @if($hasMenuAccess('stock_recon'))
                        <li>
                            <a href="{{ route('any', 'company/FuelManagement/stockRecon') }}">
                                <i class="ri-exchange-line"></i>
                                <span>Stock Reconciliation</span>
                            </a>
                        </li>
                        @endif
                    </ul>
                </div>
            </li>
            <li class="side-nav-item">
                <a data-bs-toggle="collapse" 
                   href="#Accounts" 
                   aria-expanded="false" 
                   aria-controls="Accounts" 
                   class="side-nav-link">
                    <i class='ri-bank-card-line'></i>
                    <span>Accounts & Deposits</span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="Accounts">
                    <ul class="side-nav-second-level">
                        @if($hasMenuAccess('bank_deposit'))
                        <li>
                            <a href="{{ route('any', 'company/FuelManagement/bankdeposit') }}">
                                <i class="ri-money-dollar-circle-line"></i>
                                <span>Bank Deposit</span>
                            </a>
                        </li>
                        @endif
                        @if($hasMenuAccess('all_account'))
                        <li>
                            <a href="{{ route('any', 'company/FuelManagement/allaccount') }}">
                                <i class="ri-money-dollar-circle-line"></i>
                                <span>All Accounts</span>
                            </a>
                        </li>
                        @endif
                    </ul>
                </div>
            </li>
            @endif

            <!-- @if($hasSubmenuAccess('master_tracker'))
            <li class="side-nav-item">
                <a data-bs-toggle="collapse" 
                   href="#MasterTracker" 
                   aria-expanded="false" 
                   aria-controls="MasterTracker" 
                   class="side-nav-link">
                    <i class="ri-dashboard-3-line"></i>
                    <span>Master Tracker</span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="MasterTracker">
                    <ul class="side-nav-second-level">
                        @if($hasMenuAccess('workforce_fleet'))
                        <li><a href="{{ route('workforce-fleet') }}">Workforce & Fleet</a></li>
                        @endif
                        @if($hasMenuAccess('team_pairing'))
                        <li><a href="{{ route('any', 'company/MasterTracker/team-pairing') }}">Team Pairing</a></li>
                        @endif
                        @if($hasMenuAccess('team_roaster'))
                        <li><a href="{{ route('any', 'company/MasterTracker/team-roaster') }}">Team Roaster</a></li>
                        @endif
                        @if($hasMenuAccess('gesl_tracker'))
                        <li><a href="{{ route('any', 'company/MasterTracker/gesl-tracker') }}">GESL Tracker</a></li>
                        @endif
                        @if($hasMenuAccess('linfra_tracker'))
                        <li><a href="{{ route('any', 'company/MasterTracker/linfra-tracker') }}">Linfra Tracker</a></li>
                        @endif
                        @if($hasMenuAccess('kpi_report'))
                        <li><a href="{{ route('any', 'company/MasterTracker/kpi-report') }}">KPI Report</a></li>
                        @endif
                        @if($hasMenuAccess('material_balance'))
                        <li><a href="{{ route('any', 'company/MasterTracker/material-balance') }}">Material Balance</a></li>
                        @endif
                        @if($hasMenuAccess('ont_restock_tracker'))
                        <li><a href="{{ route('any', 'company/MasterTracker/ont-restock-tracker') }}">ONT Restock Tracker</a></li>
                        @endif
                        @if($hasMenuAccess('sbc_list_scoring'))
                        <li><a href="{{ route('any', 'company/MasterTracker/sbc-list-scoring') }}">SBC List & Scoring</a></li>
                        @endif
                        @if($hasMenuAccess('aging_dashboard'))
                        <li><a href="{{ route('any', 'company/MasterTracker/aging-dashboard') }}">Aging Dashboard</a></li>
                        @endif
                        @if($hasMenuAccess('MasterTracker_Report'))
                        <li><a href="{{ route('any', 'company/MasterTracker/master-report') }}">Master Tracker Report</a></li>
                        @endif
                    </ul>
                </div>
            </li>
            @endif -->
            
            
            <!-- @if($hasSubmenuAccess('human_resources'))
            <li class="side-nav-item">
                <a data-bs-toggle="collapse" 
                   href="#HR" 
                   aria-expanded="false" 
                   aria-controls="HR" 
                   class="side-nav-link">
                    <i class="ri-group-2-line"></i>
                    <span>Human Resources</span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="HR">
                    <ul class="side-nav-second-level">
                        @if($hasMenuAccess('hr_desk'))
                        <li><a href="{{ route('any', 'company/HumanResource/hr') }}">HR Desk</a></li>
                        @endif
                        @if($hasMenuAccess('staff_desk'))
                        <li><a href="{{ route('any', 'company/HumanResource/staff') }}">Staff Desk</a></li>
                        @endif
                    </ul>
                </div>
            </li>
            @endif -->
           <!-- @if($hasSubmenuAccess('crm'))
            <li class="side-nav-item">
                <a data-bs-toggle="collapse" 
                   href="#CRM" 
                   aria-expanded="false" 
                   aria-controls="CRM" 
                   class="side-nav-link">
                    <i class="ri-customer-service-2-line"></i>
                    <span>CRM</span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="CRM">
                    <ul class="side-nav-second-level">
                        @if($hasMenuAccess('crm_dashboard'))
                        <li><a href="{{ route('any', 'company/CRM/crmdash') }}">Dashboard</a></li>
                        @endif
                        @if($hasMenuAccess('crm_main'))
                        <li><a href="{{ route('any', 'company/CRM/crm') }}">Customer Management</a></li>
                        @endif
                        @if($hasMenuAccess('contract'))
                        <li><a href="{{ route('any', 'company/CRM/contract') }}">Contract</a></li>
                        @endif
                    </ul>
                </div>
            </li>
            @endif -->
            
            <!-- @if($hasSubmenuAccess('management'))
            <li class="side-nav-item">
                <a data-bs-toggle="collapse" 
                   href="#Management" 
                   aria-expanded="false" 
                   aria-controls="Management" 
                   class="side-nav-link">
                    <i class="ri-settings-3-line"></i>
                    <span>Management</span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="Management">
                    <ul class="side-nav-second-level">
                        @if($hasMenuAccess('purchase_order_management'))
                        <li><a href="{{ route('management.purchase-order-approval') }}">Purchase Order Approval</a></li>
                        @endif
                        @if($hasMenuAccess('requisition_approval'))
                        <li><a href="{{ route('management.requisition-approval') }}">Requisition Approval</a></li>
                        @endif
                    </ul>
                </div>
            </li>
            @endif -->
            
           <!-- @if($hasSubmenuAccess('warehouse_management'))
            <li class="side-nav-item">
                <a data-bs-toggle="collapse" 
                   href="#warehousemanagement" 
                   aria-expanded="false" 
                   aria-controls="warehousemanagement" 
                   class="side-nav-link">
                    <i class="ri-store-2-line"></i>
                    <span>Warehouse Management</span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="warehousemanagement">
                    <ul class="side-nav-second-level">
                        @if($hasMenuAccess('requisition'))
                        <li><a href="{{ route('any', 'company/InventoryManagement/requisition') }}">Requisition</a></li>
                        @endif
                        @if($hasMenuAccess('procurement'))
                        <li><a href="{{ route('any', 'company/InventoryManagement/procurement') }}">Procurement</a></li>
                        @endif
                        @if($hasMenuAccess('warehouse'))
                        <li><a href="{{ route('any', 'company/InventoryManagement/WarehouseOperations')}}">Warehouse</a></li>
                        @endif
                        @if($hasMenuAccess('warehouse_report'))
                        <li><a href="{{ route('any', 'company/InventoryManagement/inventory-dash') }}">Report</a></li>
                        @endif
                    </ul>
                </div>
            </li>
            @endif -->
           <!-- <li class="side-nav-item">
                <a href="{{ route('any', 'id-verification') }}" class="side-nav-link">
                    <i class="ri-fingerprint-line"></i>
                    <span>ID Verification</span>
                </a>
            </li> -->
           <!-- @if($hasSubmenuAccess('project_management'))
            <li class="side-nav-item">
                <a data-bs-toggle="collapse" 
                   href="#projectManagement" 
                   aria-expanded="false" 
                   aria-controls="projectManagement" 
                   class="side-nav-link">
                    <i class="ri-task-line"></i>
                    <span>Project Management</span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="projectManagement">
                    <ul class="side-nav-second-level">
                        @if($hasMenuAccess('gpon'))
                        <li>
                            <a href="{{ route('any', 'company/ProjectManagement/pm') }}">GPON</a>
                        </li>
                        @endif
                        @if($hasMenuAccess('home_connection'))
                        <li>
                            <a href="{{ route('any', 'company/ProjectManagement/homeConnection') }}">Home Connection</a>
                        </li>
                        @endif
                        @if($hasMenuAccess('field_update'))
                        <li>
                            <a href="{{ route('any', 'company/ProjectManagement/field-update') }}">Field Update</a>
                        </li>
                        @endif
                        @if($hasMenuAccess('quality_audit'))
                        <li>
                            <a href="{{ route('any', 'company/ProjectManagement/qualityAudit') }}">Quality Audit</a>
                        </li>
                        @endif
                    </ul>  -->
                    <!-- <li class="side-nav-item">
                        <a href="{{ route('any', 'company/LoyaltyPoints/loyalty') }}" class="side-nav-link">
                            <i class="ri-star-line"></i>
                            <span>Loyalty</span>
                        </a>
                    </li> -->

                   <!-- <li class="side-nav-item">
                        <a href="{{ route('any', 'company/ProjectManagement/generalService') }}" class="side-nav-link">
                            <i class="ri-settings-5-line"></i>
                            <span>General Service</span>
                        </a>
                    </li>
                </div>
            </li>
            @endif -->
        </ul>
        <!--- End Sidemenu -->

        <div class="clearfix"></div>
    </div>
</div>
<!-- ========== Left Sidebar Endin it here ========== -->
