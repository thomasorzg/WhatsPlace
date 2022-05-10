@extends('layouts.admin')
@section('page-title')
    {{__('Plans')}}
@endsection
@php
    $dir= asset(Storage::url('uploads/plan'));
@endphp
@section('title')
    <div class="d-inline-block">
        <h5 class="h4 d-inline-block text-white font-weight-bold mb-0 ">{{__('Plans')}}</h5>
    </div>
@endsection
@section('action-btn')
    @if(Auth::user()->type == 'super admin')
        @if((isset($admin_payments_setting['is_stripe_enabled']) && $admin_payments_setting['is_stripe_enabled'] == 'on')
            || (isset($admin_payments_setting['is_paypal_enabled']) && $admin_payments_setting['is_paypal_enabled'] == 'on')
            || (isset($admin_payments_setting['is_paystack_enabled']) && $admin_payments_setting['is_paystack_enabled'] == 'on')
            || (isset($admin_payments_setting['is_flutterwave_enabled']) && $admin_payments_setting['is_flutterwave_enabled'] == 'on')
            || (isset($admin_payments_setting['is_razorpay_enabled']) && $admin_payments_setting['is_razorpay_enabled'] == 'on')
            || (isset($admin_payments_setting['is_mercado_enabled']) && $admin_payments_setting['is_mercado_enabled'] == 'on')
            || (isset($admin_payments_setting['is_paytm_enabled']) && $admin_payments_setting['is_paytm_enabled'] == 'on')
            || (isset($admin_payments_setting['is_mollie_enabled']) && $admin_payments_setting['is_mollie_enabled'] == 'on')
            || (isset($admin_payments_setting['is_skrill_enabled']) && $admin_payments_setting['is_skrill_enabled'] == 'on')
            || (isset($admin_payments_setting['is_coingate_enabled']) && $admin_payments_setting['is_coingate_enabled'] == 'on')
            || (isset($admin_payments_setting['is_paymentwall_enabled']) && $admin_payments_setting['is_paymentwall_enabled'] == 'on')
        )
            <div class="">
                <button type="button" class="btn btn-sm btn-white bor-radius ml-4" data-ajax-popup="true" data-size="lg" data-title="{{ __('Add Plan') }}" data-url="{{route('plans.create')}}">
                    <i class="fas fa-plus"></i> {{ __('Add Plan') }}
                </button>
            </div>
        @endif
    @endif
@endsection
@section('content')
    <div class="row">
        @foreach($plans as $plan)
            <div class="col-md-3">
                <div class="card card-fluid">
                    <div class="card-header border-0 pb-0">
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
                    <div class="card-body text-center {{!empty(\Auth::user()->type != 'super admin')?'plan-box':''}}">
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
                        @if(($plan->id != \Auth::user()->plan) && \Auth::user()->type!='super admin' )
                            @if($plan->price > 0)
                                <a class="badge badge-pill badge-primary" href="{{route('stripe',\Illuminate\Support\Facades\Crypt::encrypt($plan->id))}}" data-toggle="tooltip" data-original-title="{{__('Buy Plan')}}">
                                    <i class="fas fa-cart-plus"></i>
                                </a>
                            @endif
                        @endif
                        

                                 @if(\Auth::user()->type == 'Owner' && $plan->id != 1 && \Auth::user()->plan != $plan->id)
                                 <div class="col-auto mt-3">
                                @if(\Auth::user()->requested_plan != $plan->id)
                                    <a href="{{ route('send.request',[\Illuminate\Support\Facades\Crypt::encrypt($plan->id)]) }}" class="badge badge-pill badge-success" data-title="{{__('Send Request')}}" data-toggle="tooltip">
                                        <span class="btn-inner--icon"><i class="fas fa-share"></i></span>

                                    </a>
                                @else
                                    <a href="{{ route('request.cancel',\Auth::user()->id) }}" class="badge badge-pill badge-danger" data-title="{{__('Cancle Request')}}" data-toggle="tooltip">
                                        <span class="btn-inner--icon"><i class="fas fa-times"></i></span>
                                    </a>
                                @endif
                            </div>
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
        @endforeach
    </div>
    <div class="card">
        <!-- Table -->
        <div class="table-responsive">
            <table class="table align-items-center dataTable">
                <thead>
                <tr>
                    <th scope="col" class="sort" data-sort="name"> {{__('Order Id')}}</th>
                    <th scope="col" class="sort" data-sort="budget">{{__('Date')}}</th>
                    <th scope="col" class="sort" data-sort="status">{{__('Name')}}</th>
                    <th scope="col">{{__('Plan Name')}}</th>
                    <th scope="col" class="sort" data-sort="completion"> {{__('Price')}}</th>
                    <th scope="col" class="sort" data-sort="completion"> {{__('Payment Type')}}</th>
                    <th scope="col" class="sort" data-sort="completion"> {{__('Status')}}</th>
                    <th scope="col" class="sort" data-sort="completion"> {{__('Coupon')}}</th>
                    <th scope="col" class="sort" data-sort="completion"> {{__('Invoice')}}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($orders as $order)
                    <tr>
                        <td>{{$order->order_id}}</td>
                        <td>{{$order->created_at->format('d M Y')}}</td>
                        <td>{{$order->user_name}}</td>
                        <td>{{$order->plan_name}}</td>
                        <td>{{env('CURRENCY_SYMBOL').$order->price}}</td>
                        <td>{{$order->payment_type}}</td>
                        <td>
                            @if($order->payment_status == 'succeeded')
                                <i class="mdi mdi-circle text-success"></i> {{ucfirst($order->payment_status)}}
                            @else
                                <i class="mdi mdi-circle text-danger"></i> {{ucfirst($order->payment_status)}}
                            @endif
                        </td>

                        <td>{{!empty($order->total_coupon_used)? !empty($order->total_coupon_used->coupon_detail)?$order->total_coupon_used->coupon_detail->code:'-':'-'}}</td>

                        <td class="text-center">
                            @if($order->receipt != 'free coupon' && $order->payment_type == 'STRIPE')
                                <a href="{{$order->receipt}}" title="Invoice" target="_blank" class=""><i class="fas fa-file-invoice"></i> </a>
                            @elseif($order->receipt =='free coupon')
                                <p>{{__('Used') .'100 %'. __('discount coupon code.')}}</p>
                            @elseif($order->payment_type == 'Manually')
                                <p>{{__('Manually plan upgraded by super admin')}}</p>
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function () {
            var tohref = '';
            @if(Auth::user()->is_register_trial == 1)
                tohref = $('#trial_{{ Auth::user()->interested_plan_id }}').attr("href");
            @elseif(Auth::user()->interested_plan_id != 0)
                tohref = $('#interested_plan_{{ Auth::user()->interested_plan_id }}').attr("href");
            @endif

            if (tohref != '') {
                window.location = tohref;
            }
        });
    </script>
@endpush
