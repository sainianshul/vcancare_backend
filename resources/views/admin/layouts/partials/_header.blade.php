<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<!--begin::Head-->

<head>
    <base href="" />
    <title>@yield('title', 'VCancares')</title>
    <meta charset="utf-8" />
    <meta name="description" content="A Nurse Scheduling Software" />
    <meta name="keywords" content="Nurse Scheduling Software" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta property="og:locale" content="en_US" />
    <meta property="og:type" content="article" />
    <meta property="og:title" content="VCancares" />
    <meta property="og:site_name" content="VCancares" />
    <link rel="shortcut icon" href="{{ asset('theme/dist/assets/media/logos/favicon.ico') }}" />
    <!--begin::Fonts(mandatory for all pages)-->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
    <!--end::Fonts-->
    <!--begin::Global Stylesheets Bundle(mandatory for all pages)-->
    <link href="{{ asset('theme/dist/assets/plugins/global/plugins.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('theme/dist/assets/css/style.bundle.css') }}" rel="stylesheet" type="text/css" />
    <!--end::Global Stylesheets Bundle-->

    {{-- ====================================================== --}}
    {{-- Premium Theme Overrides — Light & Dark Mode Support    --}}
    {{-- ====================================================== --}}
    <style>
        /* ============================================
           LIGHT MODE — Crisp, Dark, Premium Text
           ============================================ */
        [data-bs-theme="light"] {
            --kt-app-sidebar-menu-link-color: #1b1b29;
            --kt-app-sidebar-menu-icon-color: #3f4254;
            --kt-app-sidebar-menu-heading-color: #3f4254;
        }

        /* --- Sidebar (Light) --- */
        [data-bs-theme="light"] .app-sidebar .menu-title {
            color: #1b1b29 !important;
            font-weight: 500 !important;
            font-size: 13.5px !important;
        }
        [data-bs-theme="light"] .app-sidebar .menu-icon i,
        [data-bs-theme="light"] .app-sidebar .menu-icon .ki-outline {
            color: #5e6278 !important;
            font-size: 1.35rem !important;
        }
        [data-bs-theme="light"] .app-sidebar .menu-heading {
            color: #a1a5b7 !important;
            font-weight: 600 !important;
            font-size: 0.7rem !important;
            letter-spacing: 0.8px;
        }
        [data-bs-theme="light"] .app-sidebar .menu-arrow {
            color: #b5b5c3 !important;
        }
        [data-bs-theme="light"] .app-sidebar .bullet-dot {
            background-color: #b5b5c3 !important;
        }
        [data-bs-theme="light"] .app-sidebar .menu-sub .menu-title {
            font-size: 13px !important;
            font-weight: 400 !important;
            color: #3f4254 !important;
        }

        /* --- Global Text Utility Classes (Light) --- */
        [data-bs-theme="light"] .text-gray-400 { color: #7e8299 !important; }
        [data-bs-theme="light"] .text-gray-500 { color: #5e6278 !important; }
        [data-bs-theme="light"] .text-gray-600 { color: #3f4254 !important; }
        [data-bs-theme="light"] .text-gray-700 { color: #1e1e2d !important; }
        [data-bs-theme="light"] .text-gray-800 { color: #181c32 !important; }
        [data-bs-theme="light"] .text-gray-900 { color: #0d0d12 !important; }
        [data-bs-theme="light"] .text-muted     { color: #5e6278 !important; }

        /* --- Headings & Body Text (Light) --- */
        [data-bs-theme="light"] h1, [data-bs-theme="light"] h2,
        [data-bs-theme="light"] h3, [data-bs-theme="light"] h4,
        [data-bs-theme="light"] h5, [data-bs-theme="light"] h6 {
            color: #181c32;
        }
        [data-bs-theme="light"] body { color: #1e1e2d; }

        /* --- Cards (Light) --- */
        [data-bs-theme="light"] .card .card-title,
        [data-bs-theme="light"] .card .card-header h3,
        [data-bs-theme="light"] .card-label { color: #181c32 !important; }

        /* --- Navbar (Light) --- */
        [data-bs-theme="light"] .app-header .page-heading,
        [data-bs-theme="light"] .app-header .page-title { color: #181c32 !important; }

        /* --- Tables (Light) --- */
        [data-bs-theme="light"] .table th { color: #1e1e2d !important; font-weight: 600 !important; }
        [data-bs-theme="light"] .table td { color: #3f4254 !important; }

        /* --- Forms (Light) --- */
        [data-bs-theme="light"] .form-label { color: #1e1e2d !important; font-weight: 500 !important; }
        [data-bs-theme="light"] .form-control { color: #1e1e2d !important; }
        [data-bs-theme="light"] .form-select  { color: #1e1e2d !important; }


        /* ============================================
           DARK MODE — Bright, Readable, Premium Text
           ============================================ */
        [data-bs-theme="dark"] {
            --kt-app-sidebar-menu-link-color: #cdcfe4;
            --kt-app-sidebar-menu-icon-color: #9a9cae;
            --kt-app-sidebar-menu-heading-color: #787a96;
        }

        /* --- Sidebar (Dark) --- */
        [data-bs-theme="dark"] .app-sidebar .menu-title {
            color: #cdcfe4 !important;
            font-weight: 500 !important;
            font-size: 13.5px !important;
        }
        [data-bs-theme="dark"] .app-sidebar .menu-icon i,
        [data-bs-theme="dark"] .app-sidebar .menu-icon .ki-outline {
            color: #9a9cae !important;
            font-size: 1.35rem !important;
        }
        [data-bs-theme="dark"] .app-sidebar .menu-heading {
            color: #565674 !important;
            font-weight: 600 !important;
            font-size: 0.7rem !important;
            letter-spacing: 0.8px;
        }
        [data-bs-theme="dark"] .app-sidebar .menu-arrow {
            color: #565674 !important;
        }
        [data-bs-theme="dark"] .app-sidebar .bullet-dot {
            background-color: #565674 !important;
        }
        [data-bs-theme="dark"] .app-sidebar .menu-sub .menu-title {
            font-size: 13px !important;
            font-weight: 400 !important;
            color: #b5b5c3 !important;
        }

        /* --- Global Text Utility Classes (Dark) --- */
        [data-bs-theme="dark"] .text-gray-400 { color: #9a9cae !important; }
        [data-bs-theme="dark"] .text-gray-500 { color: #a1a5b7 !important; }
        [data-bs-theme="dark"] .text-gray-600 { color: #b5b5c3 !important; }
        [data-bs-theme="dark"] .text-gray-700 { color: #cdcfe4 !important; }
        [data-bs-theme="dark"] .text-gray-800 { color: #e4e6ef !important; }
        [data-bs-theme="dark"] .text-gray-900 { color: #f5f5fa !important; }
        [data-bs-theme="dark"] .text-muted     { color: #9a9cae !important; }

        /* --- Headings & Body Text (Dark) --- */
        [data-bs-theme="dark"] h1, [data-bs-theme="dark"] h2,
        [data-bs-theme="dark"] h3, [data-bs-theme="dark"] h4,
        [data-bs-theme="dark"] h5, [data-bs-theme="dark"] h6 {
            color: #e4e6ef;
        }
        [data-bs-theme="dark"] body { color: #cdcfe4; }

        /* --- Cards (Dark) --- */
        [data-bs-theme="dark"] .card .card-title,
        [data-bs-theme="dark"] .card .card-header h3,
        [data-bs-theme="dark"] .card-label { color: #e4e6ef !important; }

        /* --- Navbar (Dark) --- */
        [data-bs-theme="dark"] .app-header .page-heading,
        [data-bs-theme="dark"] .app-header .page-title { color: #e4e6ef !important; }

        /* --- Tables (Dark) --- */
        [data-bs-theme="dark"] .table th { color: #e4e6ef !important; font-weight: 600 !important; }
        [data-bs-theme="dark"] .table td { color: #cdcfe4 !important; }

        /* --- Forms (Dark) --- */
        [data-bs-theme="dark"] .form-label { color: #cdcfe4 !important; font-weight: 500 !important; }
        [data-bs-theme="dark"] .form-control { color: #cdcfe4 !important; }
        [data-bs-theme="dark"] .form-select  { color: #cdcfe4 !important; }


        /* ============================================
           SHARED — Hover/Active (Purple Theme Color)
           ============================================ */
        .app-sidebar .menu-link {
            transition: all 0.2s ease;
        }
        .app-sidebar .menu-link:hover .menu-title,
        .app-sidebar .menu-link.active .menu-title {
            color: #7239ea !important;
        }
        .app-sidebar .menu-link:hover .menu-icon i,
        .app-sidebar .menu-link:hover .menu-icon .ki-outline,
        .app-sidebar .menu-link.active .menu-icon i,
        .app-sidebar .menu-link.active .menu-icon .ki-outline {
            color: #7239ea !important;
        }
        .app-sidebar .menu-link.active .bullet-dot {
            background-color: #7239ea !important;
        }
        .app-sidebar .menu-link:hover .menu-arrow,
        .app-sidebar .menu-link.active .menu-arrow {
            color: #7239ea !important;
        }
        .app-sidebar .menu-sub .menu-link:hover .menu-title,
        .app-sidebar .menu-sub .menu-link.active .menu-title {
            color: #7239ea !important;
        }

        /* Active menu item subtle background */
        .app-sidebar .menu-link.active {
            background-color: rgba(114, 57, 234, 0.06) !important;
        }
        .app-sidebar .menu-link:hover {
            background-color: rgba(114, 57, 234, 0.04) !important;
        }


        /* ============================================
           PREMIUM LAYOUT — No Borders, Use Shadows
           ============================================ */

        /* Remove sidebar right border, add sharp shadow */
        .app-sidebar {
            border-right: 0 !important;
            box-shadow: 1px 0 6px rgba(0, 0, 0, 0.06) !important;
        }
        [data-bs-theme="dark"] .app-sidebar {
            box-shadow: 1px 0 8px rgba(0, 0, 0, 0.2) !important;
        }

        /* Remove header bottom border, add sharp shadow */
        .app-header {
            border-bottom: 0 !important;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.06) !important;
        }
        [data-bs-theme="dark"] .app-header {
            box-shadow: 0 1px 6px rgba(0, 0, 0, 0.15) !important;
        }

        /* Remove any separator borders inside header */
        .app-header .separator,
        .app-header [class*="border-bottom"] {
            border-bottom: 0 !important;
        }

        /* Card — sharp clean shadow */
        .card {
            border: 0 !important;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.05) !important;
        }
        [data-bs-theme="dark"] .card {
            box-shadow: 0 1px 6px rgba(0, 0, 0, 0.15) !important;
        }
    </style>

    {{-- Page-specific styles --}}
    @stack('styles')
    <script>// Frame-busting to prevent site from being loaded within a frame without permission (click-jacking) if (window.top != window.self) { window.top.location.replace(window.self.location.href); }</script>
</head>
<!--end::Head-->
<!--begin::Body-->