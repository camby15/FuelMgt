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
                        <ul class="side-nav-second-level">
                            <li>
                                <a href="{{ route('superAdmin.dashboard') }}">Dashboard</a>
                            </li>
                            <li>
                                <a href="{{ route('superadmin.users.index') }}">SuperUsers</a>
                            </li>
                            <li>
                                <a href="{{ route('superadmin.roles.index') }}">Roles & Permissions</a>
                            </li>
                            <li>
                                <a href="{{ url('/superadmin/settings') }}">Site Settings</a>
                            </li>
                            <li>
                                <a href="{{ url('/superadmin/audit') }}">Audit Logs</a>
                            </li>
                            <li>
                                <a href="{{ url('/superadmin/agents')}}">Agents Management</a>
                            </li>
                            <li>
                                <a href="{{ url('/superadmin/agentdash')}}">Agents Centre</a>
                            </li>
                        </ul>
                </div>
            </li>
        </ul>
        <!--- End Sidemenu -->

        <div class="clearfix"></div>
    </div>
</div>
<!-- ========== Left Sidebar End ========== -->
