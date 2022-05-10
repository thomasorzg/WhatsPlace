<form method="POST" action="{{ route('get.product.variants.possibilities') }}">
    @csrf
    <div class="form-group">
        <label for="variant_name">{{ __('Variant Name') }}</label>
        <input class="form-control" name="variant_name" type="text" id="variant_name" placeholder="{{ __('Variant Name, i.e Size, Color etc') }}">
    </div>
    <div class="form-group">
        <label for="variant_options">{{ __('Variant Options') }}</label>
        <input class="form-control" name="variant_options" type="text" id="variant_options" placeholder="{{ __('Variant Options separated by|pipe symbol, i.e Black|Blue|Red') }}">
    </div>
    <div class="form-group">
        <button class="btn btn-primary add-variants" type="button"> {{ __('Add Variants') }} </button>
    </div>
</form>
