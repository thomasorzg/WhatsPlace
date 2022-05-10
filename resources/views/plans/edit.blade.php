{{Form::model($plan, array('route' => array('plans.update', $plan->id), 'method' => 'PUT','enctype'=>'multipart/form-data')) }}
@csrf
@method('put')

<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            {{Form::label('name',__('Name'),array('class'=>'form-control-label')) }}
            {!! Form::text('name',null,array('class'=>'form-control','placeholder'=>__('Enter Name'))) !!}
        </div>
    </div>
    @if($plan->price>0)
        <div class="col-md-6">
            <div class="form-group">
                {{Form::label('price',__('Price'),array('class'=>'form-control-label')) }}
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text">{{env('CURRENCY_SYMBOL')}}</span>
                    </div>
                    {!! Form::number('price',null,array('class'=>'form-control', 'id'=>'monthly_price','min'=>'0','placeholder'=>__('Enter Price'))) !!}
                </div>
            </div>
        </div>
    @endif
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
    <div class="col-6">
        <div class="form-group">
            {{Form::label('max_stores',__('Maximum stores'),array('class'=>'form-control-label')) }}
            {!! Form::text('max_stores',null,array('class'=>'form-control','id'=>'max_stores','placeholder'=>__('Enter Max Stores'))) !!}
            <span><small>{{__("Note: '-1' for Unlimited")}}</small></span>
        </div>
    </div>
    <div class="col-6">
        <div class="form-group">
            {{Form::label('max_products',__('Maximum Product Per Store'),array('class'=>'form-control-label')) }}
            {!! Form::text('max_products',null,array('class'=>'form-control','id'=>'max_products','placeholder'=>__('Enter Products Per Store'))) !!}
            <span><small>{{__("Note: '-1' for Unlimited")}}</small></span>
        </div>
    </div>
    <div class="col-6">
        <div class="custom-control custom-switch pt-4">
            <input type="checkbox" class="custom-control-input" name="enable_custdomain" id="enable_custdomain" {{ ($plan['enable_custdomain'] == 'on') ? 'checked=checked' : '' }}>
            <label class="custom-control-label form-control-label" for="enable_custdomain">{{__('Enable Domain')}}</label>
        </div>
    </div>
    <div class="col-6">
        <div class="custom-control custom-switch pt-4">
            <input type="checkbox" class="custom-control-input" name="enable_custsubdomain" id="enable_custsubdomain" {{ ($plan['enable_custsubdomain'] == 'on') ? 'checked=checked' : '' }}>
            <label class="custom-control-label form-control-label" for="enable_custsubdomain">{{__('Enable Sub Domain')}}</label>
        </div>
    </div>
    <div class="col-6">
        <div class="custom-control custom-switch pt-4">
            <input type="checkbox" class="custom-control-input" name="shipping_method" id="shipping_method" {{ ($plan['shipping_method'] == 'on') ? 'checked=checked' : '' }}>
            <label class="custom-control-label form-control-label" for="shipping_method">{{__('Enable Shipping Method')}}</label>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group">
            {{Form::label('description',__('Description'),array('class'=>'form-control-label')) }}
            {!! Form::textarea('description',null,array('class'=>'form-control','id'=>'description','rows'=>2,'placeholder'=>__('Enter Description'))) !!}
        </div>
    </div>
</div>
<div class="form-group text-right">
    <button class="btn btn-sm btn-primary rounded-pill mr-auto" type="submit">{{ __('Update Plan') }}</button>
</div>
</form>
