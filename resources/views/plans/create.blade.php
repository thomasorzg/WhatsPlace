{{Form::open(array('route'=>'plans.store','method'=>'post','enctype'=>'multipart/form-data'))}}
@csrf
<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            {{Form::label('name',__('Name'),array('class'=>'form-control-label')) }}
            {{Form::text('name',null,array('class'=>'form-control','placeholder'=>__('Enter Name'),'required'=>'required'))}}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            {{Form::label('price',__('Price'),array('class'=>'form-control-label')) }}
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text">{{env('CURRENCY_SYMBOL')}}</span>
                </div>
                {{Form::number('price',null,array('class'=>'form-control','id'=>'monthly_price','min'=>'0','placeholder'=>__('Enter Price'),'required'=>'required'))}}
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            {{ Form::label('image', __('Image')) }}
            {{ Form::file('image', array('class' => 'form-control')) }}
        </div>
    </div>
    <div class="form-group col-md-6">
        {{ Form::label('duration', __('Duration')) }}
        {!! Form::select('duration', $arrDuration, null,array('class' => 'form-control','data-toggle'=>'select','required'=>'required')) !!}
    </div>
    <div class="col-md-6">
        <div class="form-group">
            {{Form::label('max_stores',__('Maximum Store'),array('class'=>'form-control-label')) }}
            {{Form::number('max_stores',null,array('class'=>'form-control','id'=>'max_stores','placeholder'=>__('Enter Max Store'),'required'=>'required'))}}
            <span><small>{{__("Note: '-1' for Unlimited")}}</small></span>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            {{Form::label('max_products',__('Maximum Products Per Store'),array('class'=>'form-control-label')) }}
            {{Form::number('max_products',null,array('class'=>'form-control','id'=>'max_products','placeholder'=>__('Enter Max Products'),'required'=>'required'))}}
            <span><small>{{__("Note: '-1' for Unlimited")}}</small></span>
        </div>
    </div>
    <div class="col-6">
        <div class="custom-control custom-switch pt-2">
            <input type="checkbox" class="custom-control-input" name="enable_custdomain" id="enable_custdomain">
            <label class="custom-control-label form-control-label" for="enable_custdomain">{{__('Enable Domain')}}</label>
        </div>
    </div>
    <div class="col-6">
        <div class="custom-control custom-switch pt-2">
            <input type="checkbox" class="custom-control-input" name="enable_custsubdomain" id="enable_custsubdomain">
            <label class="custom-control-label form-control-label" for="enable_custsubdomain">{{__('Enable Sub Domain')}}</label>
        </div>
    </div>
    <div class="col-6">
        <div class="custom-control custom-switch pt-2">
            <input type="checkbox" class="custom-control-input" name="shipping_method" id="shipping_method">
            <label class="custom-control-label form-control-label" for="shipping_method">{{__('Enable Shipping Method')}}</label>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <div class="form-group">
            {{Form::label('description',__('Description'),array('class'=>'form-control-label')) }}
            {{Form::textarea('description',null,array('class'=>'form-control','id'=>'description', 'rows' => 2,'placeholder'=>__('Enter Description')))}}
        </div>
    </div>
</div>
<div class="form-group text-right">
    {{Form::submit(__('Create Plan'),array('class'=>'btn btn-sm btn-primary rounded-pill mr-auto'))}}
</div>
{{Form::close()}}
