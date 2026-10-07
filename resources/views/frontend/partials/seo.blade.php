@php
    $pageKey = $pageKey ?? 'home';
    $pageMeta = config("xcodrix.pages.{$pageKey}", config('xcodrix.pages.home'));
    $title = trim($__env->yieldContent('title')) ?: $pageMeta['title'];
    $description = trim($__env->yieldContent('meta_description')) ?: $pageMeta['description'];
    $canonical = trim($__env->yieldContent('canonical')) ?: (request()->path() === '/' ? config('app.url') : config('app.url') . '/' . request()->path());
    $ogImage = asset('images/xcodrix-og.png');
@endphp

<title>{!! $title !!}</title>
<meta name="description" content="{{ $description }}">
<link rel="canonical" href="{{ $canonical }}">
<meta name="robots" content="index, follow">
<meta name="author" content="{{ $siteSettings->site_name }}">

<meta property="og:type" content="website">
<meta property="og:url" content="{{ $canonical }}">
<meta property="og:title" content="{!! $title !!}">
<meta property="og:description" content="{{ $description }}">
<meta property="og:image" content="{{ $ogImage }}">
<meta property="og:site_name" content="{{ $siteSettings->site_name }}">

<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{!! $title !!}">
<meta name="twitter:description" content="{{ $description }}">
<meta name="twitter:image" content="{{ $ogImage }}">

<link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
<link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/favicon-32.png') }}">
<link rel="icon" type="image/png" sizes="180x180" href="{{ asset('images/favicon-180.png') }}">
<link rel="icon" type="image/png" sizes="512x512" href="{{ asset('images/favicon-512.png') }}">
<link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/favicon-180.png') }}">
