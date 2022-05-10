@extends('layouts.admin')
@section('page-title')
    {{__('Custom Domain')}}
@endsection
@section('title')
    <div class="d-inline-block">
        <h5 class="h4 d-inline-block text-white font-weight-bold mb-0 ">{{__('Domain')}}</h5>
    </div>
@endsection
@section('breadcrumb')
@endsection
@section('action-btn')
    <a href="{{ route('store.subDomain') }}" class="btn btn-sm btn-white bor-radius">
        {{__('Sub Domain')}}
    </a>
    <a href="{{ route('store.grid') }}" class="btn btn-sm btn-white bor-radius">
        {{__('Grid View')}}
    </a>
    <a href="{{ route('store-resource.index') }}" class="btn btn-sm btn-white bor-radius">
        {{__('List View')}}
    </a>
    <a href="#" data-size="lg" data-url="{{ route('store-resource.create') }}" data-ajax-popup="true" data-title="{{__('Create New Store')}}" class="btn btn-sm btn-white btn-icon-only rounded-circle">
        <i class="fa fa-plus"></i>
    </a>
@endsection
@section('filter')
@endsection
@push('css-page')
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
                    <h6 class="text-muted d-inline-block mb-0">{{__('If you\'re using cPanel or Plesk then you need to manually add below custom domain in your server with the same root directory as the script\'s installation. and user need to point their custom domain A record with your server IP ').$serverIp}}</h6>
                </div>
            </div>
        </div>
        <!-- Table -->
        <div class="table-responsive">
            <table class="table align-items-center">
                <thead>
                <tr>
                    <th scope="col">{{ __('Custom Domain Name')}}</th>
                    <th scope="col">{{ __('Store Name')}}</th>
                    <th scope="col">{{ __('Email')}}</th>

                </tr>
                </thead>
                <tbody class="list">
                @foreach($stores as $store)
                    <tr>
                        <td>
                            {{$store->domains}}
                        </td>
                        <td>
                            {{$store->name}}
                        </td>
                        <td>
                            {{($store->email)}}
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
