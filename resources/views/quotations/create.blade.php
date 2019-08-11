@extends('layouts.master')
@section('css')
<style type="text/css">
  
</style>
@endsection
@section('content')
<section class="content-header">
  <h1>
    {{ __('title.new_quotation') }}
  </h1>
  <ol class="breadcrumb">
    <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i>{{ __('title.dashboard') }}</a></li>
    <li><a href="{{ route('quotation.index') }}">{{ __('title.quotations') }}</a></li>
    <li class="active">{{ __('title.create') }}</li>
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
  <div class="row">
    <div class="col-md-12">
      <div style="padding-bottom: 10px">
        @if(Auth::user()->allowCreate(config('global.modules.quotation')))
          <button id="submit-button" type="button" class="btn btn-primary"><i class="fa fa-save"></i>&nbsp; {{ __('title.save') }}</button>
        @endif         
        &nbsp;           
        <a href="{{ route('quotation.index') }}" class="btn btn-default"><i class="fa fa-close"></i>&nbsp; {{ __('title.cancel') }}</a>
      </div>
      <div class="box">
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
                      <select class="form-control select2" id="customer" name="customer_id" style="width: 100%">
                        <option data-telephone="0" data-customer-group-id="0" value="0"></option> 
                        @foreach($customers as $item)                      
                        <option data-telephone="{{ $item->contact->primary_telephone }}" data-customer-group-id="{{ $item->group_id }}" value="{{ $item->id }}">{{ $item->contact->contact_name }} ({{ $item->code }})</option>
                        @endforeach
                      </select>  
                      <p id="customer-require-message" class="required" style="display: none;" >{{ __('message.field_required') }}</p> 
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="telephone" class="col-sm-3 control-label">{{ __('app.telephone') }}</label>
                    <div class="col-sm-9">
                      <input type="text" class="form-control" id="telephone" readonly>
                    </div>
                  </div> 
                  <div class="form-group">
                    <label for="telephone" class="col-sm-3 control-label">{{ __('app.discount') }}</label>
                    <div class="col-sm-9">
                    <div class="input-group" style="width: 130px">
                      <input id="discount" type="number" name="discount" value="0" class="form-control" min="0" max="100">
                      <span class="input-group-btn">
                        <button type="button" class="btn btn-info btn-flat"><i class="fa fa-percent"></i></button>
                      </span>
                    </div>
                    </div>
                  </div>                   
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="validity" class="col-sm-3 control-label">{{ __('app.validity') }} <span class="required">*</span></label>
                    <div class="col-sm-9">
                      <div class="input-group date">
                        <div class="input-group-addon">
                          <i class="fa fa-calendar"></i>
                        </div>
                        <input id="validity" type="date" class="form-control datepicker" name="validity">
                      </div>                        
                      <p id="validity-require-message" class="required" style="display: none" >{{ __('message.field_required') }}</p>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="term" class="col-sm-3 control-label">{{ __('app.payment_terms') }}</label>
                    <div class="col-sm-9">
                      <select class="form-control" id="term" name="payment_term_id">
                        @foreach($terms as $item)                      
                        <option value="{{ $item->id }}"> {{ $item->payment_term }}</option>
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
                        <option value="{{ $item->id }}"> {{ $item->delivery_method }} </option>
                        @endforeach
                      </select>  
                    </div>
                  </div>
                  @endif  
                  <div class="form-group">
                    <label for="currency" class="col-sm-3 control-label">{{ __('app.currency') }}</label>
                    <div class="col-sm-9">
                      <select class="form-control" id="currency" name="currency_id" style="width: 130px">
                        @foreach($currencies as $item)                      
                        <option value="{{ $item->id }}"> {{ $item->currency }}</option>
                        @endforeach
                      </select>  
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
                            <input id="amount-hidden" type="hidden" name="amount">
                            <th style="width:150px">{{ __('app.untax_amount') }}:</th>
                            <td id="untax-amount">0.00</td>
                          </tr>
                          <tr>
                            <input id="pay-tax-hidden" type="hidden" name="pay_tax">
                            <th>{{ __('app.tax') }}:</th>
                            <td id="pay-tax">0.00</td>
                          </tr>
                          <tr>
                            <input id="discount-amount-hidden" type="hidden" name="discount_amount">
                            <th>{{ __('app.discount') }}:</th>
                            <td id="discount-amount">0.00</td>
                          </tr>                          
                          <tr>
                            <input id="grand-total-hidden" type="hidden" name="grand_total">
                            <th style="font-size: 18px">{{ __('app.grand_total') }}:</th>
                            <td id="grand-total" style="font-size: 20px; font-weight: bold">0.00</td>
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

  var currencyId = "{{ Auth::user()->defaultCurrency()->id }}";
  var customerGroupId = 0;
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

  $('body').on('click', '.add-description', function(){
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
          // console.log(data);
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
          // console.log(data);
          if(data.status == 'success') {
            $('#price').html(data.price);
            loading(false);
          }
        },
    });    
  }


  function submitForm() {
    hideErrorMessage();    
    var isCompleted = true;
    var customerId = $('#customer').val();
    var validity = $('#validity').val();
    var count = $('#quotation-table tbody tr').length;

    // ...
    if(customerId <= 0) {
      $('#customer-require-message').show();
      isCompleted = false;
    }

    if(validity == "") {
      $('#validity-require-message').show();
      isCompleted = false;
    }

    if(count == 0) {
      $('#product-require-message').show();
      isCompleted = false;
    }

    // ...
    if(isCompleted == true) {
      $('#loading-table').show();
      $.ajax({
        type: "POST",
        url: "{{ route('quotation.store') }}",
        data: $('form').serialize(),
        success: function(data){
          // ...
          var url = "{{ route('quotation.edit', [':id']) }}";
          url = url.replace(':id', data.quotation.id);
          window.location.replace(url);
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