@php 
  $last_dimension_id = 0;
@endphp
<div class="row">
  <div class="col-md-6">
    @foreach($data as $variant)
      <div class="form-group">
        @if($last_dimension_id != $variant->dimension_id)
        <label for="name">{{ $variant->dimension->dimension_name }}<span class="required">*</span></label>
        @endif
        <div class="radio">
          <label>
            <input class="dimension-radio" type="radio" value="{{ $variant->id }}" name="{{ $variant->dimension->dimension_name }}" @if($variant->extra_price == 0) checked @endif>
            {{ $variant->value }} 
            @if($variant->extra_price > 0)
              <span class="badge bg-green" title="Extra Price">+ {{ $currency->symbol }} {{ number_format($variant->extra_price, $currency->digit) }}</span>
            @endif
          </label>
        </div>
      </div> 
      @php 
        $last_dimension_id =  $variant->dimension_id;    
      @endphp
    @endforeach    
  </div>
  <div class="col-md-6">
    <label id="price" style="font-size: 22px;">{{ $currency->symbol }} {{ number_format($default_price, $currency->digit) }}</label>  
    <div class="input-group input-group" style="width: 150px">                  
      <span class="input-group-btn">
        <button id="qty-down" type="button" class="btn btn-info btn-flat"><i class="fa fa-minus"></i></button>
      </span>                  
      <input type="text" id="qty" name="qty" value="{{ $product->default_qty }}" class="form-control" min="1" style="text-align: center; font-weight: bold;" readonly>
      <span class="input-group-btn">
        <button id="qty-up" type="button" class="btn btn-info btn-flat"><i class="fa fa-plus"></i></button>
      </span>
    </div>                
  </div>
</div>



<script type="text/javascript">   
$(document).ready(function() {
    // to close the modal popup
    $('#close-modal').click(function () {
      $("#model-popup").modal('hide');
    });   

});  
</script>