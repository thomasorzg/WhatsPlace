{{Form::open(array('url'=>'product_tax','method'=>'post'))}}
<div class="row">
    <div class="col-12">
        <div class="form-group">
            {{Form::label('tax_name',__('Tax Name')) }}
            {{Form::text('tax_name',null,array('class'=>'form-control','placeholder'=>__('Enter Tax Name'),'required'=>'required'))}}
        </div>
    </div>
    <div class="col-12">
        <div class="form-group">
            {{Form::label('rate',__('Rate').' '.'(%)') }}
            {{Form::text('rate',null,array('class'=>'form-control','placeholder'=>__('Enter Rate'),'required'=>'required'))}}
        </div>
    </div>
    <div class="w-100 text-right">
        {{Form::submit(__('Save'),array('class'=>'btn btn-sm btn-primary rounded-pill mr-auto'))}}
    </div>
</div>
{{Form::close()}}
