<!--begin::Footer-->
<div class="app-sidebar-footer d-flex align-items-center px-8 pb-10" id="kt_app_sidebar_footer">
    <div class="">
        <!--begin::User info-->
        <div class="d-flex align-items-center" data-kt-menu-trigger="{default: 'click', lg: 'hover'}"
            data-kt-menu-overflow="true" data-kt-menu-placement="top-start">
            <div class="d-flex flex-center cursor-pointer symbol symbol-circle symbol-40px">
                <span class="symbol-label bg-light-primary text-primary fw-bold fs-6">
                    {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                </span>
            </div>
            <div class="d-flex flex-column align-items-start justify-content-center ms-3">
                <span class="text-gray-500 fs-8 fw-semibold">Hello</span>
                <span class="text-gray-800 fs-7 fw-bold">{{ auth()->user()->name ?? 'Admin' }}</span>
            </div>
        </div>
        <!--end::User info-->

        <!--begin::User account menu-->
        <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg menu-state-color fw-semibold py-4 fs-6 w-275px"
            data-kt-menu="true">
            <!--begin::Menu item-->
            <div class="menu-item px-3">
                <div class="menu-content d-flex align-items-center px-3">
                    <div class="d-flex flex-center symbol symbol-circle symbol-50px me-5">
                        <span class="symbol-label bg-light-primary text-primary fw-bold fs-3">
                            {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                        </span>
                    </div>
                    <div class="d-flex flex-column">
                        <div class="fw-bold d-flex align-items-center fs-5">
                            {{ auth()->user()->name ?? 'Admin' }}
                        </div>
                        <span class="fw-semibold text-muted fs-7">{{ auth()->user()->email ?? '' }}</span>
                    </div>
                </div>
            </div>
            <!--end::Menu item-->

            <div class="separator my-2"></div>

            <!--begin::Theme mode-->
            <div class="menu-item px-5" data-kt-menu-trigger="{default: 'click', lg: 'hover'}"
                data-kt-menu-placement="left-start" data-kt-menu-offset="-15px, 0">
                <a href="#" class="menu-link px-5">
                    <span class="menu-title position-relative">Mode
                        <span class="ms-5 position-absolute translate-middle-y top-50 end-0">
                            <i class="ki-outline ki-night-day theme-light-show fs-2"></i>
                            <i class="ki-outline ki-moon theme-dark-show fs-2"></i>
                        </span></span>
                </a>
                <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-title-gray-700 menu-icon-gray-500 menu-active-bg menu-state-color fw-semibold py-4 fs-base w-150px"
                    data-kt-menu="true" data-kt-element="theme-mode-menu">
                    <div class="menu-item px-3 my-0">
                        <a href="#" class="menu-link px-3 py-2" data-kt-element="mode" data-kt-value="light">
                            <span class="menu-icon" data-kt-element="icon">
                                <i class="ki-outline ki-night-day fs-2"></i>
                            </span>
                            <span class="menu-title">Light</span>
                        </a>
                    </div>
                    <div class="menu-item px-3 my-0">
                        <a href="#" class="menu-link px-3 py-2" data-kt-element="mode" data-kt-value="dark">
                            <span class="menu-icon" data-kt-element="icon">
                                <i class="ki-outline ki-moon fs-2"></i>
                            </span>
                            <span class="menu-title">Dark</span>
                        </a>
                    </div>
                    <div class="menu-item px-3 my-0">
                        <a href="#" class="menu-link px-3 py-2" data-kt-element="mode" data-kt-value="system">
                            <span class="menu-icon" data-kt-element="icon">
                                <i class="ki-outline ki-screen fs-2"></i>
                            </span>
                            <span class="menu-title">System</span>
                        </a>
                    </div>
                </div>
            </div>
            <!--end::Theme mode-->

            <div class="separator my-2"></div>

            <!--begin::Sign out-->
            <div class="menu-item px-5">
                <form method="POST" action="{{ route('admin.logout') }}" id="logout-form">
                    @csrf
                    <a href="#" class="menu-link px-5"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        Sign Out
                    </a>
                </form>
            </div>
            <!--end::Sign out-->
        </div>
        <!--end::User account menu-->
    </div>
</div>
<!--end::Footer-->