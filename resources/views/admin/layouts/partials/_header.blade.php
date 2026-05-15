<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<!--begin::Head-->

<head>
    <title>@yield('title', 'VCancares Admin')</title>
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

    <!--begin::Critical inline styles — renders INSTANTLY, zero network wait-->
    <style>
        /* Page loading overlay — hides white flash between navigations */
        #page-loader{position:fixed;top:0;left:0;right:0;bottom:0;z-index:10000;display:flex;align-items:center;justify-content:center;transition:opacity .2s ease}
        [data-bs-theme="light"] #page-loader{background:#f5f8fa}
        [data-bs-theme="dark"] #page-loader{background:#1e1e2d}
        #page-loader .spinner{width:28px;height:28px;border:3px solid rgba(114,57,234,.15);border-top-color:#7239ea;border-radius:50%;animation:spin .6s linear infinite}
        @keyframes spin{to{transform:rotate(360deg)}}
        /* Prevent FOUC — hide body until CSS loads */
        body{opacity:0;transition:opacity .15s ease}
        body.loaded{opacity:1}
        /* Immediate background color */
        html,body{background:#f5f8fa;margin:0;padding:0}
        [data-bs-theme="dark"],[data-bs-theme="dark"] body{background:#1e1e2d}
    </style>
    <!--end::Critical inline styles-->

    <!--begin::Theme mode setup — runs BEFORE any render-->
    <script>
        (function(){
            var d=document.documentElement;
            var mode=localStorage.getItem('data-bs-theme')||'light';
            if(mode==='system') mode=window.matchMedia('(prefers-color-scheme:dark)').matches?'dark':'light';
            d.setAttribute('data-bs-theme',mode);
        })();
    </script>
    <!--end::Theme mode setup-->

    <!--begin::Fonts (non-render-blocking)-->
    <link rel="preconnect" href="https://fonts.googleapis.com" crossorigin />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link rel="preload" as="style"
        href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700&display=swap" />
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

    <!--begin::Global Stylesheets Bundle (CSS only — keeps the premium Metronic look)-->
    <link href="{{ asset('theme/dist/assets/plugins/global/plugins.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('theme/dist/assets/css/style.bundle.css') }}" rel="stylesheet" type="text/css" />
    <!--end::Global Stylesheets Bundle-->

    <!--begin::Custom admin styles (overrides theme, browser-cached)-->
    <link href="{{ asset('css/admin-custom.css') }}?v=4" rel="stylesheet" type="text/css" />
    <!--end::Custom admin styles-->

    {{-- DataTables CSS — only on table pages --}}
    @stack('datatables_css')

    {{-- Page-specific styles --}}
    @stack('styles')

</head>
<!--end::Head-->
<!--begin::Body-->