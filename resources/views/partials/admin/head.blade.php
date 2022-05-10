@php
    $logo=asset(Storage::url('uploads/logo/'));
   $favicon=\App\Models\Utility::getValByName('company_favicon');

@endphp
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="{{env('APP_NAME')}} - Online Whatsapp Store Builder">

    <title>@yield('page-title') - {{(\App\Models\Utility::getValByName('title_text')) ? \App\Models\Utility::getValByName('title_text') : config('app.name', 'WhatsStore')}}</title>
    <link rel="icon" href="{{$logo.'/'.(isset($favicon) && !empty($favicon)?$favicon:'favicon.png')}}" type="image" sizes="16x16">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="{{ asset('assets/libs/@fortawesome/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/libs/fullcalendar/dist/fullcalendar.min.css')}}">

    <link rel="stylesheet" href="{{ asset('assets/libs/animate.css/animate.min.css')}}" id="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/libs/select2/dist/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{asset('assets/libs/@fancyapps/fancybox/dist/jquery.fancybox.min.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/css/site.css')}}" id="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/jquery.dataTables.min.css')}}">

    <link rel="stylesheet" href="{{ asset('assets/css/site-'.Auth::user()->mode.'.css') }}" id="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/custom.css')}}" id="stylesheet')}}">
    <script type="text/javascript" src="{{ asset('assets/js/jquery.min.js')}}"></script>

    @if(env('SITE_RTL')=='on')
        <link rel="stylesheet" href="{{ asset('css/bootstrap-rtl.css') }}">
    @endif

    @stack('css-page')
</head>
