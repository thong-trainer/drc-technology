@extends('layouts.master')
@section('css')
<style type="text/css">
  
</style>
@endsection
@section('content')
<section class="content-header">
  <h1>
    {{ Request::get('label') == 'stock_in' ? __('title.stock_in') : __('title.stock_out') }}
  </h1>
  <ol class="breadcrumb">
    <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i>{{ __('title.dashboard') }}</a></li>
    <li><a href="{{ route('role.index') }}">{{ __('title.stock_movements') }}</a></li>
    <li class="active">{{ __('title.create') }}</li>
  </ol>
</section>

<section class="content">
  @if(session()->has('message'))      
    <div class="alert alert-success alert-dismissible">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
      <h4><i class="icon fa fa-check"></i> {{ __('message.success') }}</h4>
      {{ session()->get('message') }}
    </div>      
  @endif  

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
              <div class="col-md-4 col-sm-6">
                <div class="form-group">
                  <img class="img-upload" id="product-image" src="{{ url(config('global.paths.product')) }}/no-image-placeholder.jpg" alt="your image" />
                </div>                  
              </div>
              <div class="col-md-7 col-sm-6 col-sm-6">
                <label id="price" style="font-size: 22px;">{{ __('app.initial_qty') }}</label>  
                <div class="input-group input-group" style="width: 150px">                  
                  <span class="input-group-btn">
                    <button id="qty-down" type="button" class="btn btn-info btn-flat"><i class="fa fa-minus"></i></button>
                  </span>                  
                  <input type="number" id="qty" name="qty" value="1" class="form-control" min="1" style="text-align: center; font-weight: bold;">
                  <span class="input-group-btn">
                    <button id="qty-up" type="button" class="btn btn-info btn-flat"><i class="fa fa-plus"></i></button>
                  </span>
                </div>                
              </div>                          
            </div>
          </div>                   
          <div class="modal-footer">
            <div id="loading" class="pull-left" style="display: none;"> 
              <i class="fa fa-refresh fa-spin"></i> Loading... 
            </div>             
            <button id="add-to-list" type="button" class="btn btn-primary">{{ __('title.add_to_list') }}</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('title.cancel') }}</button>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div style="padding-bottom: 10px">
        @if(Auth::user()->allowCreate(config('global.modules.stock')))
          <button id="submit-button" type="button" class="btn btn-primary"><i class="fa fa-save"></i>&nbsp; {{ __('title.save') }}</button>
        @endif         
        &nbsp;           
        <a href="{{ route('stock.index') }}" class="btn btn-default"><i class="fa fa-close"></i>&nbsp; {{ __('title.cancel') }}</a>
      </div>      
      <div class="box">
        <!-- /.box-header -->
        <div class="box-body">            
          <form role="form" class="form-horizontal">
            @csrf
            <input type="hidden" name="remark" value="{{ Request::get('label') }}">
            <div class="box-body">
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="contact" class="col-sm-3 control-label">{{ __('app.contact') }} <span class="required">*</span></label>
                    <div class="col-sm-9">
                      <select class="form-control select2" id="contact" name="contact_id" style="width: 100%">
                        <option value="0"></option> 
                        @foreach($contacts as $item)                      
                        <option value="{{ $item->id }}">{{ $item->contact_name }} - {{ $item->primary_telephone }}</option>
                        @endforeach
                      </select>  
                      <p id="contact-require-message" class="required" style="display: none" >{{ __('message.field_required') }}</p>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="location" class="col-sm-3 control-label">{{ __('app.location') }}</label>
                    <div class="col-sm-9">
                      <select class="form-control" id="location" name="location_id">                        
                        @foreach($locations as $item)                      
                        <option value="{{ $item->id }}">{{ $item->location_name }} </option>
                        @endforeach
                      </select>
                    </div>
                  </div> 
                  <div class="form-group">
                    <label for="type" class="col-sm-3 control-label">{{ __('app.movement_type') }} <span class="required">*</span></label>
                    <div class="col-sm-9">
                      <select class="form-control" id="type" name="movement_type_id">
                        <option value="0">{{ __('app.choose_option') }}</option>
                        @foreach($types as $item)                      
                        <option value="{{ $item->id }}">{{ $item->movement_type }} </option>
                        @endforeach
                      </select>
                      <p id="movement-type-require-message" class="required" style="display: none" >{{ __('message.field_required') }}</p>
                    </div>
                  </div>                        
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="source-document" class="col-sm-3 control-label">{{ __('app.source_document') }}</label>
                    <div class="col-sm-9">
                      <input type="text" class="form-control" id="source-document" name="source_document" placeholder="eg: {{ Request::get('label') == config('global.stock_status.stock_in') ? 'PO1003' : 'SO1002' }}" required>                      
                    </div>
                  </div>                                                                   
                </div> 
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="notes" class="col-sm-3 control-label">{{ __('app.notes') }}</label>
                    <div class="col-sm-9">
                      <textarea class="form-control" id="ntoes" name="notes" rows="4"></textarea>                      
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
                        <th style="width: 130px">{{ __('app.initial_qty') }}</th>
                        <th>{{ __('app.unit') }}</th>
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
  var productId;

  function hideErrorMessage() {
    $('#contact-require-message').hide();
    $('#movement-type-require-message').hide();
    $('#product-require-message').hide();
  }

  function loading(isShow) {
    if(isShow == true) {
      $('#loading').show();
      $('#add-to-list').attr("disabled", true);
    } else {
      $('#loading').hide();
      $('#add-to-list').attr("disabled", false);      
    }
  }

  // when the user clicked on the add new product button below the table
  $('#add-new-product').on('click', function (e) {
    $('#product').val(null).trigger('change');
    $('#qty').val(1);
    $("#modal-product").modal('show');   
    $('#add-to-list').attr("disabled", true); 
    $('#product-image').attr('src', "{{ url(config('global.paths.product')) }}/no-image-placeholder.jpg");     
  });

  // generate variants based on each product  
  $('#product').on('select2:select', function (e) {
    productId = e.params.data.id;
    var imageUrl = e.params.data.element.attributes['data-image'].nodeValue;
    $('#product-image').attr('src', "{{ url('')}}" + imageUrl);
    $('#add-to-list').attr("disabled", false); 
  });

  $('#add-to-list').on('click', function (e) {
    loading(true);
    var qty = $('#qty').val();
    var url = "{{ route('ajax.stock.row', [':product_id', ':qty']) }}";
    url = url.replace(':product_id', productId);    
    url = url.replace(':qty', qty);
    console.log(url);

    $.ajax({
        url: url,
        success: function(data){
          $('#quotation-table tbody').append(data);
          $('#modal-product').modal('hide');  
          loading(false);
        },
        error: function(data) {
          alert("{{ __('message.server_error') }}");
          loading(false);
        }           
    });   
  });


  // when you clicked on button (decrease quantity)
  $('body').on('click', '#qty-up', function(){     
    var qty = parseInt($('#qty').val());
    $('#qty').val(qty + 1);
  });    

  // when you clicked on button (increase quantity)
  $('body').on('click', '#qty-down', function(){    
    // ...
    var qty = parseInt($('#qty').val());
    if(qty == 1)
      return
    // ...
    $('#qty').val(qty - 1);
  });   

  // remove row from the table
  $('body').on('click', '.remove', function(){
    $(this).parents("tr").remove();
  });   


  // ...........................
  // action functions
  // ...........................
  $('#submit-button').on('click', function (e) {
    submitForm();    
  });


  function submitForm() {
    hideErrorMessage();    
    var isCompleted = true;
    var contactId = $('#contact').val();
    var typeId = $('#type').val();
    var count = $('#quotation-table tbody tr').length;

    // ...
    if(contactId <= 0) {
      $('#contact-require-message').show();
      isCompleted = false;
    }

    if(typeId <= 0) {
      $('#movement-type-require-message').show();
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
        url: "{{ route('stock.store') }}",
        data: $('form').serialize(),
        success: function(data){
          console.log(data);
          // console.log(data);
          // $('#loading-table').hide();

          var url = "{{ route('stock.edit', [':id']) }}";
          url = url.replace(':id', data.stock.id);
          window.location.replace(url);

        },
        error: function(errors){
          alert("{{ __('message.server_error') }}");
          $('#loading-table').hide();
        }
      });     
    }      
  }


});
</script>
@endsection