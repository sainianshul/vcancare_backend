@include('admin.layouts.partials._header')

<body id="kt_app_body" data-kt-app-header-fixed="true" data-kt-app-header-fixed-mobile="true"
    data-kt-app-sidebar-enabled="true" data-kt-app-sidebar-fixed="true" data-kt-app-sidebar-push-header="true"
    data-kt-app-sidebar-push-toolbar="true" data-kt-app-sidebar-push-footer="true" class="app-default">

    <!--begin::Page Loader Overlay (must be FIRST element in body)-->
    <div id="page-loader">
        <span class="spinner-border text-primary" style="width:2rem;height:2rem;" role="status">
            <span class="visually-hidden">Loading...</span>
        </span>
    </div>
    <!--end::Page Loader Overlay-->

    <!--begin::Theme mode setup on page load-->
    <script>
        (function(){
            var d = document.documentElement;
            var mode = localStorage.getItem('data-bs-theme');
            if (!mode) mode = 'light';
            if (mode === 'system') mode = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
            d.setAttribute('data-bs-theme', mode);
            // Match loader bg to theme immediately
            var loader = document.getElementById('page-loader');
            if (loader && mode === 'dark') loader.style.background = '#1e1e2d';
        })();
    </script>
    <!--end::Theme mode setup-->

    <!--begin::App-->
    <div class="d-flex flex-column flex-root app-root" id="kt_app_root">

        <!--begin::Page-->
        <div class="app-page flex-column flex-column-fluid" id="kt_app_page">

            {{-- Top Navbar --}}
            @include('admin.layouts.partials._navbar')

            <!--begin::Wrapper-->
            <div class="app-wrapper flex-column flex-row-fluid" id="kt_app_wrapper">

                {{-- Sidebar --}}
                @include('admin.layouts.partials._sidebar')

                {{-- Main Content Area --}}
                <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
                    <div class="d-flex flex-column flex-column-fluid">
                        <div id="kt_app_content" class="app-content flex-column-fluid">
                            <div id="kt_app_content_container" class="app-container container-xxl">
                                @yield('content')
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <!--end::Wrapper-->

        </div>
        <!--end::Page-->

    </div>
    <!--end::App-->

    {{-- Scripts --}}
    @include('admin.layouts.partials._scripts')

</body>

</html>