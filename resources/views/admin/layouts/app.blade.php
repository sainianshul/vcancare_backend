@include('admin.layouts.partials._header')

<body id="kt_app_body" data-kt-app-header-fixed="true" data-kt-app-header-fixed-mobile="true"
    data-kt-app-sidebar-enabled="true" data-kt-app-sidebar-fixed="true" data-kt-app-sidebar-push-header="true"
    data-kt-app-sidebar-push-toolbar="true" data-kt-app-sidebar-push-footer="true" class="app-default">

    <style>
        /* Force a clean, neutral light-gray background instead of Metronic's default purplish tint */
        body, 
        #kt_app_body,
        #kt_app_root, 
        #kt_app_page, 
        #kt_app_wrapper, 
        .app-default,
        .app-root,
        .app-page,
        .app-wrapper,
        #kt_app_main,
        .app-main,
        #kt_app_content,
        .app-content {
            background-color: #ffffff !important;
            background: #ffffff !important;
        }

        /* Ensure cards remain pure white for contrast */
        .card:not([class*="bg-"]) {
            background-color: #ffffff !important;
            background: #ffffff !important;
        }

        /* Increase sidebar text font size */
        #kt_app_sidebar .menu-item .menu-title {
            font-size: 1.05rem !important;
        }
        
        #kt_app_sidebar .menu-item .menu-heading {
            font-size: 0.9rem !important;
        }
    </style>

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