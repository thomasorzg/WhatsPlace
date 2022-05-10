<form method="post" action="{{ route('product-coupon.update', $productCoupon->id) }}" id="product-coupon-store">
    @csrf
    @method('PUT')
    <div class="row">
        <div class="form-group col-md-12">
            <label for="name">{{__('Name')}}</label>
            <input type="text" name="name" class="form-control" required value="{{$productCoupon->name}}">
        </div>
        <div class="form-group col-md-12">
            {{Form::label('enable_flat',__('Flat Discount'),array('class'=>'form-control-label mb-3')) }}
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input" name="enable_flat" id="enable_flat" {{ ($productCoupon['enable_flat'] == 'on') ? 'checked=checked' : '' }}>
                <label class="custom-control-label form-control-label" for="enable_flat"></label>
            </div>
        </div>
        <div class="form-group col-md-6 nonflat_discount">
            {{Form::label('discount',__('Discount') ,array('class'=>'form-control-label')) }}
            {{Form::number('discount',$productCoupon->discount,array('class'=>'form-control','step'=>'0.01','placeholder'=>__('Enter Discount')))}}
            <span class="small">{{__('Note: Discount in Percentage')}}</span>
        </div>
        <div class="form-group col-md-6 flat_discount" style="display: none;">
            {{Form::label('pro_flat_discount',__('Flat Discount') ,array('class'=>'form-control-label')) }}
            {{Form::number('pro_flat_discount',$productCoupon->flat_discount,array('class'=>'form-control','step'=>'0.01','placeholder'=>__('Enter Flat Discount')))}}
            <span class="small">{{__('Note: Discount in Value')}}</span>
        </div>
        <div class="form-group col-md-6">
            <label for="limit">{{__('Limit')}}</label>
            <input type="number" name="limit" class="form-control" required value="{{$productCoupon->limit}}">
        </div>
        <div class="form-group col-md-12" id="auto">
            <label for="code">{{__('Code')}}</label>
            <div class="input-group">
                <input class="form-control" name="code" type="text" id="auto-code" value="{{$productCoupon->code}}">
                <div class="input-group-prepend">
                    <button type="button" class="input-group-text" id="code-generate"><i class="fa fa-history pr-1"></i> {{__('Generate')}}</button>
                </div>
            </div>
        </div>
        <div class="form-group col-md-12 text-right">
            <button class="btn btn-sm btn-primary rounded-pill mr-auto" type="submit">{{ __('Update') }}</button>
        </div>
    </div>
</form>

