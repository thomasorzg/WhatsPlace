@extends('layouts.admin')
@section('page-title')
    {{__('WhatsStore')}}
@endsection
@section('title')
    <div class="d-inline-block">
        <h5 class="h4 d-inline-block text-white font-weight-bold mb-0 ">{{__('Store')}}</h5>
    </div>
@endsection
@section('action-btn')
    <a href="{{ route('store.subDomain') }}" class="btn btn-sm btn-white bor-radius">
        {{__('Sub Domain')}}
    </a>
    <a href="{{ route('store.customDomain') }}" class="btn btn-sm btn-white bor-radius">
        {{__('Custom Domain')}}
    </a>
    <a href="{{ route('store.grid') }}" class="btn btn-sm btn-white bor-radius">
        {{__('Grid View')}}
    </a>
    <a href="#" data-size="lg" data-url="{{ route('store-resource.create') }}" data-ajax-popup="true" data-title="{{__('Create New Store')}}" class="btn btn-sm btn-white btn-icon-only rounded-circle">
        <i class="fa fa-plus"></i>
    </a>
@endsection
@push('css-page')
    <link rel="stylesheet" href="{{asset('assets/libs/summernote/summernote-bs4.css')}}">
@endpush
@push('script-page')
    <script src="{{asset('assets/libs/summernote/summernote-bs4.js')}}"></script>
@endpush
@section('content')
    <!-- Listing -->
    <div class="card">
        <!-- Card header -->
        <div class="card-header actions-toolbar">
            <div class="actions-search" id="actions-search">
                <div class="input-group input-group-merge input-group-flush">
                    <div class="input-group-prepend">
                        <span class="input-group-text bg-transparent"><i class="far fa-search"></i></span>
                    </div>
                    <input type="text" class="form-control form-control-flush" placeholder="Type and hit enter ...">
                    <div class="input-group-append">
                        <a href="#" class="input-group-text bg-transparent" data-action="search-close" data-target="#actions-search"><i class="far fa-times"></i></a>
                    </div>
                </div>
            </div>
            <div class="row justify-content-between align-items-center">
                <div class="col">
                    <h6 class="d-inline-block mb-0">{{__('All Store')}}</h6>
                </div>
            </div>
        </div>
        <!-- Table -->
        <div class="table-responsive">
            <table class="table align-items-center">
                <thead>
                <tr>
                    <th scope="col">{{ __('User Name')}}</th>
                    <th scope="col">{{ __('Email')}}</th>
                    <th scope="col">{{ __('Stores')}}</th>
                    <th scope="col">{{ __('Plan')}}</th>
                    <th scope="col">{{ __('Created At')}}</th>
                    <th scope="col" class="text-right">{{ __('Action')}}</th>
                </tr>
                </thead>
                <tbody class="list">
                @foreach($users as $usr)
                    <tr>
                        <td>{{ $usr->name }}</td>
                        <td>{{ $usr->email }}</td>
                        <td>{{ $usr->stores->count() }}</td>
                        <td>{{ !empty($usr->currentPlan->name)?$usr->currentPlan->name:'-' }}</td>
                        <td>{{\App\Models\Utility::dateFormat($usr->created_at)}}</td>
                        <td class="text-right">
                            <!-- Actions -->
                            <div class="actions ml-3">
                                <a href="#" data-size="lg" data-url="{{route('user.reset',$usr->id)}}" data-ajax-popup="true" class="action-item" data-title="{{__('Change Password')}}" data-toggle="tooltip" title="" data-original-title="password">
                                    <i class="fas fa-key"></i>
                                </a>
                                <a href="#" data-size="lg" data-url="{{ route('store-resource.edit',$usr->id) }}" data-ajax-popup="true" class="action-item" data-title="{{__('Edit Store')}}" data-toggle="tooltip" title="" data-original-title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="#" class="action-item" data-size="lg" data-url="{{ route('plan.upgrade',$usr->id) }}" data-ajax-popup="true" data-toggle="tooltip" data-title="{{__('Upgrade Plan')}}">
                                    <i class="fas fa-trophy"></i>
                                </a>
                                <a href="#" class="action-item" data-toggle="tooltip" data-original-title="{{__('Delete')}}" data-confirm="{{__('Are You Sure?').' | '.__('This action can not be undone. Do you want to continue?')}}" data-confirm-yes="document.getElementById('delete-form-{{$usr->id}}').submit();">
                                    <i class="fas fa-trash"></i>
                                </a>
                                {!! Form::open(['method' => 'DELETE', 'route' => ['store-resource.destroy', $usr->id],'id'=>'delete-form-'.$usr->id]) !!}
                                {!! Form::close() !!}
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
@push('script-page')
    <script>
        $(document).on('click', '#billing_data', function () {
            $("[name='shipping_address']").val($("[name='billing_address']").val());
            $("[name='shipping_city']").val($("[name='billing_city']").val());
            $("[name='shipping_state']").val($("[name='billing_state']").val());
            $("[name='shipping_country']").val($("[name='billing_country']").val());
            $("[name='shipping_postalcode']").val($("[name='billing_postalcode']").val());
        })
    </script>
@endpush

