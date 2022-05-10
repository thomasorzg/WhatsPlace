@extends('layouts.admin')
@section('page-title')
    {{__('Product')}}
@endsection
@section('title')
    <div class="d-inline-block">
        <h5 class="h4 d-inline-block text-white font-weight-bold mb-0 ">{{__('Product')}}</h5>
    </div>
@endsection
@section('breadcrumb')
@endsection
@section('action-btn')
    <a href="{{ route('product.grid') }}" class="btn btn-sm btn-white bor-radius ">
        {{__('Grid View')}}
    </a>
    <a href="#"  data-size="lg"  data-url="{{route('product.file.import')}}" data-ajax-popup="true" data-title="{{__('Import Product')}}" class="btn btn-sm btn-white bor-radius">
    <p class=" d-sm-inline-block mb-0 pl-3 pr-2">{{__('Import')}}</p>
    <i class="fa fa-file-csv"></i> 
    </a>
     <a href="{{route('product.export',$store_id->id)}}" class="btn btn-sm btn-white bor-radius " 
                >
                <p class=" d-sm-inline-block mb-0 pl-3 pr-2">{{__('Export')}}</p>
                <i class="fa fa-file-excel"></i> 
    </a>









    <a href="{{ route('product.create') }}" class="btn btn-sm btn-white btn-icon-only rounded-circle">
        <i class="fa fa-plus"></i>
    </a>
@endsection
@section('filter')
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
                    <h6 class="d-inline-block mb-0">{{__('All Products')}}</h6>
                </div>
            </div>
        </div>
        <!-- Table -->
        <div class="table-responsive">
            <table class="table align-items-center">
                <thead>
                <tr>
                    <th scope="col">{{ __('Product')}}</th>
                    <th scope="col">{{ __('Category')}}</th>
                    <th scope="col">{{ __('Price')}}</th>
                    <th scope="col">{{ __('Quantity')}}</th>
                    <th scope="col">{{ __('Stock')}}</th>
                    <th scope="col">{{ __('Created at')}}</th>
                    <th scope="col" class="text-right">{{ __('Action')}}</th>
                </tr>
                </thead>
                <tbody class="list">
                @foreach($products as $product)
                    <tr>
                        <th scope="row">
                            <div class="media align-items-center">
                                <div>
                                    @if(!empty($product->is_cover))
                                        <img alt="Image placeholder" src="{{asset(Storage::url('uploads/is_cover_image/'.$product->is_cover))}}" class="" style="width: 80px;">
                                    @else
                                        <img alt="Image placeholder" src="{{asset(Storage::url('uploads/is_cover_image/default.jpg'))}}" class="" style="width: 80px;">
                                    @endif

                                </div>
                                <div class="media-body ml-4">
                                    <a href="{{route('product.show',$product->id)}}" class="name h6 mb-0 text-sm">
                                        {{$product->name}}
                                    </a>
                                </div>
                            </div>
                        </th>
                        <td>
                            {{!empty($product->product_category()) ? $product->product_category() :'-'}}
                        </td>
                        <td>
                            @if($product->enable_product_variant == 'on')
                                {{ __('In Variant')}}
                            @else
                                {{\App\Models\Utility::priceFormat($product->price)}}
                            @endif
                        </td>
                        <td>
                            @if($product->enable_product_variant == 'on')
                                {{ __('In Variant')}}
                            @else
                                {{$product->quantity}}
                            @endif
                        </td>
                        <td>
                            @if($product->enable_product_variant == 'on')
                                <span class="badge badge-success rounded-pill">
                                    {{__('In Variant')}}
                                </span>
                            @else
                                @if($product->quantity == 0)
                                    <span class="badge badge-danger rounded-pill">
                                        {{__('Out of stock')}}
                                    </span>
                                @else
                                    <span class="badge badge-success rounded-pill">
                                        {{__('In stock')}}
                                    </span>
                                @endif
                            @endif
                        </td>
                        <td>
                            {{\App\Models\Utility::dateFormat($product->created_at)}}
                        </td>
                        <td class="text-right">
                            <!-- Actions -->
                            <div class="actions ml-3">
                                <a href="{{route('product.show',$product->id)}}" class="action-item mr-2" data-toggle="tooltip" title="" data-original-title="Quick view">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{route('product.edit',$product->id)}}" class="action-item mr-2" data-toggle="tooltip" title="" data-original-title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="#" class="action-item  mr-2 " data-toggle="tooltip" data-original-title="{{__('Delete')}}" data-confirm="{{__('Are You Sure?').' | '.__('This action can not be undone. Do you want to continue?')}}" data-confirm-yes="document.getElementById('delete-form-{{$product->id}}').submit();">
                                    <i class="fas fa-trash"></i>
                                </a>
                                {!! Form::open(['method' => 'DELETE', 'route' => ['product.destroy', $product->id],'id'=>'delete-form-'.$product->id]) !!}
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

