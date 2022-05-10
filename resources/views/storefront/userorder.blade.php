<!DOCTYPE html>
<html lang="en" dir="{{env('SITE_RTL') == 'on'?'rtl':''}}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="{{ucfirst($store->name)}} - {{ucfirst($store->tagline)}}">

    <title>{{__('User Order')}} - {{($store->tagline) ?  $store->tagline : env('APP_NAME', ucfirst($store->name))}}</title>
    <link rel="icon" href="{{asset(Storage::url('uploads/logo/').(!empty($settings->value)?$settings->value:'favicon.png'))}}" type="image/png">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="{{ asset('assets/libs/@fortawesome/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/libs/fullcalendar/dist/fullcalendar.min.css')}}">

    <link rel="stylesheet" href="{{ asset('assets/libs/animate.css/animate.min.css')}}" id="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/libs/select2/dist/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{asset('assets/libs/@fancyapps/fancybox/dist/jquery.fancybox.min.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/css/site-light.css')}}" id="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/jquery.dataTables.min.css')}}">

    <link rel="stylesheet" href="{{ asset('assets/css/custom.css')}}" id="stylesheet')}}">
    @if(env('SITE_RTL')=='on')
        <link rel="stylesheet" href="{{ asset('css/bootstrap-rtl.css') }}">
    @endif
    <script type="text/javascript" src="{{ asset('assets/js/jquery.min.js')}}"></script>

    @stack('css-page')
</head>

<body class="application application-offset">
@php
    if(!empty(session()->get('lang')))
    {
        $currantLang = session()->get('lang');
    }else{
        $currantLang = $store->lang;
    }
    $languages=\App\Models\Utility::languages();
@endphp
<div class="container-fluid container-application">
    <div class="main-content position-relative">
        <div id="navbar-top-main" class="navbar-top  navbar-dark bg-primary border-bottom">
            <div class="container px-0">
                <div class="navbar-nav align-items-center float-left">
                    <div class="d-none d-lg-inline-block">
                        <a class="navbar-brand mr-lg-4 pt-0" href="{{route('store.slug',$store->slug)}}">
                            @if(!empty($store->logo))
                                <img alt="Image placeholder" src="{{asset(Storage::url('uploads/store_logo/'.$store->logo))}}" id="navbar-logo" style="height: 40px;">
                            @else
                                <img alt="Image placeholder" src="{{asset(Storage::url('uploads/store_logo/logo.png'))}}" id="navbar-logo" style="height: 40px;">
                            @endif
                        </a>
                        <div class="d-lg-inline-block">
                            <span class="navbar-text mr-3 pt-3 text-lg align-middle">{{ucfirst($store->name)}}</span>
                        </div>
                    </div>
                </div>
                <div class="float-right">
                    <ul class="nav">
                        <li class="nav-item dropdown">
                            <div class="dropdown-menu dropdown-menu-sm">
                                @foreach($languages as $language)
                                    <a href="{{route('change.languagestore',[$store->slug,$language])}}" class="dropdown-item @if($language == $currantLang) active-language @endif">
                                        <span> {{Str::upper($language)}}</span>
                                    </a>
                                @endforeach
                            </div>
                            <a class="nav-link text-white pt-4" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="text-sm mb-0"><i class="fas fa-globe-asia"></i>
                                    {{Str::upper($currantLang)}}
                                </span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <nav class="navbar navbar-main navbar-expand-lg navbar-dark bg-primary " id="navbar-main">
            <div class="container px-lg-0">
                <!-- Logo -->

                <!-- Navbar collapse trigger -->
                <button class="navbar-toggler pr-0" type="button" data-toggle="collapse" data-target="#navbar-main-collapse" aria-controls="navbar-main-collapse" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbar-main-collapse">
                    <ul class="navbar-nav align-items-lg-center ml-lg-auto">
                        <li class="nav-item dropdown dropdown-animate" data-toggle="hover">
                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-lg dropdown-menu-arrow p-0">
                                <div class="dropdown-menu-links rounded-bottom delimiter-top p-4">
                                    <div class="row">
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <div class="main-content">
            <header class="bg-primary d-flex align-items-end">
                <div class="container">
                    <div class="row float-left">
                        <div class=" col-auto">
                            <div class="row align-items-center ">
                                <h4 class="text-white">{{__('Your Order Details')}}</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-auto text-right">
                        <a href="#" onclick="saveAsPDF();" data-toggle="tooltip" data-title="{{__('Download')}}" id="download-buttons" class="btn btn-sm btn-white btn-icon rounded-pill">
                            <span class="btn-inner--icon text-dark"><i class="fa fa-print"></i></span>
                            <span class="btn-inner--text text-dark">{{__('Print')}}</span>
                        </a>
                    </div>
                </div>
            </header>
            <div class="container">
                <div class="mt-4">
                    <div id="printableArea">
                        <div class="row">
                            <div class=" col-6 pb-2 invoice_logo"></div>
                            <div class=" col-6 pb-2 delivered_Status text-right">
                                @if($order->status == 'pending')
                                    <button class="btn btn-sm btn-success">{{__('Pending')}}</button>
                                @elseif($order->status == 'Cancel Order')
                                    <button class="btn btn-sm btn-danger">{{__('Order Canceled')}}</button>
                                @else
                                    <button class="btn btn-sm btn-success">{{__('Delivered')}}</button>
                                @endif
                            </div>
                            <div class="col-lg-8">
                                <div class="card">
                                    <div class="card-header border-0">
                                        <h6 class="mb-0">{{__('Items from Order')}} {{$order->order_id}}</h6>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table mb-0">
                                            <thead class="thead-light">
                                            <tr>
                                                <th>{{__('Item')}}</th>
                                                <th>{{__('Quantity')}}</th>
                                                <th>{{__('Price')}}</th>
                                                <th>{{__('Total')}}</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @php
                                                $sub_tax = 0;
                                                $total = 0;
                                            @endphp
                                            @foreach($order_products->products as $key=>$product)
                                                @if($product->variant_id != 0)
                                                    <tr>
                                                        <td class="total">
                                                        <span class="h6 text-sm">
                                                            {{$product->product_name .' - ( '.$product->variant_name.' )'}}
                                                        </span>
                                                            @if(!empty($product->tax))
                                                                @php
                                                                    $total_tax=0;
                                                                @endphp
                                                                @foreach($product->tax as $tax)
                                                                    @php
                                                                        $sub_tax = ($product->variant_price* $product->quantity * $tax->tax) / 100;
                                                                        $total_tax += $sub_tax;
                                                                    @endphp
                                                                    {{$tax->tax_name.' '.$tax->tax.'%'.' ('.$sub_tax.')'}}
                                                                @endforeach
                                                            @else
                                                                @php
                                                                    $total_tax = 0
                                                                @endphp
                                                            @endif

                                                        </td>
                                                        <td>
                                                            {{$product->quantity}}
                                                        </td>
                                                        <td>
                                                            {{App\Models\Utility::priceFormat($product->variant_price)}}
                                                        </td>
                                                        <td>
                                                            {{App\Models\Utility::priceFormat($product->variant_price*$product->quantity+$total_tax)}}
                                                        </td>
                                                    </tr>
                                                @else
                                                    <tr>
                                                        <td class="total">
                                                        <span class="h6 text-sm">
                                                            {{$product->product_name}}
                                                        </span>
                                                            @if(!empty($product->tax))
                                                                @php
                                                                    $total_tax=0;
                                                                @endphp
                                                                @foreach($product->tax as $tax)
                                                                    @php
                                                                        $sub_tax = ($product->price* $product->quantity * $tax->tax) / 100;
                                                                        $total_tax += $sub_tax;
                                                                    @endphp
                                                                    {{$tax->tax_name.' '.$tax->tax.'%'.' ('.$sub_tax.')'}}
                                                                @endforeach
                                                            @else
                                                                @php
                                                                    $total_tax = 0
                                                                @endphp
                                                            @endif

                                                        </td>
                                                        <td>
                                                            {{$product->quantity}}
                                                        </td>
                                                        <td>
                                                            {{App\Models\Utility::priceFormat($product->price)}}
                                                        </td>
                                                        <td>
                                                            {{App\Models\Utility::priceFormat($product->price*$product->quantity+$total_tax)}}
                                                        </td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    @if($order->status == 'delivered')
                                        <div class="card card-body mb-0 py-0">
                                            <div class="card my-5 bg-secondary">
                                                <div class="card-body">
                                                    <div class="row justify-content-between align-items-center">
                                                        <div class="col-md-6 order-md-2 mb-4 mb-md-0">
                                                            <div class="d-flex align-items-center justify-content-md-end">
                                                                <button data-id="{{$order->id}}" data-value="{{asset(Storage::url('uploads/downloadable_prodcut'.'/'.$product->downloadable_prodcut))}}" class="btn btn-sm btn-primary downloadable_prodcut">{{__('Download')}}</button>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 order-md-1">
                                                            <span class="h6 text-muted d-inline-block mr-3 mb-0"></span>
                                                            <span class="h5 mb-0">{{__('Get your product from here')}}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                @if(!empty($user_details->special_instruct))
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="card card-fluid">
                                                <div class="card-body">
                                                    <h6 class="mb-4">{{__('Order Notes')}}</h6>
                                                    <dl class="row mt-4 align-items-center">
                                                        <dd class="p-2"> {{$user_details->special_instruct}}</dd>
                                                    </dl>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <div class="col-lg-4">
                                <div class="card">
                                    <div class="card-header border-0">
                                        <h6 class="mb-0">{{__('Items from Order '). $order->order_id}}</h6>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table mb-0">
                                            <thead class="thead-light">

                                            </thead>
                                            <tbody>
                                            <tr>
                                                <td>{{__('Sub Total')}} :</td>
                                                <td>{{App\Models\Utility::priceFormat($sub_total)}}</td>
                                            </tr>
                                            <tr>
                                                <td>{{__('Estimated Tax')}} :</td>
                                                <td>{{App\Models\Utility::priceFormat($final_taxs)}}</td>
                                            </tr>
                                            @if(!empty($discount_price))
                                                <tr>
                                                    <td>{{__('Apply Coupon')}} :</td>
                                                    <td>{{$discount_price}}</td>
                                                </tr>
                                            @endif
                                            @if(!empty($shipping_data))
                                                @if(!empty($discount_value))
                                                    <tr>
                                                        <td>{{__('Shipping Price')}} :</td>
                                                        <td>{{App\Models\Utility::priceFormat($shipping_data->shipping_price)}}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>{{__('Grand Total')}} :</th>
                                                        <th>{{ App\Models\Utility::priceFormat($grand_total+$shipping_data->shipping_price-$discount_value) }}</th>
                                                    </tr>
                                                @else
                                                    <tr>
                                                        <td>{{__('Shipping Price')}} :</td>
                                                        <td>{{App\Models\Utility::priceFormat($shipping_data->shipping_price)}}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>{{__('Grand Total')}} :</th>
                                                        <th>{{ App\Models\Utility::priceFormat($grand_total+$shipping_data->shipping_price) }}</th>
                                                    </tr>
                                                @endif
                                            @elseif(!empty($discount_value))
                                                <tr>
                                                    <th>{{__('Grand  Total')}} :</th>
                                                    <th>{{ App\Models\Utility::priceFormat($grand_total-$discount_value) }}</th>
                                                </tr>
                                            @else
                                                <tr>
                                                    <th>{{__('Grand  Total')}} :</th>
                                                    <th>{{ App\Models\Utility::priceFormat($grand_total) }}</th>
                                                </tr>
                                            @endif

                                            <th>{{__('Payment Type')}} :</th>
                                            <th>{{ $order['payment_type'] }}</th>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="card card-fluid">
                                        <div class="card-body">
                                            <h6 class="mb-4">{{__('Shipping Information')}}</h6>
                                            <address class="mb-0 text-sm">
                                                <dl class="row mt-4 align-items-center">
                                                    <dt class="col-sm-3 h6 text-sm">{{__('Name')}}</dt>
                                                    <dd class="col-sm-9 text-sm"> {{$user_details->name}}</dd>
                                                    <dt class="col-sm-3 h6 text-sm">{{__('Phone')}}</dt>
                                                    <dd class="col-sm-9 text-sm">{{$user_details->phone}}</dd>
                                                    <dt class="col-sm-3 h6 text-sm">{{__('Billing Address')}}</dt>
                                                    <dd class="col-sm-9 text-sm">{{$user_details->billing_address}}</dd>
                                                    <dt class="col-sm-3 h6 text-sm">{{__('Shipping Address')}}</dt>
                                                    <dd class="col-sm-9 text-sm">{{$user_details->shipping_address}}</dd>
                                                    @if(!empty($location_data && $shipping_data))
                                                        <dt class="col-sm-3 h6 text-sm">{{__('Location')}}</dt>
                                                        <dd class="col-sm-9 text-sm">{{$location_data->name}}</dd>
                                                        <dt class="col-sm-3 h6 text-sm">{{__('Shipping Method')}}</dt>
                                                        <dd class="col-sm-9 text-sm">{{$shipping_data->shipping_name}}</dd>
                                                    @endif
                                                </dl>
                                            </address>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="card card-fluid">
                                        <div class="card-body">
                                            <h6 class="mb-4">{{__('Billing Information')}}</h6>
                                            <dl class="row mt-4 align-items-center">
                                                <dt class="col-sm-3 h6 text-sm">{{__('Name')}}</dt>
                                                <dd class="col-sm-9 text-sm"> {{$user_details->name}}</dd>
                                                <dt class="col-sm-3 h6 text-sm">{{__('Phone')}}</dt>
                                                <dd class="col-sm-9 text-sm">{{$user_details->phone}}</dd>
                                                <dt class="col-sm-3 h6 text-sm">{{__('Billing Address')}}</dt>
                                                <dd class="col-sm-9 text-sm">{{$user_details->billing_address}}</dd>
                                                <dt class="col-sm-3 h6 text-sm">{{__('Shipping Address')}}</dt>
                                                <dd class="col-sm-9 text-sm">{{$user_details->shipping_address}}</dd>
                                                @if(!empty($location_data && $shipping_data))
                                                    <dt class="col-sm-3 h6 text-sm">{{__('Location')}}</dt>
                                                    <dd class="col-sm-9 text-sm">{{$location_data->name}}</dd>
                                                    <dt class="col-sm-3 h6 text-sm">{{__('Shipping Method')}}</dt>
                                                    <dd class="col-sm-9 text-sm">{{$shipping_data->shipping_name}}</dd>
                                                @endif
                                            </dl>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="card card-fluid">
                                        <div class="card-body">
                                            <h6 class="mb-4">{{__('Extra Information')}}</h6>
                                            <dl class="row mt-4 align-items-center">
                                                <dt class="col-sm-3 h6 text-sm">{{$store['custom_field_title_1']}}</dt>
                                                <dd class="col-sm-9 text-sm"> {{$user_details->custom_field_title_1}}</dd>
                                                <dt class="col-sm-3 h6 text-sm">{{$store['custom_field_title_2']}}</dt>
                                                <dd class="col-sm-9 text-sm"> {{$user_details->custom_field_title_2}}</dd>
                                                <dt class="col-sm-3 h6 text-sm">{{$store['custom_field_title_3']}}</dt>
                                                <dd class="col-sm-9 text-sm">{{$user_details->custom_field_title_3}}</dd>
                                                <dt class="col-sm-3 h6 text-sm">{{$store['custom_field_title_4']}}</dt>
                                                <dd class="col-sm-9 text-sm"> {{$user_details->custom_field_title_4}}</dd>
                                            </dl>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<footer id="footer-main">
    <div class="footer pt-1 py-4 footer_bottom">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <div class="copyright text-sm font-weight-bold text-center text-md-left">
                        {{$store->footer_note}}
                    </div>
                </div>
                <div class="col-md-6">
                    <ul class="nav justify-content-center justify-content-md-end mt-3 mt-md-0">
                        @if(!empty($store->email))
                            <li class="nav-item">
                                <a class="nav-link" href="mailto:{{$store->email}}" target="_blank">
                                    <i class="fas fa-envelope"></i>
                                </a>
                            </li>
                        @endif
                        @if(!empty($store->whatsapp))
                            <li class="nav-item">
                                <a class="nav-link" href="{{$store->whatsapp}}" target=”_blank”>
                                    <i class="fab fa-whatsapp"></i>
                                </a>
                            </li>
                        @endif
                        @if(!empty($store->facebook))
                            <li class="nav-item">
                                <a class="nav-link" href="{{$store->facebook}}" target="_blank">
                                    <i class="fab fa-facebook-square"></i>
                                </a>
                            </li>
                        @endif
                        @if(!empty($store->instagram))
                            <li class="nav-item">
                                <a class="nav-link" href="{{$store->instagram}}" target="_blank">
                                    <i class="fab fa-instagram"></i>
                                </a>
                            </li>
                        @endif
                        @if(!empty($store->twitter))
                            <li class="nav-item">
                                <a class="nav-link" href="{{$store->twitter}}" target="_blank">
                                    <i class="fab fa-twitter"></i>
                                </a>
                            </li>
                        @endif
                        @if(!empty($store->youtube))
                            <li class="nav-item">
                                <a class="nav-link" href="{{$store->youtube}}" target="_blank">
                                    <i class="fab fa-youtube"></i>
                                </a>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>
</footer>

<div id="invoice_logo_img" class="d-none">
    <div class="row align-items-center py-2 px-3">
        @if(!empty($store->invoice_logo))
            <img alt="Image placeholder" src="{{asset(Storage::url('uploads/store_logo/'.$store->invoice_logo))}}" id="navbar-logo" style="height: 40px;">
        @else
            <img alt="Image placeholder" src="{{asset(Storage::url('uploads/store_logo/invoice_logo.png'))}}" id="navbar-logo" style="height: 40px;">
        @endif
    </div>
</div>

<script src="{{asset('assets/js/site.core.js')}}"></script>
<script type="text/javascript" src="{{ asset('assets/js/custom.js')}}"></script>
<script src="{{ asset('assets/libs/bootstrap-notify/bootstrap-notify.min.js')}}"></script>
<script src="{{asset('assets/libs/swiper/dist/js/swiper.min.js')}}"></script>
<script src="{{asset('assets/js/site.js')}}"></script>
<script src="{{asset('assets/js/demo.js')}}"></script>
<script type="text/javascript" src="{{ asset('assets/js/html2pdf.bundle.min.js') }}"></script>


@php
    $store_settings = \App\Models\Store::where('slug',$store->slug)->first();
@endphp

<script async src="https://www.googletagmanager.com/gtag/js?id={{$store_settings->google_analytic}}"></script>

{!! $store_settings->storejs !!}

<script>
    window.dataLayer = window.dataLayer || [];

    function gtag() {
        dataLayer.push(arguments);
    }

    gtag('js', new Date());

    gtag('config', '{{ $store_settings->google_analytic }}');
</script>



<!-- Facebook Pixel Code -->
<script>
  !function(f,b,e,v,n,t,s)
  {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
  n.callMethod.apply(n,arguments):n.queue.push(arguments)};
  if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
  n.queue=[];t=b.createElement(e);t.async=!0;
  t.src=v;s=b.getElementsByTagName(e)[0];
  s.parentNode.insertBefore(t,s)}(window, document,'script',
  'https://connect.facebook.net/en_US/fbevents.js');
  fbq('init', '{{ !empty($store_settings->facebook_pixel)}}');
  fbq('track', 'PageView');
</script>
<noscript><img height="1" width="1" style="display:none"
  src="https://www.facebook.com/tr?id={{$store_settings->facebook_pixel}}&ev=PageView&noscript=1"
/></noscript>
<!-- End Facebook Pixel Code -->

<script>
    var filename = $('#filesname').val();

    function saveAsPDF() {
        var element = document.getElementById('printableArea');
        var logo_html = $('#invoice_logo_img').html();
        $('.invoice_logo').empty();
        $('.invoice_logo').html(logo_html);

        var opt = {
            margin: 0.3,
            filename: filename,
            image: {type: 'jpeg', quality: 1},
            html2canvas: {scale: 4, dpi: 72, letterRendering: true},
            jsPDF: {unit: 'in', format: 'A2'}
        };

        html2pdf().set(opt).from(element).save();
        setTimeout(function () {
            $('.invoice_logo').empty();
        }, 0);
    }

    $(document).on('click', '.downloadable_prodcut', function () {

        var download_product = $(this).attr('data-value');
        var order_id = $(this).attr('data-id');

        var data = {
            download_product: download_product,
            order_id: order_id,
        }

        $.ajax({
            url: '{{ route('user.downloadable_prodcut',$store->slug) }}',
            method: 'POST',
            data: data,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (data) {
                if (data.status == 'success') {
                    show_toastr("success", data.message+'<br> <b>'+data.msg+'<b>', data["status"]);
                    $('.downloadab_msg').html('<span class="text-success">' + data.msg + '</sapn>');
                } else {
                    show_toastr("Error", data.message+'<br> <b>'+data.msg+'<b>', data["status"]);
                }
            }
        });
    });
</script>
</body>
</html>

