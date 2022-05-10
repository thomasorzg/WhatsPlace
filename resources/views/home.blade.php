@extends('layouts.admin')
@section('page-title')
{{__('Dashboard')}}
@endsection
@section('content')
    @php
        $logo=asset(Storage::url('uploads/logo/'));
       $company_logo=\App\Models\Utility::getValByName('company_logo');
    @endphp

    <div class="page-content">
        <!-- Page title -->
        @if(\Auth::user()->type=='Owner')
            <div class="page-title">
                <div class="row justify-content-between align-items-center">
                    <div class="col-md-6 mb-3 mb-md-0">
                        <h5 class="h1 font-weight-400 mb-0 text-white"><b>{{__('Morning')}}, {{$store->name}} &#128515;!</b></h5>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-3">
                    <div class="card bg-gradient-warning hover-shadow-lg hover-translate-y-n3 mb-4 ml-lg-0 border-0">
                        <div class="card-body pt-3 pb-14">
                            <div class="row row-grid align-items-center">
                                <div class="col-lg-12">
                                    <div class="media align-items-center">
                                        <div class="media-body ">
                                            <div class="float-left">
                                                <h5 class="text-white mb-1">{{$store_id->name}}</h5>
                                                <a href="#" class="btn btn-primary btn-sm text-sm cp_link" data-link="{{ $store_id['store_url'] }}" data-toggle="tooltip" data-original-title="{{__('Click to copy link')}}">{{__('Store Link')}}</a>
                                            </div>
                                            <div class="float-right">
                                                {!! QrCode::generate($store_id['store_url']); !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3">
                    <div class="card card-stats bg-gradient-primary border-0 hover-shadow-lg hover-translate-y-n3 mb-4 ml-lg-0">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h6 class="text-white font-bold-700 mb-1">{{__('Total Products')}}</h6>
                                    <span class="h5 font-weight-bold text-white mb-0">{{$newproduct}}</span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon bg-gradient-warning text-white rounded-circle icon-shape">
                                        <i class="fas fa-cube"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="card card-stats bg-gradient-info border-0 hover-shadow-lg hover-translate-y-n3 mb-4 ml-lg-0">
                        <!-- Card body -->
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h6 class="text-white font-bold-700 mb-1">{{__('Total Sales')}}</h6>
                                    <span class="h5 font-weight-bold text-white mb-0">{{\App\Models\Utility::priceFormat($totle_sale)}}</span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon bg-gradient-primary text-white rounded-circle icon-shape">
                                        <i class="fas fa-cart-plus"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="card card-stats bg-gradient-dark border-0 hover-shadow-lg hover-translate-y-n3 mb-4 ml-lg-0">
                        <!-- Card body -->
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h6 class="text-white font-bold-700 mb-1">{{__('Total Orders')}}</h6>
                                    <span class="h5 font-weight-bold text-white mb-0">{{$totle_order}}</span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon bg-gradient-danger text-white rounded-circle icon-shape">
                                        <i class="fas fa-shopping-bag"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-12 col-md-6">
                    <div class="card card-fluid">
                        <div class="card-header">
                            <h6 class="mb-0 float-left">{{__('Order')}}</h6>
                            <span class="float-right mb-0">{{__('Last 15 Days')}}</span>
                        </div>
                        <div class="card-body">
                            <!-- Chart -->
                            <div id="apex-dashborad" data-color="primary" data-height="280"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-12 col-md-6">
                    <div class="card card-fluid">
                        <div class="card-header border-0">
                            <h6 class="mb-0 float-left">{{__('Top Products')}}</h6>
                            <span class="float-right mb-0">{{__('Top').'5'.__('Products')}}</span>
                        </div>
                        <div class="table-responsive">
                            <table class="table align-items-center">
                                <thead>
                                <tr>
                                    <th scope="col" class="sort" data-sort="name">{{__('Product')}}</th>
                                    <th scope="col" class="sort" data-sort="budget">{{__('Quantity')}}</th>
                                    <th scope="col" class="sort text-right" data-sort="completion">{{__('Price')}}</th>
                                </tr>
                                </thead>
                                <tbody class="list">
                                @foreach($products as $product)
                                    @foreach($item_id as $k => $item)
                                        @if($product->id == $item)
                                            <tr>
                                                <th scope="row">
                                                    <div class="media align-items-center">
                                                        <div>
                                                            @if(!empty($product->is_cover))
                                                                <img alt="Image placeholder" src="{{asset(Storage::url('uploads/is_cover_image/'.$product->is_cover))}}" width="80px">
                                                            @else
                                                                <img alt="Image placeholder" src="{{asset(Storage::url('uploads/is_cover_image/default.jpg'))}}" class="" style="width: 80px;">
                                                            @endif
                                                        </div>
                                                        <div class="media-body ml-4">
                                                            <span class="mb-0 h6 text-sm">{{$product->name}}</span>
                                                        </div>
                                                    </div>
                                                </th>
                                                <td class="budget">
                                                    {{$product->quantity}}
                                                </td>
                                                <td class="text-right">
                                                    <div>
                                                        <span class="completion mr-2 text-dark text-right ">{{\App\Models\Utility::priceFormat($product->price)}}</span>
                                                    </div>
                                                    <span class="completion mr-2 text-right">{{$totle_qty[$k]}} {{__('Sold')}}</span>
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-xl-12 col-md-6">
                    <div class="card card-fluid">
                        <div class="card-header border-0">
                            <h6 class="mb-0 float-left">{{__('Recent Orders')}}</h6>
                            <span class="float-right mb-0">{{__('Top') .'5'. __('Recent Orders')}}</span>
                        </div>
                        <div class="table-responsive">
                            <table class="table align-items-center">
                                <thead>
                                <tr>
                                    <th scope="col">{{__('Order')}}</th>
                                    <th scope="col" class="sort">{{__('Date')}}</th>
                                    <th scope="col" class="sort">{{__('Name')}}</th>
                                    <th scope="col" class="sort">{{__('Value')}}</th>
                                    <th scope="col" class="sort">{{__('Payment Type')}}</th>
                                    <th scope="col" class="text-right">{{__("Action")}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(!empty($new_orders))
                                    @foreach($new_orders as $order)
                                        @if($order->status != 'Cancel Order')
                                            <tr>
                                                <th scope="row">
                                                    <a href="{{route('orders.show',\Illuminate\Support\Facades\Crypt::encrypt($order->id))}}" class="btn btn-sm btn-secondary btn-icon rounded-pill text-dark">
                                                        <span class="btn-inner--icon"><i class="fas fa-download"></i></span>
                                                        <span class="btn-inner--text">{{$order->order_id}}</span>
                                                    </a>
                                                </th>
                                                <td class="order">
                                                    <span class="h6 text-sm font-weight-bold mb-0">{{\App\Models\Utility::dateFormat($order->created_at)}}</span>
                                                </td>
                                                <td>
                                                    <span class="client">{{$order->name}}</span>
                                                </td>
                                                <td>
                                                    <span class="value text-sm mb-0">{{\App\Models\Utility::priceFormat($order->price)}}</span>
                                                </td>
                                                <td>
                                                    <span class="taxes text-sm mb-0">{{$order->payment_type}}</span>
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center justify-content-end">
                                                        <button type="button" class="btn btn-sm {{($order->status == 'pending')?'btn-soft-info':'btn-soft-success'}} btn-icon rounded-pill">
                                                        <span class="btn-inner--icon">
                                                         @if($order->status == 'pending')
                                                                <i class="fas fa-check soft-info"></i>
                                                            @else
                                                                <i class="fa fa-check-double soft-success"></i>
                                                            @endif
                                                        </span>
                                                            @if($order->payment_status == 'approved' && $order->status == 'pending')
                                                                <span class="btn-inner--text">
                                                            {{__('pending')}}: {{\App\Models\Utility::dateFormat($order->created_at)}}
                                                                    @else
                                                        </span><span class="btn-inner--text">
                                                            {{__('Delivered')}}: {{\App\Models\Utility::dateFormat($order->updated_at)}}
                                                        </span>
                                                            @endif
                                                        </button>
                                                        <!-- Actions -->
                                                        <div class="actions ml-3">
                                                            <a href="{{route('orders.show',\Illuminate\Support\Facades\Crypt::encrypt($order->id))}}" class="action-item mr-2" data-toggle="tooltip" data-title="{{__('Details')}}">
                                                                <i class="fas fa-eye"></i>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="row">
                <div class="col-xl-4 col-sm-6">
                    <div class="card card-stats border-0">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h6 class="text-muted mb-1">{{__('Total Store')}}</h6>
                                    <span class="h6 font-weight-bold mb-0 ">{{$user->total_user}}</span>
                                </div>
                                <div class="col-auto">
                                    <h6 class="text-muted mb-1">{{__('Paid Store')}}</h6>
                                    <span class="h6 font-weight-bold mb-0 ">{{$user['total_paid_user']}}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-sm-6">
                    <div class="card card-stats border-0">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h6 class="text-muted mb-1">{{__('Total Orders')}}</h6>
                                    <span class="h6 font-weight-bold mb-0 ">{{$user->total_orders}}</span>
                                </div>
                                <div class="col-auto">
                                    <h6 class="text-muted mb-1">{{__('Total Order Amount')}}</h6>
                                    <span class="h6 font-weight-bold mb-0 ">{{env("CURRENCY_SYMBOL").$user['total_plan_price']}}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-sm-6">
                    <div class="card card-stats border-0">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h6 class="text-muted mb-1">{{__('Total Plans')}}</h6>
                                    <span class="h6 font-weight-bold mb-0 ">{{$user['total_plan']}}</span>
                                </div>
                                <div class="col-auto">
                                    <h6 class="text-muted mb-1">{{__('Most Purchase Plan')}}</h6>
                                    <span class="h6 font-weight-bold mb-0 ">{{!empty($user['most_purchese_plan'])?$user['most_purchese_plan']:'-'}}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-12 col-md-12">
                    <div class="card card-fluid">
                        <div class="card-header">
                            <h6 class="mb-0">{{__('Recent Order')}}</h6>
                        </div>
                        <div class="card-body">
                            <div id="plan_order" data-color="primary" data-height="280"></div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
@push('script-page')
    @if(\Auth::user()->type=='Owner')
        <script>
            $(document).ready(function () {
                $('.cp_link').on('click', function () {
                    var value = $(this).attr('data-link');
                    var $temp = $("<input>");
                    $("body").append($temp);
                    $temp.val(value).select();
                    document.execCommand("copy");
                    $temp.remove();
                    show_toastr('Success', '{{__('Link copied')}}', 'success')
                });
            });

            EngagementChart = function () {
                var e = $("#apex-dashborad");
                e.length && e.each(function () {
                    !function (e) {
                        var t = {
                            chart: {width: "100%", zoom: {enabled: !1}, toolbar: {show: !1}, shadow: {enabled: !1}},
                            stroke: {width: 7, curve: "smooth"},
                            series: [
                                {
                                    name: "{{__('Order')}}",
                                    data: {!! json_encode($chartData['data']) !!}
                                }
                            ],
                            xaxis: {
                                labels: {
                                    format: "MMM",
                                    style: {
                                        colors: "#051c4b", fontSize: "14px", fontFamily: PurposeStyle.fonts.base, cssClass: "apexcharts-xaxis-label"
                                    }
                                },
                                axisBorder: {
                                    show: !1
                                },
                                axisTicks: {
                                    show: !0, borderType: "solid", color: PurposeStyle.colors.gray[300], height: 6, offsetX: 0, offsetY: 0
                                },
                                type: "MMM",
                                categories: {!! json_encode($chartData['label']) !!}
                            },
                            yaxis: {
                                labels: {
                                    style: {
                                        color: PurposeStyle.colors.gray[600], fontSize: "12px", fontFamily: PurposeStyle.fonts.base
                                    }
                                },
                                axisBorder: {
                                    show: !1
                                },
                                axisTicks: {
                                    show: !0, borderType: "solid", color: PurposeStyle.colors.gray[300], height: 6, offsetX: 0, offsetY: 0
                                }
                            },
                            fill: {type: "solid"},
                            markers: {size: 4, opacity: .7, strokeColor: "#fff", strokeWidth: 3, hover: {size: 7}},
                            grid: {borderColor: PurposeStyle.colors.gray[300], strokeDashArray: 5},
                            dataLabels: {enabled: !1}
                        }, a = (e.data().dataset, e.data().labels, e.data().color), n = e.data().height, o = e.data().type;
                        t.colors = [PurposeStyle.colors.theme[a]], t.markers.colors = [PurposeStyle.colors.theme[a]], t.chart.height = n || 350, t.chart.type = o || "line";
                        var i = new ApexCharts(e[0], t);
                        setTimeout(function () {
                            i.render()
                        }, 300)
                    }($(this))
                })
            }()
        </script>
    @else
        <script>
            EngagementChart = function () {
                var e = $("#plan_order");
                e.length && e.each(function () {
                    !function (e) {
                        var t = {
                            chart: {width: "100%", zoom: {enabled: !1}, toolbar: {show: !1}, shadow: {enabled: !1}},
                            stroke: {width: 7, curve: "smooth"},
                            series: [
                                {
                                    name: "{{__('Order')}}",
                                    data: {!! json_encode($chartData['data']) !!}
                                }
                            ],
                            xaxis: {
                                labels: {
                                    format: "MMM",
                                    style: {
                                        colors: PurposeStyle.colors.gray[600], fontSize: "14px", fontFamily: PurposeStyle.fonts.base, cssClass: "apexcharts-xaxis-label"
                                    }
                                },
                                axisBorder: {
                                    show: !1
                                },
                                axisTicks: {
                                    show: !0, borderType: "solid", color: PurposeStyle.colors.gray[300], height: 6, offsetX: 0, offsetY: 0
                                },
                                type: "MMM",
                                categories: {!! json_encode($chartData['label']) !!}
                            },
                            yaxis: {
                                labels: {
                                    style: {
                                        color: PurposeStyle.colors.gray[600], fontSize: "12px", fontFamily: PurposeStyle.fonts.base
                                    }
                                },
                                axisBorder: {
                                    show: !1
                                },
                                axisTicks: {
                                    show: !0, borderType: "solid", color: PurposeStyle.colors.gray[300], height: 6, offsetX: 0, offsetY: 0
                                }
                            },
                            fill: {type: "solid"},
                            markers: {size: 4, opacity: .7, strokeColor: "#fff", strokeWidth: 3, hover: {size: 7}},
                            grid: {borderColor: PurposeStyle.colors.gray[300], strokeDashArray: 5},
                            dataLabels: {enabled: !1}
                        }, a = (e.data().dataset, e.data().labels, e.data().color), n = e.data().height, o = e.data().type;
                        t.colors = [PurposeStyle.colors.theme[a]], t.markers.colors = [PurposeStyle.colors.theme[a]], t.chart.height = n || 350, t.chart.type = o || "line";
                        var i = new ApexCharts(e[0], t);
                        setTimeout(function () {
                            i.render()
                        }, 300)
                    }($(this))
                })
            }()
        </script>
    @endif
@endpush
