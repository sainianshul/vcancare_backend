<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<!--begin::Head-->
<head>
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

    <!--begin::Fonts (non-render-blocking)-->
    <link rel="preconnect" href="https://fonts.googleapis.com" crossorigin />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link rel="preload" as="style" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700&display=swap" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700&display=swap"
        media="print" onload="this.media='all'" />
    <noscript>
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700&display=swap" />
    </noscript>
    <!--end::Fonts-->

    <!--begin::Preload icon font-->
    <link rel="preload" href="{{ asset('theme/dist/assets/plugins/global/fonts/keenicons/keenicons-outline.woff') }}"
        as="font" type="font/woff" crossorigin />
    <!--end::Preload icon font-->

    <!--begin::Global Stylesheets Bundle-->
    <link href="{{ asset('theme/dist/assets/plugins/global/plugins.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('theme/dist/assets/css/style.bundle.css') }}" rel="stylesheet" type="text/css" />
    <!--end::Global Stylesheets Bundle-->

    <!--begin::Custom admin styles (browser cached static file)-->
    <link href="{{ asset('css/admin-custom.css') }}?v=1" rel="stylesheet" type="text/css" />
    <!--end::Custom admin styles-->

    {{-- DataTables CSS — global bundle ke BAAD, sirf table pages pe load hoga --}}
    @stack('datatables_css')

    {{-- Page-specific styles --}}
    @stack('styles')




</head>
<!--end::Head-->
<!--begin::Body-->