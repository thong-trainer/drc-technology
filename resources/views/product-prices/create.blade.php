@php 
$currency_symbol = Auth::user()->defaultCurrency()->symbol
@endphp
<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
  <h4 class="modal-title">{{ __('title.new_product_price') }} </h4>
</div>
<form role="form" action="{{ route('product-price.store') }}" method="POST">
  @csrf
  <input type="hidden" name="product_id" value="{{ $product_id }}">
  <div class="modal-body">
    <div class="form-group @error('customer_group_id') has-error @enderror">
      <label>{{ __('app.customer_group') }} <span class="required">*</span></label>
      <select class="form-control" name="customer_group_id">
        @foreach($groups as $item)
        <option value="{{ $item->id }}" @if($item->id == old('customer_group_id')) selected @endif>{{ $item->group_name }}</option>
        @endforeach
      </select>  
      @error('customer_group_id')
      <span class="help-block">{{ $message }}</span>
      @enderror                                            
    </div>
    <div class="form-group">
      <label for="price">{{ __('app.price') }} </label>
      <div class="input-group">
        <span class="input-group-addon"><b>{{ $currency_symbol }}</b></span>
        <input type="number" step="any" id="price" name="price" class="form-control" min="0" required>
      </div>                                             
    </div> 
    <div class="form-group @error('minimum_qty') has-error @enderror">
      <label for="price">{{ __('app.minimum_qty') }} <span class="required">*</span></label>
      <input type="number" class="form-control" id="minimum_qty" name="minimum_qty" value="1" min="1" required>
      @error('minimum_qty')
      <span class="help-block">{{ $message }}</span>
      @enderror                      
    </div>
  </div>
  <div class="modal-footer">
    @if(Auth::user()->allowCreate(config('global.modules.product_price')))
    <button type="submit" class="btn btn-primary">{{ __('title.save') }}</button>&nbsp;&nbsp;&nbsp;
    @endif            
    <a href="#model-popup" class="btn btn-default"  data-toggle="modal">{{ __('title.cancel') }}</a>
  </div>
</form>







@section('js')
<!-- date-range-picker -->
<script src="{{ asset('bower_components/moment/min/moment.min.js') }}"></script>
<script src="{{ asset('bower_components/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
<!-- bootstrap datepicker -->
<script src="{{ asset('bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
<!-- bootstrap color picker -->
<script src="{{ asset('bower_components/bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js') }}"></script>
<!-- bootstrap time picker -->
<script src="{{ asset('plugins/timepicker/bootstrap-timepicker.min.js') }}"></script>
<script type="text/javascript">   
$(document).ready(function() {
    //Date picker
    $('.datepicker').datepicker({
      autoclose: true
    })
});  
</script>
@endsection