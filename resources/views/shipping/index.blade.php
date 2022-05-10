@extends('layouts.admin')
@section('page-title')
    {{__('Shipping')}}
@endsection

@section('action-btn')
   <a href="{{route('shipping.export')}}" class="btn btn-sm btn-white bor-radius " 
                >
                <p class=" d-sm-inline-block mb-0 pl-3 pr-2">{{__('Export')}}</p>
                <i class="fa fa-file-excel"></i> 
    </a>
@endsection

@section('content')
    <div class="card">
        <ul class="nav nav-tabs nav-overflow profile-tab-list" role="tablist">
            <li class="nav-item ml-4">
                <a href="#location" id="location_tab" class="nav-link active" data-toggle="tab" role="tab" aria-controls="home" aria-selected="true">
                    <i class="fas fa-location-arrow mr-2"></i>{{__('Location')}}
                </a>
            </li>
            <li class="nav-item ml-4">
                <a href="#shipping" id="shipping_tab" class="nav-link" data-toggle="tab" role="tab" aria-controls="home" aria-selected="true">
                    <i class="fas fa-shipping-fast mr-2"></i>
                    {{__('Shipping')}}
                </a>
            </li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane fade show active" id="location" role="tabpanel" aria-labelledby="orders-tab">
                <!-- Table -->
                <div class="table-responsive">
                    <div class="employee_menu view_employee">
                        <div class="card-header actions-toolbar border-0">
                            <div class="row justify-content-between align-items-center">
                                <div class="col">
                                    <h6 class="d-inline-block mb-0 text-capitalize">{{__('Location')}}</h6>
                                </div>
                                <div class="col text-right">
                                    <div class="actions">
                                        <div class="rounded-pill d-inline-block search_round">
                                            <div class="input-group input-group-sm input-group-merge input-group-flush">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text bg-transparent"><i class="fas fa-search"></i></span>
                                                </div>
                                                <input type="text" id="user_keyword" class="form-control form-control-flush search-user" placeholder="{{__('Search Location')}}">
                                            </div>
                                        </div>
                                        <a href="#" data-size="lg" data-url="{{ route('location.create') }}" data-ajax-popup="true" data-title="{{__('Create New Location')}}" class="btn btn-sm btn-primary btn-icon-only rounded-circle">
                                            <i class="fa fa-plus"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Table -->
                        <div class="table-responsive">
                            <table class="table align-items-center employee_tableese">
                                <thead>
                                <tr>
                                    <th scope="col" class="sort" data-sort="name">{{__('Name')}}</th>
                                    <th scope="col" class="sort" data-sort="name">{{__('Created At')}}</th>
                                    <th class="text-right">{{__('Action')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($locations as $location)
                                    <tr data-name="{{$location->name}}">
                                        <td class="sorting_1">{{$location->name}}</td>
                                        <td class="sorting_1">{{\App\Models\Utility::dateFormat($location->created_at)}}</td>
                                        <td class="action text-right">
                                            <a href="#" data-size="lg" data-url="{{ route('location.edit',$location->id) }}" data-toggle="tooltip" data-original-title="{{__('Edit')}}" data-ajax-popup="true" data-title="{{__('Edit type')}}" class="action-item"> <i class="far fa-edit"></i>
                                            </a>
                                            <a href="#" class="action-item" data-toggle="tooltip" data-original-title="{{__('Delete')}}" data-confirm="{{__('Are You Sure?').' | '.__('This action can not be undone. Do you want to continue?')}}" data-confirm-yes="document.getElementById('delete-location-form-{{$location->id}}').submit();">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                            {!! Form::open(['method' => 'DELETE', 'route' => ['location.destroy', $location->id],'id'=>'delete-location-form-'.$location->id]) !!}
                                            {!! Form::close() !!}
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
            <div class="tab-pane" id="shipping" role="tabpanel" aria-labelledby="orders-tab">
                <div class="table-responsive">
                    <div class="employee_menu view_employee">
                        <div class="card-header actions-toolbar border-0">
                            <div class="row justify-content-between align-items-center">
                                <div class="col">
                                    <h6 class="d-inline-block mb-0 text-capitalize">{{__('Shipping')}}</h6>
                                </div>
                                <div class="col text-right">
                                    <div class="actions">
                                        <div class="rounded-pill d-inline-block search_round">
                                            <div class="input-group input-group-sm input-group-merge input-group-flush">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text bg-transparent"><i class="fas fa-search"></i></span>
                                                </div>
                                                <input type="text" id="user_keyword" class="form-control form-control-flush search-user" placeholder="{{__('Search Shipping')}}">
                                            </div>
                                        </div>
                                        <a href="#" data-size="lg" data-url="{{ route('shipping.create') }}" data-ajax-popup="true" data-title="{{__('Create New Shipping')}}" class="btn btn-sm btn-primary btn-icon-only rounded-circle">
                                            <i class="fa fa-plus"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Table -->
                        <div class="table-responsive">
                            <table class="table align-items-center employee_tableese">
                                <thead>
                                <tr>
                                    <th scope="col" class="sort" data-sort="name">{{__('Name')}}</th>
                                    <th scope="col" class="sort" data-sort="name">{{__('Price')}}</th>
                                    <th scope="col" class="sort" data-sort="name">{{__('Location')}}</th>
                                    <th scope="col" class="sort" data-sort="name">{{__('Created At')}}</th>
                                    <th class="text-right">{{__('Action')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($shippings as $shipping)
                                    <tr data-name="{{$shipping->name}}">
                                        <td class="sorting_1">{{$shipping->name}}</td>
                                        <td class="sorting_1">{{\App\Models\Utility::priceFormat($shipping->price)}}</td>
                                        <td class="sorting_1">{{!empty($shipping->locationName()) ? $shipping->locationName() :'-'}}</td>
                                        <td class="sorting_1">{{\App\Models\Utility::dateFormat($shipping->created_at)}}</td>
                                        <td class="action text-right">
                                            <a href="#" data-size="lg" data-url="{{ route('shipping.edit',$shipping->id) }}" data-toggle="tooltip" data-original-title="{{__('Edit')}}" data-ajax-popup="true" data-title="{{__('Edit type')}}" class="action-item"> <i class="far fa-edit"></i>
                                            </a>
                                            <a href="#" class="action-item" data-toggle="tooltip" data-original-title="{{__('Delete')}}" data-confirm="{{__('Are You Sure?').' | '.__('This action can not be undone. Do you want to continue?')}}" data-confirm-yes="document.getElementById('delete-form-{{$shipping->id}}').submit();">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                            {!! Form::open(['method' => 'DELETE', 'route' => ['shipping.destroy', $shipping->id],'id'=>'delete-form-'.$shipping->id]) !!}
                                            {!! Form::close() !!}
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
@endsection
@push('script-page')
    <script>
        $(document).ready(function () {
            $(document).on('keyup', '.search-user', function () {
                var value = $(this).val();
                $('.employee_tableese tbody>tr').each(function (index) {
                    var name = $(this).attr('data-name').toLowerCase();
                    if (name.includes(value.toLowerCase())) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            });
        });
        $('#search'). keydown(function (e) {
            if (e. keyCode == 13) {
                e. preventDefault();
                return false;
            }
        });
    </script>
@endpush
