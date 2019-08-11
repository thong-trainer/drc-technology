
@foreach($data as $item)
@php
$key = str_random(32);
@endphp
<tr>
  <td>                              
    {{ $item['product_name'] }} <a href="#modal-note-{{$key}}" data-toggle="modal" title="View Detail"><i class="fa fa-pencil-square-o"></i></a>

    <div class="modal fade" id="modal-note-{{$key}}" tabindex="-1" data-keyboard="false" data-backdrop="static" role="dialog" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
            <h4 class="modal-title">{{ $item['product_name'] }}</h4>
          </div>
          <div class="modal-body">
            <textarea name="description_array[]" rows="6" class="form-control" >{{ $item['description'] }}</textarea>
            <p>{{ __('message.write_description_here') }}...</p>
          </div>
          <div class="modal-footer">
            <button type="button" data-dismiss="modal" class="btn btn-primary add-description">{{ __('title.save') }}</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('title.cancel') }}</button>
          </div>
        </div>
      </div>
    </div> 

    <input type="hidden" name="product_id_array[]" value="{{ $item['product_id'] }}">
    <input type="hidden" name="product_name_array[]" value="{{ $item['product_name'] }}">
    <input type="hidden" name="variant_ids[]" value="{{ $item['variant_ids'] }}">    
    <input type="hidden" name="current_currency_id" value="{{ $currency->id }}">
    <input id="current-currency-digit" type="hidden" name="current_currency_digit" value="{{ $currency->digit }}">
    <input id="current-currency-symbol" type="hidden" name="current_currency_symbol" value="{{ $currency->symbol }}">
  </td>
  <td>
    {{ $currency->symbol }} {{ number_format($item['price'], $currency->digit) }}
    <input type="hidden" name="price_array[]" value="{{ $item['price'] }}">
  </td>
  <td>
    <input style="width: 70px" type="number" name="qty_array[]" min="1" value="{{ $item['qty'] }}" class="qty" >
  </td>
  <td>
    <span class="badge bg-default">{{ $item['tax'] }}%</span>
    <input type="hidden" name="tax_array[]" value="{{ $item['tax'] }}">
    <input type="hidden" name="pay_tax_array[]" value="{{ $item['pay_tax'] }}" class="tax">
    <input type="hidden" name="discount_array[]" value="{{ $item['discount'] }}">
    <input type="hidden" name="discount_amount_array[]" value="{{ $item['discount_amount'] }}" class="discount">
  </td>                  
  <td>
    {{ $currency->symbol }} {{ number_format($item['subtotal'], $currency->digit) }}
    <input type="hidden" name="subtotal_array[]" value="{{ $item['subtotal'] }}" class="subtotal">
  </td>
  <td>
    <a href="#" title="Remove" class="remove"><i class="fa fa-trash text-danger"></i></a>
  </td>
</tr>
@endforeach