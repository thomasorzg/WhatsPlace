@extends('layouts.admin')
@section('page-title')
    {{__('Product Category')}}
@endsection
@section('title')
    <div class="d-inline-block">
        <h5 class="h5 d-inline-block text-white font-weight-bold mb-0 ">{{__('Product Category')}}</h5>
    </div>
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="#">{{__('Home')}}</a></li>
    <li class="breadcrumb-item active text-white" aria-current="page">{{__('Product Category')}}</li>
@endsection
@section('action-btn')
    <a href="#" data-size="lg" data-url="{{ route('product_categorie.create') }}" data-ajax-popup="true" data-title="{{__('Create New Category')}}" class="btn btn-sm btn-white btn-icon-only rounded-circle">
        <i class="fa fa-plus"></i>
    </a>
@endsection
@section('filter')
@endsection
@section('content')
    <div class="card">
        <!-- Table -->
        <div class="table-responsive">
            <div class="employee_menu view_employee">
                <div class="card-header actions-toolbar border-0">
                    <div class="row justify-content-between align-items-center">
                        <div class="col text-right">
                            <div class="actions">
                                <div class="rounded-pill d-inline-block search_round">
                                    <div class="input-group input-group-sm input-group-merge input-group-flush">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-transparent"><i class="fas fa-search"></i></span>
                                        </div>
                                        <input type="text" id="user_keyword" class="form-control form-control-flush search-user" placeholder="{{__('Search Category')}}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Table -->
                <div class="table-responsive">
                    <table class="table align-items-center employee_tableese">
                        <thead>
                        <tr>
                            <th scope="col" class="sort" data-sort="name">{{__('Category Name')}}</th>
                            <th class="text-right">{{__('Action')}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($product_categorys as $product_category)
                            <tr data-name="{{$product_category->name}}">
                                <td class="sorting_1">{{$product_category->name}}</td>
                                <td class="action text-right">
                                    <a href="#" data-size="lg" data-url="{{ route('product_categorie.edit',$product_category->id) }}" data-toggle="tooltip" data-original-title="{{__('Edit')}}" data-ajax-popup="true" data-title="{{__('Edit Categories')}}" class="action-item">
                                        <i class="far fa-edit"></i>
                                    </a>
                                    <a href="#" class="action-item" data-toggle="tooltip" data-original-title="{{__('Delete')}}" data-confirm="{{__('Are You Sure?').' | '.__('This action can not be undone. Do you want to continue?')}}" data-confirm-yes="document.getElementById('delete-form-{{$product_category->id}}').submit();">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                    {!! Form::open(['method' => 'DELETE', 'route' => ['product_categorie.destroy', $product_category->id],'id'=>'delete-form-'.$product_category->id]) !!}
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
