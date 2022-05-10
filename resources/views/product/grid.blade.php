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
    <a href="{{ route('product.index') }}" class="btn btn-sm btn-white bor-radius ml-4">
        {{__('List View')}}
    </a>
    <a href="{{ route('product.create') }}" class="btn btn-sm btn-white btn-icon-only rounded-circle">
        <i class="fa fa-plus"></i>
    </a>
@endsection
@section('filter')
@endsection
@section('content')
    <div class="row">
        @foreach($products as $product)
            <div class="col-lg-3 col-sm-6">
                <div class="card card-fluid card-product">
                    <div class="card-header border-0">
                        <h2 class="h6">
                            <a href="{{route('product.show',$product->id)}}">{{$product->name}}</a>
                        </h2>
                    </div>
                    <!-- Product image -->
                    <figure class="figure">
                        <img alt="Image placeholder" src="{{asset(Storage::url('uploads/is_cover_image/'.$product->is_cover))}}" class="img-center img-fluid">
                    </figure>
                    <div class="card-body">
                        <!-- Price -->
                        <div class="d-flex align-items-center mt-4">
                            <span class="h6 mb-0">{{\App\Models\Utility::priceFormat($product->price)}}</span>
                            @if($product->quantity == 0)
                                <span class="badge badge-danger rounded-pill ml-auto">
                                {{__('Out of stock')}}
                                </span>
                            @else
                                <span class="badge badge-success rounded-pill ml-auto">
                                    {{__('In stock')}}
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="actions d-flex justify-content-between">
                            <a href="{{route('product.show',$product->id)}}" class="action-item">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{route('product.edit',$product->id)}}" class="action-item">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="#" class="action-item mr-2 " data-toggle="tooltip" data-original-title="{{__('Delete')}}" data-confirm="{{__('Are You Sure?').' | '.__('This action can not be undone. Do you want to continue?')}}" data-confirm-yes="document.getElementById('delete-form-{{$product->id}}').submit();">
                                <i class="fas fa-trash-alt"></i>
                            </a>
                        </div>
                        {!! Form::open(['method' => 'DELETE', 'route' => ['product.destroy', $product->id],'id'=>'delete-form-'.$product->id]) !!}
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection
