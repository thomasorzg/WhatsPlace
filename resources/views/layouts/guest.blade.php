<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{env('SITE_RTL') == 'on'?'rtl':''}}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="{{env('APP_NAME')}} - Online Whatsapp Store Builder">


    <title>@yield('page-title') - {{(\App\Models\Utility::getValByName('title_text')) ? \App\Models\Utility::getValByName('title_text') : config('app.name', 'WhatsStore')}}</title>

    <link rel="icon" href="{{asset(Storage::url('uploads/logo/')).'/favicon.png'}}" type="image/png">
    <link rel="stylesheet" href="{{ asset('assets/libs/@fortawesome/fontawesome-free/css/all.min.css')}} ">
    <link rel="stylesheet" href="{{ asset('assets/css/site-light.css')}}" id="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/libs/animate.css/animate.min.css')}}" id="stylesheet">

    <link rel="stylesheet" href="{{ asset('assets/css/custom.css')}}" id="stylesheet">
    @if(env('SITE_RTL')=='on')
        <link rel="stylesheet" href="{{ asset('css/bootstrap-rtl.css') }}">
    @endif
</head>
<body class="application application-offset">
<div class="container-fluid container-application">
    <div class="main-content position-relative">
        <div class="page-content">
            <div class="min-vh-100 py-5 d-flex align-items-center">
                @yield('content')
            </div>
        </div>
    </div>
</div>
</body>
<script src="{{ asset('assets/js/site.core.js')}}"></script>
<script src="{{ asset('assets/js/site.js')}}"></script>
<script src="{{ asset('assets/js/demo.js')}}"></script>
<script src="{{ asset('assets/libs/bootstrap-notify/bootstrap-notify.min.js')}}"></script>
<script type="text/javascript" src="{{ asset('assets/js/custom.js')}}"></script>
@if(Session::has('success'))
    <script>
        show_toastr('{{__('Success')}}', '{!! session('success') !!}', 'success');
    </script>
    {{ Session::forget('success') }}
@endif
@if(Session::has('error'))
    <script>
        show_toastr('{{__('Error')}}', '{!! session('error') !!}', 'error');
    </script>
    {{ Session::forget('error') }}
@endif
@stack('custom-scripts')
</html>
