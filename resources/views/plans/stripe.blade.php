@extends('layouts.admin')
@php
    $dir= asset(Storage::url('uploads/plan'));
@endphp
@push('script-page')
    <script src="https://js.stripe.com/v3/"></script>
    <script src="https://js.paystack.co/v1/inline.js"></script>
    <script src="https://api.ravepay.co/flwv3-pug/getpaidx/api/flwpbf-inline.js"></script>
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>

    <script type="text/javascript">
        @if($plan->price > 0.0 && isset($admin_payments_details['is_stripe_enabled']) && $admin_payments_details['is_stripe_enabled']=='on')
        var stripe = Stripe('{{ $admin_payments_details['stripe_key'] }}');
        var elements = stripe.elements();

        // Custom styling can be passed to options when creating an Element.
        var style = {
            base: {
                // Add your base input styles here. For example:
                fontSize: '14px',
                color: '#32325d',
            },
        };

        // Create an instance of the card Element.
        var card = elements.create('card', {style: style});

        // Add an instance of the card Element into the `card-element` <div>.
        card.mount('#card-element');

        // Create a token or display an error when the form is submitted.
        var form = document.getElementById('payment-form');
        form.addEventListener('submit', function (event) {
            event.preventDefault();

            stripe.createToken(card).then(function (result) {
                if (result.error) {
                    $("#card-errors").html(result.error.message);
                    show_toastr('Error', result.error.message, 'error');
                } else {
                    // Send the token to your server.
                    stripeTokenHandler(result.token);
                }
            });
        });

        function stripeTokenHandler(token) {
            // Insert the token ID into the form so it gets submitted to the server
            var form = document.getElementById('payment-form');
            var hiddenInput = document.createElement('input');
            hiddenInput.setAttribute('type', 'hidden');
            hiddenInput.setAttribute('name', 'stripeToken');
            hiddenInput.setAttribute('value', token.id);
            form.appendChild(hiddenInput);

            // Submit the form
            form.submit();
        }

        @endif
        function preparePayment(ele, payment) {
            var coupon = $(ele).closest('.row').find('.coupon').val();
            var amount = 0;
            $.ajax({
                url: '{{route('plan.prepare.amount')}}',
                datType: 'json',
                data: {
                    plan_id: '{{\Illuminate\Support\Facades\Crypt::encrypt($plan->id)}}',
                    coupon: coupon
                },
                success: function (data) {

                    if (data.is_success == true) {
                        amount = data.price;
                        $('#coupon_use_id').val(data.coupon_id);
                        if (payment == 'paystack') {
                            payWithPaystack(amount);
                        }
                        if (payment == 'flutterwave') {
                            payWithRave(amount);
                        }
                        if (payment == 'razorpay') {
                            payRazorPay(amount);
                        }
                        if (payment == 'mercado') {
                            payMercado(amount);
                        }
                    } else {
                        show_toastr('Error', 'Paymenent request failed', 'error');
                    }

                }
            })
        }
        @if(isset($admin_payments_details['is_paystack_enabled']) && $admin_payments_details['is_paystack_enabled']=='on')
        function payWithPaystack(amount) {
            var coupon_id = $('#coupon_use_id').val();
            var paystack_callback = "{{ url('/paystack-plan') }}";
            var handler = PaystackPop.setup({
                key: '{{ $admin_payments_details['paystack_public_key']  }}',
                email: '{{Auth::user()->email}}',
                amount: amount * 100,
                currency: '{{env('CURRENCY')}}',
                ref: 'pay_ref_id' + Math.floor((Math.random() * 1000000000) +
                    1
                ), // generates a pseudo-unique reference. Please replace with a reference you generated. Or remove the line entirely so our API will generate one for you
                metadata: {
                    custom_fields: [{
                        display_name: "Mobile Number",
                        variable_name: "mobile_number",
                    }]
                },

                callback: function (response) {
                    {{--console.log(paystack_callback +'/'+ response.reference + '/' + '{{\Illuminate\Support\Facades\Crypt::encrypt($plan->id)}}');--}}
                        window.location.href = paystack_callback + '/' + response.reference + '/' + '{{\Illuminate\Support\Facades\Crypt::encrypt($plan->id)}}?coupon_id=' + coupon_id;
                },
                onClose: function () {
                    alert('window closed');
                }
            });
            handler.openIframe();

        }
        @endif
        @if(isset($admin_payments_details['is_flutterwave_enabled']) && $admin_payments_details['is_flutterwave_enabled']=='on')
        {{-- Flutterwave JAVASCRIPT FUNCTION --}}
        function payWithRave(amount) {
            var coupon_id = $('#coupon_use_id').val();
            var API_publicKey = '{{ $admin_payments_details['flutterwave_public_key']  }}';
            var nowTim = "{{ date('d-m-Y-h-i-a') }}";
            var flutter_callback = "{{ url('/flutterwave-plan') }}";
            var x = getpaidSetup({
                PBFPubKey: API_publicKey,
                customer_email: '{{Auth::user()->email}}',
                amount: amount,
                currency: '{{env('CURRENCY')}}',
                txref: nowTim + '__' + Math.floor((Math.random() * 1000000000)) + 'fluttpay_online-' +
                {{ date('Y-m-d') }},
                meta: [{
                    metaname: "payment_id",
                    metavalue: "id"
                }],
                onclose: function () {
                },
                callback: function (response) {

                    var txref = response.tx.txRef;

                    if (
                        response.tx.chargeResponseCode == "00" ||
                        response.tx.chargeResponseCode == "0"
                    ) {
                        window.location.href = flutter_callback + '/' + txref + '/' + '{{\Illuminate\Support\Facades\Crypt::encrypt($plan->id)}}?coupon_id=' + coupon_id;
                    } else {
                        // redirect to a failure page.
                    }
                    x.close(); // use this to close the modal immediately after payment.
                }
            });
        }
        @endif
        @if(isset($admin_payments_details['is_razorpay_enabled']) && $admin_payments_details['is_razorpay_enabled']=='on')
        {{-- Razorpay JAVASCRIPT FUNCTION --}}
        @php
            $logo         =asset(Storage::url('uploads/logo/'));
            $company_logo =\App\Models\Utility::getValByName('company_logo');
        @endphp
        function payRazorPay(amount) {
            var razorPay_callback = '{{url('razorpay-plan')}}';
            var totalAmount = amount * 100;
            var coupon_id = $('#coupon_use_id').val();
            var options = {
                "key": "{{ $admin_payments_details['razorpay_public_key']  }}", // your Razorpay Key Id
                "amount": totalAmount,
                "name": 'Plan',
                "currency": '{{env('CURRENCY')}}',
                "description": "",
                "image": "{{$logo.'/'.(isset($company_logo) && !empty($company_logo)?$company_logo:'logo.png')}}",
                "handler": function (response) {
                    window.location.href = razorPay_callback + '/' + response.razorpay_payment_id + '/' + '{{\Illuminate\Support\Facades\Crypt::encrypt($plan->id)}}?coupon_id=' + coupon_id;
                },
                "theme": {
                    "color": "#528FF0"
                }
            };
            var rzp1 = new Razorpay(options);
            rzp1.open();
        }
        @endif
        @if(isset($admin_payments_details['is_mercado_enabled']) && $admin_payments_details['is_mercado_enabled']=='on')
        {{-- Mercado JAVASCRIPT FUNCTION --}}
        function payMercado(amount) {
            var coupon_id = $('#coupon_use_id').val();
            var data = {
                coupon_id: coupon_id,
                total_price: amount,
                plan: {{$plan->id}},
            }
            console.log(data);
            $.ajax({
                url: '{{ route('mercadopago.prepare.plan') }}',
                method: 'POST',
                data: data,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (data) {
                    if (data.status == 'success') {
                        window.location.href = data.url;
                    } else {
                        show_toastr("Error", data.error, data["status"]);
                    }
                }
            });
        }

        @endif
        $(document).ready(function () {
            $(document).on('click', '.apply-coupon', function () {
                var ele = $(this);
                var coupon = ele.closest('.row').find('.coupon').val();

                $.ajax({
                    url: '{{route('apply.coupon')}}',
                    datType: 'json',
                    data: {
                        plan_id: '{{\Illuminate\Support\Facades\Crypt::encrypt($plan->id)}}',
                        coupon: coupon
                    },
                    success: function (data) {
                        $('.final-price').text(data.final_price);
                        $('#final_price_pay').val(data.price);
                        $('#mollie_total_price').val(data.price);
                        $('#skrill_total_price').val(data.price);
                        $('#coingate_total_price').val(data.price);
                        $('#stripe_coupon, #paypal_coupon, #skrill_coupon,#coingate_coupon').val(coupon);
                        if (data.is_success == true) {
                            show_toastr('Success', data.message, 'success');
                        } else if (data.is_success == false) {
                            show_toastr('Error', data.message, 'error');
                        } else {
                            show_toastr('Error', 'Coupon code is required', 'error');
                        }
                    }
                })
            });
        });

    </script>
@endpush
@php
    $dir= asset(Storage::url('uploads/plan'));
    $dir_payment= asset(Storage::url('uploads/payments'));
@endphp
@section('page-title')
    {{__('Order Summary')}}
@endsection
@section('title')
    <div class="d-inline-block">
        <h5 class="h4 d-inline-block font-weight-400 mb-0 text-white">{{__('Order Summary')}}</h5>
    </div>
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item"><a href="{{route('plans.index')}}">{{__('Plan')}}</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{__('Order Summary')}}</li>
@endsection
@section('action-btn')
@endsection
@section('content')
    <div class="row">
        <div class="col-md-3">
                <div class="card card-fluid">
                    <div class="card-header border-0 pb-0 ">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-0">{{$plan->name}}</h6>
                            </div>
                            <div class="text-right">
                                <div class="actions">
                                    @if( \Auth::user()->type == 'super admin')
                                        <a title="Edit Plan" data-size="lg" href="#" class="action-item" data-url="{{ route('plans.edit',$plan->id) }}" data-ajax-popup="true" data-title="{{__('Edit Plan')}}" data-toggle="tooltip" data-original-title="{{__('Edit')}}"><i class="fas fa-edit"></i></a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body text-center  {{!empty(\Auth::user()->type != 'super admin')?'plan-box':''}}" style="flex:unset;">
                        <a href="#" class="avatar rounded-circle avatar-lg hover-translate-y-n3">
                            @if(!empty($plan->image))
                                <img alt="Image placeholder" src="{{$dir.'/'.$plan->image}}" class="">
                            @endif
                        </a>

                        <h5 class="h6 my-4"> {{env('CURRENCY_SYMBOL').$plan->price.' / ' . __($plan->duration)}}</h5>

                        @if(\Auth::user()->type=='Owner' && \Auth::user()->plan == $plan->id)
                            <h5 class="h6 my-4">
                                {{__('Expired : ')}} {{\Auth::user()->plan_expire_date ? \App\Models\Utility::dateFormat(\Auth::user()->plan_expire_date):__('Unlimited')}}
                            </h5>
                        @endif

                        <h5 class="h6 my-4">{{$plan->description}}</h5>
                        @if(\Auth::user()->type == 'Owner' && \Auth::user()->plan == $plan->id)
                            <span class="clearfix"></span>
                            <span class="badge badge-pill badge-success">{{__('Active')}}</span>
                        @endif
                    </div>
                    <div class="card-footer">
                        <div class="row">
                            <div class="col-6 text-center">
                                @if($plan->max_products == '-1')
                                    <span class="h5 mb-0">{{__('Unlimited')}}</span>
                                @else
                                    <span class="h5 mb-0">{{$plan->max_products}}</span>
                                @endif
                                <span class="d-block text-sm">{{__('Products')}}</span>
                            </div>
                            <div class="col-6 text-center">
                                <span class="h5 mb-0">
                                    @if($plan->max_stores == '-1')
                                        <span class="h5 mb-0">{{__('Unlimited')}}</span>
                                    @else
                                        <span class="h5 mb-0">{{$plan->max_stores}}</span>
                                    @endif
                                </span>
                                <span class="d-block text-sm">{{__('Store')}}</span>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="row">
                            <ul class="plan-detail">
                                @if($plan->enable_custdomain == 'on')
                                    <li>{{__('Custom Domain')}}</li>
                                @else
                                    <div>{{__('Custom Domain')}}</div>
                                @endif
                                @if($plan->enable_custsubdomain == 'on')
                                    <li>{{__('Sub Domain')}}</li>
                                @else
                                    <div>{{__('Sub Domain')}}</div>
                                @endif
                                @if($plan->shipping_method == 'on')
                                    <li>{{__('Shipping Method')}}</li>
                                @else
                                    <div>{{__('Shipping Method')}}</div>
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        <div class="col-md-9">
            <div class="row">
                <div class="col-lg-4 order-lg-2">
                    <div class="card plan-stripe-box">
                        <div class="list-group list-group-flush" id="tabs">
                            @if(isset($admin_payments_details['is_stripe_enabled']) && $admin_payments_details['is_stripe_enabled']=='on')
                                <div data-href="#stripe-payment" class="custom-list-group-item list-group-item text-primary">
                                    <div class="media">
                                        <i class="fas fa-cog pt-1"></i>
                                        <div class="media-body ml-3">
                                            <a href="#" class="stretched-link h6 mb-1">{{__('Stripe')}}</a>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            @if(isset($admin_payments_details['is_paypal_enabled']) && $admin_payments_details['is_paypal_enabled']=='on')
                                <div data-href="#paypal-payment" class="custom-list-group-item list-group-item text-primary border-top">
                                    <div class="media">
                                        <i class="fas fa-cog pt-1"></i>
                                        <div class="media-body ml-3">
                                            <a href="#" class="stretched-link h6 mb-1">{{__('Paypal')}}</a>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            @if(isset($admin_payments_details['is_paystack_enabled']) && $admin_payments_details['is_paystack_enabled']=='on')
                                <div data-href="#paystack-payment" class="custom-list-group-item list-group-item text-primary border-top">
                                    <div class="media">
                                        <i class="fas fa-cog pt-1"></i>
                                        <div class="media-body ml-3">
                                            <a href="#" class="stretched-link h6 mb-1">{{__('Paystack')}}</a>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            @if(isset($admin_payments_details['is_flutterwave_enabled']) && $admin_payments_details['is_flutterwave_enabled']=='on')
                                <div data-href="#flutterwave-payment" class="custom-list-group-item list-group-item text-primary border-top">
                                    <div class="media">
                                        <i class="fas fa-cog pt-1"></i>
                                        <div class="media-body ml-3">
                                            <a href="#" class="stretched-link h6 mb-1">{{__('Flutterwave')}}</a>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            @if(isset($admin_payments_details['is_razorpay_enabled']) && $admin_payments_details['is_razorpay_enabled']=='on')
                                <div data-href="#razorpay-payment" class="custom-list-group-item list-group-item text-primary border-top">
                                    <div class="media">
                                        <i class="fas fa-cog pt-1"></i>
                                        <div class="media-body ml-3">
                                            <a href="#" class="stretched-link h6 mb-1">{{__('Razorpay')}}</a>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            @if(isset($admin_payments_details['is_paytm_enabled']) && $admin_payments_details['is_paytm_enabled']=='on')
                                <div data-href="#paytm-payment" class="custom-list-group-item list-group-item text-primary border-top">
                                    <div class="media">
                                        <i class="fas fa-cog pt-1"></i>
                                        <div class="media-body ml-3">
                                            <a href="#" class="stretched-link h6 mb-1">{{__('Paytm')}}</a>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            @if(isset($admin_payments_details['is_mercado_enabled']) && $admin_payments_details['is_mercado_enabled']=='on')
                                <div data-href="#mercado-payment" class="custom-list-group-item list-group-item text-primary border-top">
                                    <div class="media">
                                        <i class="fas fa-cog pt-1"></i>
                                        <div class="media-body ml-3">
                                            <a href="#" class="stretched-link h6 mb-1">{{__('Mercado Pago')}}</a>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            @if(isset($admin_payments_details['is_mollie_enabled']) && $admin_payments_details['is_mollie_enabled']=='on')
                                <div data-href="#mollie-payment" class="custom-list-group-item list-group-item text-primary border-top">
                                    <div class="media">
                                        <i class="fas fa-cog pt-1"></i>
                                        <div class="media-body ml-3">
                                            <a href="#" class="stretched-link h6 mb-1">{{__('Mollie')}}</a>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            @if(isset($admin_payments_details['is_skrill_enabled']) && $admin_payments_details['is_skrill_enabled']=='on')
                                <div data-href="#skrill-payment" class="custom-list-group-item list-group-item text-primary border-top">
                                    <div class="media">
                                        <i class="fas fa-cog pt-1"></i>
                                        <div class="media-body ml-3">
                                            <a href="#" class="stretched-link h6 mb-1">{{__('Skrill')}}</a>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            @if(isset($admin_payments_details['is_coingate_enabled']) && $admin_payments_details['is_coingate_enabled']=='on')
                                <div data-href="#coingate-payment" class="custom-list-group-item list-group-item text-primary border-top">
                                    <div class="media">
                                        <i class="fas fa-cog pt-1"></i>
                                        <div class="media-body ml-3">
                                            <a href="#" class="stretched-link h6 mb-1">{{__('CoinGate')}}</a>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            @if(isset($admin_payments_details['is_paymentwall_enabled']) && $admin_payments_details['is_paymentwall_enabled']=='on')
                                <div data-href="#paymentwall-payment" class="custom-list-group-item list-group-item text-primary border-top">
                                    <div class="media">
                                        <i class="fas fa-cog pt-1"></i>
                                        <div class="media-body ml-3">
                                            <a href="#" class="stretched-link h6 mb-1">{{__('Paymentwall')}}</a>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-lg-8 order-lg-1">
                    @if(isset($admin_payments_details['is_stripe_enabled']) && $admin_payments_details['is_stripe_enabled']=='on')
                        <div id="stripe-payment" class="tabs-card {{ (isset($admin_payments_details['is_stripe_enabled']) && $admin_payments_details['is_stripe_enabled']=='on') ? "active" : "" }}">
                            <div class="card">
                                <div class="card-header"><h3 href="#" class=" h6 mb-1">{{__('Stripe')}}</h3></div>
                                <form role="form" action="{{ route('stripe.payment') }}" method="post" class="require-validation" id="payment-form">
                                    @csrf
                                    <div class="border p-3 mb-3 rounded stripe-payment-div">
                                        <div class="row">
                                            <div class="col-sm-8">
                                                <div class="custom-radio">
                                                    <label class="font-16 font-weight-bold">{{__('Credit / Debit Card')}}</label>
                                                </div>
                                                <p class="mb-0 pt-1 text-sm">{{__('Safe money transfer using your bank account. We support Mastercard, Visa, Discover and American express.')}}</p>
                                            </div>

                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="card-name-on">{{__('Name on card')}}</label>
                                                    <input type="text" name="name" id="card-name-on" class="form-control required" placeholder="{{\Auth::user()->name}}">
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div id="card-element"></div>
                                                <div id="card-errors" role="alert"></div>
                                            </div>
                                            <div class="col-md-10">
                                                <br>
                                                <div class="form-group">
                                                    <label for="stripe_coupon">{{__('Coupon')}}</label>
                                                    <input type="text" id="stripe_coupon" name="coupon" class="form-control coupon" placeholder="{{ __('Enter Coupon Code') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-2 coupon-apply-btn">
                                                <div class="form-group apply-stripe-btn-coupon">
                                                    <a href="#" class="btn btn-primary coupon-apply-btn apply-coupon btn-sm">{{ __('Apply') }}</a>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-12">
                                                <div class="error" style="display: none;">
                                                    <div class='alert-danger alert'>{{__('Please correct the errors and try again.')}}</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <div class="text-sm-right mr-2">
                                                    <input type="hidden" name="plan_id" value="{{\Illuminate\Support\Facades\Crypt::encrypt($plan->id)}}">
                                                    <button class="btn btn-primary btn-sm" type="submit">
                                                        <i class="mdi mdi-cash-multiple mr-1"></i> {{__('Pay Now')}}
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endif
                    @if(isset($admin_payments_details['is_paypal_enabled']) && $admin_payments_details['is_paypal_enabled']=='on')
                        <div id="paypal-payment" class="tabs-card {{ (isset($admin_payments_details['is_stripe_enabled']) && $admin_payments_details['is_stripe_enabled']=='off') && (isset($admin_payments_details['is_paypal_enabled']) && $admin_payments_details['is_paypal_enabled']=='on') ? "active" : "" }} d-none">
                            <div class="card ">
                                <div class="card-header"><h3 href="#" class="h6 mb-1">{{__('Paypal')}}</h3></div>
                                <form class="w3-container w3-display-middle w3-card-4" method="POST" id="payment-form" action="{{ route('plan.pay.with.paypal') }}">
                                    @csrf
                                    <input type="hidden" name="plan_id" value="{{\Illuminate\Support\Facades\Crypt::encrypt($plan->id)}}">

                                    <div class="border p-3 mb-3 rounded payment-box">
                                        <div class="row">
                                            <div class="col-md-10">
                                                <div class="form-group">
                                                    <label for="paypal_coupon">{{__('Coupon')}}</label>
                                                    <input type="text" id="paypal_coupon" name="coupon" class="form-control coupon" placeholder="{{ __('Enter Coupon Code') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-2 coupon-apply-btn">
                                                <div class="form-group apply-paypal-btn-coupon">
                                                    <a href="#" class="btn btn-primary apply-coupon btn-sm">{{ __('Apply') }}</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group mt-3 mr-3">
                                        <div class="text-sm-right">
                                            <button class="btn btn-primary btn-sm" type="submit">
                                                <i class="mdi mdi-cash-multiple mr-1"></i> {{__('Pay Now')}}
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endif
                    @if(isset($admin_payments_details['is_paystack_enabled']) && $admin_payments_details['is_paystack_enabled']=='on')
                        <div id="paystack-payment" class="tabs-card  d-none">
                            <div class="card ">
                                <div class="card-header"><h3 href="#" class="h6 mb-1">{{__('Paystack')}}</h3></div>

                                <div class="border p-3 mb-3 rounded payment-box">
                                    <div class="row">
                                        <div class="col-md-10">
                                            <div class="form-group">
                                                <label for="paypal_coupon">{{__('Coupon')}}</label>
                                                <input type="text" id="paypal_coupon" name="coupon" class="form-control coupon" placeholder="{{ __('Enter Coupon Code') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-2 coupon-apply-btn">
                                            <div class="form-group apply-paypal-btn-coupon">
                                                <a href="#" class="btn btn-primary apply-coupon btn-sm">{{ __('Apply') }}</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group mt-3 mr-3">
                                    <div class="text-sm-right">
                                        <button class="btn btn-primary btn-sm" type="button" onclick="preparePayment(this,'paystack')">
                                            <i class="mdi mdi-cash-multiple mr-1"></i> {{__('Pay Now')}}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if(isset($admin_payments_details['is_flutterwave_enabled']) && $admin_payments_details['is_flutterwave_enabled']=='on')
                        <div id="flutterwave-payment" class="tabs-card  d-none">
                            <div class="card ">
                                <div class="card-header"><h3 href="#" class="h6 mb-1">{{__('Flutterwave')}}</h3></div>
                                <form class="w3-container w3-display-middle w3-card-4" method="POST" id="payment-form" action="{{ route('plan.pay.with.paypal') }}">
                                    @csrf
                                    <input type="hidden" name="plan_id" value="{{\Illuminate\Support\Facades\Crypt::encrypt($plan->id)}}">

                                    <div class="border p-3 mb-3 rounded payment-box">
                                        <div class="row">
                                            <div class="col-md-10">
                                                <div class="form-group">
                                                    <label for="paypal_coupon">{{__('Coupon')}}</label>
                                                    <input type="text" id="paypal_coupon" name="coupon" class="form-control coupon" placeholder="{{ __('Enter Coupon Code') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-2 coupon-apply-btn">
                                                <div class="form-group apply-paypal-btn-coupon">
                                                    <a href="#" class="btn btn-primary apply-coupon btn-sm">{{ __('Apply') }}</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group mt-3 mr-3">
                                        <div class="text-sm-right">
                                            <button class="btn btn-primary btn-sm" type="button" onclick="preparePayment(this,'flutterwave')">
                                                <i class="mdi mdi-cash-multiple mr-1"></i> {{__('Pay Now')}}
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endif
                    @if(isset($admin_payments_details['is_razorpay_enabled']) && $admin_payments_details['is_razorpay_enabled']=='on')
                        <div id="razorpay-payment" class="tabs-card  d-none">
                            <div class="card ">
                                <div class="card-header"><h3 href="#" class="h6 mb-1">{{__('Razorpay')}}</h3></div>
                                <form class="w3-container w3-display-middle w3-card-4" method="POST" id="payment-form" action="{{ route('plan.pay.with.paypal') }}">
                                    @csrf
                                    <input type="hidden" name="plan_id" value="{{\Illuminate\Support\Facades\Crypt::encrypt($plan->id)}}">

                                    <div class="border p-3 mb-3 rounded payment-box">
                                        <div class="row">
                                            <div class="col-md-10">
                                                <div class="form-group">
                                                    <label for="paypal_coupon">{{__('Coupon')}}</label>
                                                    <input type="text" id="paypal_coupon" name="coupon" class="form-control coupon" placeholder="{{ __('Enter Coupon Code') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-2 coupon-apply-btn">
                                                <div class="form-group apply-paypal-btn-coupon">
                                                    <a href="#" class="btn btn-primary apply-coupon btn-sm">{{ __('Apply') }}</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group mt-3 mr-3">
                                        <div class="text-sm-right">
                                            <button class="btn btn-primary btn-sm" type="button" onclick="preparePayment(this,'razorpay')">
                                                <i class="mdi mdi-cash-multiple mr-1"></i> {{__('Pay Now')}}
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endif
                    @if(isset($admin_payments_details['is_paytm_enabled']) && $admin_payments_details['is_paytm_enabled']=='on')
                        <div id="paytm-payment" class="tabs-card  d-none">
                            <div class="card ">
                                <div class="card-header"><h3 href="#" class="h6 mb-1">{{__('Paytm')}}</h3></div>
                                <form class="w3-container w3-display-middle w3-card-4" method="POST" id="payment-form" action="{{ route('paytm.prepare.plan') }}">
                                    @csrf
                                    <input type="hidden" name="plan_id" value="{{\Illuminate\Support\Facades\Crypt::encrypt($plan->id)}}">
                                    <input type="hidden" name="total_price" id="paytm_total_price" value="{{$plan->price}}" class="form-control">
                                    <div class="border p-3 mb-3 rounded payment-box">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="paypal_coupon">{{__('Mobile Number')}}</label>
                                                    <input type="text" id="mobile_number" name="mobile_number" class="form-control coupon" placeholder="{{ __('Enter Mobile Number') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-10">
                                                <div class="form-group">
                                                    <label for="paypal_coupon">{{__('Coupon')}}</label>
                                                    <input type="text" id="paypal_coupon" name="coupon" class="form-control coupon" placeholder="{{ __('Enter Coupon Code') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-2 coupon-apply-btn">
                                                <div class="form-group apply-paypal-btn-coupon">
                                                    <a href="#" class="btn btn-primary apply-coupon btn-sm">{{ __('Apply') }}</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group mt-3 mr-3">
                                        <div class="text-sm-right">
                                            <button class="btn btn-primary btn-sm" type="submit">
                                                <i class="mdi mdi-cash-multiple mr-1"></i> {{__('Pay Now')}}
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                    @endif
                    @if(isset($admin_payments_details['is_mercado_enabled']) && $admin_payments_details['is_mercado_enabled']=='on')
                        <div id="mercado-payment" class="tabs-card  d-none">
                            <div class="card ">
                                <div class="card-header"><h3 href="#" class="h6 mb-1">{{__('Mercado Pago')}}</h3></div>
                                <form class="w3-container w3-display-middle w3-card-4" method="POST" id="payment-form" action="{{ route('plan.pay.with.paypal') }}">
                                    @csrf
                                    <input type="hidden" name="plan_id" value="{{\Illuminate\Support\Facades\Crypt::encrypt($plan->id)}}">

                                    <div class="border p-3 mb-3 rounded payment-box">
                                        <div class="row">
                                            <div class="col-md-10">
                                                <div class="form-group">
                                                    <label for="paypal_coupon">{{__('Coupon')}}</label>
                                                    <input type="text" id="paypal_coupon" name="coupon" class="form-control coupon" placeholder="{{ __('Enter Coupon Code') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-2 coupon-apply-btn">
                                                <div class="form-group apply-paypal-btn-coupon">
                                                    <a href="#" class="btn btn-primary apply-coupon btn-sm">{{ __('Apply') }}</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group mt-3 mr-3">
                                        <div class="text-sm-right">
                                            <button class="btn btn-primary btn-sm" type="button" onclick="preparePayment(this,'mercado')">
                                                <i class="mdi mdi-cash-multiple mr-1"></i> {{__('Pay Now')}}
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                    @endif
                    @if(isset($admin_payments_details['is_mollie_enabled']) && $admin_payments_details['is_mollie_enabled']=='on')
                        <div id="mollie-payment" class="tabs-card  d-none">
                            <div class="card ">
                                <div class="card-header"><h3 href="#" class="h6 mb-1">{{__('Mollie')}}</h3></div>
                                <form class="w3-container w3-display-middle w3-card-4" method="POST" id="payment-form" action="{{ route('mollie.prepare.plan') }}">
                                    @csrf
                                    <input type="hidden" name="plan_id" value="{{\Illuminate\Support\Facades\Crypt::encrypt($plan->id)}}">
                                    <input type="hidden" name="total_price" id="mollie_total_price" value="{{$plan->price}}" class="form-control">
                                    <div class="border p-3 mb-3 rounded payment-box">
                                        <div class="row">
                                            <div class="col-md-10">
                                                <div class="form-group">
                                                    <label for="paypal_coupon">{{__('Coupon')}}</label>
                                                    <input type="text" id="paypal_coupon" name="coupon" class="form-control coupon" placeholder="{{ __('Enter Coupon Code') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-2 coupon-apply-btn">
                                                <div class="form-group apply-paypal-btn-coupon">
                                                    <a href="#" class="btn btn-primary apply-coupon btn-sm">{{ __('Apply') }}</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group mt-3 mr-3">
                                        <div class="text-sm-right">
                                            <button class="btn btn-primary btn-sm" type="submit">
                                                <i class="mdi mdi-cash-multiple mr-1"></i> {{__('Pay Now')}}
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endif
                    @if(isset($admin_payments_details['is_skrill_enabled']) && $admin_payments_details['is_skrill_enabled']=='on')
                        <div id="skrill-payment" class="tabs-card  d-none">
                            <div class="card ">
                                <div class="card-header"><h3 href="#" class="h6 mb-1">{{__('Skrill')}}</h3></div>
                                <form class="w3-container w3-display-middle w3-card-4" method="POST" id="payment-form" action="{{ route('skrill.prepare.plan') }}">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ date('Y-m-d') }}-{{ strtotime(date('Y-m-d H:i:s')) }}-payatm">
                                    <input type="hidden" name="order_id" value="{{str_pad(!empty($order->id) ? $order->id + 1 : 0 + 1, 4, "100", STR_PAD_LEFT)}}">
                                    @php
                                        $skrill_data = [
                                            'transaction_id' => md5(date('Y-m-d') . strtotime('Y-m-d H:i:s') . 'user_id'),
                                            'user_id' => 'user_id',
                                            'amount' => 'amount',
                                            'currency' => 'currency',
                                        ];
                                        session()->put('skrill_data', $skrill_data);

                                    @endphp
                                    <input type="hidden" name="plan_id" value="{{\Illuminate\Support\Facades\Crypt::encrypt($plan->id)}}">
                                    <input type="hidden" name="total_price" id="skrill_total_price" value="{{$plan->price}}" class="form-control">
                                    <div class="border p-3 mb-3 rounded payment-box">
                                        <div class="row">
                                            <div class="col-md-10">
                                                <div class="form-group">
                                                    <label for="skrill_coupon">{{__('Coupon')}}</label>
                                                    <input type="text" id="skrill_coupon" name="coupon" class="form-control coupon" placeholder="{{ __('Enter Coupon Code') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-2 coupon-apply-btn">
                                                <div class="form-group apply-paypal-btn-coupon">
                                                    <a href="#" class="btn btn-primary apply-coupon btn-sm">{{ __('Apply') }}</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group mt-3 mr-3">
                                        <div class="text-sm-right">
                                            <button class="btn btn-primary btn-sm" type="submit">
                                                <i class="mdi mdi-cash-multiple mr-1"></i> {{__('Pay Now')}}
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                    @endif
                    @if(isset($admin_payments_details['is_coingate_enabled']) && $admin_payments_details['is_coingate_enabled']=='on')
                        <div id="coingate-payment" class="tabs-card  d-none">
                            <div class="card ">
                                <div class="card-header"><h3 href="#" class="h6 mb-1">{{__('CoinGate')}}</h3></div>
                                <form class="w3-container w3-display-middle w3-card-4" method="POST" id="payment-form" action="{{ route('coingate.prepare.plan') }}">
                                    @csrf
                                    <input type="hidden" name="counpon" id="coingate_coupon" value="">
                                    <input type="hidden" name="plan_id" value="{{\Illuminate\Support\Facades\Crypt::encrypt($plan->id)}}">
                                    <input type="hidden" name="total_price" id="coingate_total_price" value="{{$plan->price}}" class="form-control">
                                    <div class="border p-3 mb-3 rounded payment-box">
                                        <div class="row">
                                            <div class="col-md-10">
                                                <div class="form-group">
                                                    <label for="coingate_coupon">{{__('Coupon')}}</label>
                                                    <input type="text" id="coingate_coupon" name="coupon" class="form-control coupon" placeholder="{{ __('Enter Coupon Code') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-2 coupon-apply-btn">
                                                <div class="form-group apply-paypal-btn-coupon">
                                                    <a href="#" class="btn btn-primary apply-coupon btn-sm">{{ __('Apply') }}</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group mt-3 mr-3">
                                        <div class="text-sm-right">
                                            <button class="btn btn-primary btn-sm" type="submit">
                                                <i class="mdi mdi-cash-multiple mr-1"></i> {{__('Pay Now')}}
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                    @endif
                    @if(isset($admin_payments_details['is_paymentwall_enabled']) && $admin_payments_details['is_paymentwall_enabled']=='on')
                        <div id="paymentwall-payment" class="tabs-card  d-none">
                            <div class="card ">
                                <div class="card-header"><h3 href="#" class="h6 mb-1">{{__('Paymentwall')}}</h3></div>
                                <form class="w3-container w3-display-middle w3-card-4" method="POST" id="" action="{{ route('paymentwall') }}">
                                    @csrf
                                    <input type="hidden" name="counpon" id="paymentwall_coupon" value="">
                                    <input type="hidden" name="plan_id" value="{{\Illuminate\Support\Facades\Crypt::encrypt($plan->id)}}">
                                    <input type="hidden" name="total_price" id="paymentwall_total_price" value="{{$plan->price}}" class="form-control">
                                    <div class="border p-3 mb-3 rounded payment-box">
                                        <div class="row">
                                            <div class="col-md-10">
                                                <div class="form-group">
                                                    <label for="coingate_coupon">{{__('Coupon')}}</label>
                                                    <input type="text" id="paymentwall_coupon" name="coupon" class="form-control coupon" placeholder="{{ __('Enter Coupon Code') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-2 coupon-apply-btn">
                                                <div class="form-group apply-paypal-btn-coupon">
                                                    <a href="#" class="btn btn-primary apply-coupon btn-sm">{{ __('Apply') }}</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group mt-3 mr-3">
                                        <div class="text-sm-right">
                                            <button class="btn btn-primary btn-sm" type="submit">
                                                <i class="mdi mdi-cash-multiple mr-1"></i> {{__('Pay Now')}}
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

