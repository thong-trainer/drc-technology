@extends('layouts.master')
@section('css')
<style type="text/css">
  
</style>
@endsection
@section('content')
<section class="content-header">
  <h1>
    {{ $quotation->status }} <small>#{{ $quotation->id }}</small>
  </h1>
  <ol class="breadcrumb">
    <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i>{{ __('title.dashboard') }}</a></li>
    <li><a href="{{ route('quotation.index') }}">{{ __('title.quotations') }}</a></li>
    <li class="active">{{ __('title.edit') }}</li>
  </ol>
</section>
  <div class="modal fade" id="modal-product" data-keyboard="false" data-backdrop="static" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
      <div id="product-form">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
            <h4 class="modal-title">{{ __('title.select_option') }} </h4>
          </div>
          <div class="modal-body">
            <div class="form-group">
              <label for="name">{{ __('app.product_name') }} <span class="required">*</span></label>
              <select class="form-control select2" id="product" name="product_id" style="width: 100%">
                @foreach($products as $item)                      
                <option data-image="{{ $item->image_url }}" value="{{ $item->id }}" >{{ $item->product_name }} ({{ $item->barcode }})</option>
                @endforeach
              </select>  
            </div>
            <div class="row">
              <div class="col-md-4">
                <div class="form-group">
                  <img class="img-upload" id="product-image" src="{{ url(config('global.paths.product')) }}/no-image-placeholder.jpg" alt="your image" />
                </div>                  
              </div>
              <div class="col-md-8" id="product-variants">
              </div>
            </div>
          </div>                   
          <div class="modal-footer">
            <div id="loading" class="pull-left"> 
              <i class="fa fa-refresh fa-spin"></i> Loading... 
            </div>             
            <button id="add-to-list" type="button" class="btn btn-primary">{{ __('title.add_to_list') }}</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('title.cancel') }}</button>
          </div>
        </div>
      </div>
    </div>
  </div>


<section class="content">
  @if(session()->has('message'))      
    <div class="alert alert-success alert-dismissible">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
      <h4><i class="icon fa fa-check"></i> {{ __('message.success') }}</h4>
      {{ session()->get('message') }}
    </div>      
  @endif  

  @php
  $currency = $quotation->currency;
  $is_confirmed = ($quotation->status == config('global.quotation_status.confirmed') ? true : false);
  $is_invoiced = ($quotation->status == config('global.quotation_status.invoiced') ? true : false);
  @endphp
  
  <div class="row">
    <div class="col-md-12">
      @if($quotation->is_delete == 0)
      <div style="padding-bottom: 10px">
        @if($is_invoiced == 0)
          @if(Auth::user()->allowEdit(config('global.modules.quotation')))
            <button id="submit-button" type="button" class="btn btn-primary" title="Save"><i class="fa fa-save"></i>&nbsp; {{ __('title.save') }}</button>                 
          &nbsp;

            @if($is_confirmed)        
            <button id="create-invoice-button" class="btn btn-warning" title="Generate Sale Order"><i class="fa fa-file-text"></i>&nbsp; {{ __('title.create_invoice') }}</button>
            <div class="modal fade" id="modal-create-invoice" tabindex="-1" data-keyboard="false" data-backdrop="static" role="dialog" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">{{ __('title.create_invoice') }} - {{ $quotation->quotation_number }}</h4>
                  </div>
                  <div class="modal-body">
                  @lang('message.alert_create_invoice')
                  </div>
                  <div class="modal-footer">
                    <form action="{{ route('invoice.store') }}" method="POST">
                      @csrf
                      <input type="hidden" name="quotation_id" value="{{ $quotation->id }}">
                      <button type="submit" class="btn btn-primary">{{ __('title.create_invoice') }}</button>
                      <button id="dismiss-modal-create-invoice" type="button" class="btn btn-default" >{{ __('title.cancel') }}</button>
                    </form>
                  </div>
                </div>
              </div>
            </div>            
            @else
            <button id="confirm-button" class="btn btn-success" title="Generate Sale Order"><i class="fa fa-check"></i>&nbsp; {{ __('title.confirm') }}</button>
            <div class="modal fade" id="modal-confirm" tabindex="-1" data-keyboard="false" data-backdrop="static" role="dialog" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">{{ __('title.confirm_quotation') }} - {{ $quotation->quotation_number }}</h4>
                  </div>
                  <div class="modal-body">
                  @lang('message.alert_confirm_quotation')
                  </div>
                  <div class="modal-footer">
                    <form action="{{ route('quotation.confirm', $quotation->id) }}" method="POST">
                      @csrf
                      <input type="hidden" name="_method" value="put">
                      <button type="submit" class="btn btn-primary">{{ __('title.confirm') }}</button>
                      <button id="dismiss-modal-confirm" type="button" class="btn btn-default" >{{ __('title.cancel') }}</button>
                    </form>
                  </div>
                </div>
              </div>
            </div>    
            @endif    
          @endif              
        @endif       
        &nbsp;
        @if(Auth::user()->allowExport(config('global.modules.quotation')))
          <a href="{{ route('quotation.print', $quotation->id) }}" class="btn btn-default print" title="Print / Save PDF"><i class="fa fa-print"></i>&nbsp; {{ __('title.print') }}</a>
        @endif         
        &nbsp;
        @if($is_invoiced == 0)
          @if(Auth::user()->allowDelete(config('global.modules.quotation')))
            <a href="#model-delete-{{$quotation->id}}" class="btn btn-danger" data-toggle="modal" title="Delete"><i class="fa fa-trash"></i>&nbsp; {{ __('title.delete') }}</a>
            <div class="modal fade" id="model-delete-{{$quotation->id}}" tabindex="-1" data-keyboard="false" data-backdrop="static" role="dialog" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">{{ __('title.delete') }} - {{ $quotation->quotation_number }}</h4>
                  </div>
                  <div class="modal-body">
                  {{ __('message.delete_confirmation') }}
                  <p class="margin"><i class="fa fa-warning"></i> @lang('message.delete_warning') </p>
                  </div>
                  <div class="modal-footer">
                    <form action="{{ route('quotation.delete', $quotation->id) }}" method="POST">
                      @csrf
                      <input type="hidden" name="_method" value="delete">
                      <button type="submit" class="btn btn-danger save-cancel">{{ __('title.yes_delete') }}</button>
                      <button type="button" class="btn btn-default save-cancel" data-dismiss="modal">{{ __('title.cancel') }}</button>
                    </form>
                  </div>
                </div>
              </div>
            </div>           
          @endif
        @else
          <a href="{{ route('invoice.show', $quotation->invoice->id) }}/show" class="btn btn-default" title="View"><i class="fa fa-eye"></i>&nbsp; {{ __('title.view_invoice') }}</a>           
        @endif
      </div>  
      @endif
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">&nbsp; {{ $quotation->quotation_number }}</h3>                   
        </div>
        <!-- /.box-header -->
        <div class="box-body">    
          <!-- form start -->
          <form role="form" class="form-horizontal">
            @csrf
            <div class="box-body">
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="customer" class="col-sm-3 control-label">{{ __('app.customer') }} <span class="required">*</span></label>
                    <div class="col-sm-9">
                      @if($is_confirmed)
                      <input class="form-control" type="text" value="{{ $quotation->customer->contact->contact_name }}" readonly>
                      <input type="hidden" name="customer_id" value="{{ $quotation->customer_id }}">
                      @else
                        <select class="form-control select2" id="customer" name="customer_id" style="width: 100%">
                          @foreach($customers as $item)                      
                          <option data-telephone="{{ $item->contact->primary_telephone }}" data-customer-group-id="{{ $item->group_id }}" value="{{ $item->id }}" @if($item->id == $quotation->customer_id) selected @endif>{{ $item->contact->contact_name }} ({{ $item->code }})</option>
                          @endforeach
                        </select>  
                        <p id="customer-require-message" class="required" style="display: none;" >{{ __('message.field_required') }}</p> 
                      @endif
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="telephone" class="col-sm-3 control-label">{{ __('app.telephone') }}</label>
                    <div class="col-sm-9">
                      <input type="text" class="form-control" id="telephone" value="{{ $quotation->customer->contact->primary_telephone}}" readonly>
                    </div>
                  </div> 
                  <div class="form-group">
                    <label for="telephone" class="col-sm-3 control-label">{{ __('app.discount') }}</label>
                    <div class="col-sm-9">
                    <div class="input-group" style="width: 130px">
                      <input id="discount" type="number" name="discount" class="form-control" min="0" max="100" value="{{ $quotation->discount }}" @if($is_confirmed) readonly @endif>
                      <span class="input-group-btn">
                        <button type="button" class="btn btn-info btn-flat"><i class="fa fa-percent"></i></button>
                      </span>
                    </div>
                    </div>
                  </div>  
                  @if($is_confirmed)
                  <div class="form-group">
                    <label class="col-sm-3 control-label">{{ __('app.confirm_date') }} </label>                    
                    <div class="col-sm-9" style="margin-top: 7px">
                      {{ $quotation->confirm_date }}                       
                    </div>
                  </div>
                  @endif                                   
                </div>
                <div class="col-md-6">

                  <div class="form-group">
                    <label for="validity" class="col-sm-3 control-label">{{ __('app.validity') }} <span class="required">*</span></label>
                    <div class="col-sm-9">
                      <div class="input-group date">
                        <div class="input-group-addon">
                          <i class="fa fa-calendar"></i>
                        </div>
                        <input id="validity" type="date" class="form-control datepicker" name="validity" value="{{ $quotation->validity_date }}" @if($is_confirmed) readonly @endif>
                      </div>                        
                      <p id="validity-require-message" class="required" style="display: none" >{{ __('message.field_required') }}</p>  
                    </div>
                  </div>

                  <div class="form-group">
                    <label for="term" class="col-sm-3 control-label">{{ __('app.payment_terms') }}</label>
                    <div class="col-sm-9">
                      <select class="form-control" id="term" name="payment_term_id">
                        @foreach($terms as $item)                      
                        <option value="{{ $item->id }}" @if($item->id == $quotation->payment_term_id) selected @endif> {{ $item->payment_term }}</option>
                        @endforeach
                      </select>  
                    </div>
                  </div>   
                  @if(Auth::user()->allowDeliveryMethod())
                  <div class="form-group">
                    <label for="delivery-method" class="col-sm-3 control-label">{{ __('app.delivery_method') }}</label>
                    <div class="col-sm-9">
                      <select class="form-control" id="delivery-method" name="delivery_method_id">
                        @foreach($delivery_methods as $item)                      
                        <option value="{{ $item->id }}" @if($item->id == $quotation->delivery_method_id) selected @endif> {{ $item->delivery_method }} </option>
                        @endforeach
                      </select>  
                    </div>
                  </div>
                  @endif        
                  <div class="form-group">
                    <label for="currency" class="col-sm-3 control-label">{{ __('app.currency') }}</label>
                    <div class="col-sm-9">
                      @if($is_confirmed)
                      <input class="form-control" type="text" name="currency_id" value="{{ $quotation->currency->currency }}" readonly> 
                      @else                      
                      <select @if($is_confirmed) disabled @endif class="form-control" id="currency" name="currency_id" style="width: 130px">
                        @foreach($currencies as $item)                      
                        <option value="{{ $item->id }}" @if($item->id == $quotation->currency_id) selected @endif> {{ $item->currency }}</option>
                        @endforeach
                      </select>  
                      @endif  
                    </div>
                  </div>       
                </div>                  
              </div>
              <!-- /.box -->
              <div class="box box-solid">
                <div class="box-header with-border" style="padding-left: 0px">
                  <h3 class="box-title"><i class="fa fa-list"></i> {{ __('app.details') }} <span class="required">*</span></h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body table-responsive no-padding" style="padding: 0px" >          
                  <table id="quotation-table" class="table table-striped table-hover">
                    <thead class="thead-dark">
                      <tr>
                        <th>{{ __('app.product_name') }}</th>
                        <th>{{ __('app.price') }}</th>
                        <th style="width: 70px">{{ __('app.qty') }}</th>
                        <th style="width: 70px">{{ __('app.tax') }}</th>
                        <!-- <th style="width: 70px">{{ __('app.dis') }}</th> -->
                        <th>{{ __('app.subtotal') }}</th>
                        <th style="width: 40px"></th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($quotation->details as $key => $detail)
                      <td>                              
                        {{ $detail->product_name }} <a href="#modal-note-{{$key}}" title="View Detail" data-toggle="modal"><i class="fa fa-pencil-square-o"></i></a>
                        <div class="modal fade" id="modal-note-{{$key}}" tabindex="-1" data-keyboard="false" data-backdrop="static" role="dialog" aria-hidden="true">
                          <div class="modal-dialog">
                            <div class="modal-content">
                              <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                <h4 class="modal-title">{{ $detail->product_name }}</h4>
                              </div>
                              <div class="modal-body">
                                <textarea name="description_array[]" rows="6" class="form-control" >{{ $detail->notes }}</textarea>
                                <p>{{ __('message.write_description_here') }}...</p>
                              </div>
                              <div class="modal-footer">
                                <button type="button" data-dismiss="modal" class="btn btn-primary add-description">{{ __('title.save') }}</button>
                                <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('title.cancel') }}</button>
                              </div>
                            </div>
                          </div>
                        </div> 

                        <input type="hidden" name="product_id_array[]" value="{{ $detail->product_id }}">
                        <input type="hidden" name="product_name_array[]" value="{{ $detail->product_name }}">
                        <input type="hidden" name="variant_ids[]" value="{{ $detail->variant_ids }}">
                        <input type="hidden" name="current_currency_id" value="{{ $currency->id }}">
                        <input id="current-currency-digit" type="hidden" name="current_currency_digit" value="{{ $currency->digit }}">
                        <input id="current-currency-symbol" type="hidden" name="current_currency_symbol" value="{{ $currency->symbol }}">
                      </td>
                      <td>
                        {{ $currency->symbol }} {{ number_format($detail->unit_price * $quotation->rate, $currency->digit) }}
                        <input type="hidden" name="price_array[]" value="{{ $detail->unit_price }}">
                      </td>
                      <td>
                        <input style="width: 70px" type="number" name="qty_array[]" min="1" value="{{ $detail->qty }}" class="qty" >
                      </td>
                      <td>
                        <span class="badge bg-default">{{ $detail->tax }}%</span>
                        <input type="hidden" name="tax_array[]" value="{{ $detail->tax }}">
                        <input type="hidden" name="pay_tax_array[]" value="{{ $detail->pay_tax }}" class="tax">
                        <input type="hidden" name="discount_array[]" value="{{ $detail->discount }}">
                        <input type="hidden" name="discount_amount_array[]" value="{{ $detail->discount_amount }}" class="discount"> 
                      </td>                  
                      <td>
                        {{ $currency->symbol }} {{ number_format($detail->subtotal * $quotation->rate, $currency->digit) }}
                        <input type="hidden" name="subtotal_array[]" value="{{ $detail->subtotal * $quotation->rate }}" class="subtotal">
                      </td>
                      <td>
                        <a href="#" title="Remove" class="remove"><i class="fa fa-trash text-danger"></i></a>
                      </td>
                    </tr>                      
                      @endforeach
                    </tbody>         
                    <tfoot>
                      @if(Auth::user()->allowCreate(config('global.modules.quotation')))
                      <tr>
                        <td colspan="7">
                          <a id="add-new-product" href="#" title="Add New"><i class="fa fa-plus text-primary new"></i> {{ __('title.add_product') }}</a></td>                                
                      </tr>
                      @endif                  
                    </tfoot>                                     
                  </table>
                    <p id="product-require-message" class="required" style="display: none" >{{ __('message.field_required') }}</p>
                </div>
                <div class="box-footer">
                  <div class="row">
                    <div class="col-xs-12">
                      <div class="table-responsive pull-right">
                        <table class="table">
                          <tr>
                            <input id="amount-hidden" type="hidden" name="amount" value="{{ $quotation->amount * $quotation->rate }}">
                            <th style="width:150px">{{ __('app.untax_amount') }}:</th>
                            <td id="untax-amount">{{ $currency->symbol }} {{ number_format($quotation->amount * $quotation->rate, $currency->digit) }}</td>
                          </tr>
                          <tr>
                            <input id="pay-tax-hidden" type="hidden" name="pay_tax" value="{{ $quotation->tax * $quotation->rate }}">
                            <th>{{ __('app.tax') }}:</th>
                            <td id="pay-tax">{{ $currency->symbol }} {{ number_format($quotation->tax * $quotation->rate, $currency->digit) }}</td>
                          </tr>
                          <tr>
                            <input id="discount-amount-hidden" type="hidden" name="discount_amount" value="{{ $quotation->discount_amount * $quotation->rate }}">
                            <th>{{ __('app.discount') }}:</th>
                            <td id="discount-amount">{{ $currency->symbol }} {{ number_format($quotation->discount_amount * $quotation->rate, $currency->digit) }}</td>
                          </tr>                          
                          <tr>
                            <input id="grand-total-hidden" type="hidden" name="grand_total" value="{{ $quotation->grand_total * $quotation->rate }}">
                            <th style="font-size: 18px">{{ __('app.grand_total') }}:</th>
                            <td id="grand-total" style="font-size: 20px; font-weight: bold">{{ $currency->symbol }} {{ number_format($quotation->grand_total * $quotation->rate, $currency->digit) }}</td>
                          </tr>
                        </table>
                      </div>
                    </div>
                    <!-- /.col -->
                  </div>                  
                </div>
              </div>              
            </div>                   
          </form>

        </div>
        <div id="loading-table" class="overlay" style="display: none">
          <i class="fa fa-refresh fa-spin"></i>
        </div>  
      </div>      
    </div>
  </div>

</section>

@endsection
@section('js')

<script type="text/javascript">   
$(document).ready(function() {

  var currencyId = "{{ $quotation->currency_id }}";
  var customerGroupId = "{{ $quotation->customer->group_id }}";
  var productId = 0;

  $('select').on('select2:open', function(e){
      $('.custom-dropdown').parent().css('z-index', 99999);
  });

  // ..................
  // support functions
  // ..................
  function loading(isShow) {
    if(isShow == true) {
      $('#loading').show();
      $('#add-to-list').attr("disabled", true);
    } else {
      $('#loading').hide();
      $('#add-to-list').attr("disabled", false);      
    }
  }

  function clearPanel() {
    loading(false);
    hideErrorMessage();
    productId = 0;
    $('#add-to-list').attr("disabled", true);  
    $('#product').val(null).trigger('change');
    $("#product-variants").html(null);
    $('#product-image').attr('src', "{{ url(config('global.paths.product')) }}/no-image-placeholder.jpg");     
  }

  function getDimensionSelected() {
    // ...
    var ids = "";
    $("#product-form input[type=radio]:checked").each(function() {
      if(this.checked == true)
        ids += this.value + ",";
    });
    // remove last character from string if comma (1,2,)
    var lastChar = ids.slice(-1);
    if (lastChar == ',')
        ids = ids.slice(0, -1);

    return ids;
  }

  function calculation() {
    // ...
    var symbol = $('#current-currency-symbol').val();
    var digit = parseInt($('#current-currency-digit').val());

    var totalPrice = 0;
    $(".subtotal").each(function() {
        totalPrice += parseFloat($(this).val());
    });

    var totalTax = 0;
    $(".tax").each(function() {
        totalTax += parseFloat($(this).val());
    });

    var totalDiscount = 0;
    $(".discount").each(function() {
        totalDiscount += parseFloat($(this).val());
    });    
    //..
    var total = (totalPrice - totalDiscount - totalTax)
    // ...
    var amount = formatNumber(totalPrice.toFixed(digit));    
    var payTax = formatNumber(totalTax.toFixed(digit));
    var discountAmount = formatNumber(totalDiscount.toFixed(digit));
    var grandTotal = formatNumber(total.toFixed(digit));
    // ...
    $('#untax-amount').html(symbol +' '+ amount);    
    $('#pay-tax').html(symbol +' '+ payTax);
    $('#discount-amount').html(symbol +' '+ discountAmount);
    $('#grand-total').html(symbol +' '+ grandTotal);
    // ...
    $('#amount-hidden').val(totalPrice);
    $('#pay-tax-hidden').val(totalTax);
    $('#discount-amount-hidden').val(totalDiscount);
    $('#grand-total-hidden').val(total);
  }  

  function hideErrorMessage() {
    $('#customer-require-message').hide();
    $('#validity-require-message').hide();
    $('#product-require-message').hide();
  }

  // ...........................
  // action functions
  // ...........................
  $('#submit-button').on('click', function (e) {
    submitForm();    
  });

  $('#confirm-button').on('click', function (e) {
    submitForm('confirm');
  });

  $('#create-invoice-button').on('click', function (e) {
    console.log('creating invoice...');
    submitForm('invoice');
  });  

  // when you clicked on button (decrease quantity)
  $('body').on('click', '#qty-up', function(){     
    var qty = parseInt($('#qty').val());
    $('#qty').val(qty + 1);
    getPrice();
  });    

  // when you clicked on button (increase quantity)
  $('body').on('click', '#qty-down', function(){    
    // ...
    var qty = parseInt($('#qty').val());
    if(qty == 1)
      return
    // ...
    $('#qty').val(qty - 1);
    getPrice();
  });   

  // when the user select changed the dimension on the popup modal
  $('body').on('change', '.dimension-radio', function(){    
    getPrice();
  });   
  
  // when the user clicked on the add new product button below the table
  $('#add-new-product').on('click', function (e) {
    // ...
    if(customerGroupId == 0) {
      alert("{{ __('message.alert_field_require_message') }}");
      $('#customer-require-message').show();
      return;
    }
    // ...
    clearPanel();
    $("#modal-product").modal('show');   
  });

  // when the user select changed the currency
  $('#currency').on('change', function (e) {
    currencyId = $( "#currency option:selected" ).val();
    refreshData();
  });

  // when the user select changed the customer
  $('#customer').on('select2:select', function (e) {
    // ...
    var telephone = e.params.data.element.attributes['data-telephone'].nodeValue;
    customerGroupId = e.params.data.element.attributes['data-customer-group-id'].nodeValue;
    if(telephone == "0") 
      telephone = "";
    // ...
    $('#telephone').val(telephone);
    $('#customer-require-message').hide();
    refreshData();
  });  

  // when you clicked on button (decrease quantity)
  $('body').on('change', '.qty,#discount', function(){     
    refreshData();
  });    

  // assign value = 1 when the user remove data from qty and discount
  $('body').on('keyup', '.qty,#discount', function(){     
    if(this.value == "")
      this.value = 1;
  });      

  // remove row from the table
  $('body').on('click', '.remove', function(){
    $(this).parents("tr").remove();
    calculation();
  });   


  $('#dismiss-modal-confirm').on('click', function(e){
    $("#modal-confirm").modal('hide');
    $('#loading-table').hide();
  });

  $('#dismiss-modal-create-invoice').on('click', function(e){
    $("#modal-create-invoice").modal('hide');
    $('#loading-table').hide();
  });


  // ..............................
  // server block - AJAX - 
  // ..............................
  $('#add-to-list').on('click', function (e) {
    // ...
    loading(true);
    var variantIds = getDimensionSelected();
    if(variantIds == "") {
      variantIds = "0,";
    }    
    var qty = $('#qty').val();
    var discount = $('#discount').val();
    // ...
    var url = "{{ route('ajax.product.generate.record', [':customer_group_id', ':product_id', ':qty', ':variant_ids', ':currency_id', ':discount']) }}";    
    url = url.replace(':customer_group_id', customerGroupId);
    url = url.replace(':product_id', productId);
    url = url.replace(':qty', qty);
    url = url.replace(':variant_ids', variantIds);
    url = url.replace(':currency_id', currencyId);
    url = url.replace(':discount', discount);
    console.log("URL:", url);
    // ...
    $.ajax({
        url: url,
        success: function(data){
          $('#quotation-table tbody').append(data);
          $('#modal-product').modal('hide');  
          calculation();
        },
        error: function(data) {
          alert("{{ __('message.server_error') }}");
          loading(false);
        }           
    });  
  });

  // generate variants based on each product  
  $('#product').on('select2:select', function (e) {
    //..
    loading(true);    
    productId = e.params.data.id;
    var imageUrl = e.params.data.element.attributes['data-image'].nodeValue;
    $('#product-image').attr('src', "{{ url('')}}" + imageUrl);
    $("#product-variants").html('');
    // ...
    var url = "{{ route('ajax.variant.prices', [':product_id', ':currency_id', ':customer_group_id']) }}";
    url = url.replace(':product_id', productId);
    url = url.replace(':currency_id', currencyId);
    url = url.replace(':customer_group_id', customerGroupId);
    console.log("URL:", url);
    //..
    $.ajax({
        url: url,
        success: function(data){
          $("#product-variants").html(data);    
          loading(false);
        }   
    });
  });

  // re-checking data when we have any updates  
  function refreshData() {    
    // ...
    var count = $('#quotation-table tbody tr').length;
    if(customerGroupId <= 0 || count <= 0)
      return;
    // ...
    $('#loading-table').show();
    var url = "{{ route('ajax.change.currency', [':currency_id']) }}";
    url = url.replace(':currency_id', currencyId);
    console.log(url);
    // ...
    $.ajax({
      type: "PUT",
      url: url,
      data: $('form').serialize(),
      success: function(data){
        $('#quotation-table tbody').html(data);        
        setTimeout( function() {
          $('#loading-table').hide();
        }, 300);      
        calculation();
      },
      error: function(errors){
        alert("{{ __('message.server_error') }}");
      }
    });     
  }

  function getPrice() {
    // ...
    loading(true);
    var variantIds = getDimensionSelected();
    if(variantIds == "") {
      variantIds = "0,";
    }    
    var qty = $('#qty').val();
    // ...
    var url = "{{ route('ajax.product.price.get', [':customer_group_id', ':product_id', ':qty', ':variant_ids', ':currency_id']) }}";
    url = url.replace(':customer_group_id', customerGroupId);
    url = url.replace(':product_id', productId);
    url = url.replace(':qty', qty);
    url = url.replace(':variant_ids', variantIds);
    url = url.replace(':currency_id', currencyId);
    console.log("URL:", url);
    // ...
    $.ajax({
        url: url,
        success: function(data){
          if(data.status == 'success') {
            $('#price').html(data.price);
            loading(false);
          }
        },
    });    
  }


  function submitForm(status) {
    hideErrorMessage();
    var isCompleted = true;    

    var customerId = $('#customer').val();
    if(customerId <= 0) {
      $('#customer-require-message').show();
      isCompleted = false;
    }
    // ...
    var validity = $('#validity').val();
    if(validity == "") {
      $('#validity-require-message').show();
      isCompleted = false;
    }
    // ...
    var count = $('#quotation-table tbody tr').length;
    if(count == 0) {
      $('#product-require-message').show();
      isCompleted = false;
    }
    // ...
    if(isCompleted == true) {
      $('#loading-table').show();
      $.ajax({
        type: "PUT",
        url: "{{ route('quotation.update', $quotation->id) }}",
        data: $('form').serialize(),
        success: function(data){
          console.log(data);
          console.log(status);
          // return;
          if(status == 'confirm') {
            $("#modal-confirm").modal('show');
          } else if (status == 'invoice') {
            $("#modal-create-invoice").modal('show');            
          } else {
            location.reload();
          }          
        },
        error: function(errors){
          alert("{{ __('message.server_error') }}");
          $('#loading-table').hide();
        }
      });     
    }      
  }

  // ..............................
  // end server block - AJAX - 
  // ..............................

});  
</script>
</script>
@endsection