@extends('layouts.admin')
@section('page-title')
    {{__('Order')}}
@endsection
@section('title')
    <div class="d-inline-block">
        <h5 class="h4 d-inline-block text-white font-weight-bold mb-0 ">{{__('Orders')}}</h5>
    </div>
@endsection
@section('breadcrumb')
@endsection
@section('action-btn')
    <div class="col-auto pt-2">
        <a href="{{route('order.receipt',$order->id)}}" data-toggle="tooltip" data-title="{{__('Receipt')}}" class="btn btn-sm btn-white btn-icon rounded-pill">
            <span class="btn-inner--icon text-dark"><i class="fa fa-receipt"></i></span>
            <span class="btn-inner--text text-dark">{{__('Thermal Receipt')}}</span>
        </a>
        <a href="#" onclick="saveAsPDF();" data-toggle="tooltip" data-title="{{__('Print')}}" id="download-buttons" class="btn btn-sm btn-white btn-icon rounded-pill">
            <span class="btn-inner--icon text-dark"><i class="fa fa-print"></i></span>
            <span class="btn-inner--text text-dark">{{__('Print')}}</span>
        </a>
        <div class="btn-group" id="deliver_btn">
            <button type="button" class="btn btn-sm btn-success dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                {{__('Status')}} : {{__(ucfirst($order->status))}}
            </button>
            <div class="dropdown-menu dropdown-menu-right" x-placement="bottom-end" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(-26px, 39px, 0px);">
                <h6 class="dropdown-header">{{__('Set order status')}}</h6>
                <a class="dropdown-item" href="#" id="delivered" data-value="delivered">
                    @if($order->status == 'pending' ||  $order->status == 'Cancel Order')
                        <i class="fa fa-check text-primary"></i>
                    @else
                        <i class="fa fa-check-double text-primary"></i>
                    @endif
                    {{__('Delivered')}}
                </a>
                <a class="dropdown-item text-danger" href="#" id="delivered" data-value="Cancel Order">
                    @if($order->status != 'Cancel Order')
                        <i class="fa fa-check text-primary"></i>
                    @else
                        <i class="fa fa-check-double text-danger"></i>
                    @endif
                    {{__('Cancel Order')}}
                </a>
            </div>
        </div>
    </div>
@endsection
@section('filter')
@endsection
@section('content')
    <div class="mt-4">
        <div id="printableArea">
            <div class="row">
                <div class="col-lg-8">
                    <div class="card card-fluid">
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
                                    @if(isset($product->variant_id) && $product->variant_id != 0)
                                        <tr>
                                            <td class="total">
                                            <span class="h6 text-sm">
                                                <a href="{{route('product.show',$product->id)}}">{{$product->product_name .' - ( '.$product->variant_name.' )'}}</a>
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
                                                <a href="{{route('product.show',$product->id)}}">{{$product->product_name}}</a>
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
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card card-fluid">
                        <div class="card-header border-0">
                            <h6 class="mb-0">{{__('Items from Order '). $order->order_id}}</h6>
                        </div>
                        <div class="table-responsive">
                            <table class="table mb-0">
                                <thead class="thead-light">
                                <tr>

                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>{{__('Grand Total')}} :</td>
                                    <td>{{App\Models\Utility::priceFormat($sub_total)}}</td>
                                </tr>
                                <tr>
                                    <td>{{__('Estimated Tax')}} :</td>
                                    <td>{{App\Models\Utility::priceFormat($total_taxs)}}</td>
                                </tr>
                                @if(!empty($shipping_data) && !empty($discount_value))
                                    <tr>
                                        <td>{{__('Coupon Price')}} :</td>
                                        <td>{{App\Models\Utility::priceFormat($discount_value)}}</td>
                                    </tr>
                                    <tr>
                                        <td>{{__('Shipping Price')}} :</td>
                                        <td>{{App\Models\Utility::priceFormat($shipping_data->shipping_price)}}</td>
                                    </tr>
                                    <tr>
                                        <th>{{__('Total')}} :</th>
                                        <th>{{ App\Models\Utility::priceFormat(($sub_total+$total_taxs+$shipping_data->shipping_price)-$discount_value) }}</th>
                                    </tr>
                                @elseif(!empty($discount_value))
                                    <tr>
                                        <td>{{__('Coupon')}} :</td>
                                        <td>{{App\Models\Utility::priceFormat($discount_value)}}</td>
                                    </tr>
                                    <tr>
                                        <th>{{__('Total')}} :</th>
                                        <th>{{ App\Models\Utility::priceFormat(($sub_total+$total_taxs)-$discount_value) }}</th>
                                    </tr>
                                @elseif(!empty($shipping_data))
                                    <tr>
                                        <td>{{__('Shipping Price')}} :</td>
                                        <td>{{App\Models\Utility::priceFormat($shipping_data->shipping_price)}}</td>
                                    </tr>
                                    <tr>
                                        <th>{{__('Total')}} :</th>
                                        <th>{{ App\Models\Utility::priceFormat($sub_total+$total_taxs+$shipping_data->shipping_price) }}</th>
                                    </tr>
                                @else
                                    <tr>
                                        <th>{{__('Total')}} :</th>
                                        <th>{{ App\Models\Utility::priceFormat($sub_total+$total_taxs) }}</th>
                                    </tr>
                                @endif
                                <th>{{__('Payment Type')}} :</th>
                                <th>{{ $order['payment_type'] }}</th>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            @if(!empty($user_details->special_instruct))
                <div class="row">
                    <div class="col-lg-8">
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
                                    <dd class="col-sm-9 text-sm">
                                        <a href="{{$url = 'https://api.whatsapp.com/send?phone=' . str_replace(' ','',$user_details->phone) . '&text=Hi'}}" target="_blank">
                                            {{$user_details->phone}}
                                        </a>
                                    </dd>
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
                                <dd class="col-sm-9 text-sm">
                                    <a href="{{$url = 'https://api.whatsapp.com/send?phone=' . str_replace(' ','',$user_details->phone) . '&text=Hi'}}" target="_blank">
                                        {{$user_details->phone}}
                                    </a>
                                </dd>
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
                @if(!empty($store_payment_setting['custom_field_title_1']) || !empty($user_details->custom_field_title_1) || !empty($store_payment_setting['custom_field_title_2']) || !empty($user_details->custom_field_title_2) || !empty($store_payment_setting['custom_field_title_3']) || !empty($user_details->custom_field_title_3) || !empty($store_payment_setting['custom_field_title_4']) || !empty($user_details->custom_field_title_4))
                    <div class="col-lg-4">
                        <div class="card card-fluid">
                            <div class="card-body">
                                <h6 class="mb-4">{{__('Extra Information')}}</h6>
                                <dl class="row mt-4 align-items-center">
                                    @if(!empty($store_payment_setting['custom_field_title_1']) && !empty($user_details->custom_field_title_1))
                                        <dt class="col-sm-3 h6 text-sm">{{$store_payment_setting['custom_field_title_1']}}</dt>
                                        <dd class="col-sm-9 text-sm"> {{$user_details->custom_field_title_1}}</dd>
                                    @endif
                                    @if(!empty($store_payment_setting['custom_field_title_2']) && !empty($user_details->custom_field_title_2))
                                        <dt class="col-sm-3 h6 text-sm">{{$store_payment_setting['custom_field_title_2']}}</dt>
                                        <dd class="col-sm-9 text-sm"> {{$user_details->custom_field_title_2}}</dd>
                                    @endif
                                    @if(!empty($store_payment_setting['custom_field_title_3']) && !empty($user_details->custom_field_title_3))
                                        <dt class="col-sm-3 h6 text-sm">{{$store_payment_setting['custom_field_title_3']}}</dt>
                                        <dd class="col-sm-9 text-sm">{{$user_details->custom_field_title_3}}</dd>
                                    @endif
                                    @if(!empty($store_payment_setting['custom_field_title_4']) && !empty($user_details->custom_field_title_4))
                                        <dt class="col-sm-3 h6 text-sm">{{$store_payment_setting['custom_field_title_4']}}</dt>
                                        <dd class="col-sm-9 text-sm"> {{$user_details->custom_field_title_4}}</dd>
                                    @endif
                                </dl>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
        <div class="card">
            <div class="input-group">
                <input type="text" value="{{env('APP_URL').'/'.$store->slug.'/order/'.$order_id}}" id="myInput" class="form-control d-inline-block" aria-label="Recipient's username" aria-describedby="button-addon2" readonly>
                <div class="input-group-append">
                    <button class="btn btn-outline-primary" type="button" onclick="myFunction()" id="button-addon2"><i class="far fa-copy"></i> {{__('Copy Link')}}</button>
                </div>
            </div>
        </div>

    </div>
@endsection
@push('script-page')
    <script type="text/javascript" src="{{ asset('assets/js/html2pdf.bundle.min.js') }}"></script>
    <script>
        var filename = $('#filesname').val();

        function saveAsPDF() {
            var element = document.getElementById('printableArea');
            var opt = {
                margin: 0.3,
                filename: filename,
                image: {type: 'jpeg', quality: 1},
                html2canvas: {scale: 4, dpi: 72, letterRendering: true},
                jsPDF: {unit: 'in', format: 'A2'}
            };
            html2pdf().set(opt).from(element).save();

        }
    </script>
    <script>
        $("#deliver_btn").on('click', '#delivered', function () {
            var status = $(this).attr('data-value');
            var data = {
                delivered: status,
            }
            $.ajax({
                url: '{{ route('orders.update',$order->id) }}',
                method: 'PUT',
                data: data,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (data) {
                    if (data.error == '') {
                        show_toastr('error', data.error, 'error');
                    } else {
                        show_toastr('success', data.success, 'success');

                    }
                    setTimeout(function () {
                        location.reload();
                    }, 1000);
                }
            });
        });
    </script>
    <script>
        function myFunction() {
            var copyText = document.getElementById("myInput");
            copyText.select();
            copyText.setSelectionRange(0, 99999)
            document.execCommand("copy");
            show_toastr('Success', 'Link copied', 'success');
        }
    </script>
@endpush
