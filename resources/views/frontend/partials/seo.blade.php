@php
    $pageKey = $pageKey ?? 'home';
    $pageMeta = config("xcodrix.pages.{$pageKey}", config('xcodrix.pages.home'));
    $title = trim($__env->yieldContent('title')) ?: $pageMeta['title'];
    $description = trim($__env->yieldContent('meta_description')) ?: $pageMeta['description'];
    $canonical = trim($__env->yieldContent('canonical')) ?: url(request()->path() === '/' ? '/' : '/' . request()->path());
    $ogImage = asset('images/xcodrix-logo.png');
@endphp

<title>{{ $title }}</title>
<meta name="description" content="{{ $description }}">
<link rel="canonical" href="{{ $canonical }}">
<meta name="robots" content="index, follow">
<meta name="author" content="XCodrix">

<meta property="og:type" content="website">
<meta property="og:url" content="{{ $canonical }}">
<meta property="og:title" content="{{ $title }}">
<meta property="og:description" content="{{ $description }}">
<meta property="og:image" content="{{ $ogImage }}">
<meta property="og:site_name" content="XCodrix">

<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $title }}">
<meta name="twitter:description" content="{{ $description }}">
<meta name="twitter:image" content="{{ $ogImage }}">

<link rel="icon" type="image/png" href="{{ asset('images/xcodrix-logo.png') }}">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
