<!-- ========== Topbar Start ========== -->
<div class="navbar-custom">
    <div class="topbar container-fluid">
        <div class="d-flex align-items-center gap-lg-2 gap-1">
            <!-- Topbar Brand Logo -->
            <div class="logo-topbar">
                <!-- Logo light -->
                <a href="/" class="logo-light">
                    <span class="logo-lg">
                        <img src="/images/yash.png" alt="logo" />
                    </span>
                    <span class="logo-sm">
                        <img src="/images/logo_white_sm.png" alt="small logo" />
                    </span>
                </a>

                <!-- Logo Dark -->
                <a href="/" class="logo-dark">
                    <span class="logo-lg">
                        <img src="/images/STACK LOGO-01.png" alt="dark logo" />
                    </span>
                    <span class="logo-sm">
                        <img src="/images/logo_white_sm.png" alt="small logo" />
                    </span>
                </a>
            </div>

            <!-- Sidebar Menu Toggle Button -->
            <button class="button-toggle-menu">
                <i class="ri-menu-2-fill"></i>
            </button>
            <!-- 
            <!-- Horizontal Menu Toggle Button -->
            <button class="navbar-toggle" data-bs-toggle="collapse" data-bs-target="#topnav-menu-content">
                <div class="lines">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </button>
        </div>

        <ul class="topbar-menu d-flex align-items-center gap-3">
            <li class="dropdown d-lg-none d-none">
                <a
                    class="nav-link dropdown-toggle arrow-none"
                    data-bs-toggle="dropdown"
                    href="#"
                    role="button"
                    aria-haspopup="false"
                    aria-expanded="false">
                    <i class="ri-search-line fs-22"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-animated dropdown-lg p-0">
                    <form class="p-3">
                        <input
                            type="search"
                            class="form-control"
                            placeholder="Search ..."
                            aria-label="Recipient's username" />
                    </form>
                </div>
            </li>

            <li class="dropdown d-none">
                <a
                    class="nav-link dropdown-toggle arrow-none"
                    data-bs-toggle="dropdown"
                    href="#"
                    role="button"
                    aria-haspopup="false"
                    aria-expanded="false">
                    <img src="/images/flags/us.jpg" alt="user-image" class="me-0 me-sm-1" height="12" />
                    <span class="align-middle d-none d-lg-inline-block">English</span>
                    <i class="ri-arrow-down-s-line d-none d-sm-inline-block align-middle"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-end dropdown-menu-animated">
                    <!-- item-->
                    <a href="javascript:void(0);" class="dropdown-item">
                        <img src="/images/flags/germany.jpg" alt="user-image" class="me-1" height="12" />
                        <span class="align-middle">German</span>
                    </a>

                    <!-- item-->
                    <a href="javascript:void(0);" class="dropdown-item">
                        <img src="/images/flags/italy.jpg" alt="user-image" class="me-1" height="12" />
                        <span class="align-middle">Italian</span>
                    </a>

                    <!-- item-->
                    <a href="javascript:void(0);" class="dropdown-item">
                        <img src="/images/flags/spain.jpg" alt="user-image" class="me-1" height="12" />
                        <span class="align-middle">Spanish</span>
                    </a>

                    <!-- item-->
                    <a href="javascript:void(0);" class="dropdown-item">
                        <img src="/images/flags/russia.jpg" alt="user-image" class="me-1" height="12" />
                        <span class="align-middle">Russian</span>
                    </a>
                </div>
            </li>

            <li class="dropdown notification-list d-none">
                <a
                    class="nav-link dropdown-toggle arrow-none"
                    data-bs-toggle="dropdown"
                    href="#"
                    role="button"
                    aria-haspopup="false"
                    aria-expanded="false">
                    <i class="ri-notification-3-line fs-22"></i>
                    <span class="noti-icon-badge"></span>
                </a>
                <div class="dropdown-menu dropdown-menu-end dropdown-menu-animated dropdown-lg py-0">
                    <div class="p-2 border-top-0 border-start-0 border-end-0 border-dashed border">
                        <div class="row align-items-center">
                            <div class="col">
                                <h6 class="m-0 fs-16 fw-semibold">Notification</h6>
                            </div>
                            <div class="col-auto">
                                <a href="javascript: void(0);" class="text-dark text-decoration-underline">
                                    <small>Clear All</small>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div style="max-height: 300px" data-simplebar>
                        <h5 class="text-muted fs-12 fw-bold p-2 text-uppercase mb-0">Today</h5>
                        <!-- item-->

                        <a
                            href="javascript:void(0);"
                            class="dropdown-item p-0 notify-item unread-noti card m-0 shadow-none">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <div class="notify-icon bg-primary">
                                            <i class="ri-message-3-line fs-18"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 text-truncate ms-2">
                                        <h5 class="noti-item-title fw-semibold fs-14">
                                            Datacorp
                                            <small class="fw-normal text-muted float-end ms-1">1 min ago</small>
                                        </h5>
                                        <small class="noti-item-subtitle text-muted">
                                            Caleb Flakelar commented on Admin
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </a>

                        <!-- item-->
                        <a
                            href="javascript:void(0);"
                            class="dropdown-item p-0 notify-item read-noti card m-0 shadow-none">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <div class="notify-icon bg-info">
                                            <i class="ri-user-add-line fs-18"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 text-truncate ms-2">
                                        <h5 class="noti-item-title fw-semibold fs-14">
                                            Admin
                                            <small class="fw-normal text-muted float-end ms-1">1 hr ago</small>
                                        </h5>
                                        <small class="noti-item-subtitle text-muted">New user registered</small>
                                    </div>
                                </div>
                            </div>
                        </a>

                        <h5 class="text-muted fs-12 fw-bold p-2 mb-0 text-uppercase">Yesterday</h5>

                        <!-- item-->
                        <a
                            href="javascript:void(0);"
                            class="dropdown-item p-0 notify-item read-noti card m-0 shadow-none">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <div class="notify-icon">
                                            <img
                                                src="/images/users/avatar-2.jpg"
                                                class="img-fluid rounded-circle"
                                                alt="" />
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 text-truncate ms-2">
                                        <h5 class="noti-item-title fw-semibold fs-14">
                                            Cristina Pride
                                            <small class="fw-normal text-muted float-end ms-1">1 day ago</small>
                                        </h5>
                                        <small class="noti-item-subtitle text-muted">
                                            Hi, How are you? What about our next meeting
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </a>

                        <h5 class="text-muted fs-12 fw-bold p-2 mb-0 text-uppercase">31 Jan 2023</h5>

                        <!-- item-->
                        <a
                            href="javascript:void(0);"
                            class="dropdown-item p-0 notify-item read-noti card m-0 shadow-none d-none">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <div class="notify-icon bg-primary">
                                            <i class="ri-discuss-line fs-18"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 text-truncate ms-2">
                                        <h5 class="noti-item-title fw-semibold fs-14">Datacorp</h5>
                                        <small class="noti-item-subtitle text-muted">
                                            Caleb Flakelar commented on Admin
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </a>

                        <!-- item-->
                        <a
                            href="javascript:void(0);"
                            class="dropdown-item p-0 notify-item read-noti card m-0 shadow-none">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <div class="notify-icon">
                                            <img
                                                src="/images/users/avatar-4.jpg"
                                                class="img-fluid rounded-circle"
                                                alt="" />
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 text-truncate ms-2">
                                        <h5 class="noti-item-title fw-semibold fs-14">Karen Robinson</h5>
                                        <small class="noti-item-subtitle text-muted">
                                            Wow ! this admin looks good and awesome design
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>

                    <!-- All-->
                    <a
                        href="javascript:void(0);"
                        class="dropdown-item text-center text-primary text-decoration-underline fw-bold notify-item border-top border-light py-2">
                        View All
                    </a>
                </div>
            </li>
            <!--
            <li class="dropdown d-none d-sm-inline-block d-none">
                <a
                    class="nav-link dropdown-toggle arrow-none"
                    data-bs-toggle="dropdown"
                    href="#"
                    role="button"
                    aria-haspopup="false"
                    aria-expanded="false">
                    <i class="ri-apps-2-line fs-22"></i>
                </a>
                
                <div class="dropdown-menu dropdown-menu-end dropdown-menu-animated dropdown-lg p-0 d-none">
                    <div class="p-2">
                        <div class="row g-0">
                            <div class="col">
                                <a class="dropdown-icon-item" href="#">
                                    <img src="/images/brands/github.png" alt="Github" />
                                    <span>GitHub</span>
                                </a>
                            </div>
                            <div class="col">
                                <a class="dropdown-icon-item" href="#">
                                    <img src="/images/brands/bitbucket.png" alt="bitbucket" />
                                    <span>Bitbucket</span>
                                </a>
                            </div>
                            <div class="col">
                                <a class="dropdown-icon-item" href="#">
                                    <img src="/images/brands/dropbox.png" alt="dropbox" />
                                    <span>Dropbox</span>
                                </a>
                            </div>
                        </div>

                        <div class="row g-0">
                            <div class="col">
                                <a class="dropdown-icon-item" href="#">
                                    <img src="/images/brands/slack.png" alt="slack" />
                                    <span>Slack</span>
                                </a>
                            </div>
                            <div class="col">
                                <a class="dropdown-icon-item" href="#">
                                    <img src="/images/brands/dribbble.png" alt="dribbble" />
                                    <span>Dribbble</span>
                                </a>
                            </div>
                            <div class="col">
                                <a class="dropdown-icon-item" href="#">
                                    <img src="/images/brands/behance.png" alt="Behance" />
                                    <span>Behance</span>
                                </a>
                            </div>
                        </div>
                        <!-- end row
                    </div>
                </div>
            </li>
            -->

            <!--
            <li class="d-none d-sm-inline-block d-none d-none">
                <a class="nav-link" data-bs-toggle="offcanvas" href="#theme-settings-offcanvas">
                    <i class="ri-settings-3-line fs-22"></i>
                </a>
            </li>
        -->

        <!--
            <li class="d-none d-sm-inline-block d-none d-none">
                <div
                    class="nav-link"
                    id="light-dark-mode"
                    data-bs-toggle="tooltip"
                    data-bs-placement="left"
                    title="Theme Mode">
                    <i class="ri-moon-line fs-22"></i>
                </div>
            </li>

            <li class="d-none d-md-inline-block d-none">
                <a class="nav-link" href="" data-toggle="fullscreen">
                    <i class="ri-fullscreen-line fs-22"></i>
                </a>
            </li>



        -->


            

            <li class="dropdown">
                <a
                    class="nav-link dropdown-toggle arrow-none nav-user px-2"
                    data-bs-toggle="dropdown"
                    href="#"
                    role="button"
                    aria-haspopup="true"
                    aria-expanded="false"
                    id="userDropdown">
                    <span class="account-user-avatar">
                        <img src="/images/users/avatar-1.jpg" alt="user-image" width="32" class="rounded-circle" />
                    </span>
                    <span class="d-lg-flex flex-column gap-1 d-none">
                        @php
                            $currentUser = null;
                            $userType = 'guest';
                            $displayRole = 'Guest';
                            
                            // Check if we have a sub_user_id in session (more reliable than guard check)
                            if (session('sub_user_id') && auth()->guard('sub_user')->check()) {
                                $currentUser = auth()->guard('sub_user')->user();
                                $userType = 'sub_user';
                                $displayRole = ucfirst(str_replace('_', ' ', $currentUser->role ?? 'Sub User'));
                            }
                            // Check regular user authentication
                            elseif (auth()->check() && !session('sub_user_id')) {
                                $currentUser = auth()->user();
                                $userType = 'regular';
                                if ($currentUser->companyProfile) {
                                    $displayRole = 'Company Owner';
                                } else {
                                    $displayRole = 'Individual User';
                                }
                            }
                        @endphp
                        
                        <h5 class="my-0">
                            {{ $currentUser ? $currentUser->fullname : 'Guest' }}
                        </h5>
                        <h6 class="my-0 fw-normal">
                            {{ $displayRole }}
                        </h6>
                    </span>
                </a>
                <div class="dropdown-menu dropdown-menu-end dropdown-menu-animated profile-dropdown" aria-labelledby="userDropdown">
                    <!-- item-->
                    <div class="dropdown-header noti-title">
                        <h6 class="text-overflow m-0">Welcome !</h6>
                    </div>

                    <!-- item-->
                    <a href="{{ route('second', ['pages', 'profile']) }}" class="dropdown-item">
                        <i class="ri-account-circle-line fs-18 align-middle me-1"></i>
                        <span>My Account</span>
                    </a>

                    <!-- item-->
                    <a href="{{ route('second', ['pages', 'profile']) }}" class="dropdown-item">
                        <i class="ri-settings-4-line fs-18 align-middle me-1"></i>
                        <span>Settings</span>
                    </a>

                    <!-- item-->
                    <a href="{{ route('second', ['pages', 'faq']) }}" class="dropdown-item">
                        <i class="ri-customer-service-2-line fs-18 align-middle me-1"></i>
                        <span>Support</span>
                    </a>

                    <!-- item-->
                    <a href="{{ route('lock-screen') }}" class="dropdown-item">
                        <i class="ri-lock-password-line fs-18 align-middle me-1"></i>
                        <span>Lock Screen</span>
                    </a>

                    <!-- item-->
                    @if(session('sub_user_id') && auth()->guard('sub_user')->check())
                        <form method="POST" action="{{ route('auth.sub_user.logout') }}">
                            @csrf
                            <a onclick="event.preventDefault(); this.closest('form').submit();" class="dropdown-item">
                                <i class="ri-logout-box-line fs-18 align-middle me-1"></i>
                                <span>Logout</span>
                            </a>
                        </form>
                    @else
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <a onclick="event.preventDefault(); this.closest('form').submit();" class="dropdown-item">
                                <i class="ri-logout-box-line fs-18 align-middle me-1"></i>
                                <span>Logout</span>
                            </a>
                        </form>
                    @endif
                </div>
            </li>
        </ul>
    </div>
</div>
<!-- ========== Topbar End ========== -->
