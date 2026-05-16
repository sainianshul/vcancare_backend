<!--begin::Sidebar-->
<div id="kt_app_sidebar" class="app-sidebar flex-column">

    <!--begin::Logo-->
    <div class="app-sidebar-logo flex-shrink-0 d-none d-md-flex align-items-center px-8" id="kt_app_sidebar_logo">
        <a href="{{ route('admin.dashboard') }}">
            <img alt="Logo" src="{{ asset('theme/media/logos/logo.svg') }}"
                class="h-25px app-sidebar-logo-default theme-light-show" />
            <img alt="Logo" src="{{ asset('theme/media/logos/logo-dark.svg') }}" class="h-25px theme-dark-show" />
        </a>
    </div>
    <!--end::Logo-->

    <!--begin::Sidebar menu-->
    <div class="app-sidebar-menu flex-grow-1" style="min-height:0; overflow:hidden; display:flex; flex-direction:column;">
        <div id="kt_app_sidebar_menu_wrapper" class="app-sidebar-wrapper" style="flex:1 1 0; overflow-y:auto; overflow-x:hidden; padding:1.25rem 0.75rem;">

            <div class="menu menu-column menu-rounded menu-sub-indention fw-semibold px-1" id="kt_app_sidebar_menu">

                {{-- ===================== --}}
                {{-- DASHBOARD --}}
                {{-- ===================== --}}
                <div class="menu-item">
                    <a class="menu-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
                        href="{{ route('admin.dashboard') }}">
                        <span class="menu-icon">
                            <i class="ki-outline ki-element-11 fs-2"></i>
                        </span>
                        <span class="menu-title">Dashboard</span>
                    </a>
                </div>

                {{-- ===================== --}}
                {{-- PEOPLE --}}
                {{-- ===================== --}}
                <div class="menu-item pt-5">
                    <div class="menu-content">
                        <span class="menu-heading fw-bold text-uppercase fs-7">People</span>
                    </div>
                </div>

                {{-- Nurses --}}
                @php
                    $pendingCount = cache()->remember('sidebar_pending_nurses_count', 300, function () {
                        return \App\Models\NurseProfile::where('status', \App\Models\NurseProfile::STATUS_UNDER_REVIEW)
                            ->whereHas('user', fn($q) => $q->where('status', 'active'))
                            ->count();
                    });
                @endphp

                <div data-kt-menu-trigger="click"
                    class="menu-item menu-accordion {{ request()->routeIs('admin.nurses.*') ? 'here show' : '' }}">

                    <span class="menu-link">
                        <span class="menu-icon">
                            <i class="ki-outline ki-profile-user fs-2"></i>
                        </span>
                        <span class="menu-title">Nurses</span>
                        @if($pendingCount > 0)
                            <span class="menu-badge">
                                <span class="badge badge-sm badge-circle badge-light-warning">
                                    {{ $pendingCount }}
                                </span>
                            </span>
                        @endif
                        <span class="menu-arrow"></span>
                    </span>

                    <div class="menu-sub menu-sub-accordion">

                        <div class="menu-item">
                            <a class="menu-link {{ request()->routeIs('admin.nurses.index') ? 'active' : '' }}"
                                href="{{ route('admin.nurses.index') }}">
                                <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                <span class="menu-title">All Nurses</span>
                            </a>
                        </div>

                        <div class="menu-item">
                            <a class="menu-link {{ request()->routeIs('admin.nurses.pending') ? 'active' : '' }}"
                                href="{{ route('admin.nurses.pending') }}">
                                <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                <span class="menu-title">Pending Approval</span>
                                @if($pendingCount > 0)
                                    <span class="menu-badge">
                                        <span class="badge badge-sm badge-light-warning fw-bold">
                                            {{ $pendingCount }}
                                        </span>
                                    </span>
                                @endif
                            </a>
                        </div>

                        <div class="menu-item">
                            <a class="menu-link {{ request()->routeIs('admin.nurses.approved') ? 'active' : '' }}"
                                href="{{ route('admin.nurses.approved') }}">
                                <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                <span class="menu-title">Approved</span>
                            </a>
                        </div>

                        <div class="menu-item">
                            <a class="menu-link {{ request()->routeIs('admin.nurses.rejected') ? 'active' : '' }}"
                                href="{{ route('admin.nurses.rejected') }}">
                                <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                <span class="menu-title">Rejected</span>
                            </a>
                        </div>

                    </div>
                </div>

                {{-- Patients --}}
                <div data-kt-menu-trigger="click"
                    class="menu-item menu-accordion {{ request()->routeIs('admin.patients.*') ? 'here show' : '' }}">
                    <span class="menu-link">
                        <span class="menu-icon">
                            <i class="ki-outline ki-people fs-2"></i>
                        </span>
                        <span class="menu-title">Patients</span>
                        <span class="menu-arrow"></span>
                    </span>
                    <div class="menu-sub menu-sub-accordion">
                        <div class="menu-item">
                            <a class="menu-link {{ request()->routeIs('admin.patients.index') ? 'active' : '' }}"
                                href="{{ route('admin.patients.index') }}">
                                <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                <span class="menu-title">All Patients</span>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a class="menu-link {{ request()->routeIs('admin.patients.blocked') ? 'active' : '' }}"
                                href="{{ route('admin.patients.blocked') }}">
                                <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                <span class="menu-title">Blocked</span>
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Login History --}}
                <div class="menu-item">
                    <a class="menu-link {{ request()->routeIs('admin.login-history.*') ? 'active' : '' }}"
                        href="{{ route('admin.login-history.index') }}">
                        <span class="menu-icon">
                            <i class="ki-outline ki-security-user fs-2"></i>
                        </span>
                        <span class="menu-title">Login History</span>
                    </a>
                </div>

                {{-- ===================== --}}
                {{-- OPERATIONS --}}
                {{-- ===================== --}}
                <div class="menu-item pt-5">
                    <div class="menu-content">
                        <span class="menu-heading fw-bold text-uppercase fs-7">Operations</span>
                    </div>
                </div>

                {{-- Care Requests --}}
                <div data-kt-menu-trigger="click"
                    class="menu-item menu-accordion {{ request()->routeIs('admin.requests.*') ? 'here show' : '' }}">
                    <span class="menu-link">
                        <span class="menu-icon">
                            <i class="ki-outline ki-clipboard fs-2"></i>
                        </span>
                        <span class="menu-title">Care Requests</span>
                        <span class="menu-arrow"></span>
                    </span>
                    <div class="menu-sub menu-sub-accordion">
                        <div class="menu-item">
                            <a class="menu-link {{ request()->routeIs('admin.requests.index') ? 'active' : '' }}"
                                href="{{ route('admin.requests.index') }}">
                                <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                <span class="menu-title">All Requests</span>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a class="menu-link {{ request()->routeIs('admin.requests.new') ? 'active' : '' }}"
                                href="{{ route('admin.requests.new') }}">
                                <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                <span class="menu-title">New</span>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a class="menu-link {{ request()->routeIs('admin.requests.active') ? 'active' : '' }}"
                                href="{{ route('admin.requests.active') }}">
                                <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                <span class="menu-title">Active</span>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a class="menu-link {{ request()->routeIs('admin.requests.completed') ? 'active' : '' }}"
                                href="{{ route('admin.requests.completed') }}">
                                <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                <span class="menu-title">Completed</span>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a class="menu-link {{ request()->routeIs('admin.requests.cancelled') ? 'active' : '' }}"
                                href="{{ route('admin.requests.cancelled') }}">
                                <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                <span class="menu-title">Cancelled</span>
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Bids --}}
                <div data-kt-menu-trigger="click"
                    class="menu-item menu-accordion {{ request()->routeIs('admin.bids.*') ? 'here show' : '' }}">
                    <span class="menu-link">
                        <span class="menu-icon">
                            <i class="ki-outline ki-price-tag fs-2"></i>
                        </span>
                        <span class="menu-title">Bids</span>
                        <span class="menu-arrow"></span>
                    </span>
                    <div class="menu-sub menu-sub-accordion">
                        <div class="menu-item">
                            <a class="menu-link {{ request()->routeIs('admin.bids.active') ? 'active' : '' }}"
                                href="{{ route('admin.bids.active') }}">
                                <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                <span class="menu-title">Active Bids</span>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a class="menu-link {{ request()->routeIs('admin.bids.accepted') ? 'active' : '' }}"
                                href="{{ route('admin.bids.accepted') }}">
                                <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                <span class="menu-title">Accepted</span>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a class="menu-link {{ request()->routeIs('admin.bids.rejected') ? 'active' : '' }}"
                                href="{{ route('admin.bids.rejected') }}">
                                <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                <span class="menu-title">Rejected</span>
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Services --}}
                <div data-kt-menu-trigger="click"
                    class="menu-item menu-accordion {{ request()->routeIs('admin.services.*') ? 'here show' : '' }}">
                    <span class="menu-link">
                        <span class="menu-icon">
                            <i class="ki-outline ki-heart fs-2"></i>
                        </span>
                        <span class="menu-title">Services</span>
                        <span class="menu-arrow"></span>
                    </span>
                    <div class="menu-sub menu-sub-accordion">
                        <div class="menu-item">
                            <a class="menu-link {{ request()->routeIs('admin.services.care-types.*') ? 'active' : '' }}"
                                href="{{ route('admin.services.care-types.index') }}">
                                <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                <span class="menu-title">Care Types</span>
                            </a>
                        </div>
                    </div>
                </div>

                {{-- ===================== --}}
                {{-- FINANCE --}}
                {{-- ===================== --}}
                <div class="menu-item pt-5">
                    <div class="menu-content">
                        <span class="menu-heading fw-bold text-uppercase fs-7">Finance</span>
                    </div>
                </div>

                {{-- Payments --}}
                <div data-kt-menu-trigger="click"
                    class="menu-item menu-accordion {{ request()->routeIs('admin.payments.*') ? 'here show' : '' }}">
                    <span class="menu-link">
                        <span class="menu-icon">
                            <i class="ki-outline ki-dollar fs-2"></i>
                        </span>
                        <span class="menu-title">Payments</span>
                        <span class="menu-arrow"></span>
                    </span>
                    <div class="menu-sub menu-sub-accordion">
                        <div class="menu-item">
                            <a class="menu-link {{ request()->routeIs('admin.payments.transactions') ? 'active' : '' }}"
                                href="{{ route('admin.payments.transactions') }}">
                                <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                <span class="menu-title">Transactions</span>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a class="menu-link {{ request()->routeIs('admin.payments.payouts') ? 'active' : '' }}"
                                href="{{ route('admin.payments.payouts') }}">
                                <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                <span class="menu-title">Nurse Payouts</span>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a class="menu-link {{ request()->routeIs('admin.payments.refunds') ? 'active' : '' }}"
                                href="{{ route('admin.payments.refunds') }}">
                                <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                <span class="menu-title">Refunds</span>
                            </a>
                        </div>
                    </div>
                </div>

                {{-- ===================== --}}
                {{-- INSIGHTS --}}
                {{-- ===================== --}}
                <div class="menu-item pt-5">
                    <div class="menu-content">
                        <span class="menu-heading fw-bold text-uppercase fs-7">Insights</span>
                    </div>
                </div>

                {{-- Reports --}}
                <div data-kt-menu-trigger="click"
                    class="menu-item menu-accordion {{ request()->routeIs('admin.reports.*') ? 'here show' : '' }}">
                    <span class="menu-link">
                        <span class="menu-icon">
                            <i class="ki-outline ki-chart-line fs-2"></i>
                        </span>
                        <span class="menu-title">Reports</span>
                        <span class="menu-arrow"></span>
                    </span>
                    <div class="menu-sub menu-sub-accordion">
                        <div class="menu-item">
                            <a class="menu-link {{ request()->routeIs('admin.reports.revenue') ? 'active' : '' }}"
                                href="{{ route('admin.reports.revenue') }}">
                                <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                <span class="menu-title">Revenue</span>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a class="menu-link {{ request()->routeIs('admin.reports.nurse-activity') ? 'active' : '' }}"
                                href="{{ route('admin.reports.nurse-activity') }}">
                                <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                <span class="menu-title">Nurse Activity</span>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a class="menu-link {{ request()->routeIs('admin.reports.requests') ? 'active' : '' }}"
                                href="{{ route('admin.reports.requests') }}">
                                <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                <span class="menu-title">Request Reports</span>
                            </a>
                        </div>
                    </div>
                </div>

                {{-- ===================== --}}
                {{-- SYSTEM --}}
                {{-- ===================== --}}
                <div class="menu-item pt-5">
                    <div class="menu-content">
                        <span class="menu-heading fw-bold text-uppercase fs-7">System</span>
                    </div>
                </div>

                {{-- System --}}
                <div data-kt-menu-trigger="click"
                    class="menu-item menu-accordion {{ request()->routeIs('admin.system.*') ? 'here show' : '' }}">
                    <span class="menu-link">
                        <span class="menu-icon">
                            <i class="ki-outline ki-setting-2 fs-2"></i>
                        </span>
                        <span class="menu-title">System</span>
                        <span class="menu-arrow"></span>
                    </span>
                    <div class="menu-sub menu-sub-accordion">
                        <div class="menu-item">
                            <a class="menu-link {{ request()->routeIs('admin.system.error-logs') ? 'active' : '' }}"
                                href="{{ route('admin.system.error-logs') }}">
                                <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                <span class="menu-title">Error Logs</span>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a class="menu-link {{ request()->routeIs('admin.system.failed-jobs') ? 'active' : '' }}"
                                href="{{ route('admin.system.failed-jobs') }}">
                                <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                <span class="menu-title">Failed Jobs</span>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a class="menu-link {{ request()->routeIs('admin.system.queue') ? 'active' : '' }}"
                                href="{{ route('admin.system.queue') }}">
                                <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                <span class="menu-title">Queue Monitor</span>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a class="menu-link {{ request()->routeIs('admin.system.backups') ? 'active' : '' }}"
                                href="{{ route('admin.system.backups') }}">
                                <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                <span class="menu-title">Backups</span>
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Settings --}}
                <div data-kt-menu-trigger="click"
                    class="menu-item menu-accordion {{ request()->routeIs('admin.settings.*') ? 'here show' : '' }}">
                    <span class="menu-link">
                        <span class="menu-icon">
                            <i class="ki-outline ki-gear fs-2"></i>
                        </span>
                        <span class="menu-title">Settings</span>
                        <span class="menu-arrow"></span>
                    </span>
                    <div class="menu-sub menu-sub-accordion">
                        <div class="menu-item">
                            <a class="menu-link {{ request()->routeIs('admin.settings.general') ? 'active' : '' }}"
                                href="{{ route('admin.settings.general') }}">
                                <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                <span class="menu-title">General</span>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a class="menu-link {{ request()->routeIs('admin.settings.app') ? 'active' : '' }}"
                                href="{{ route('admin.settings.app') }}">
                                <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                <span class="menu-title">App Config</span>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a class="menu-link {{ request()->routeIs('admin.settings.roles') ? 'active' : '' }}"
                                href="{{ route('admin.settings.roles') }}">
                                <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                <span class="menu-title">Roles &amp; Permissions</span>
                            </a>
                        </div>
                    </div>
                </div>

            </div>
            <!--end::Menu-->
        </div>
    </div>
    <!--end::Sidebar menu-->

    @include('admin.layouts.partials._sidebar_footer')

</div>
<!--end::Sidebar-->