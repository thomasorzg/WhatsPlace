{{Form::model($productTax, array('route' => array('product_tax.update', $productTax->id), 'method' => 'PUT')) }}
<div class="row">
    <div class="col-12">
        <div class="form-group">
            {{Form::label('tax_name',__('Tax Name'))}}
            {{Form::text('name',null,array('class'=>'form-control','placeholder'=>__('Enter Account Type')))}}
            @error('tax_name')
            <span class="invalid-tax_name" role="alert">
                    <strong class="text-danger">{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>
    <div class="col-12">
        <div class="form-group">
            {{Form::label('rate',__('Rate'))}}
            {{Form::text('rate',null,array('class'=>'form-control','placeholder'=>__('Enter Account Type')))}}
            @error('rate')
            <span class="invalid-rate" role="alert">
                    <strong class="text-danger">{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>
</div>
<div class="text-right">
    {{Form::submit(__('Update'),array('class'=>'btn btn-sm btn-primary rounded-pill'))}}
</div>
{{Form::close()}}
