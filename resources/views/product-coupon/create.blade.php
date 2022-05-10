<form method="post" action="{{ route('product-coupon.store') }}" id="product-coupon-store">
    @csrf
    <div class="row">
        <div class="form-group col-md-12">
            {{Form::label('name',__('Name'),array('class'=>'form-control-label'))}}
            {{Form::text('name',null,array('class'=>'form-control','placeholder'=>__('Enter Name'),'required'=>'required'))}}
        </div>
        <div class="form-group col-md-12">
            {{Form::label('enable_flat',__('Flat Discount'),array('class'=>'form-control-label mb-3')) }}
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input" name="enable_flat" id="enable_flat">
                <label class="custom-control-label form-control-label" for="enable_flat"></label>
            </div>
        </div>
        <div class="form-group col-md-6 nonflat_discount">
            {{Form::label('discount',__('Discount') ,array('class'=>'form-control-label')) }}
            {{Form::number('discount',null,array('class'=>'form-control','step'=>'0.01','placeholder'=>__('Enter Discount')))}}
            <span class="small">{{__('Note: Discount in Percentage')}}</span>
        </div>
        <div class="form-group col-md-6 flat_discount" style="display: none;">
            {{Form::label('pro_flat_discount',__('Flat Discount') ,array('class'=>'form-control-label')) }}
            {{Form::number('pro_flat_discount',null,array('class'=>'form-control','step'=>'0.01','placeholder'=>__('Enter Flat Discount')))}}
            <span class="small">{{__('Note: Discount in Value')}}</span>
        </div>
        <div class="form-group col-md-6">
            {{Form::label('limit',__('Limit') ,array('class'=>'form-control-label'))}}
            {{Form::number('limit',null,array('class'=>'form-control','placeholder'=>__('Enter Limit'),'required'=>'required'))}}
        </div>
        <div class="form-group col-md-12" id="auto">
            {{Form::label('limit',__('Code') ,array('class'=>'form-control-label'))}}
            <div class="input-group">
                {{Form::text('code',null,array('class'=>'form-control','id'=>'auto-code','required'=>'required'))}}
                <div class="input-group-prepend">
                    <button type="button" class="input-group-text" id="code-generate"><i class="fa fa-history pr-1"></i> {{__('Generate')}}</button>
                </div>
            </div>
        </div>
        <div class="form-group col-md-12 text-right">
            <button class="btn btn-sm btn-primary rounded-pill mr-auto" type="submit">{{ __('Create') }}</button>
        </div>
    </div>
</form>

