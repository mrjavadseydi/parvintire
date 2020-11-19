@extends(includeTemplate('master'))
@section('title', getOption('site-title'))
@section('keywords', siteKeywords())
@section('description', siteDescription())
@section('ogType', 'website')
@section('ogTitle', getOption('site-title'))
@section('ogDescription', siteDescription())
<?php
$logo = getOptionImage('digishopLogo');
?>
@section('ogImage', $logo['src'])
<?php
$personalShop = 1;
$getPersonalShop = getOption('personalShop');
if (!empty($getPersonalShop))
    $personalShop = $getPersonalShop;
?>
@if($personalShop == 1)
    @section('header', '')
@endif
@section('head-content')
    @if(view()->exists('admin.seo.pages.home'))
        @include('admin.seo.pages.home')
    @endif
@endsection
@section('content')
    <h1 class="d-none">{{ getOption('site-title') }}</h1>
    @include(includeTemplate('pages.home'.$personalShop))
@endsection
