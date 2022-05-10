@extends('layouts.admin')
@php
    $logo=asset(Storage::url('uploads/logo/'));
    $company_logo=\App\Models\Utility::getValByName('company_logo');
    $company_favicon=\App\Models\Utility::getValByName('company_favicon');
    $store_logo=asset(Storage::url('uploads/store_logo/'));
    $lang=\App\Models\Utility::getValByName('default_language');
    if(Auth::user()->type == 'Owner')
    {
        $store_lang=$store_settings->lang;
    }
@endphp
@section('page-title')
    @if(Auth::user()->type == 'super admin')
        {{__('Setting')}}
    @else
        {{__('Store Setting')}}
    @endif
@endsection
@section('title')
    <div class="d-inline-block">
        @if(Auth::user()->type == 'super admin')
            <h5 class="h4 d-inline-block font-weight-bold mb-0 text-white">{{__('Setting')}}</h5>
        @else
            <h5 class="h4 d-inline-block font-weight-bold mb-0 text-white">{{__('Store Setting')}}</h5>
        @endif
    </div>
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{__('Home')}}</a></li>
@endsection
@section('action-btn')
@endsection
@section('filter')
@endsection
@push('css-page')
    <link rel="stylesheet" href="{{asset('assets/libs/summernote/summernote-bs4.css')}}">
    <style>
        hr {
            margin: 8px;
        }
    </style>
@endpush
@push('script-page')
    <script src="{{asset('assets/libs/summernote/summernote-bs4.js')}}"></script>
    <script>
        // start for tab selection
        $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
            // save current clicked tab in local storage does not work in Stacksnippets
            localStorage.setItem('lastActiveTab', $(this).attr('href'));
        });
        //get last active tab from local storage
        var lastTab = localStorage.getItem('lastActiveTab');
        if (lastTab) {
            $('[href="' + lastTab + '"]').tab('show');
        }
        //end for tab selection
    </script>
    <script type="text/javascript">
        @can('On-Off Email Template')
        $(document).on("click", ".email-template-checkbox", function () {
            var chbox = $(this);
            $.ajax({
                url: chbox.attr('data-url'),
                data: {_token: $('meta[name="csrf-token"]').attr('content'), status: chbox.val()},
                type: 'PUT',
                success: function (response) {
                    if (response.is_success) {
                        show_toastr('Success', response.success, 'success');
                        if (chbox.val() == 1) {
                            $('#' + chbox.attr('id')).val(0);
                        } else {
                            $('#' + chbox.attr('id')).val(1);
                        }
                    } else {
                        show_toastr('Error', response.error, 'error');
                    }
                },
                error: function (response) {
                    response = response.responseJSON;
                    if (response.is_success) {
                        show_toastr('Error', response.error, 'error');
                    } else {
                        show_toastr('Error', response, 'error');
                    }
                }
            })
        });
        @endcan
    </script>
@endpush
@section('content')
    <div class="mt-4">
        <div class="card">
            <ul class="nav nav-tabs nav-overflow profile-tab-list" role="tablist">
                @if(Auth::user()->type == 'Owner')
                    <li class="nav-item ml-4">
                        <a href="#store_setting" id="store_setting_tab" class="nav-link active" data-toggle="tab" role="tab" aria-controls="home" aria-selected="true">
                            <i class="fas fa-store mr-2"></i>
                            {{__('Store Settings')}}
                        </a>
                    </li>
                    <li class="nav-item ml-4">
                        <a href="#store_theme_setting" id="theme_setting_tab" class="nav-link" data-toggle="tab" role="tab" aria-controls="home" aria-selected="true">
                            <i class="fas fa-cog mr-2"></i>{{__('Store Theme Setting')}}
                        </a>
                    </li>
                    <li class="nav-item ml-4">
                        <a href="#store_site_setting" id="site_setting_tab" class="nav-link" data-toggle="tab" role="tab" aria-controls="home" aria-selected="true">
                            <i class="fas fa-cog mr-2"></i>{{__('Site Setting')}}
                        </a>
                    </li>
                    <li class="nav-item ml-4">
                        <a href="#store_payment-setting" id="payment-setting_tab" class="nav-link" data-toggle="tab" role="tab" aria-controls="home" aria-selected="true">
                            <i class="fab fa-cc-visa mr-2"></i>{{__('Store Payment')}}
                        </a>
                    </li>
                    <li class="nav-item ml-4">
                        <a href="#store_email_setting" id="email_store_setting" class="nav-link" data-toggle="tab" role="tab" aria-controls="home" aria-selected="true">
                            <i class="fas fa-envelope mr-2"></i>{{__('Store Email Setting')}}
                        </a>
                    </li>
                    <li class="nav-item ml-4">
                        <a href="#whatsapp_custom_massage" id="system_setting_tab" class="nav-link" data-toggle="tab" role="tab" aria-controls="home" aria-selected="true">
                            <i class="fab fa-whatsapp-square mr-2"></i>{{__('Whatsapp Massage Setting')}}
                        </a>
                    </li>
                    <li class="nav-item ml-4">
                        <a href="#twilio_setting" id="twilio_setting_tab" class="nav-link" data-toggle="tab" role="tab" aria-controls="home" aria-selected="true">
                            <i class="fas fa-comment-dots mr-2"></i>{{__('Twilio setting')}}
                        </a>
                    </li>
                @endif
                @if(Auth::user()->type == 'super admin')
                    <li class="nav-item ml-4">
                        <a href="#site_setting" id="site_setting_tab" class="nav-link active" data-toggle="tab" role="tab" aria-controls="home" aria-selected="true">
                            <i class="fas fa-cog mr-2"></i>{{__('Site Setting')}}
                        </a>
                    </li>
                    <li class="nav-item ml-4">
                        <a href="#payment-setting" id="payment-setting_tab" class="nav-link" data-toggle="tab" role="tab" aria-controls="home" aria-selected="true">
                            <i class="fab fa-cc-visa mr-2"></i>{{__('Payment')}}
                        </a>
                    </li>
                    <li class="nav-item ml-4">
                        <a href="#email_setting" id="system_setting_tab" class="nav-link" data-toggle="tab" role="tab" aria-controls="home" aria-selected="true">
                            <i class="fas fa-envelope mr-2"></i>{{__('Email Setting')}}
                        </a>
                    </li>
                    <li class="nav-item ml-4">
                        <a href="#recaptcha-settings" id="system_setting_tab" class="nav-link" data-toggle="tab" role="tab" aria-controls="home" aria-selected="true">
                            <i class="fas fa-sync mr-2"></i>{{__('ReCaptcha Setting')}}
                        </a>
                    </li>
                    <li class="nav-item ml-4">
                        <a href="#email-template-settings" id="system_setting_tab" class="nav-link" data-toggle="tab" role="tab" aria-controls="home" aria-selected="true">
                            <i class="fas fa-email mr-2"></i>{{__('Email Template Setting')}}
                        </a>
                    </li>
                @endif
            </ul>
            <div class="tab-content">
                @if(Auth::user()->type == 'Owner')
                    <div class="tab-pane fade show active" id="store_setting" role="tabpanel" aria-labelledby="orders-tab">
                        {{Form::model($store_settings,array('route'=>array('settings.store',$store_settings['id']),'method'=>'POST','enctype' => "multipart/form-data"))}}
                        @csrf
                        <div class="card-body">
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="logo" class="form-control-label">{{ __('Logo') }}</label>
                                        <input type="file" name="logo" id="logo" class="custom-input-file">
                                        <label for="logo">
                                            <i class="fa fa-upload"></i>
                                            <span>{{__('Choose a file')}}</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-6 d-flex align-items-center justify-content-center mt-3">
                                    <div class="logstore_settingso-div">
                                        <img src="{{$store_logo.'/'.(isset($store_settings['logo']) && !empty($store_settings['logo'])?$store_settings['logo']:'logo.png')}}" width="180px" class="img_setting">
                                    </div>
                                </div>
                                <div class="col-12">
                                    @error('logo')
                                    <div class="row">
                                    <span class="invalid-logo" role="alert">
                                        <strong class="text-danger">{{ $message }}</strong>
                                     </span>
                                    </div>
                                    @enderror
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="invoice_logo" class="form-control-label">{{ __('Invoice Logo') }}</label>
                                        <input type="file" name="invoice_logo" id="invoice_logo" class="custom-input-file">
                                        <label for="invoice_logo">
                                            <i class="fa fa-upload"></i>
                                            <span>{{__('Choose a file')}}</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-6 d-flex align-items-center justify-content-center mt-3">
                                    <div class="logstore_settingso-div">
                                        <img src="{{$store_logo.'/'.(isset($store_settings['invoice_logo']) && !empty($store_settings['invoice_logo'])?$store_settings['invoice_logo']:'invoice_logo.png')}}" width="170px" class="img_setting">
                                    </div>
                                </div>
                                <div class="col-12">
                                    @error('invoice_logo')
                                    <div class="row">
                                    <span class="invalid-invoice_logo" role="alert">
                                        <strong class="text-danger">{{ $message }}</strong>
                                     </span>
                                    </div>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    {{Form::label('store_name',__('Store Name'),array('class'=>'form-control-label')) }}
                                    {!! Form::text('name',null,array('class'=>'form-control','placeholder'=>__('Store Name'))) !!}
                                    @error('store_name')
                                    <span class="invalid-store_name" role="alert">
                                         <strong class="text-danger">{{ $message }}</strong>
                                     </span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    {{Form::label('email',__('Email'),array('class'=>'form-control-label')) }}
                                    {{Form::text('email',null,array('class'=>'form-control','placeholder'=>__('Email')))}}
                                    @error('email')
                                    <span class="invalid-email" role="alert">
                                         <strong class="text-danger">{{ $message }}</strong>
                                     </span>
                                    @enderror
                                </div>
                                @if($plan->enable_custdomain == 'on' || $plan->enable_custsubdomain == 'on')
                                    <div class="col-6 py-4">
                                        <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                            <label class="btn btn-primary {{ ($store_settings['enable_storelink'] == 'on') ? 'active' : '' }}">
                                                <input type="radio" class="domain_click" name="enable_domain" value="enable_storelink" id="enable_storelink" {{ ($store_settings['enable_storelink'] == 'on') ? 'checked' : '' }}"> {{__('Store Link')}}
                                            </label>
                                            @if($plan->enable_custdomain == 'on')
                                                <label class="btn btn-primary {{ ($store_settings['enable_domain'] == 'on') ? 'active' : '' }}">
                                                    <input type="radio" class="domain_click" name="enable_domain" value="enable_domain" id="enable_domain" {{ ($store_settings['enable_domain'] == 'on') ? 'checked' : '' }} > {{__('Domain')}}
                                                </label>
                                            @endif
                                            @if($plan->enable_custsubdomain == 'on')
                                                <label class="btn btn-primary {{ ($store_settings['enable_subdomain'] == 'on') ? 'active' : '' }}">
                                                    <input type="radio" class="domain_click" name="enable_domain" value="enable_subdomain" id="enable_subdomain" {{ ($store_settings['enable_subdomain'] == 'on') ? 'checked' : '' }}> {{__('Sub Domain')}}
                                                </label>
                                            @endif
                                        </div>
                                        <div class="text-sm" id="domainnote" style="display: none">{{__('Note : Before add custom domain, your domain A record is pointing to our server IP :')}}{{$serverIp}} <br>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-6" id="StoreLink" style="{{ ($store_settings['enable_storelink'] == 'on') ? 'display: block':'display: none' }}">
                                        {{Form::label('store_link',__('Store Link'),array('class'=>'form-control-label')) }}
                                        <div class="input-group">
                                            <input type="text" value="{{ $store_settings['store_url'] }}" id="myInput" class="form-control d-inline-block" aria-label="Recipient's username" aria-describedby="button-addon2" readonly>
                                            <div class="input-group-append">
                                                <button class="btn btn-outline-primary" type="button" onclick="myFunction()" id="button-addon2"><i class="far fa-copy"></i> {{__('Copy Link')}}</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-6 domain" style="{{ ($store_settings['enable_domain'] == 'on') ? 'display:block':'display:none' }}">
                                        {{Form::label('store_domain',__('Custom Domain'),array('class'=>'form-control-label')) }}
                                        {{Form::text('domains',$store_settings['domains'],array('class'=>'form-control','placeholder'=>__('xyz.com')))}}
                                    </div>
                                    @if($plan->enable_custsubdomain == 'on')
                                        <div class="form-group col-md-6 sundomain" style="{{ ($store_settings['enable_subdomain'] == 'on') ? 'display:block':'display:none' }}">
                                            {{Form::label('store_subdomain',__('Sub Domain'),array('class'=>'form-control-label')) }}
                                            <div class="input-group">
                                                {{Form::text('subdomain',$store_settings['slug'],array('class'=>'form-control','placeholder'=>__('Enter Domain'),'readonly'))}}
                                                <div class="input-group-append">
                                                    <span class="input-group-text" id="basic-addon2">.{{$subdomain_name}}</span>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @else
                                    <div class="form-group col-md-6" id="StoreLink">
                                        {{Form::label('store_link',__('Store Link'),array('class'=>'form-control-label')) }}
                                        <div class="input-group">
                                            <input type="text" value="{{ $store_settings['store_url'] }}" id="myInput" class="form-control d-inline-block" aria-label="Recipient's username" aria-describedby="button-addon2" readonly>
                                            <div class="input-group-append">
                                                <button class="btn btn-outline-primary" type="button" onclick="myFunction()" id="button-addon2"><i class="far fa-copy"></i> {{__('Copy Link')}}</button>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                <div class="form-group col-md-6">
                                    {{Form::label('tagline',__('Tagline'),array('class'=>'form-control-label')) }}
                                    {{Form::text('tagline',null,array('class'=>'form-control','placeholder'=>__('Tagline')))}}
                                    @error('tagline')
                                    <span class="invalid-tagline" role="alert">
                                         <strong class="text-danger">{{ $message }}</strong>
                                     </span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    {{Form::label('address',__('Address'),array('class'=>'form-control-label')) }}
                                    {{Form::text('address',null,array('class'=>'form-control','placeholder'=>__('Address')))}}
                                    @error('address')
                                    <span class="invalid-address" role="alert">
                                         <strong class="text-danger">{{ $message }}</strong>
                                     </span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    {{Form::label('city',__('City'),array('class'=>'form-control-label')) }}
                                    {{Form::text('city',null,array('class'=>'form-control','placeholder'=>__('City')))}}
                                    @error('city')
                                    <span class="invalid-city" role="alert">
                                         <strong class="text-danger">{{ $message }}</strong>
                                     </span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    {{Form::label('state',__('State'),array('class'=>'form-control-label')) }}
                                    {{Form::text('state',null,array('class'=>'form-control','placeholder'=>__('State')))}}
                                    @error('state')
                                    <span class="invalid-state" role="alert">
                                         <strong class="text-danger">{{ $message }}</strong>
                                     </span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    {{Form::label('zipcode',__('Zipcode'),array('class'=>'form-control-label')) }}
                                    {{Form::text('zipcode',null,array('class'=>'form-control','placeholder'=>__('Zipcode')))}}
                                    @error('zipcode')
                                    <span class="invalid-zipcode" role="alert">
                                         <strong class="text-danger">{{ $message }}</strong>
                                     </span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    {{Form::label('country',__('Country'),array('class'=>'form-control-label')) }}
                                    {{Form::text('country',null,array('class'=>'form-control','placeholder'=>__('Country')))}}
                                    @error('country')
                                    <span class="invalid-country" role="alert">
                                         <strong class="text-danger">{{ $message }}</strong>
                                     </span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    {{Form::label('store_default_language',__('Store Default Language'), array('class'=>'form-control-label')) }}
                                    <div class="changeLanguage">
                                        <select name="store_default_language" id="store_default_language" class="form-control custom-select" data-toggle="select">
                                            @foreach(\App\Models\Utility::languages() as $language)
                                                <option @if($store_lang == $language) selected @endif value="{{$language }}">{{Str::upper($language)}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                @if($plan->shipping_method == 'on')
                                    <div class="form-group col-md-3">
                                        {{Form::label('Shipping Method Enable',__('Shipping Method Enable'),array('class'=>'form-control-label mb-3')) }}
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" class="custom-control-input" name="enable_shipping" id="enable_shipping" {{ ($store_settings['enable_shipping'] == 'on') ? 'checked=checked' : '' }}>
                                            <label class="custom-control-label form-control-label" for="enable_shipping"></label>
                                        </div>
                                    </div>
                                @endif
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <i class="fab fa-google" aria-hidden="true"></i>
                                        {{Form::label('google_analytic',__('Google Analytic'),array('class'=>'form-control-label')) }}
                                        {{Form::text('google_analytic',null,array('class'=>'form-control','placeholder'=>'UA-XXXXXXXXX-X'))}}
                                        @error('google_analytic')
                                        <span class="invalid-google_analytic" role="alert">
                                         <strong class="text-danger">{{ $message }}</strong>
                                     </span>
                                        @enderror
                                    </div>
                                </div>
                                

                                <div class="col-md-6">
                                    <div class="form-group">
                                         <i class="fab fa-facebook-square" aria-hidden="true"></i>
                                        {{Form::label('Facebook Pixel',__('Facebook Pixel'),array('class'=>'form-control-label')) }}
                                        {{Form::text('facebook_pixel',null,array('class'=>'form-control','placeholder'=>'0000000000'))}}
                                        @error('facebook_pixel')
                                        <span class="invalid-facebook_pixel" role="alert">
                                         <strong class="text-danger">{{ $message }}</strong>
                                     </span>
                                        @enderror
                                    </div>
                                </div>



                                 <div class="form-group col-md-12">
                                    {{Form::label('storejs',__('Store Custom JS'),array('class'=>'form-control-label')) }}
                                    {{Form::textarea('storejs',null,array('class'=>'form-control','rows'=>3,'placeholder'=>__('Store Custom JS')))}}
                                    @error('storejs')
                                    <span class="invalid-storejs" role="alert">
                                         <strong class="text-danger">{{ $message }}</strong>
                                     </span>
                                    @enderror
                                </div>


                                   <div class="col-md-6">
                                    <div class="form-group">
                                       
                                        {{Form::label('meta data',__('Meta Data Keyword'),array('class'=>'form-control-label')) }}
                                         {{Form::textarea('meta_data',null,array('class'=>'form-control','rows'=>3,'placeholder'=>__('Store Meta Data')))}}
                                       
                                        @error('meta_data')
                                        <span class="invalid-meta_data" role="alert">
                                         <strong class="text-danger">{{ $message }}</strong>
                                     </span>
                                        @enderror
                                    </div>
                                </div>



                                 <div class="form-group col-md-6">
                                    {{Form::label('Meta Data Description',__('Meta Data Description'),array('class'=>'form-control-label')) }}
                                    {{Form::textarea('meta_description',null,array('class'=>'form-control','rows'=>3,'placeholder'=>__('Store Meta Description')))}}
                                    @error('storejs')
                                    <span class="invalid-storejs" role="alert">
                                         <strong class="text-danger">{{ $message }}</strong>
                                     </span>
                                    @enderror
                                </div>



                                <div class="col-12 pt-4">
                                    <h5 class="h6 mb-0">{{__('Footer Note')}}</h5>
                                    <small>{{__('This detail will use for make explore social media.')}}</small>
                                    <hr class="my-4">
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <i class="fas fa-envelope"></i>
                                        {{Form::label('email',__('Email'),array('class'=>'form-control-label')) }}
                                        {{Form::text('email',null,array('class'=>'form-control','rows'=>3,'placeholder'=>__('Email')))}}
                                        @error('email')
                                        <span class="invalid-email" role="alert">
                                         <strong class="text-danger">{{ $message }}</strong>
                                     </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <i class="fab fa-whatsapp" aria-hidden="true"></i>
                                        {{Form::label('whatsapp',__('Whatsapp'),array('class'=>'form-control-label')) }}
                                        {{Form::text('whatsapp',null,array('class'=>'form-control','rows'=>3,'placeholder'=>'https://wa.me/1XXXXXXXXXX'))}}
                                        @error('whatsapp')
                                        <span class="invalid-whatsapp" role="alert">
                                         <strong class="text-danger">{{ $message }}</strong>
                                     </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <i class="fab fa-facebook-square" aria-hidden="true"></i>
                                        {{Form::label('facebook',__('Facebook'),array('class'=>'form-control-label')) }}
                                        {{Form::text('facebook',null,array('class'=>'form-control','rows'=>3,'placeholder'=>'https://www.facebook.com/'))}}
                                        @error('facebook')
                                        <span class="invalid-facebook" role="alert">
                                         <strong class="text-danger">{{ $message }}</strong>
                                     </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <i class="fab fa-instagram" aria-hidden="true"></i>
                                        {{Form::label('instagram',__('Instagram'),array('class'=>'form-control-label')) }}
                                        {{Form::text('instagram',null,array('class'=>'form-control','placeholder'=>'https://www.instagram.com/'))}}
                                        @error('instagram')
                                        <span class="invalid-instagram" role="alert">
                                         <strong class="text-danger">{{ $message }}</strong>
                                     </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <i class="fab fa-twitter" aria-hidden="true"></i>
                                        {{Form::label('twitter',__('Twitter'),array('class'=>'form-control-label')) }}
                                        {{Form::text('twitter',null,array('class'=>'form-control','placeholder'=>'https://twitter.com/'))}}
                                        @error('twitter')
                                        <span class="invalid-twitter" role="alert">
                                         <strong class="text-danger">{{ $message }}</strong>
                                     </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <i class="fab fa-youtube" aria-hidden="true"></i>
                                        {{Form::label('youtube',__('Youtube'),array('class'=>'form-control-label')) }}
                                        {{Form::text('youtube',null,array('class'=>'form-control','placeholder'=>'https://www.youtube.com/'))}}
                                        @error('youtube')
                                        <span class="invalid-youtube" role="alert">
                                         <strong class="text-danger">{{ $message }}</strong>
                                     </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <i class="fas    fa-copyright" aria-hidden="true"></i>
                                        {{Form::label('footer_note',__('Footer Note'),array('class'=>'form-control-label')) }}
                                        {{Form::text('footer_note',null,array('class'=>'form-control','placeholder'=>__('Footer Note')))}}
                                        @error('footer_note')
                                        <span class="invalid-footer_note" role="alert">
                                         <strong class="text-danger">{{ $message }}</strong>
                                     </span>
                                        @enderror
                                    </div>
                                </div>
                               
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="row">
                                <div class="col-6">
                                    <button type="button" class="btn btn-sm btn-soft-danger btn-icon rounded-pill" data-toggle="tooltip" data-original-title="{{__('Delete')}}" data-confirm="{{__('Are You Sure?').' | '.__('This action can not be undone. Do you want to continue?')}}" data-confirm-yes="document.getElementById('delete-form-{{$store_settings->id}}').submit();">
                                        <span class="btn-inner--text">{{__('Delete Store')}}</span>
                                    </button>
                                </div>
                                <div class="col-6 text-right">
                                    {{Form::submit(__('Save Change'),array('class'=>'btn btn-sm btn-primary rounded-pill'))}}
                                </div>
                            </div>
                        </div>
                        </form>
                    </div>
                    {!! Form::open(['method' => 'DELETE', 'route' => ['ownerstore.destroy', $store_settings->id],'id'=>'delete-form-'.$store_settings->id]) !!}
                    {!! Form::close() !!}
                @endif
                @if(Auth::user()->type == 'super admin')
                    <div class="tab-pane fade show active" id="site_setting" role="tabpanel" aria-labelledby="orders-tab">
                        {{Form::model($settings,array('route'=>'business.setting','method'=>'POST','enctype' => "multipart/form-data"))}}
                        <div class="card-body">
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="full_logo" class="form-control-label">{{ __('Logo') }}</label>
                                        <input type="file" name="logo" id="full_logo" class="custom-input-file">
                                        <label for="full_logo">
                                            <i class="fa fa-upload"></i>
                                            <span>{{__('Choose a file')}}</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-6 d-flex align-items-center justify-content-center mt-3">
                                    <div class="logo-div">
                                        @if(\Illuminate\Support\Facades\Auth::user()->type == 'super admin')
                                            <img src="{{asset(Storage::url('uploads/logo/logo.png'))}}" width="170px" class="img_setting">
                                        @else
                                            <img src="{{asset(Storage::url('uploads/logo/'.$settings->logo))}}" width="170px" class="img_setting">
                                        @endif
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="favicon" class="form-control-label">{{ __('Favicon') }}</label>
                                        <input type="file" name="favicon" id="favicon" class="custom-input-file">
                                        <label for="favicon">
                                            <i class="fa fa-upload"></i>
                                            <span>{{__('Choose a file')}}</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-6 d-flex align-items-center justify-content-center mt-3">
                                    <div class="logo-div">
                                        <img src="{{$logo.'/'.(isset($company_favicon) && !empty($company_favicon)?$company_favicon:'favicon.png')}}" width="50px" class="img_setting">
                                    </div>
                                </div>
                                <div class="col-12">
                                    @error('logo')
                                    <div class="row">
                                    <span class="invalid-logo" role="alert">
                                        <strong class="text-danger">{{ $message }}</strong>
                                     </span>
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    {{Form::label('title_text',__('Title Text')) }}
                                    {{Form::text('title_text',null,array('class'=>'form-control','placeholder'=>__('Title Text')))}}
                                    @error('title_text')
                                    <span class="invalid-title_text" role="alert">
                                     <strong class="text-danger">{{ $message }}</strong>
                                 </span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    {{Form::label('footer_text',__('Footer Text')) }}
                                    {{Form::text('footer_text',null,array('class'=>'form-control','placeholder'=>__('Footer Text')))}}
                                    @error('footer_text')
                                    <span class="invalid-footer_text" role="alert">
                                        <strong class="text-danger">{{ $message }}</strong>
                                     </span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    {{Form::label('default_language',__('Default Language')) }}
                                    <div class="changeLanguage">
                                        <select name="default_language" id="default_language" class="form-control custom-select" data-toggle="select">
                                            @foreach(\App\Models\Utility::languages() as $language)
                                                <option @if($lang == $language) selected @endif value="{{$language }}">{{Str::upper($language)}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group col-md-3">
                                    {{Form::label('display_landing_page_',__('Landing Page Display')) }}
                                    <div class="col-12 mt-2">
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" class="custom-control-input" name="display_landing_page" id="display_landing_page" {{ $settings['display_landing_page'] == 'on' ? 'checked="checked"' : '' }}>
                                            <label class="custom-control-label form-control-label" for="display_landing_page"></label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-md-3">
                                    {{Form::label('SITE_RTL',__('RTL')) }}
                                    <div class="col-12 mt-2">
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" class="custom-control-input" name="SITE_RTL" id="SITE_RTL" {{ env('SITE_RTL') == 'on' ? 'checked="checked"' : '' }}>
                                            <label class="custom-control-label form-control-label" for="SITE_RTL"></label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-md-6">
                                    <div class="form-group">
                                        {{Form::label('currency_symbol',__('Currency Symbol *')) }}
                                        {{Form::text('currency_symbol',null,array('class'=>'form-control'))}}
                                        <small>{{__('Note: This value will assign when any new store created by Store Owner.')}}</small>
                                        @error('currency_symbol')
                                        <span class="invalid-currency_symbol" role="alert">
                                            <strong class="text-danger">{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group col-md-6">
                                    <div class="form-group">
                                        {{Form::label('currency',__('Currency *')) }}
                                        {{Form::text('currency',null,array('class'=>'form-control font-style'))}}
                                        {{__('Note: Add currency code as per three-letter ISO code.')}}
                                        <small>
                                            <a href="https://stripe.com/docs/currencies" target="_blank">{{__('you can find out here..')}}</a>
                                        </small>
                                        <br>
                                        <small>
                                            {{__('This value will assign when any new store created by Store Owner.')}}
                                        </small>

                                        @error('currency')
                                        <span class="invalid-currency" role="alert">
                                            <strong class="text-danger">{{ $message }}</strong>
                                        </span>
                                        @enderror

                                    </div>
                                </div>

                            </div>
                                 <div class="row">
                                    <div class="form-group col-md-12">
                                    {{Form::label('gdpr_cookie',__('GDPR Cookie')) }}
                                    
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" class="custom-control-input" name="gdpr_cookie" id="gdpr_cookie" {{ isset($settings['gdpr_cookie']) && $settings['gdpr_cookie'] == 'on' ? 'checked="checked"' : '' }}>
                                            <label class="custom-control-label form-control-label" for="gdpr_cookie"></label>
                                        </div>
                                   </div>
                                    </div>

                                    <div class="form-group col-md-12">
                                        @if(App\Models\Utility::getValByName('gdpr_cookie') == 'on')

                                        {{Form::label('cookie_text',__('GDPR Cookie Text')) }}
                                         
                                        {!! Form::textarea('cookie_text',$settings['cookie_text'], ['class'=>'form-control','rows'=>'2','style'=>'display: hidden;height: auto !important;resize:none;']) !!}
                                        @endif
                                    </div>
                                    <div class="form-group">
                                        {{Form::label('signup_button',__('Sign Up')) }}
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" class="custom-control-input" name="signup_button" id="signup_button" {{ isset($settings['signup_button']) && $settings['signup_button'] == 'on' ? 'checked="checked"' : '' }}>
                                            <label class="custom-control-label form-control-label" for="signup_button"></label>
                                        </div>
                                    </div>

                            <div class="card-footer text-right">
                                {{Form::submit(__('Save Change'),array('class'=>'btn btn-sm btn-primary rounded-pill'))}}
                            </div>
                            {{Form::close()}}
                        </div>
                    </div>
                    <div class="tab-pane fade show" id="payment-setting" role="tabpanel" aria-labelledby="orders-tab">
                        <div class="card-body">
                            {{Form::open(array('route'=>'payment.setting','method'=>'post'))}}
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        {{Form::label('currency_symbol',__('Currency Symbol *')) }}
                                        {{Form::text('currency_symbol',env('CURRENCY_SYMBOL'),array('class'=>'form-control','required'))}}
                                        @error('currency_symbol')
                                        <span class="invalid-currency_symbol" role="alert">
                                            <strong class="text-danger">{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        {{Form::label('currency',__('Currency *')) }}
                                        {{Form::text('currency',env('CURRENCY'),array('class'=>'form-control font-style','required'))}}
                                        {{__('Note: Add currency code as per three-letter ISO code.')}}
                                        <small>
                                            <a href="https://stripe.com/docs/currencies" target="_blank">{{__('you can find out here..')}}</a>
                                        </small>
                                        @error('currency')
                                        <span class="invalid-currency" role="alert">
                                            <strong class="text-danger">{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class=" pb-3">
                                <hr>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div id="accordion-2" class="accordion accordion-spaced">
                                        <!-- Strip -->
                                        <div class="card">
                                            <div class="card-header py-4" id="heading-2-2" data-toggle="collapse" role="button" data-target="#collapse-2-2" aria-expanded="false" aria-controls="collapse-2-2">
                                                <h6 class="mb-0"><i class="far fa-credit-card mr-3"></i>{{__('Stripe')}}</h6>
                                            </div>
                                            <div id="collapse-2-2" class="collapse" aria-labelledby="heading-2-2" data-parent="#accordion-2">
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-6 py-2">
                                                            <h5 class="h5">{{__('Stripe')}}</h5>
                                                            <small> {{__('Note: This detail will use for make checkout of shopping cart.')}}</small>
                                                        </div>
                                                        <div class="col-6 py-2 text-right">
                                                            <div class="custom-control custom-switch">
                                                                <input type="hidden" name="is_stripe_enabled" value="off">
                                                                <input type="checkbox" class="custom-control-input" name="is_stripe_enabled" id="is_stripe_enabled" {{ isset($admin_payment_setting['is_stripe_enabled']) && $admin_payment_setting['is_stripe_enabled'] == 'on' ? 'checked="checked"' : '' }}>
                                                                <label class="custom-control-label form-control-label" for="is_stripe_enabled">{{__('Enable Stripe')}}</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                {{Form::label('stripe_key',__('Stripe Key')) }}
                                                                {{Form::text('stripe_key',isset($admin_payment_setting['stripe_key'])?$admin_payment_setting['stripe_key']:'',['class'=>'form-control','placeholder'=>__('Enter Stripe Key')])}}
                                                                @error('stripe_key')
                                                                <span class="invalid-stripe_key" role="alert">
                                                                                                 <strong class="text-danger">{{ $message }}</strong>
                                                                                             </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                {{Form::label('stripe_secret',__('Stripe Secret')) }}
                                                                {{Form::text('stripe_secret',isset($admin_payment_setting['stripe_secret'])?$admin_payment_setting['stripe_secret']:'',['class'=>'form-control ','placeholder'=>__('Enter Stripe Secret')])}}
                                                                @error('stripe_secret')
                                                                <span class="invalid-stripe_secret" role="alert">
                                                                 <strong class="text-danger">{{ $message }}</strong>
                                                             </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Paypal -->
                                        <div class="card">
                                            <div class="card-header py-4" id="heading-2-3" data-toggle="collapse" role="button" data-target="#collapse-2-3" aria-expanded="false" aria-controls="collapse-2-3">
                                                <h6 class="mb-0"><i class="far fa-credit-card mr-3"></i>{{__('PayPal')}}</h6>
                                            </div>
                                            <div id="collapse-2-3" class="collapse" aria-labelledby="heading-2-3" data-parent="#accordion-2">
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-6 py-2">
                                                            <h5 class="h5">{{__('PayPal')}}</h5>
                                                            <small> {{__('Note: This detail will use for make checkout of shopping cart.')}}</small>
                                                        </div>
                                                        <div class="col-6 py-2 text-right">
                                                            <div class="custom-control custom-switch">
                                                                <input type="hidden" name="is_paypal_enabled" value="off">
                                                                <input type="checkbox" class="custom-control-input" name="is_paypal_enabled" id="is_paypal_enabled" {{ isset($admin_payment_setting['is_paypal_enabled']) && $admin_payment_setting['is_paypal_enabled'] == 'on' ? 'checked="checked"' : '' }}>
                                                                <label class="custom-control-label form-control-label" for="is_paypal_enabled">{{__('Enable Paypal')}}</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12 pb-4">
                                                            <label class="paypal-label form-control-label" for="paypal_mode">{{__('Paypal Mode')}}</label> <br>
                                                            <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                                                <label class="btn btn-primary btn-sm {{isset($admin_payment_setting['paypal_mode']) && $admin_payment_setting['paypal_mode'] == 'sandbox' ? 'active' : ''}}">
                                                                    <input type="radio" name="paypal_mode" value="sandbox" {{ isset($admin_payment_setting['paypal_mode']) && $admin_payment_setting['paypal_mode'] == '' || isset($admin_payment_setting['paypal_mode']) && $admin_payment_setting['paypal_mode'] == 'sandbox' ? 'checked="checked"' : '' }}>{{__('Sandbox')}}
                                                                </label>
                                                                <label class="btn btn-primary btn-sm {{isset($admin_payment_setting['paypal_mode']) && $admin_payment_setting['paypal_mode'] == 'live' ? 'active' : ''}}">
                                                                    <input type="radio" name="paypal_mode" value="live" {{ isset($admin_payment_setting['paypal_mode']) && $admin_payment_setting['paypal_mode'] == 'live' ? 'checked="checked"' : '' }}>{{__('Live')}}
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="paypal_client_id">{{ __('Client ID') }}</label>
                                                                <input type="text" name="paypal_client_id" id="paypal_client_id" class="form-control" value="{{isset($admin_payment_setting['paypal_client_id'])?$admin_payment_setting['paypal_client_id']:''}}" placeholder="{{ __('Client ID') }}"/>
                                                                @if ($errors->has('paypal_client_id'))
                                                                    <span class="invalid-feedback d-block">
                                                                                            {{ $errors->first('paypal_client_id') }}
                                                                                        </span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="paypal_secret_key">{{ __('Secret Key') }}</label>
                                                                <input type="text" name="paypal_secret_key" id="paypal_secret_key" class="form-control" value="{{isset($admin_payment_setting['paypal_secret_key'])?$admin_payment_setting['paypal_secret_key']:''}}" placeholder="{{ __('Secret Key') }}"/>
                                                                @if ($errors->has('paypal_secret_key'))
                                                                    <span class="invalid-feedback d-block">
                                                                    {{ $errors->first('paypal_secret_key') }}
                                                                </span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Paystack -->
                                        <div class="card">
                                            <div class="card-header py-4" id="heading-2-6" data-toggle="collapse" role="button" data-target="#collapse-2-6" aria-expanded="false" aria-controls="collapse-2-6">
                                                <h6 class="mb-0"><i class="far fa-credit-card mr-3"></i>{{__('Paystack')}}</h6>
                                            </div>
                                            <div id="collapse-2-6" class="collapse" aria-labelledby="heading-2-6" data-parent="#accordion-2">
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-6 py-2">
                                                            <h5 class="h5">{{__('Paystack')}}</h5>
                                                            <small> {{__('Note: This detail will use for make checkout of shopping cart.')}}</small>
                                                        </div>
                                                        <div class="col-6 py-2 text-right">
                                                            <div class="custom-control custom-switch">
                                                                <input type="hidden" name="is_paystack_enabled" value="off">
                                                                <input type="checkbox" class="custom-control-input" name="is_paystack_enabled" id="is_paystack_enabled" {{ isset($admin_payment_setting['is_paystack_enabled']) && $admin_payment_setting['is_paystack_enabled'] == 'on' ? 'checked="checked"' : '' }}>
                                                                <label class="custom-control-label form-control-label" for="is_paystack_enabled">{{__('Enable Paystack')}}</label>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="paypal_client_id">{{ __('Public Key') }}</label>
                                                                <input type="text" name="paystack_public_key" id="paystack_public_key" class="form-control" value="{{isset($admin_payment_setting['paystack_public_key']) ? $admin_payment_setting['paystack_public_key']:''}}" placeholder="{{ __('Public Key') }}"/>
                                                                @if ($errors->has('paystack_public_key'))
                                                                    <span class="invalid-feedback d-block">
                                                                    {{ $errors->first('paystack_public_key') }}
                                                                </span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="paystack_secret_key">{{ __('Secret Key') }}</label>
                                                                <input type="text" name="paystack_secret_key" id="paystack_secret_key" class="form-control" value="{{isset($admin_payment_setting['paystack_secret_key']) ? $admin_payment_setting['paystack_secret_key']:''}}" placeholder="{{ __('Secret Key') }}"/>
                                                                @if ($errors->has('paystack_secret_key'))
                                                                    <span class="invalid-feedback d-block">
                                                                    {{ $errors->first('paystack_secret_key') }}
                                                                </span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- FLUTTERWAVE -->
                                        <div class="card">
                                            <div class="card-header py-4" id="heading-2-7" data-toggle="collapse" role="button" data-target="#collapse-2-7" aria-expanded="false" aria-controls="collapse-2-7">
                                                <h6 class="mb-0"><i class="far fa-credit-card mr-3"></i>{{__('Flutterwave')}}</h6>
                                            </div>
                                            <div id="collapse-2-7" class="collapse" aria-labelledby="heading-2-7" data-parent="#accordion-2">
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-6 py-2">
                                                            <h5 class="h5">{{__('Flutterwave')}}</h5>
                                                            <small> {{__('Note: This detail will use for make checkout of shopping cart.')}}</small>
                                                        </div>
                                                        <div class="col-6 py-2 text-right">
                                                            <div class="custom-control custom-switch">
                                                                <input type="hidden" name="is_flutterwave_enabled" value="off">
                                                                <input type="checkbox" class="custom-control-input" name="is_flutterwave_enabled" id="is_flutterwave_enabled" {{ isset($admin_payment_setting['is_flutterwave_enabled'])  && $admin_payment_setting['is_flutterwave_enabled']== 'on' ? 'checked="checked"' : '' }}>
                                                                <label class="custom-control-label form-control-label" for="is_flutterwave_enabled">{{__('Enable Flutterwave')}}</label>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="paypal_client_id">{{ __('Public Key') }}</label>
                                                                <input type="text" name="flutterwave_public_key" id="flutterwave_public_key" class="form-control" value="{{isset($admin_payment_setting['flutterwave_public_key'])?$admin_payment_setting['flutterwave_public_key']:''}}" placeholder="{{ __('Public Key') }}"/>
                                                                @if ($errors->has('flutterwave_public_key'))
                                                                    <span class="invalid-feedback d-block">
                                                                    {{ $errors->first('flutterwave_public_key') }}
                                                                </span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="paystack_secret_key">{{ __('Secret Key') }}</label>
                                                                <input type="text" name="flutterwave_secret_key" id="flutterwave_secret_key" class="form-control" value="{{isset($admin_payment_setting['flutterwave_secret_key'])?$admin_payment_setting['flutterwave_secret_key']:''}}" placeholder="{{ __('Secret Key') }}"/>
                                                                @if ($errors->has('flutterwave_secret_key'))
                                                                    <span class="invalid-feedback d-block">
                                                                    {{ $errors->first('flutterwave_secret_key') }}
                                                                </span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Razorpay -->
                                        <div class="card">
                                            <div class="card-header py-4" id="heading-2-8" data-toggle="collapse" role="button" data-target="#collapse-2-8" aria-expanded="false" aria-controls="collapse-2-8">
                                                <h6 class="mb-0"><i class="far fa-credit-card mr-3"></i>{{__('Razorpay')}}</h6>
                                            </div>
                                            <div id="collapse-2-8" class="collapse" aria-labelledby="heading-2-7" data-parent="#accordion-2">
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-6 py-2">
                                                            <h5 class="h5">{{__('Razorpay')}}</h5>
                                                            <small> {{__('Note: This detail will use for make checkout of shopping cart.')}}</small>
                                                        </div>
                                                        <div class="col-6 py-2 text-right">
                                                            <div class="custom-control custom-switch">
                                                                <input type="hidden" name="is_razorpay_enabled" value="off">
                                                                <input type="checkbox" class="custom-control-input" name="is_razorpay_enabled" id="is_razorpay_enabled" {{ isset($admin_payment_setting['is_razorpay_enabled']) && $admin_payment_setting['is_razorpay_enabled'] == 'on' ? 'checked="checked"' : '' }}>
                                                                <label class="custom-control-label form-control-label" for="is_razorpay_enabled">{{__('Enable Razorpay')}}</label>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="paypal_client_id">{{ __('Public Key') }}</label>

                                                                <input type="text" name="razorpay_public_key" id="razorpay_public_key" class="form-control" value="{{ isset($admin_payment_setting['razorpay_public_key'])?$admin_payment_setting['razorpay_public_key']:''}}" placeholder="{{ __('Public Key') }}"/>
                                                                @if ($errors->has('razorpay_public_key'))
                                                                    <span class="invalid-feedback d-block">
                                                                    {{ $errors->first('razorpay_public_key') }}
                                                                </span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="paystack_secret_key">{{ __('Secret Key') }}</label>
                                                                <input type="text" name="razorpay_secret_key" id="razorpay_secret_key" class="form-control" value="{{ isset($admin_payment_setting['razorpay_secret_key'])?$admin_payment_setting['razorpay_secret_key']:''}}" placeholder="{{ __('Secret Key') }}"/>
                                                                @if ($errors->has('razorpay_secret_key'))
                                                                    <span class="invalid-feedback d-block">
                                                                    {{ $errors->first('razorpay_secret_key') }}
                                                                </span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Mercado Pago-->
                                        <div class="card">
                                            <div class="card-header py-4" id="heading-2-12" data-toggle="collapse" role="button" data-target="#collapse-2-12" aria-expanded="false" aria-controls="collapse-2-12">
                                                <h6 class="mb-0"><i class="far fa-credit-card mr-3"></i>{{__('Mercado Pago')}}</h6>
                                            </div>
                                            <div id="collapse-2-12" class="collapse" aria-labelledby="heading-2-12" data-parent="#accordion-2">
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-6 py-2">
                                                            <h5 class="h5">{{__('Mercado Pago')}}</h5>
                                                            <small> {{__('Note: This detail will use for make checkout of plan.')}}</small>
                                                        </div>
                                                        <div class="col-6 py-2 text-right">
                                                            <div class="custom-control custom-switch">
                                                                <input type="hidden" name="is_mercado_enabled" value="off">
                                                                <input type="checkbox" class="custom-control-input" name="is_mercado_enabled" id="is_mercado_enabled" {{isset($admin_payment_setting['is_mercado_enabled']) &&  $admin_payment_setting['is_mercado_enabled'] == 'on' ? 'checked="checked"' : '' }}>
                                                                <label class="custom-control-label form-control-label" for="is_mercado_enabled">{{__('Enable Mercado Pago')}}</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12 pb-4">
                                                            <label class="coingate-label form-control-label" for="mercado_mode">{{__('Mercado Mode')}}</label> <br>
                                                            <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                                                <label class="btn btn-primary btn-sm {{isset($admin_payment_setting['mercado_mode']) && $admin_payment_setting['mercado_mode'] == 'sandbox' ? 'active' : ''}}">
                                                                    <input type="radio" name="mercado_mode" value="sandbox" {{ isset($admin_payment_setting['mercado_mode']) && $admin_payment_setting['mercado_mode'] == '' || isset($admin_payment_setting['mercado_mode']) && $admin_payment_setting['mercado_mode'] == 'sandbox' ? 'checked="checked"' : '' }}>{{__('Sandbox')}}
                                                                </label>
                                                                <label class="btn btn-primary btn-sm {{isset($admin_payment_setting['mercado_mode']) && $admin_payment_setting['mercado_mode'] == 'live' ? 'active' : ''}}">
                                                                    <input type="radio" name="mercado_mode" value="live" {{ isset($admin_payment_setting['mercado_mode']) && $admin_payment_setting['mercado_mode'] == 'live' ? 'checked="checked"' : '' }}>{{__('Live')}}
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="mercado_access_token">{{ __('Access Token') }}</label>
                                                                <input type="text" name="mercado_access_token" id="mercado_access_token" class="form-control" value="{{isset($admin_payment_setting['mercado_access_token']) ? $admin_payment_setting['mercado_access_token']:''}}" placeholder="{{ __('Access Token') }}"/>
                                                                @if ($errors->has('mercado_secret_key'))
                                                                    <span class="invalid-feedback d-block">
                                                                {{ $errors->first('mercado_access_token') }}
                                                            </span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Paytm -->
                                        <div class="card">
                                            <div class="card-header py-4" id="heading-2-8" data-toggle="collapse" role="button" data-target="#collapse-2-9" aria-expanded="false" aria-controls="collapse-2-9">
                                                <h6 class="mb-0"><i class="far fa-credit-card mr-3"></i>{{__('Paytm')}}</h6>
                                            </div>
                                            <div id="collapse-2-9" class="collapse" aria-labelledby="heading-2-7" data-parent="#accordion-2">
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-6 py-2">
                                                            <h5 class="h5">{{__('Paytm')}}</h5>
                                                            <small> {{__('Note: This detail will use for make checkout of shopping cart.')}}</small>
                                                        </div>
                                                        <div class="col-6 py-2 text-right">
                                                            <div class="custom-control custom-switch">
                                                                <input type="hidden" name="is_paytm_enabled" value="off">
                                                                <input type="checkbox" class="custom-control-input" name="is_paytm_enabled" id="is_paytm_enabled" {{ isset($admin_payment_setting['is_paytm_enabled']) && $admin_payment_setting['is_paytm_enabled'] == 'on' ? 'checked="checked"' : '' }}>
                                                                <label class="custom-control-label form-control-label" for="is_paytm_enabled">{{__('Enable Paytm')}}</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12 pb-4">
                                                            <label class="paypal-label form-control-label" for="paypal_mode">{{__('Paytm Environment')}}</label> <br>
                                                            <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                                                <label class="btn btn-primary btn-sm {{isset($admin_payment_setting['paytm_mode']) && $admin_payment_setting['paytm_mode'] == 'local' ? 'active' : ''}}">
                                                                    <input type="radio" name="paytm_mode" value="local" {{ isset($admin_payment_setting['paytm_mode']) && $admin_payment_setting['paytm_mode'] == '' || isset($admin_payment_setting['paytm_mode']) && $admin_payment_setting['paytm_mode'] == 'local' ? 'checked="checked"' : '' }}>{{__('Local')}}
                                                                </label>
                                                                <label class="btn btn-primary btn-sm {{isset($admin_payment_setting['paytm_mode']) && $admin_payment_setting['paytm_mode'] == 'live' ? 'active' : ''}}">
                                                                    <input type="radio" name="paytm_mode" value="production" {{ isset($admin_payment_setting['paytm_mode']) && $admin_payment_setting['paytm_mode'] == 'production' ? 'checked="checked"' : '' }}>{{__('Production')}}
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="paytm_public_key">{{ __('Merchant ID') }}</label>
                                                                <input type="text" name="paytm_merchant_id" id="paytm_merchant_id" class="form-control" value="{{isset($admin_payment_setting['paytm_merchant_id'])? $admin_payment_setting['paytm_merchant_id']:''}}" placeholder="{{ __('Merchant ID') }}"/>
                                                                @if ($errors->has('paytm_merchant_id'))
                                                                    <span class="invalid-feedback d-block">
                                                                {{ $errors->first('paytm_merchant_id') }}
                                                            </span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="paytm_secret_key">{{ __('Merchant Key') }}</label>
                                                                <input type="text" name="paytm_merchant_key" id="paytm_merchant_key" class="form-control" value="{{ isset($admin_payment_setting['paytm_merchant_key']) ? $admin_payment_setting['paytm_merchant_key']:''}}" placeholder="{{ __('Merchant Key') }}"/>
                                                                @if ($errors->has('paytm_merchant_key'))
                                                                    <span class="invalid-feedback d-block">
                                                                {{ $errors->first('paytm_merchant_key') }}
                                                            </span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="paytm_industry_type">{{ __('Industry Type') }}</label>
                                                                <input type="text" name="paytm_industry_type" id="paytm_industry_type" class="form-control" value="{{isset($admin_payment_setting['paytm_industry_type']) ?$admin_payment_setting['paytm_industry_type']:''}}" placeholder="{{ __('Industry Type') }}"/>
                                                                @if ($errors->has('paytm_industry_type'))
                                                                    <span class="invalid-feedback d-block">
                                                                {{ $errors->first('paytm_industry_type') }}
                                                            </span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Mollie -->
                                        <div class="card">
                                            <div class="card-header py-4" id="heading-2-8" data-toggle="collapse" role="button" data-target="#collapse-2-10" aria-expanded="false" aria-controls="collapse-2-10">
                                                <h6 class="mb-0"><i class="far fa-credit-card mr-3"></i>{{__('Mollie')}}</h6>
                                            </div>
                                            <div id="collapse-2-10" class="collapse" aria-labelledby="heading-2-7" data-parent="#accordion-2">
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-6 py-2">
                                                            <h5 class="h5">{{__('Mollie')}}</h5>
                                                            <small> {{__('Note: This detail will use for make checkout of shopping cart.')}}</small>
                                                        </div>
                                                        <div class="col-6 py-2 text-right">
                                                            <div class="custom-control custom-switch">
                                                                <input type="hidden" name="is_mollie_enabled" value="off">
                                                                <input type="checkbox" class="custom-control-input" name="is_mollie_enabled" id="is_mollie_enabled" {{ isset($admin_payment_setting['is_mollie_enabled']) && $admin_payment_setting['is_mollie_enabled'] == 'on' ? 'checked="checked"' : '' }}>
                                                                <label class="custom-control-label form-control-label" for="is_mollie_enabled">{{__('Enable Mollie')}}</label>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="mollie_api_key">{{ __('Mollie Api Key') }}</label>
                                                                <input type="text" name="mollie_api_key" id="mollie_api_key" class="form-control" value="{{ isset($admin_payment_setting['mollie_api_key'])?$admin_payment_setting['mollie_api_key']:''}}" placeholder="{{ __('Mollie Api Key') }}"/>
                                                                @if ($errors->has('mollie_api_key'))
                                                                    <span class="invalid-feedback d-block">
                                                                    {{ $errors->first('mollie_api_key') }}
                                                                </span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="mollie_profile_id">{{ __('Mollie Profile Id') }}</label>
                                                                <input type="text" name="mollie_profile_id" id="mollie_profile_id" class="form-control" value="{{ isset($admin_payment_setting['mollie_profile_id'])?$admin_payment_setting['mollie_profile_id']:''}}" placeholder="{{ __('Mollie Profile Id') }}"/>
                                                                @if ($errors->has('mollie_profile_id'))
                                                                    <span class="invalid-feedback d-block">
                                                                    {{ $errors->first('mollie_profile_id') }}
                                                                </span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="mollie_partner_id">{{ __('Mollie Partner Id') }}</label>
                                                                <input type="text" name="mollie_partner_id" id="mollie_partner_id" class="form-control" value="{{ isset($admin_payment_setting['mollie_partner_id'])?$admin_payment_setting['mollie_partner_id']:''}}" placeholder="{{ __('Mollie Partner Id') }}"/>
                                                                @if ($errors->has('mollie_partner_id'))
                                                                    <span class="invalid-feedback d-block">
                                                                    {{ $errors->first('mollie_partner_id') }}
                                                                </span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Skrill -->
                                        <div class="card">
                                            <div class="card-header py-4" id="heading-2-8" data-toggle="collapse" role="button" data-target="#collapse-2-13" aria-expanded="false" aria-controls="collapse-2-10">
                                                <h6 class="mb-0"><i class="far fa-credit-card mr-3"></i>{{__('Skrill')}}</h6>
                                            </div>
                                            <div id="collapse-2-13" class="collapse" aria-labelledby="heading-2-7" data-parent="#accordion-2">
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-6 py-2">
                                                            <h5 class="h5">{{__('Skrill')}}</h5>
                                                            <small> {{__('Note: This detail will use for make checkout of shopping cart.')}}</small>
                                                        </div>
                                                        <div class="col-6 py-2 text-right">
                                                            <div class="custom-control custom-switch">
                                                                <input type="hidden" name="is_skrill_enabled" value="off">
                                                                <input type="checkbox" class="custom-control-input" name="is_skrill_enabled" id="is_skrill_enabled" {{ isset($admin_payment_setting['is_skrill_enabled']) && $admin_payment_setting['is_skrill_enabled'] == 'on' ? 'checked="checked"' : '' }}>
                                                                <label class="custom-control-label form-control-label" for="is_skrill_enabled">{{__('Enable Skrill')}}</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="mollie_api_key">{{ __('Mollie Api Key') }}</label>
                                                                <input type="email" name="skrill_email" id="skrill_email" class="form-control" value="{{ isset($admin_payment_setting['skrill_email'])?$admin_payment_setting['skrill_email']:''}}" placeholder="{{ __('Mollie Api Key') }}"/>
                                                                @if ($errors->has('skrill_email'))
                                                                    <span class="invalid-feedback d-block">
                                                                    {{ $errors->first('skrill_email') }}
                                                                </span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- CoinGate -->
                                        <div class="card">
                                            <div class="card-header py-4" id="heading-2-8" data-toggle="collapse" role="button" data-target="#collapse-2-15" aria-expanded="false" aria-controls="collapse-2-10">
                                                <h6 class="mb-0"><i class="far fa-credit-card mr-3"></i>{{__('CoinGate')}}</h6>
                                            </div>
                                            <div id="collapse-2-15" class="collapse" aria-labelledby="heading-2-7" data-parent="#accordion-2">
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-6 py-2">
                                                            <h5 class="h5">{{__('CoinGate')}}</h5>
                                                            <small> {{__('Note: This detail will use for make checkout of shopping cart.')}}</small>
                                                        </div>
                                                        <div class="col-6 py-2 text-right">
                                                            <div class="custom-control custom-switch">
                                                                <input type="hidden" name="is_coingate_enabled" value="off">
                                                                <input type="checkbox" class="custom-control-input" name="is_coingate_enabled" id="is_coingate_enabled" {{ isset($admin_payment_setting['is_coingate_enabled']) && $admin_payment_setting['is_coingate_enabled'] == 'on' ? 'checked="checked"' : '' }}>
                                                                <label class="custom-control-label form-control-label" for="is_coingate_enabled">{{__('Enable CoinGate')}}</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12 pb-4">
                                                            <label class="coingate-label form-control-label" for="coingate_mode">{{__('CoinGate Mode')}}</label> <br>
                                                            <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                                                <label class="btn btn-primary btn-sm {{isset($admin_payment_setting['coingate_mode']) && $admin_payment_setting['coingate_mode'] == 'sandbox' ? 'active' : ''}}">
                                                                    <input type="radio" name="coingate_mode" value="sandbox" {{ isset($admin_payment_setting['coingate_mode']) && $admin_payment_setting['coingate_mode'] == '' || isset($admin_payment_setting['coingate_mode']) && $admin_payment_setting['coingate_mode'] == 'sandbox' ? 'checked="checked"' : '' }}>{{__('Sandbox')}}
                                                                </label>
                                                                <label class="btn btn-primary btn-sm {{isset($admin_payment_setting['coingate_mode']) && $admin_payment_setting['coingate_mode'] == 'live' ? 'active' : ''}}">
                                                                    <input type="radio" name="coingate_mode" value="live" {{ isset($admin_payment_setting['coingate_mode']) && $admin_payment_setting['coingate_mode'] == 'live' ? 'checked="checked"' : '' }}>{{__('Live')}}
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="coingate_auth_token">{{ __('CoinGate Auth Token') }}</label>
                                                                <input type="text" name="coingate_auth_token" id="coingate_auth_token" class="form-control" value="{{ isset($admin_payment_setting['coingate_auth_token'])?$admin_payment_setting['coingate_auth_token']:''}}" placeholder="{{ __('CoinGate Auth Token') }}"/>
                                                                @if($errors->has('coingate_auth_token'))
                                                                    <span class="invalid-feedback d-block">
                                                                {{ $errors->first('coingate_auth_token') }}
                                                            </span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Paymentwall -->
                                        <div class="card">
                                            <div class="card-header py-4" id="heading-2-8" data-toggle="collapse" role="button" data-target="#collapse-2-16" aria-expanded="false" aria-controls="collapse-2-10">
                                                <h6 class="mb-0"><i class="far fa-credit-card mr-3"></i>{{__('Paymentwall')}}</h6>
                                            </div>
                                            <div id="collapse-2-16" class="collapse" aria-labelledby="heading-2-7" data-parent="#accordion-2">
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-6 py-2">
                                                            <h5 class="h5">{{__('Paymentwall')}}</h5>
                                                        </div>
                                                        <div class="col-6 py-2 text-right">
                                                            <div class="custom-control custom-switch">
                                                            <input type="hidden" name="is_paymentwall_enabled" value="off">
                                                            <input type="checkbox" class="custom-control-input" name="is_paymentwall_enabled" id="is_paymentwall_enabled" {{ isset($admin_payment_setting['is_paymentwall_enabled'])  && $admin_payment_setting['is_paymentwall_enabled']== 'on' ? 'checked="checked"' : '' }}>
                                                            <label class="custom-control-label form-control-label" for="is_paymentwall_enabled">{{__('Enable PaymentWall')}}</label>
                                                        </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="paymentwall_public_key" class="form-control-label">{{ __('Public Key') }}</label>
                                                            <input type="text" name="paymentwall_public_key" id="paymentwall_public_key" class="form-control" value="{{isset($admin_payment_setting['paymentwall_public_key'])?$admin_payment_setting['paymentwall_public_key']:''}}" placeholder="{{ __('Public Key') }}"/>
                                                            @if ($errors->has('paymentwall_public_key'))
                                                                <span class="invalid-feedback d-block">
                                                                    {{ $errors->first('paymentwall_public_key') }}
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="paymentwall_private_key" class="form-control-label">{{ __('Private Key') }}</label>
                                                            <input type="text" name="paymentwall_private_key" id="paymentwall_private_key" class="form-control form-control-label" value="{{isset($admin_payment_setting['paymentwall_private_key'])?$admin_payment_setting['paymentwall_private_key']:''}}" placeholder="{{ __('Private Key') }}"/>
                                                            @if ($errors->has('paymentwall_private_key'))
                                                                <span class="invalid-feedback d-block">
                                                                    {{ $errors->first('paymentwall_private_key') }}
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer text-right">
                                {{Form::submit(__('Save Change'),array('class'=>'btn btn-sm btn-primary rounded-pill'))}}
                            </div>
                            {{Form::close()}}
                        </div>
                    </div>
                    <div class="tab-pane fade show" id="email_setting" role="tabpanel" aria-labelledby="orders-tab">
                        {{Form::open(array('route'=>'email.setting','method'=>'post'))}}
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-md-6">
                                    {{Form::label('mail_driver',__('Mail Driver')) }}
                                    {{Form::text('mail_driver',env('MAIL_DRIVER'),array('class'=>'form-control','placeholder'=>__('Enter Mail Driver')))}}
                                    @error('mail_driver')
                                    <span class="invalid-mail_driver" role="alert">
                                     <strong class="text-danger">{{ $message }}</strong>
                                     </span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    {{Form::label('mail_host',__('Mail Host')) }}
                                    {{Form::text('mail_host',env('MAIL_HOST'),array('class'=>'form-control ','placeholder'=>__('Enter Mail Host')))}}
                                    @error('mail_host')
                                    <span class="invalid-mail_driver" role="alert">
                                        <strong class="text-danger">{{ $message }}</strong>
                                 </span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    {{Form::label('mail_port',__('Mail Port')) }}
                                    {{Form::text('mail_port',env('MAIL_PORT'),array('class'=>'form-control','placeholder'=>__('Enter Mail Port')))}}
                                    @error('mail_port')
                                    <span class="invalid-mail_port" role="alert">
                                    <strong class="text-danger">{{ $message }}</strong>
                                </span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    {{Form::label('mail_username',__('Mail Username')) }}
                                    {{Form::text('mail_username',env('MAIL_USERNAME'),array('class'=>'form-control','placeholder'=>__('Enter Mail Username')))}}
                                    @error('mail_username')
                                    <span class="invalid-mail_username" role="alert">
                                    <strong class="text-danger">{{ $message }}</strong>
                                </span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    {{Form::label('mail_password',__('Mail Password')) }}
                                    {{Form::text('mail_password',env('MAIL_PASSWORD'),array('class'=>'form-control','placeholder'=>__('Enter Mail Password')))}}
                                    @error('mail_password')
                                    <span class="invalid-mail_password" role="alert">
                                    <strong class="text-danger">{{ $message }}</strong>
                                </span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    {{Form::label('mail_encryption',__('Mail Encryption')) }}
                                    {{Form::text('mail_encryption',env('MAIL_ENCRYPTION'),array('class'=>'form-control','placeholder'=>__('Enter Mail Encryption')))}}
                                    @error('mail_encryption')
                                    <span class="invalid-mail_encryption" role="alert">
                                    <strong class="text-danger">{{ $message }}</strong>
                                </span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    {{Form::label('mail_from_address',__('Mail From Address')) }}
                                    {{Form::text('mail_from_address',env('MAIL_FROM_ADDRESS'),array('class'=>'form-control','placeholder'=>__('Enter Mail From Address')))}}
                                    @error('mail_from_address')
                                    <span class="invalid-mail_from_address" role="alert">
                                    <strong class="text-danger">{{ $message }}</strong>
                                </span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    {{Form::label('mail_from_name',__('Mail From Name')) }}
                                    {{Form::text('mail_from_name',env('MAIL_FROM_NAME'),array('class'=>'form-control','placeholder'=>__('Enter Mail From Name')))}}
                                    @error('mail_from_name')
                                    <span class="invalid-mail_from_name" role="alert">
                                    <strong class="text-danger">{{ $message }}</strong>
                                </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <a href="#" data-url="{{route('test.mail' )}}" data-ajax-popup="true" data-title="{{__('Send Test Mail')}}" class="btn btn-sm btn-info rounded-pill">
                                        {{__('Send Test Mail')}}
                                    </a>
                                </div>
                                <div class="form-group col-md-6 text-right">
                                    {{Form::submit(__('Save Change'),array('class'=>'btn btn-sm btn-primary rounded-pill'))}}
                                </div>
                            </div>
                        </div>
                        {{Form::close()}}
                    </div>
                    <div id="recaptcha-settings" class="tab-pane">
                        <div class="col-md-12">
                            
                                <form method="POST" action="{{ route('recaptcha.settings.store') }}" accept-charset="UTF-8">
                                    @csrf
                                    <div class="row mt-5 px-3 ">
                                        <div class="col-lg-12 col-md-12 col-sm-12 form-group">
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input" name="recaptcha_module" id="recaptcha_module" value="yes" {{ env('RECAPTCHA_MODULE') == 'yes' ? 'checked="checked"' : '' }}>
                                                <label class="custom-control-label form-control-label" for="recaptcha_module">
                                                    {{ __('Google Recaptcha') }}
                                                    <a href="https://phppot.com/php/how-to-get-google-recaptcha-site-and-secret-key/" target="_blank" class="text-blue">
                                                        <small>({{__('How to Get Google reCaptcha Site and Secret key')}})</small>
                                                    </a>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-6 form-group">
                                            <label for="google_recaptcha_key" class="form-control-label">{{ __('Google Recaptcha Key') }}</label>
                                            <input class="form-control" placeholder="{{ __('Enter Google Recaptcha Key') }}" name="google_recaptcha_key" type="text" value="{{env('NOCAPTCHA_SITEKEY')}}" id="google_recaptcha_key">
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-6 form-group">
                                            <label for="google_recaptcha_secret" class="form-control-label">{{ __('Google Recaptcha Secret') }}</label>
                                            <input class="form-control " placeholder="{{ __('Enter Google Recaptcha Secret') }}" name="google_recaptcha_secret" type="text" value="{{env('NOCAPTCHA_SECRET')}}" id="google_recaptcha_secret">
                                        </div>
                                    </div>
                                    <div class="col-lg-12  text-right mb-5">
                                        {{Form::submit(__('Save Change'),array('class'=>'btn btn-sm btn-primary rounded-pill'))}}
                                    </div>
                                </form>
                            
                        </div>
                    </div>
                    <div id="email-template-settings" class="tab-pane">
                        <div class="row">
                            <div class="col-12">
                                <div class="">
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-striped">
                                                <thead>
                                                <tr>
                                                    <th width="92%"> {{__('Name')}}</th>
                                                    @if(\Auth::user()->type == 'Super Admin')
                                                        <th> {{__('Action')}}</th>
                                                    @elseif(\Auth::user()->type == 'Owner')
                                                        <th> {{__('On/Off')}}</th>
                                                    @endcan
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach ($EmailTemplates as $EmailTemplate)
                                                    <tr>
                                                        <td>{{ $EmailTemplate->name }}</td>
                                                        <td class="">
                                                            <a href="{{ route('manage.email.language',[$EmailTemplate->id,\Auth::user()->lang]) }}" class="edit-icon">
                                                                <i class="fas fa-eye"></i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                @endif
                @if(\Auth::user()->type=='Owner')
                    <div id="store_theme_setting" class="tab-pane fade show" role="tabpanel" aria-labelledby="orders-tab">
                        {{Form::open(array('route' => array('store.changetheme', $store_settings->id),'method' => 'POST'))}}
                        <div class="card-body">
                            <div class="row">
                                @foreach(\App\Models\Utility::themeOne() as $key => $v)
                                    <div class="col-4 cc-selector mb-2">
                                        <div class="mb-3">
                                            <img src="{{asset(Storage::url('uploads/store_theme/'.$key.'/Home.png'))}}" class="img-center pro_max_width pro_max_height {{$key}}_img">
                                        </div>
                                        <div class="form-group">
                                            <div class="row gutters-xs" id="{{$key}}">
                                                @foreach($v as $css => $val)
                                                    <div class="col-auto">
                                                        <label class="colorinput">
                                                            <input name="theme_color" type="radio" value="{{$css}}" data-theme="{{$key}}" data-imgpath="{{$val['img_path']}}" class="colorinput-input" {{(isset($store_settings['store_theme']) && $store_settings['store_theme'] == $css) ? 'checked' : ''}}>
                                                            <span class="colorinput-color" style="background:#{{$val['color']}}"></span>
                                                        </label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="card-footer text-right">
                            {{Form::submit(__('Save Change'),array('class'=>'btn btn-sm btn-primary rounded-pill'))}}
                        </div>
                        {{Form::close()}}
                    </div>
                    <div id="store_site_setting" class="tab-pane fade show" role="tabpanel" aria-labelledby="orders-tab">
                        {{Form::model($settings,array('route'=>'business.setting','method'=>'POST','enctype' => "multipart/form-data"))}}
                        <div class="card-body">
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="company_logo" class="form-control-label">{{ __('Logo') }}</label>
                                        <input type="file" name="company_logo" id="company_logo" class="custom-input-file">
                                        <label for="company_logo">
                                            <i class="fa fa-upload"></i>
                                            <span>{{__('Choose a file')}}</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-6 d-flex align-items-center justify-content-center mt-3">
                                    <div class="logo-div">
                                        <img src="{{$logo.'/'.(isset($company_logo) && !empty($company_logo)?$company_logo:'logo.png')}}" width="170px" class="img_setting">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="company_favicon" class="form-control-label">{{ __('Favicon') }}</label>
                                        <input type="file" name="company_favicon" id="company_favicon" class="custom-input-file">
                                        <label for="company_favicon">
                                            <i class="fa fa-upload"></i>
                                            <span>{{__('Choose a file')}}</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-6 d-flex align-items-center justify-content-center mt-3">
                                    <div class="logo-div">
                                        <img src="{{$logo.'/'.(isset($company_favicon) && !empty($company_favicon)?$company_favicon:'favicon.png')}}" width="50px" class="img_setting">
                                    </div>
                                </div>
                                <div class="col-12">
                                    @error('logo')
                                    <div class="row">
                                    <span class="invalid-logo" role="alert">
                                        <strong class="text-danger">{{ $message }}</strong>
                                     </span>
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    {{Form::label('title_text',__('Title Text'),array('class'=>'form-control-label')) }}
                                    {{Form::text('title_text',null,array('class'=>'form-control','placeholder'=>__('Title Text')))}}
                                    @error('title_text')
                                    <span class="invalid-title_text" role="alert">
                                     <strong class="text-danger">{{ $message }}</strong>
                                 </span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    {{Form::label('footer_text',__('Footer Text'),array('class'=>'form-control-label'))}}
                                    {{Form::text('footer_text',null,array('class'=>'form-control','placeholder'=>__('Footer Text')))}}
                                    @error('footer_text')
                                    <span class="invalid-footer_text" role="alert">
                                        <strong class="text-danger">{{ $message }}</strong>
                                     </span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="site_date_format" class="form-control-label">{{__('Date Format')}}</label>
                                    <select type="text" name="site_date_format" class="form-control selectric" id="site_date_format">
                                        <option value="M j, Y" @if(@$settings['site_date_format'] == 'M j, Y') selected="selected" @endif>Jan 1,2015</option>
                                        <option value="d-m-Y" @if(@$settings['site_date_format'] == 'd-m-Y') selected="selected" @endif>d-m-y</option>
                                        <option value="m-d-Y" @if(@$settings['site_date_format'] == 'm-d-Y') selected="selected" @endif>m-d-y</option>
                                        <option value="Y-m-d" @if(@$settings['site_date_format'] == 'Y-m-d') selected="selected" @endif>y-m-d</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="site_time_format" class="form-control-label">{{__('Time Format')}}</label>
                                    <select type="text" name="site_time_format" class="form-control selectric" id="site_time_format">
                                        <option value="g:i A" @if(@$settings['site_time_format'] == 'g:i A') selected="selected" @endif>10:30 PM</option>
                                        <option value="g:i a" @if(@$settings['site_time_format'] == 'g:i a') selected="selected" @endif>10:30 pm</option>
                                        <option value="H:i" @if(@$settings['site_time_format'] == 'H:i') selected="selected" @endif>22:30</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-right">
                            {{Form::submit(__('Save Change'),array('class'=>'btn btn-sm btn-primary rounded-pill'))}}
                        </div>
                        {{Form::close()}}
                    </div>
                    <div class="tab-pane fade show" id="store_payment-setting" role="tabpanel" aria-labelledby="orders-tab">
                        <div class="card-body">
                            {{Form::open(array('route'=>array('owner.payment.setting',$store_settings->slug),'method'=>'post'))}}
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        {{Form::label('currency_symbol',__('Currency Symbol *')) }}
                                        {{Form::text('currency_symbol',$store_settings['currency'],array('class'=>'form-control','required'))}}
                                        @error('currency_symbol')
                                        <span class="invalid-currency_symbol" role="alert">
                                            <strong class="text-danger">{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        {{Form::label('currency',__('Currency *')) }}
                                        {{Form::text('currency',$store_settings['currency_code'],array('class'=>'form-control font-style','required'))}}
                                        {{__('Note: Add currency code as per three-letter ISO code.')}}
                                        <small>
                                            <a href="https://stripe.com/docs/currencies" target="_blank">{{__('you can find out here..')}}</a>
                                        </small>
                                        @error('currency')
                                        <span class="invalid-currency" role="alert">
                                            <strong class="text-danger">{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-control-label" for="example3cols3Input">{{__('Currency Symbol Position')}}</label>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="custom-control custom-radio mb-3">
                                                    <input type="radio" id="customRadio5" name="currency_symbol_position" value="pre" class="custom-control-input" @if(@$store_settings['currency_symbol_position'] == 'pre') checked @endif>
                                                    <label class="custom-control-label" for="customRadio5">{{__('Pre')}}</label>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="custom-control custom-radio mb-3">
                                                    <input type="radio" id="customRadio6" name="currency_symbol_position" value="post" class="custom-control-input" @if(@$store_settings['currency_symbol_position'] == 'post') checked @endif>
                                                    <label class="custom-control-label" for="customRadio6">{{__('Post')}}</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-control-label" for="example3cols3Input">{{__('Currency Symbol Space')}}</label>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="custom-control custom-radio mb-3">
                                                    <input type="radio" id="customRadio7" name="currency_symbol_space" value="with" class="custom-control-input" @if(@$store_settings['currency_symbol_space'] == 'with') checked @endif>
                                                    <label class="custom-control-label" for="customRadio7">{{__('With Space')}}</label>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="custom-control custom-radio mb-3">
                                                    <input type="radio" id="customRadio8" name="currency_symbol_space" value="without" class="custom-control-input" @if(@$store_settings['currency_symbol_space'] == 'without') checked @endif>
                                                    <label class="custom-control-label" for="customRadio8">{{__('Without Space')}}</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <h6>{{__('Custom Field For Checkout')}}</h6>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        {{Form::label('custom_field_title_1',__('Custom Field Title'),array("class"=>"form-control-label")) }}
                                        {{Form::text('custom_field_title_1',!empty($store_settings['custom_field_title_1'])?$store_settings['custom_field_title_1']:'',array('class'=>'form-control','placeholder'=>__('Enter Custom Field Title')))}}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        {{Form::label('custom_field_title_2',__('Custom Field Title'),array("class"=>"form-control-label")) }}
                                        {{Form::text('custom_field_title_2',!empty($store_settings['custom_field_title_2'])?$store_settings['custom_field_title_2']:'',array('class'=>'form-control','placeholder'=>__('Enter Custom Field Title')))}}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        {{Form::label('custom_field_title_3',__('Custom Field Title'),array("class"=>"form-control-label")) }}
                                        {{Form::text('custom_field_title_3',!empty($store_settings['custom_field_title_3'])?$store_settings['custom_field_title_3']:'',array('class'=>'form-control','placeholder'=>__('Enter Custom Field Title')))}}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        {{Form::label('custom_field_title_4',__('Custom Field Title'),array("class"=>"form-control-label")) }}
                                        {{Form::text('custom_field_title_4',!empty($store_settings['custom_field_title_4'])?$store_settings['custom_field_title_4']:'',array('class'=>'form-control','placeholder'=>__('Enter Custom Field Title')))}}
                                    </div>
                                </div>
                            </div>
                            
                            <div id="accordion-2" class="accordion accordion-spaced">

                                <!-- Telegram -->
                                <div class="card">
                                    <div class="card-header py-4" id="heading-2-16" data-toggle="collapse" role="button" data-target="#collapse-2-16" aria-expanded="false" aria-controls="collapse-2-16">
                                        <h6 class="mb-0"><i class="far fa-credit-card mr-3"></i>{{__('Telegram')}}</h6>
                                    </div>
                                    <div id="collapse-2-16" class="collapse" aria-labelledby="heading-2-16" data-parent="#accordion-2">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-6 py-2">
                                                    <div class="mb-2">
                                                        <h5 class="h5 mb-0">{{__('Telegram')}}</h5>
                                                        <small> {{__('Note: This detail will use for make checkout of shopping cart.')}}</small>
                                                    </div>
                                                    <span>* {{__('Use Telegram-Bot code with your Telegram')}} *</span>

                                                    <div class="form-group">
                                                        {{Form::label('telegrambot',__('Telegram Access Token')) }}
                                                            {{Form::text('telegrambot',$store_settings['telegrambot'],array('class'=>'form-control active telegrambot','placeholder'=>'1234567890:AAbbbbccccddddxvGENZCi8Hd4B15M8xHV0'))}}
                                                            <p>{{__('Get Chat ID')}} : https://api.telegram.org/bot-TOKEN-/getUpdates</p>
                                                            @if ($errors->has('telegrambot'))
                                                                <span class="invalid-feedback d-block">
                                                                {{ $errors->first('telegrambot') }}
                                                            </span>
                                                            @endif
                                                    </div>
                                                    <div class="form-group">
                                                        {{Form::label('telegramchatid',__('Telegram Chat Id')) }}
                                                            {{Form::text('telegramchatid',$store_settings['telegramchatid'],array('class'=>'form-control active telegramchatid','placeholder'=>'123456789'))}}
                                                            @if ($errors->has('telegramchatid'))
                                                                <span class="invalid-feedback d-block">
                                                        {{ $errors->first('telegramchatid') }}
                                                        </span>
                                                            @endif
                                                    </div>
                                                </div>
                                                <div class="col-6 py-2 text-right">
                                                    <div class="custom-control custom-switch">
                                                        <input type="hidden" name="enable_telegram" value="off">
                                                    <input type="checkbox" class="custom-control-input" name="enable_telegram" id="enable_telegram" {{  $store_settings['enable_telegram'] == 'on' ? 'checked="checked"' : ''}}>
                                                    <label class="custom-control-label form-control-label" for="enable_telegram">{{__('Enable Telegram')}}</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Whatsapp -->
                                <div class="card">
                                    <div class="card-header py-4" id="heading-2-3" data-toggle="collapse" role="button" data-target="#collapse-2-4" aria-expanded="false" aria-controls="collapse-2-4">
                                        <h6 class="mb-0"><i class="far fa-credit-card mr-3"></i>{{__('Whatsapp')}}</h6>
                                    </div>
                                    <div id="collapse-2-4" class="collapse" aria-labelledby="heading-2-3" data-parent="#accordion-2">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-6 py-2">
                                                    <div class="mb-2">
                                                        <h5 class="h5 mb-0">{{__('Whatsapp')}}</h5>
                                                        <small> {{__('Note: This detail will use for make checkout of shopping cart.')}}</small>
                                                    </div>
                                                    <span>* {{__('Use country code with your number')}} *</span>

                                                    <div class="form-group">
                                                        {{Form::text('whatsapp_number',$store_settings['whatsapp_number'],array('class'=>'form-control active whatsapp_number','placeholder'=>'(+99) 12345 67890'))}}
                                                        @if ($errors->has('whatsapp_number'))
                                                            <span class="invalid-feedback d-block">
                                                            {{ $errors->first('whatsapp_number') }}
                                                        </span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-6 py-2 text-right">
                                                    <div class="custom-control custom-switch">
                                                        <input type="hidden" name="enable_whatsapp" value="off">
                                                        <input type="checkbox" class="custom-control-input" name="enable_whatsapp" id="enable_whatsapp" {{ $store_settings['enable_whatsapp'] == 'on' ? 'checked="checked"' : '' }}>
                                                        <label class="custom-control-label form-control-label" for="enable_whatsapp">{{__('Enable Whatsapp')}}</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>  

                                <!-- Strip -->
                                <div class="card">
                                    <div class="card-header py-4" id="heading-2-2" data-toggle="collapse" role="button" data-target="#collapse-2-2" aria-expanded="false" aria-controls="collapse-2-2">
                                        <h6 class="mb-0"><i class="far fa-credit-card mr-3"></i>{{__('Stripe')}}</h6>
                                    </div>
                                    <div id="collapse-2-2" class="collapse" aria-labelledby="heading-2-2" data-parent="#accordion-2">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-6 py-2">
                                                    <h5 class="h5">{{__('Stripe')}}</h5>
                                                    <small> {{__('Note: This detail will use for make checkout of shopping cart.')}}</small>
                                                </div>
                                                <div class="col-6 py-2 text-right">
                                                    <div class="custom-control custom-switch">
                                                        <input type="hidden" name="is_stripe_enabled" value="off">
                                                        <input type="checkbox" class="custom-control-input" name="is_stripe_enabled" id="is_stripe_enabled" {{ isset($store_payment_setting['is_stripe_enabled']) && $store_payment_setting['is_stripe_enabled'] == 'on' ? 'checked="checked"' : '' }}>
                                                        <label class="custom-control-label form-control-label" for="is_stripe_enabled">{{__('Enable Stripe')}}</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        {{Form::label('stripe_key',__('Stripe Key')) }}
                                                        {{Form::text('stripe_key',isset($store_payment_setting['stripe_key'])?$store_payment_setting['stripe_key']:'',['class'=>'form-control','placeholder'=>__('Enter Stripe Key')])}}
                                                        @error('stripe_key')
                                                        <span class="invalid-stripe_key" role="alert">
                                                                                             <strong class="text-danger">{{ $message }}</strong>
                                                                                         </span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        {{Form::label('stripe_secret',__('Stripe Secret')) }}
                                                        {{Form::text('stripe_secret',isset($store_payment_setting['stripe_secret'])?$store_payment_setting['stripe_secret']:'',['class'=>'form-control ','placeholder'=>__('Enter Stripe Secret')])}}
                                                        @error('stripe_secret')
                                                        <span class="invalid-stripe_secret" role="alert">
                                                             <strong class="text-danger">{{ $message }}</strong>
                                                         </span>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Paypal -->
                                <div class="card">
                                    <div class="card-header py-4" id="heading-2-3" data-toggle="collapse" role="button" data-target="#collapse-2-3" aria-expanded="false" aria-controls="collapse-2-3">
                                        <h6 class="mb-0"><i class="far fa-credit-card mr-3"></i>{{__('PayPal')}}</h6>
                                    </div>
                                    <div id="collapse-2-3" class="collapse" aria-labelledby="heading-2-3" data-parent="#accordion-2">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-6 py-2">
                                                    <h5 class="h5">{{__('PayPal')}}</h5>
                                                    <small> {{__('Note: This detail will use for make checkout of shopping cart.')}}</small>
                                                </div>
                                                <div class="col-6 py-2 text-right">
                                                    <div class="custom-control custom-switch">
                                                        <input type="hidden" name="is_paypal_enabled" value="off">
                                                        <input type="checkbox" class="custom-control-input" name="is_paypal_enabled" id="is_paypal_enabled" {{ isset($store_payment_setting['is_paypal_enabled']) && $store_payment_setting['is_paypal_enabled'] == 'on' ? 'checked="checked"' : '' }}>
                                                        <label class="custom-control-label form-control-label" for="is_paypal_enabled">{{__('Enable Paypal')}}</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 pb-4">
                                                    <label class="paypal-label form-control-label" for="paypal_mode">{{__('Paypal Mode')}}</label> <br>
                                                    <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                                        <label class="btn btn-primary btn-sm {{$store_settings['paypal_mode'] == 'sandbox' ? 'active' : ''}}">
                                                            <input type="radio" name="paypal_mode" value="sandbox" {{ isset($store_payment_setting['paypal_mode']) && $store_settings['paypal_mode'] == '' || isset($store_payment_setting['paypal_mode']) && $store_settings['paypal_mode'] == 'sandbox' ? 'checked="checked"' : '' }}>{{__('Sandbox')}}
                                                        </label>
                                                        <label class="btn btn-primary btn-sm {{isset($store_payment_setting['paypal_mode']) && $store_payment_setting['paypal_mode'] == 'live' ? 'active' : ''}}">
                                                            <input type="radio" name="paypal_mode" value="live" {{ isset($store_payment_setting['paypal_mode']) && $store_settings['paypal_mode'] == 'live' ? 'checked="checked"' : '' }}>{{__('Live')}}
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="paypal_client_id">{{ __('Client ID') }}</label>
                                                        <input type="text" name="paypal_client_id" id="paypal_client_id" class="form-control" value="{{isset($store_settings['paypal_client_id'])?$store_settings['paypal_client_id']:''}}" placeholder="{{ __('Client ID') }}"/>
                                                        @if ($errors->has('paypal_client_id'))
                                                            <span class="invalid-feedback d-block">
                                                                {{ $errors->first('paypal_client_id') }}
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="paypal_secret_key">{{ __('Secret Key') }}</label>
                                                        <input type="text" name="paypal_secret_key" id="paypal_secret_key" class="form-control" value="{{isset($store_settings['paypal_secret_key'])?$store_settings['paypal_secret_key']:''}}" placeholder="{{ __('Secret Key') }}"/>
                                                        @if ($errors->has('paypal_secret_key'))
                                                            <span class="invalid-feedback d-block">
                                                                {{ $errors->first('paypal_secret_key') }}
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Paystack -->
                                <div class="card">
                                    <div class="card-header py-4" id="heading-2-6" data-toggle="collapse" role="button" data-target="#collapse-2-6" aria-expanded="false" aria-controls="collapse-2-6">
                                        <h6 class="mb-0"><i class="far fa-credit-card mr-3"></i>{{__('Paystack')}}</h6>
                                    </div>
                                    <div id="collapse-2-6" class="collapse" aria-labelledby="heading-2-6" data-parent="#accordion-2">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-6 py-2">
                                                    <h5 class="h5">{{__('Paystack')}}</h5>
                                                    <small> {{__('Note: This detail will use for make checkout of shopping cart.')}}</small>
                                                </div>
                                                <div class="col-6 py-2 text-right">
                                                    <div class="custom-control custom-switch">
                                                        <input type="hidden" name="is_paystack_enabled" value="off">
                                                        <input type="checkbox" class="custom-control-input" name="is_paystack_enabled" id="is_paystack_enabled" {{ isset($store_payment_setting['is_paystack_enabled']) && $store_payment_setting['is_paystack_enabled'] == 'on' ? 'checked="checked"' : '' }}>
                                                        <label class="custom-control-label form-control-label" for="is_paystack_enabled">{{__('Enable Paystack')}}</label>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="paypal_client_id">{{ __('Public Key') }}</label>
                                                        <input type="text" name="paystack_public_key" id="paystack_public_key" class="form-control" value="{{isset($store_payment_setting['paystack_public_key']) ? $store_payment_setting['paystack_public_key']:''}}" placeholder="{{ __('Public Key') }}"/>
                                                        @if ($errors->has('paystack_public_key'))
                                                            <span class="invalid-feedback d-block">
                                                                {{ $errors->first('paystack_public_key') }}
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="paystack_secret_key">{{ __('Secret Key') }}</label>
                                                        <input type="text" name="paystack_secret_key" id="paystack_secret_key" class="form-control" value="{{isset($store_payment_setting['paystack_secret_key']) ? $store_payment_setting['paystack_secret_key']:''}}" placeholder="{{ __('Secret Key') }}"/>
                                                        @if ($errors->has('paystack_secret_key'))
                                                            <span class="invalid-feedback d-block">
                                                                {{ $errors->first('paystack_secret_key') }}
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- FLUTTERWAVE -->
                                <div class="card">
                                    <div class="card-header py-4" id="heading-2-7" data-toggle="collapse" role="button" data-target="#collapse-2-7" aria-expanded="false" aria-controls="collapse-2-7">
                                        <h6 class="mb-0"><i class="far fa-credit-card mr-3"></i>{{__('Flutterwave')}}</h6>
                                    </div>
                                    <div id="collapse-2-7" class="collapse" aria-labelledby="heading-2-7" data-parent="#accordion-2">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-6 py-2">
                                                    <h5 class="h5">{{__('Flutterwave')}}</h5>
                                                    <small> {{__('Note: This detail will use for make checkout of shopping cart.')}}</small>
                                                </div>
                                                <div class="col-6 py-2 text-right">
                                                    <div class="custom-control custom-switch">
                                                        <input type="hidden" name="is_flutterwave_enabled" value="off">
                                                        <input type="checkbox" class="custom-control-input" name="is_flutterwave_enabled" id="is_flutterwave_enabled" {{ isset($store_payment_setting['is_flutterwave_enabled'])  && $store_payment_setting['is_flutterwave_enabled']== 'on' ? 'checked="checked"' : '' }}>
                                                        <label class="custom-control-label form-control-label" for="is_flutterwave_enabled">{{__('Enable Flutterwave')}}</label>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="paypal_client_id">{{ __('Public Key') }}</label>
                                                        <input type="text" name="flutterwave_public_key" id="flutterwave_public_key" class="form-control" value="{{isset($store_payment_setting['flutterwave_public_key'])?$store_payment_setting['flutterwave_public_key']:''}}" placeholder="{{ __('Public Key') }}"/>
                                                        @if ($errors->has('flutterwave_public_key'))
                                                            <span class="invalid-feedback d-block">
                                                                {{ $errors->first('flutterwave_public_key') }}
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="paystack_secret_key">{{ __('Secret Key') }}</label>
                                                        <input type="text" name="flutterwave_secret_key" id="flutterwave_secret_key" class="form-control" value="{{isset($store_payment_setting['flutterwave_secret_key'])?$store_payment_setting['flutterwave_secret_key']:''}}" placeholder="{{ __('Secret Key') }}"/>
                                                        @if ($errors->has('flutterwave_secret_key'))
                                                            <span class="invalid-feedback d-block">
                                                                {{ $errors->first('flutterwave_secret_key') }}
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Razorpay -->
                                <div class="card">
                                    <div class="card-header py-4" id="heading-2-8" data-toggle="collapse" role="button" data-target="#collapse-2-8" aria-expanded="false" aria-controls="collapse-2-8">
                                        <h6 class="mb-0"><i class="far fa-credit-card mr-3"></i>{{__('Razorpay')}}</h6>
                                    </div>
                                    <div id="collapse-2-8" class="collapse" aria-labelledby="heading-2-7" data-parent="#accordion-2">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-6 py-2">
                                                    <h5 class="h5">{{__('Razorpay')}}</h5>
                                                    <small> {{__('Note: This detail will use for make checkout of shopping cart.')}}</small>
                                                </div>
                                                <div class="col-6 py-2 text-right">
                                                    <div class="custom-control custom-switch">
                                                        <input type="hidden" name="is_razorpay_enabled" value="off">
                                                        <input type="checkbox" class="custom-control-input" name="is_razorpay_enabled" id="is_razorpay_enabled" {{ isset($store_payment_setting['is_razorpay_enabled']) && $store_payment_setting['is_razorpay_enabled'] == 'on' ? 'checked="checked"' : '' }}>
                                                        <label class="custom-control-label form-control-label" for="is_razorpay_enabled">{{__('Enable Razorpay')}}</label>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="paypal_client_id">{{ __('Public Key') }}</label>

                                                        <input type="text" name="razorpay_public_key" id="razorpay_public_key" class="form-control" value="{{ isset($store_payment_setting['razorpay_public_key'])?$store_payment_setting['razorpay_public_key']:''}}" placeholder="{{ __('Public Key') }}"/>
                                                        @if ($errors->has('razorpay_public_key'))
                                                            <span class="invalid-feedback d-block">
                                                                {{ $errors->first('razorpay_public_key') }}
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="paystack_secret_key">{{ __('Secret Key') }}</label>
                                                        <input type="text" name="razorpay_secret_key" id="razorpay_secret_key" class="form-control" value="{{ isset($store_payment_setting['razorpay_secret_key'])?$store_payment_setting['razorpay_secret_key']:''}}" placeholder="{{ __('Secret Key') }}"/>
                                                        @if ($errors->has('razorpay_secret_key'))
                                                            <span class="invalid-feedback d-block">
                                                                {{ $errors->first('razorpay_secret_key') }}
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Paytm -->
                                <div class="card">
                                    <div class="card-header py-4" id="heading-2-14" data-toggle="collapse" role="button" data-target="#collapse-2-14" aria-expanded="false" aria-controls="collapse-2-14">
                                        <h6 class="mb-0"><i class="far fa-credit-card mr-3"></i>{{__('Paytm')}}</h6>
                                    </div>
                                    <div id="collapse-2-14" class="collapse" aria-labelledby="heading-2-14" data-parent="#accordion-2">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-6 py-2">
                                                    <h5 class="h5">{{__('Paytm')}}</h5>
                                                    <small> {{__('Note: This detail will use for make checkout of shopping cart.')}}</small>
                                                </div>
                                                <div class="col-6 py-2 text-right">
                                                    <div class="custom-control custom-switch">
                                                        <input type="hidden" name="is_paytm_enabled" value="off">
                                                        <input type="checkbox" class="custom-control-input" name="is_paytm_enabled" id="is_paytm_enabled" {{ isset($store_payment_setting['is_paytm_enabled']) && $store_payment_setting['is_paytm_enabled'] == 'on' ? 'checked="checked"' : '' }}>
                                                        <label class="custom-control-label form-control-label" for="is_paytm_enabled">{{__('Enable Paytm')}}</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 pb-4">
                                                    <label class="paypal-label form-control-label" for="paypal_mode">{{__('Paytm Environment')}}</label> <br>
                                                    <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                                        <label class="btn btn-primary btn-sm {{isset($store_payment_setting['paytm_mode']) && $store_payment_setting['paytm_mode'] == 'local' ? 'active' : ''}}">
                                                            <input type="radio" name="paytm_mode" value="local" {{ isset($store_payment_setting['paytm_mode']) && $store_payment_setting['paytm_mode'] == '' || isset($store_payment_setting['paytm_mode']) && $store_payment_setting['paytm_mode'] == 'local' ? 'checked="checked"' : '' }}>{{__('Local')}}
                                                        </label>
                                                        <label class="btn btn-primary btn-sm {{isset($store_payment_setting['paytm_mode']) && $store_payment_setting['paytm_mode'] == 'live' ? 'active' : ''}}">
                                                            <input type="radio" name="paytm_mode" value="production" {{ isset($store_payment_setting['paytm_mode']) && $store_payment_setting['paytm_mode'] == 'production' ? 'checked="checked"' : '' }}>{{__('Production')}}
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="paytm_public_key">{{ __('Merchant ID') }}</label>
                                                        <input type="text" name="paytm_merchant_id" id="paytm_merchant_id" class="form-control" value="{{isset($store_payment_setting['paytm_merchant_id'])? $store_payment_setting['paytm_merchant_id']:''}}" placeholder="{{ __('Merchant ID') }}"/>
                                                        @if ($errors->has('paytm_merchant_id'))
                                                            <span class="invalid-feedback d-block">
                                                                {{ $errors->first('paytm_merchant_id') }}
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="paytm_secret_key">{{ __('Merchant Key') }}</label>
                                                        <input type="text" name="paytm_merchant_key" id="paytm_merchant_key" class="form-control" value="{{ isset($store_payment_setting['paytm_merchant_key']) ? $store_payment_setting['paytm_merchant_key']:''}}" placeholder="{{ __('Merchant Key') }}"/>
                                                        @if ($errors->has('paytm_merchant_key'))
                                                            <span class="invalid-feedback d-block">
                                                                {{ $errors->first('paytm_merchant_key') }}
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="paytm_industry_type">{{ __('Industry Type') }}</label>
                                                        <input type="text" name="paytm_industry_type" id="paytm_industry_type" class="form-control" value="{{isset($store_payment_setting['paytm_industry_type']) ?$store_payment_setting['paytm_industry_type']:''}}" placeholder="{{ __('Industry Type') }}"/>
                                                        @if ($errors->has('paytm_industry_type'))
                                                            <span class="invalid-feedback d-block">
                                                                {{ $errors->first('paytm_industry_type') }}
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Mercado Pago-->
                                <div class="card">
                                    <div class="card-header py-4" id="heading-2-12" data-toggle="collapse" role="button" data-target="#collapse-2-12" aria-expanded="false" aria-controls="collapse-2-12">
                                        <h6 class="mb-0"><i class="far fa-credit-card mr-3"></i>{{__('Mercado Pago')}}</h6>
                                    </div>
                                    <div id="collapse-2-12" class="collapse" aria-labelledby="heading-2-12" data-parent="#accordion-2">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-6 py-2">
                                                    <h5 class="h5">{{__('Mercado Pago')}}</h5>
                                                    <small> {{__('Note: This detail will use for make checkout of shopping cart.')}}</small>
                                                </div>
                                                <div class="col-6 py-2 text-right">
                                                    <div class="custom-control custom-switch">
                                                        <input type="hidden" name="is_mercado_enabled" value="off">
                                                        <input type="checkbox" class="custom-control-input" name="is_mercado_enabled" id="is_mercado_enabled" {{isset($store_payment_setting['is_mercado_enabled']) &&  $store_payment_setting['is_mercado_enabled'] == 'on' ? 'checked="checked"' : '' }}>
                                                        <label class="custom-control-label form-control-label" for="is_mercado_enabled">{{__('Enable Mercado Pago')}}</label>
                                                    </div>
                                                </div>

                                                <div class="col-md-12 pb-4">
                                                    <label class="coingate-label form-control-label" for="mercado_mode">{{__('Mercado Mode')}}</label> <br>
                                                    <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                                        <label class="btn btn-primary btn-sm {{isset($store_payment_setting['mercado_mode']) && $store_payment_setting['mercado_mode'] == 'sandbox' ? 'active' : ''}}">
                                                            <input type="radio" name="mercado_mode" value="sandbox" {{ isset($store_payment_setting['mercado_mode']) && $store_payment_setting['mercado_mode'] == '' || isset($store_payment_setting['mercado_mode']) && $store_payment_setting['mercado_mode'] == 'sandbox' ? 'checked="checked"' : '' }}>{{__('Sandbox')}}
                                                        </label>
                                                        <label class="btn btn-primary btn-sm {{isset($store_payment_setting['mercado_mode']) && $store_payment_setting['mercado_mode'] == 'live' ? 'active' : ''}}">
                                                            <input type="radio" name="mercado_mode" value="live" {{ isset($store_payment_setting['mercado_mode']) && $store_payment_setting['mercado_mode'] == 'live' ? 'checked="checked"' : '' }}>{{__('Live')}}
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="mercado_access_token">{{ __('Access Token') }}</label>
                                                        <input type="text" name="mercado_access_token" id="mercado_access_token" class="form-control" value="{{isset($store_payment_setting['mercado_access_token']) ? $store_payment_setting['mercado_access_token']:''}}" placeholder="{{ __('Access Token') }}"/>
                                                        @if ($errors->has('mercado_secret_key'))
                                                            <span class="invalid-feedback d-block">
                                                                    {{ $errors->first('mercado_access_token') }}
                                                                </span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Mollie -->
                                <div class="card">
                                    <div class="card-header py-4" id="heading-2-8" data-toggle="collapse" role="button" data-target="#collapse-2-10" aria-expanded="false" aria-controls="collapse-2-10">
                                        <h6 class="mb-0"><i class="far fa-credit-card mr-3"></i>{{__('Mollie')}}</h6>
                                    </div>
                                    <div id="collapse-2-10" class="collapse" aria-labelledby="heading-2-7" data-parent="#accordion-2">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-6 py-2">
                                                    <h5 class="h5">{{__('Mollie')}}</h5>
                                                    <small> {{__('Note: This detail will use for make checkout of shopping cart.')}}</small>
                                                </div>
                                                <div class="col-6 py-2 text-right">
                                                    <div class="custom-control custom-switch">
                                                        <input type="hidden" name="is_mollie_enabled" value="off">
                                                        <input type="checkbox" class="custom-control-input" name="is_mollie_enabled" id="is_mollie_enabled" {{ isset($store_payment_setting['is_mollie_enabled']) && $store_payment_setting['is_mollie_enabled'] == 'on' ? 'checked="checked"' : '' }}>
                                                        <label class="custom-control-label form-control-label" for="is_mollie_enabled">{{__('Enable Mollie')}}</label>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="mollie_api_key">{{ __('Mollie Api Key') }}</label>
                                                        <input type="text" name="mollie_api_key" id="mollie_api_key" class="form-control" value="{{ isset($store_payment_setting['mollie_api_key'])?$store_payment_setting['mollie_api_key']:''}}" placeholder="{{ __('Mollie Api Key') }}"/>
                                                        @if ($errors->has('mollie_api_key'))
                                                            <span class="invalid-feedback d-block">
                                                                    {{ $errors->first('mollie_api_key') }}
                                                                </span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="mollie_profile_id">{{ __('Mollie Profile Id') }}</label>
                                                        <input type="text" name="mollie_profile_id" id="mollie_profile_id" class="form-control" value="{{ isset($store_payment_setting['mollie_profile_id'])?$store_payment_setting['mollie_profile_id']:''}}" placeholder="{{ __('Mollie Profile Id') }}"/>
                                                        @if ($errors->has('mollie_profile_id'))
                                                            <span class="invalid-feedback d-block">
                                                                    {{ $errors->first('mollie_profile_id') }}
                                                                </span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="mollie_partner_id">{{ __('Mollie Partner Id') }}</label>
                                                        <input type="text" name="mollie_partner_id" id="mollie_partner_id" class="form-control" value="{{ isset($store_payment_setting['mollie_partner_id'])?$store_payment_setting['mollie_partner_id']:''}}" placeholder="{{ __('Mollie Partner Id') }}"/>
                                                        @if ($errors->has('mollie_partner_id'))
                                                            <span class="invalid-feedback d-block">
                                                                    {{ $errors->first('mollie_partner_id') }}
                                                                </span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Skrill -->
                                <div class="card">
                                    <div class="card-header py-4" id="heading-2-8" data-toggle="collapse" role="button" data-target="#collapse-2-13" aria-expanded="false" aria-controls="collapse-2-10">
                                        <h6 class="mb-0"><i class="far fa-credit-card mr-3"></i>{{__('Skrill')}}</h6>
                                    </div>
                                    <div id="collapse-2-13" class="collapse" aria-labelledby="heading-2-7" data-parent="#accordion-2">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-6 py-2">
                                                    <h5 class="h5">{{__('Skrill')}}</h5>
                                                    <small> {{__('Note: This detail will use for make checkout of shopping cart.')}}</small>
                                                </div>
                                                <div class="col-6 py-2 text-right">
                                                    <div class="custom-control custom-switch">
                                                        <input type="hidden" name="is_skrill_enabled" value="off">
                                                        <input type="checkbox" class="custom-control-input" name="is_skrill_enabled" id="is_skrill_enabled" {{ isset($store_payment_setting['is_skrill_enabled']) && $store_payment_setting['is_skrill_enabled'] == 'on' ? 'checked="checked"' : '' }}>
                                                        <label class="custom-control-label form-control-label" for="is_skrill_enabled">{{__('Enable Skrill')}}</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="mollie_api_key">{{ __('Skrill Email') }}</label>
                                                        <input type="email" name="skrill_email" id="skrill_email" class="form-control" value="{{ isset($store_payment_setting['skrill_email'])?$store_payment_setting['skrill_email']:''}}" placeholder="{{ __('Enter Skrill Email') }}"/>
                                                        @if ($errors->has('skrill_email'))
                                                            <span class="invalid-feedback d-block">
                                                                    {{ $errors->first('skrill_email') }}
                                                                </span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- CoinGate -->
                                <div class="card">
                                    <div class="card-header py-4" id="heading-2-8" data-toggle="collapse" role="button" data-target="#collapse-2-15" aria-expanded="false" aria-controls="collapse-2-10">
                                        <h6 class="mb-0"><i class="far fa-credit-card mr-3"></i>{{__('CoinGate')}}</h6>
                                    </div>
                                    <div id="collapse-2-15" class="collapse" aria-labelledby="heading-2-7" data-parent="#accordion-2">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-6 py-2">
                                                    <h5 class="h5">{{__('CoinGate')}}</h5>
                                                    <small> {{__('Note: This detail will use for make checkout of shopping cart.')}}</small>
                                                </div>
                                                <div class="col-6 py-2 text-right">
                                                    <div class="custom-control custom-switch">
                                                        <input type="hidden" name="is_coingate_enabled" value="off">
                                                        <input type="checkbox" class="custom-control-input" name="is_coingate_enabled" id="is_coingate_enabled" {{ isset($store_payment_setting['is_coingate_enabled']) && $store_payment_setting['is_coingate_enabled'] == 'on' ? 'checked="checked"' : '' }}>
                                                        <label class="custom-control-label form-control-label" for="is_coingate_enabled">{{__('Enable CoinGate')}}</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 pb-4">
                                                    <label class="coingate-label form-control-label" for="coingate_mode">{{__('CoinGate Mode')}}</label> <br>
                                                    <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                                        <label class="btn btn-primary btn-sm {{isset($store_payment_setting['coingate_mode']) == 'sandbox' ? 'active' : ''}}">
                                                            <input type="radio" name="coingate_mode" value="sandbox" {{ isset($store_payment_setting['coingate_mode']) && $store_settings['coingate_mode'] == '' || isset($store_payment_setting['coingate_mode']) && $store_settings['coingate_mode'] == 'sandbox' ? 'checked="checked"' : '' }}>{{__('Sandbox')}}
                                                        </label>
                                                        <label class="btn btn-primary btn-sm {{isset($store_payment_setting['coingate_mode']) && $store_payment_setting['coingate_mode'] == 'live' ? 'active' : ''}}">
                                                            <input type="radio" name="coingate_mode" value="live" {{ isset($store_payment_setting['coingate_mode']) && $store_settings['coingate_mode'] == 'live' ? 'checked="checked"' : '' }}>{{__('Live')}}
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="coingate_auth_token">{{ __('CoinGate Auth Token') }}</label>
                                                        <input type="text" name="coingate_auth_token" id="coingate_auth_token" class="form-control" value="{{ isset($store_payment_setting['coingate_auth_token'])?$store_payment_setting['coingate_auth_token']:''}}" placeholder="{{ __('CoinGate Auth Token') }}"/>
                                                        @if($errors->has('coingate_auth_token'))
                                                            <span class="invalid-feedback d-block">
                                                                {{ $errors->first('coingate_auth_token') }}
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Paymentwall -->
                                        <div class="card">
                                            <div class="card-header py-4" id="heading-2-8" data-toggle="collapse" role="button" data-target="#collapse-2-16" aria-expanded="false" aria-controls="collapse-2-10">
                                                <h6 class="mb-0"><i class="far fa-credit-card mr-3"></i>{{__('Paymentwall')}}</h6>
                                            </div>
                                            <div id="collapse-2-16" class="collapse" aria-labelledby="heading-2-7" data-parent="#accordion-2">
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-6 py-2">
                                                            <h5 class="h5">{{__('Paymentwall')}}</h5>
                                                        </div>
                                                        <div class="col-6 py-2 text-right">
                                                            <div class="custom-control custom-switch">
                                                            <input type="hidden" name="is_paymentwall_enabled" value="off">
                                                            <input type="checkbox" class="custom-control-input" name="is_paymentwall_enabled" id="is_paymentwall_enabled" {{ isset($store_payment_setting['is_paymentwall_enabled'])  && $store_payment_setting['is_paymentwall_enabled']== 'on' ? 'checked="checked"' : '' }}>
                                                            <label class="custom-control-label form-control-label" for="is_paymentwall_enabled">{{__('Enable PaymentWall')}}</label>
                                                        </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="paymentwall_public_key" class="form-control-label">{{ __('Public Key') }}</label>
                                                            <input type="text" name="paymentwall_public_key" id="paymentwall_public_key" class="form-control" value="{{isset($store_payment_setting['paymentwall_public_key'])?$store_payment_setting['paymentwall_public_key']:''}}" placeholder="{{ __('Public Key') }}"/>
                                                            @if ($errors->has('paymentwall_public_key'))
                                                                <span class="invalid-feedback d-block">
                                                                    {{ $errors->first('paymentwall_public_key') }}
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="paymentwall_private_key" class="form-control-label">{{ __('Private Key') }}</label>
                                                            <input type="text" name="paymentwall_private_key" id="paymentwall_private_key" class="form-control form-control-label" value="{{isset($store_payment_setting['paymentwall_private_key'])?$store_payment_setting['paymentwall_private_key']:''}}" placeholder="{{ __('Private Key') }}"/>
                                                            @if ($errors->has('paymentwall_private_key'))
                                                                <span class="invalid-feedback d-block">
                                                                    {{ $errors->first('paymentwall_private_key') }}
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                            </div>
                            <div class="card-footer text-right">
                                {{Form::submit(__('Save Change'),array('class'=>'btn btn-sm btn-primary rounded-pill'))}}
                            </div>
                            {{Form::close()}}
                        </div>
                    </div>
                    <div class="tab-pane fade show" id="store_email_setting" role="tabpanel" aria-labelledby="orders-tab">
                        {{Form::open(array('route'=>array('owner.email.setting',$store_settings->slug),'method'=>'post'))}}
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-md-6">
                                    {{Form::label('mail_driver',__('Mail Driver')) }}
                                    {{Form::text('mail_driver',$store_settings->mail_driver,array('class'=>'form-control','placeholder'=>__('Enter Mail Driver')))}}
                                    @error('mail_driver')
                                    <span class="invalid-mail_driver" role="alert">
                                     <strong class="text-danger">{{ $message }}</strong>
                                     </span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    {{Form::label('mail_host',__('Mail Host')) }}
                                    {{Form::text('mail_host',$store_settings->mail_host,array('class'=>'form-control ','placeholder'=>__('Enter Mail Host')))}}
                                    @error('mail_host')
                                    <span class="invalid-mail_driver" role="alert">
                                        <strong class="text-danger">{{ $message }}</strong>
                                 </span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    {{Form::label('mail_port',__('Mail Port')) }}
                                    {{Form::text('mail_port',$store_settings->mail_port,array('class'=>'form-control','placeholder'=>__('Enter Mail Port')))}}
                                    @error('mail_port')
                                    <span class="invalid-mail_port" role="alert">
                                    <strong class="text-danger">{{ $message }}</strong>
                                </span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    {{Form::label('mail_username',__('Mail Username')) }}
                                    {{Form::text('mail_username',$store_settings->mail_username,array('class'=>'form-control','placeholder'=>__('Enter Mail Username')))}}
                                    @error('mail_username')
                                    <span class="invalid-mail_username" role="alert">
                                    <strong class="text-danger">{{ $message }}</strong>
                                </span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    {{Form::label('mail_password',__('Mail Password')) }}
                                    {{Form::text('mail_password',$store_settings->mail_password,array('class'=>'form-control','placeholder'=>__('Enter Mail Password')))}}
                                    @error('mail_password')
                                    <span class="invalid-mail_password" role="alert">
                                    <strong class="text-danger">{{ $message }}</strong>
                                </span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    {{Form::label('mail_encryption',__('Mail Encryption')) }}
                                    {{Form::text('mail_encryption',$store_settings->mail_encryption,array('class'=>'form-control','placeholder'=>__('Enter Mail Encryption')))}}
                                    @error('mail_encryption')
                                    <span class="invalid-mail_encryption" role="alert">
                                    <strong class="text-danger">{{ $message }}</strong>
                                </span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    {{Form::label('mail_from_address',__('Mail From Address')) }}
                                    {{Form::text('mail_from_address',$store_settings->mail_from_address,array('class'=>'form-control','placeholder'=>__('Enter Mail From Address')))}}
                                    @error('mail_from_address')
                                    <span class="invalid-mail_from_address" role="alert">
                                    <strong class="text-danger">{{ $message }}</strong>
                                </span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    {{Form::label('mail_from_name',__('Mail From Name')) }}
                                    {{Form::text('mail_from_name',$store_settings->mail_from_name,array('class'=>'form-control','placeholder'=>__('Enter Mail From Name')))}}
                                    @error('mail_from_name')
                                    <span class="invalid-mail_from_name" role="alert">
                                    <strong class="text-danger">{{ $message }}</strong>
                                </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <a href="#" data-url="{{route('test.mail' )}}" data-ajax-popup="true" data-title="{{__('Send Test Mail')}}" class="btn btn-sm btn-info rounded-pill">
                                        {{__('Send Test Mail')}}
                                    </a>
                                </div>
                                <div class="form-group col-md-6 text-right">
                                    {{Form::submit(__('Save Change'),array('class'=>'btn btn-sm btn-primary rounded-pill'))}}
                                </div>
                            </div>
                        </div>
                        {{Form::close()}}
                    </div>
                    <div class="tab-pane fade show" id="whatsapp_custom_massage" role="tabpanel" aria-labelledby="orders-tab">
                        {{Form::model($store_settings, array('route' => array('customMassage',$store_settings->slug), 'method' => 'POST')) }}
                        <div class="col-12 p-4">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row text-xs">
                                        <div class="col-6">
                                            <h6 class="font-weight-bold">{{__('Order Variable')}}</h6>
                                            <div class="col-6 float-left">
                                                <p class="mb-1">{{__('Store Name')}} : <span class="pull-right text-primary">{store_name}</span></p>
                                                <p class="mb-1">{{__('Order No')}} : <span class="pull-right text-primary">{order_no}</span></p>
                                                <p class="mb-1">{{__('Customer Name')}} : <span class="pull-right text-primary">{customer_name}</span></p>
                                                <p class="mb-1">{{__('Phone')}} : <span class="pull-right text-primary">{phone}</span></p>
                                                <p class="mb-1">{{__('Billing Address')}} : <span class="pull-right text-primary">{billing_address}</span></p>
                                                <p class="mb-1">{{__('Shipping Address')}} : <span class="pull-right text-primary">{shipping_address}</span></p>
                                                <p class="mb-1">{{__('Special Instruct')}} : <span class="pull-right text-primary">{special_instruct}</span></p>
                                            </div>
                                            <p class="mb-1">{{__('Item Variable')}} : <span class="pull-right text-primary">{item_variable}</span></p>
                                            <p class="mb-1">{{__('Qty Total')}} : <span class="pull-right text-primary">{qty_total}</span></p>
                                            <p class="mb-1">{{__('Sub Total')}} : <span class="pull-right text-primary">{sub_total}</span></p>
                                            <p class="mb-1">{{__('Discount Amount')}} : <span class="pull-right text-primary">{discount_amount}</span></p>
                                            <p class="mb-1">{{__('Shipping Amount')}} : <span class="pull-right text-primary">{shipping_amount}</span></p>
                                            <p class="mb-1">{{__('Total Tax')}} : <span class="pull-right text-primary">{total_tax}</span></p>
                                            <p class="mb-1">{{__('Final Total')}} : <span class="pull-right text-primary">{final_total}</span></p>
                                        </div>
                                        <div class="col-6">
                                            <h6 class="font-weight-bold">{{__('Item Variable')}}</h6>
                                            <p class="mb-1">{{__('Sku')}} : <span class="pull-right text-primary">{sku}</span></p>
                                            <p class="mb-1">{{__('Quantity')}} : <span class="pull-right text-primary">{quantity}</span></p>
                                            <p class="mb-1">{{__('Product Name')}} : <span class="pull-right text-primary">{product_name}</span></p>
                                            <p class="mb-1">{{__('Variant Name')}} : <span class="pull-right text-primary">{variant_name}</span></p>
                                            <p class="mb-1">{{__('Item Tax')}} : <span class="pull-right text-primary">{item_tax}</span></p>
                                            <p class="mb-1">{{__('Item total')}} : <span class="pull-right text-primary">{item_total}</span></p>
                                            <div class="form-group">
                                                <label for="storejs" class="form-control-label">{item_variable}</label>
                                                {{Form::text('item_variable',null,array('class'=>'form-control','placeholder'=>"{quantity} x {product_name} - {variant_name} + {item_tax} = {item_total}"))}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 px-4 language-form-wrap">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="form-group col-12">
                                            {{Form::label('content',__('Whatsapp Message'),['class'=>'form-control-label text-dark'])}}
                                            {{Form::textarea('content',null,array('class'=>'form-control','required'=>'required'))}}
                                        </div>
                                        <div class="col-md-12 text-right">
                                            <div class="form-group col-md-12 text-right">
                                                {{Form::submit(__('Save Change'),array('class'=>'btn btn-sm btn-primary rounded-pill'))}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{ Form::close() }}
                    </div>
                     <div class="tab-pane fade" id="twilio_setting" role="tabpanel" aria-labelledby="orders-tab">
                        <div class="col-12">
                                <div class="card-body">
                                    <div class="p-3">
                                        <form method="POST" action="{{ route('owner.twilio.setting',$store_settings->slug) }}" accept-charset="UTF-8">
                                            @csrf
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-sm-12 form-group">
                                                    <div class="custom-control custom-switch">
                                                        <input type="checkbox" class="custom-control-input" name="is_twilio_enabled" id="twilio_module"  {{ ($store_settings['is_twilio_enabled'] == 'on') ? 'checked=checked' : '' }}>
                                                        <label class="custom-control-label form-control-label" for="twilio_module">
                                                            {{ __('Twilio') }}
                                                            </a>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-md-6 col-sm-6 form-group">
                                                    <label for="twilio_token" class="form-control-label">{{ __('Twillo SID') }}</label>
                                                    <input class="form-control" name="twilio_sid" type="text" value="{{$store_settings->twilio_sid}}" id="twilio_sid">
                                                </div>
                                                <div class="col-lg-4 col-md-6 col-sm-6 form-group">
                                                    <label for="twilio_token" class="form-control-label">{{ __('Twillo Token') }}</label>
                                                    <input class="form-control "  name="twilio_token" type="text" value="{{$store_settings->twilio_token}}" id="twilio_token">
                                                </div>
                                                <div class="col-lg-4 col-md-6 col-sm-6 form-group">
                                                    <label for="twilio_from" class="form-control-label">{{ __('Twillo From') }}</label>
                                                    <input class="form-control " name="twilio_from" type="text" value="{{$store_settings->twilio_from}}" id="twilio_from">
                                                </div>
                                                 <div class="col-lg-4 col-md-6 col-sm-6 form-group">
                                                    <label for="notification_number" class="form-control-label">{{ __('Notification Number') }}</label>
                                                    <input class="form-control " name="notification_number" type="text" value="{{$store_settings->notification_number}}" id="notification_number">
                                                     <small>* Use country code with your number *</small>
                                                </div>
                                            </div>
                                            <div class="col-lg-12  text-right">
                                                <input type="submit" value="{{ __('Save Changes') }}" class="btn btn-sm btn-primary rounded-pill">
                                            </div>
                                        </form>
                                    </div>
                                </div>
                        </div>
                    </div>  
                @endif
            </div>
        </div>
    </div>
@endsection
@push('script-page')
    <script src="{{asset('assets/libs/jquery-mask-plugin/dist/jquery.mask.min.js')}}"></script>

    <script>
        function myFunction() {
            var copyText = document.getElementById("myInput");
            copyText.select();
            copyText.setSelectionRange(0, 99999)
            document.execCommand("copy");
            show_toastr('Success', "{{__('Link copied')}}", 'success');
        }

        $(document).on('click', 'input[name="theme_color"]', function () {
            var eleParent = $(this).attr('data-theme');
            var imgpath = $(this).attr('data-imgpath');
            $('.' + eleParent + '_img').attr('src', imgpath);
        });

        $(document).ready(function () {
            setTimeout(function (e) {
                var checked = $("input[type=radio][name='theme_color']:checked");
                $('.' + checked.attr('data-theme') + '_img').attr('src', checked.attr('data-imgpath'));
            }, 300);
        });
    </script>
@endpush
