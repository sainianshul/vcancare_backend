@include('admin.layouts.partials._header')

<body id="kt_app_body" data-kt-app-header-fixed="true" data-kt-app-header-fixed-mobile="true"
    data-kt-app-sidebar-enabled="true" data-kt-app-sidebar-fixed="true" data-kt-app-sidebar-push-header="true"
    data-kt-app-sidebar-push-toolbar="true" data-kt-app-sidebar-push-footer="true" class="app-default">

    <!--begin::Page loading overlay — matches theme bg, hides white flash-->
    <div id="page-loader">
        <div class="spinner"></div>
    </div>
    <!--end::Page loading overlay-->

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

    <!--begin::Fast body reveal + page-loader dismissal-->
    <script>
        // Reveal body immediately once DOM + CSS is ready
        document.body.classList.add('loaded');

        // Fade out the page loader
        var loader = document.getElementById('page-loader');
        if (loader) {
            loader.style.opacity = '0';
            setTimeout(function(){ loader.style.display = 'none'; }, 200);
        }

        // Show loader on next navigation (masks white flash)
        window.addEventListener('beforeunload', function() {
            var l = document.getElementById('page-loader');
            if (l) { l.style.display = 'flex'; l.style.opacity = '1'; }
        });
    </script>
    <!--end::Fast body reveal-->

</body>

</html>