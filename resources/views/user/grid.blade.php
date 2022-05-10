@extends('layouts.admin')
@section('page-title')
    {{__('WhatsStore')}}
@endsection
@section('title')
    <div class="d-inline-block">
        <h5 class="h4 d-inline-block font-weight-400 mb-0 text-white">{{__('Store')}}</h5>
    </div>
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{__('Home')}}</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{__('User')}}</li>
@endsection
@section('action-btn')
    <a href="{{ route('store.subDomain') }}" class="btn btn-sm btn-white bor-radius">
        {{__('Sub Domain')}}
    </a>
    <a href="{{ route('store.customDomain') }}" class="btn btn-sm btn-white bor-radius">
        {{__('Custom Domain')}}
    </a>
    <a href="{{ route('store-resource.index') }}" class="btn btn-sm btn-white bor-radius">
        {{__('List View')}}
    </a>
    <a href="#" data-size="lg" data-url="{{ route('store-resource.create') }}" data-ajax-popup="true" data-title="{{__('Create New User')}}" class="btn btn-sm btn-white btn-icon-only rounded-circle">
        <i class="fa fa-plus"></i>
    </a>
@endsection
@section('filter')
@endsection
@section('content')
    @if(\Auth::user()->type = 'super admin')
        <div class="row">
            @foreach($users as $user)
                <div class="col-lg-3 col-sm-6">
                    <div class="card hover-shadow-lg">
                        <div class="card-body text-center">
                            <div class="avatar-parent-child">
                                <img alt="" src="{{ asset(Storage::url("uploads/profile/")).'/'}}{{ !empty($user->avatar)?$user->avatar:'avatar.png' }}" class="avatar  rounded-circle avatar-lg">
                            </div>
                            <h5 class="h6 mt-4 mb-0"> {{$user->name}}</h5>
                            <a href="#" class="d-block text-sm text-muted mb-3"> {{$user->email}}</a>
                            <div class="actions d-flex justify-content-between pl-5">
                                <a href="#" data-size="lg" data-url="{{ route('user.edit',$user->id) }}" data-ajax-popup="true" class="action-item" data-toggle="tooltip" data-title="{{__('Edit')}}" data-original-title="{{__('Edit')}}">
                                    <i class="far fa-edit"></i>
                                </a>
                                <a href="#" class="action-item" data-size="lg" data-url="{{ route('plan.upgrade',$user->id) }}" data-ajax-popup="true" data-toggle="tooltip" data-title="{{__('Upgrade Plan')}}">
                                    <i class="fas fa-trophy"></i>
                                </a>

                                <a href="#" class="action-item " data-toggle="tooltip" data-original-title="{{__('Delete')}}" data-confirm="{{__('Are You Sure?').' | '.__('This action can not be undone. Do you want to continue?')}}" data-confirm-yes="document.getElementById('delete-form-{{$user->id}}').submit();">
                                    <i class="fas fa-trash"></i>
                                </a>
                                {!! Form::open(['method' => 'DELETE', 'route' => ['user.destroy', $user->id],'id'=>'delete-form-'.$user->id]) !!}
                                {!! Form::close() !!}
                            </div>
                        </div>
                        <div class="card-body border-top">
                            <div class="row justify-content-between align-items-center">
                                <div class="col-6 text-center">
                                    <span class="d-block h4 mb-0">{{$user->countProducts($user->id)}}</span>
                                    <span class="d-block text-sm text-muted">{{__('Products')}}</span>
                                </div>
                                <div class="col-6 text-center">
                                    <span class="d-block h4 mb-0">{{$user->countStores($user->id)}}</span>
                                    <span class="d-block text-sm text-muted">{{__('Stores')}}</span>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="actions d-flex justify-content-between">
                                <span class="d-block text-sm text-muted"> {{__('Plan') }} : {{$user->currentPlan->name}}</span>

                            </div>
                            <div class="actions d-flex justify-content-between mt-1">
                                <span class="d-block text-sm text-muted">{{__('Plan Expired') }} : {{!empty($user->plan_expire_date) ? \Auth::user()->dateFormat($user->plan_expire_date):'Unlimited'}}</span>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
@endsection
