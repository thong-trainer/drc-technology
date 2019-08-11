<tr>
  <td>
    {{ $product->product_name }} <a href="#" title="View Detail"><i class="fa fa-external-link"></i></a>
    <input type="hidden" name="product_id_array[]" value="{{ $product->id }}">
  </td>
  <td>
    <input style="width: 70px" type="number" name="initial_qty_array[]" value="{{ $qty }}">
  </td>
  <td>{{ $product->saleUnit->unit_name }}</td>      
  <td>
    <a href="#" title="Remove" class="remove"><i class="fa fa-trash text-danger"></i></a>
  </td>    
</tr> 