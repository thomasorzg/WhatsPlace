@extends('layouts.admin')
@section('page-title')
    {{__('Coupon Detail')}}
@endsection
@section('title')
    <div class="d-inline-block">
        <h5 class="h4 d-inline-block text-white font-weight-bold mb-0 ">{{__('Coupon Detail')}}</h5>
    </div>
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between w-100">
                        <h5>{{$productCoupon->code}}</h5>
                    </div>
                </div>
                <div class="dataTables_wrapper">
                    <div class="table-responsive">
                        <table id="selection-datatable" class="table no-footer" width="100%" role="grid" aria-describedby="selection-datatable_info" style="width: 100%;">
                            <thead>
                            <tr role="row">
                                <th class="sorting" tabindex="0" aria-controls="selection-datatable" rowspan="1" colspan="1" aria-label=" Coupon: activate to sort column ascending" style="width: 354px;"> Coupon</th>
                                <th class="sorting" tabindex="0" aria-controls="selection-datatable" rowspan="1" colspan="1" aria-label=" User: activate to sort column ascending" style="width: 411px;"> User</th>
                                <th class="sorting" tabindex="0" aria-controls="selection-datatable" rowspan="1" colspan="1" aria-label=" Date: activate to sort column ascending" style="width: 642px;"> Date</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($productCoupons as $userCoupon)
                                <tr role="row" class="odd">
                                    <td>{{ !empty($productCoupon->name)?$productCoupon->name:'' }}</td>
                                    <td>{{ !empty($userCoupon->name)?$userCoupon->name:'' }}</td>
                                    <td>{{ $userCoupon->created_at }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
