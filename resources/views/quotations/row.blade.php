<tr>
  <td>                              
    {{ $product_name }} <a href="#" title="View Detail"><i class="fa fa-external-link"></i></a>
    <input type="hidden" name="product_id_array[]" value="{{ $product_id }}">
    <input type="hidden" name="product_name_array[]" value="{{ $product_name }}">
    <input type="hidden" name="current_currency_id" value="{{ $currency->id }}">
  </td>
  <td>
    {{ $currency->symbol }} {{ number_format($price, $currency->digit) }}
    <input type="hidden" name="price_array[]" value="{{ $price }}">
  </td>
  <td>
    <input style="width: 70px" type="number" name="qty_array[]" min="1" value="{{ $qty }}" class="qty" readonly>
  </td>
  <td>
    {{ $tax }}
    <input style="width: 70px" type="hidden" name="tax_array[]" value="{{ $tax }}">
  </td>                  
  <td>
    {{ $currency->symbol }} {{ number_format($subtotal, $currency->digit) }}
    <input type="hidden" name="subtotal_array[]" value="{{ $subtotal }}">
  </td>
  <td>
    <a href="#" title="Remove" class="remove"><i class="fa fa-trash text-danger"></i></a>
  </td>
</tr>