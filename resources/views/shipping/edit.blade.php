{{Form::model($shipping, array('route' => array('shipping.update', $shipping->id), 'method' => 'PUT')) }}
<div class="row">
    <div class="col-12">
        <div class="form-group">
            {{Form::label('name',__('Name'))}}
            {{Form::text('name',null,array('class'=>'form-control','placeholder'=>__('Enter Product Category')))}}
            @error('name')
            <span class="invalid-name" role="alert">
                    <strong class="text-danger">{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>
    <div class="col-12">
        <div class="form-group">
            {{Form::label('price',__('Price'))}}
            {{Form::text('price',null,array('class'=>'form-control','placeholder'=>__('Enter State Name')))}}
            @error('price')
            <span class="invalid-price" role="alert">
                    <strong class="text-danger">{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>
    <div class="col-12">
        <div class="form-group ">
            {{Form::label('location',__('Location'),array('class'=>'form-control-label')) }}
            {!! Form::select('location[]',$locations,explode(',',$shipping->location_id),array('class' => 'form-control','data-toggle'=>'select','multiple')) !!}
        </div>
    </div>
</div>
<div class="text-right">
    {{Form::submit(__('Update'),array('class'=>'btn btn-sm btn-primary rounded-pill'))}}
</div>
{{Form::close()}}
