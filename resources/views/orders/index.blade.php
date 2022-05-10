@extends('layouts.admin')
@section('page-title')
    {{__('Order')}}
@endsection
@section('title')
    <div class="d-inline-block">
        <h5 class="h4 d-inline-block text-white font-weight-bold mb-0">{{__('Orders')}}</h5>
    </div>
@endsection
@section('action-btn')
   <a href="{{route('order.export', $store->id)}}" class="btn btn-sm btn-white bor-radius " 
                >
                <p class=" d-sm-inline-block mb-0 pl-3 pr-2">{{__('Export')}}</p>
                <i class="fa fa-file-excel"></i> 
    </a>
@endsection
@section('filter')
@endsection
@section('content')
    <div class="card">
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
                @foreach($orders as $order)
                    <tr>
                        <th scope="row">
                            <a href="{{route('orders.show',\Illuminate\Support\Facades\Crypt::encrypt($order->id))}}" class="btn btn-sm btn-white btn-icon rounded-pill text-dark">
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
                                @if($order->status != 'Cancel Order')
                                    <button type="button" class="btn btn-sm {{($order->status == 'pending')?'btn-soft-info':'btn-soft-success'}} btn-icon rounded-pill">
                                        <span class="btn-inner--icon">
                                         @if($order->status == 'pending')
                                                <i class="fas fa-check soft-success"></i>
                                            @else
                                                <i class="fa fa-check-double soft-success"></i>
                                            @endif
                                        </span>
                                        @if($order->status == 'pending')
                                            <span class="btn-inner--text">
                                                {{__('Pending')}}: {{\App\Models\Utility::dateFormat($order->created_at)}}
                                            </span>
                                        @else
                                            <span class="btn-inner--text">
                                                {{__('Delivered')}}: {{\App\Models\Utility::dateFormat($order->updated_at)}}
                                            </span>
                                        @endif
                                    </button>
                                @else
                                    <button type="button" class="btn btn-sm btn-soft-danger btn-icon rounded-pill">
                                        <span class="btn-inner--icon">
                                            @if($order->status == 'pending')
                                                <i class="fas fa-check soft-success"></i>
                                            @else
                                                <i class="fa fa-check-double soft-success"></i>
                                            @endif
                                        </span>
                                        <span class="btn-inner--text">
                                            {{__('Cancel Order')}}: {{\App\Models\Utility::dateFormat($order->created_at)}}
                                        </span>
                                    </button>
                            @endif
                            <!-- Actions -->
                                <div class="actions ml-3">
                                    <a href="{{route('orders.show',\Illuminate\Support\Facades\Crypt::encrypt($order->id))}}" class="action-item mr-2" data-toggle="tooltip" data-title="{{__('Details')}}">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="#" class="action-item mr-2 " data-toggle="tooltip" data-original-title="{{__('Delete')}}" data-confirm="{{__('Are You Sure?').' | '.__('This action can not be undone. Do you want to continue?')}}" data-confirm-yes="document.getElementById('delete-form-{{$order->id}}').submit();">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                    {!! Form::open(['method' => 'DELETE', 'route' => ['orders.destroy', $order->id],'id'=>'delete-form-'.$order->id]) !!}
                                    {!! Form::close() !!}
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
