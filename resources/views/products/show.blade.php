@extends('layouts.master')
@section('css')
<style type="text/css">
  
</style>
@endsection
@section('content')
@php 
$currency = Auth::user()->defaultCurrency()
@endphp

@if(Auth::user()->allowCreate(config('global.modules.stock')))
  <div class="modal fade" id="modelUpdateQty" tabindex="-1" data-keyboard="false" data-backdrop="static" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
      <form action="{{ route('stock.store') }}" method="POST">
        @csrf                       
        <input type="hidden" name="product_id" value="{{ $product->id }}">  
        <input type="hidden" name="status" value="{{ config('global.stock_status.stock_in')}}">  
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
            <h4 class="modal-title">{{ __('title.update_qty_on_hand') }} </h4>
          </div>
          <div class="modal-body">
            <div class="form-group">
              <label for="name">{{ __('app.new_qty') }} <span class="required">*</span></label>
              <input type="number" id="qty" name="qty" class="form-control" min="1" value="" required>
            </div> 
            @if(Auth::user()->allowMultiStorageLocations())
            <div class="form-group">
              <label>{{ __('app.location') }} </label>
              <select class="form-control" name="location_id">
                @foreach($locations as $item)                      
                <option value="{{ $item->id }}">{{ $item->location_name }}</option>
                @endforeach
              </select>              
            </div>
            @else
            <input type="hidden" name="location_id" value="{{ $locations[0]->id }}">
            @endif            
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-primary">{{ __('title.save_changes') }}</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('title.cancel') }}</button>
          </div>
        </div>
      </form>
    </div>
  </div>
@endif 
<section class="content-header">
  <h1>
    {{ __('title.view_product') }} <small>#{{ $product->id }}</small>
  </h1>
  <ol class="breadcrumb">
    <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i>{{ __('title.dashboard') }}</a></li>
    <li><a href="{{ route('product.index') }}">{{ __('title.products') }}</a></li>
    <li class="active">{{ __('title.view') }}</li>
  </ol>
</section>
  <!-- href="#modelPopup" data-toggle="modal" -->
<section class="content">
  @if(session()->has('message'))      
    <div class="alert alert-success alert-dismissible">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
      <h4><i class="icon fa fa-check"></i> {{ __('message.success') }}</h4>
      {{ session()->get('message') }}
    </div>      
  @endif   
  <!-- <div class="row pull-right"> -->
    <!-- <div class="col-sm-12"> -->
      
    <!-- </div> -->
  <!-- </div>   -->
  <div class="row">
    <div class="col-md-12">
      @if(Auth::user()->allowVariant())
      <a id="variant" class="btn btn-app btn-app-white"> 
        <span class="badge bg-red">{{ $product->hasVariant() ? '' : 'setup' }}</span>
        <i class="fa fa-sitemap"></i> {{ __('title.variants') }}
      </a>       
      @endif

      @if(Auth::user()->allowVariant() && Auth::user()->allowEdit(config('global.modules.product_price')))
      <a id="pricing" href="{{ route('variant.extra-price.list', $product->id) }}" class="btn btn-app btn-app-white" >
        <span class="badge bg-red"></span>
        <i class="fa fa-dollar"></i> {{ __('title.extra_prices') }}
      </a>
      @endif
      
      @if(Auth::user()->allowStock() && Auth::user()->allowCreate(config('global.modules.stock')) && $product->product_type == 'storable')
<!--       <a href="#modelUpdateQty" data-toggle="modal" class="btn btn-app btn-app-white" >
        <span class="badge bg-yellow">stock in</span>
        <i class="fa fa-pencil-square-o"></i> {{ __('title.update_qty') }}
      </a> 

          -->   
      <a href="{{ route('stock.show', $product->id) }}/show" class="btn btn-app btn-app-white" >
        <span class="badge bg-aqua">{{ $product->onHand() }}</span>
        <i class="fa fa-cubes"></i> {{ __('title.on_hand') }}
      </a>   
      @endif
      <a class="btn btn-app btn-app-white" >
        <span class="badge bg-green">{{ $product->sold() }}</span>
        <i class="fa fa-signal"></i> {{ __('title.sold') }}
      </a>  
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-arrows"></i> {{ __('title.product_information') }}</h3>   
          @if(Auth::user()->allowEdit(config('global.modules.product')))          
            <div class="pull-right box-tools">

              @if($product->is_release == 0)
              <a href="#modelRelease" class="btn btn-success btn-sm" data-toggle="modal" data-toggle="tooltip" title="Release"><i class="fa fa-check"></i> {{ __('title.release') }}</a>&nbsp;&nbsp;   
              <div class="modal fade" id="modelRelease" tabindex="-1" data-keyboard="false" data-backdrop="static" role="dialog" aria-hidden="true">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                      <h4 class="modal-title">{{ __('title.release') }} ({{ $product->product_name }})</h4>
                    </div>
                    <div class="modal-body">
                    {{ __('message.release_confirmation') }}
                    <p class="margin"><i class="fa fa-info"></i> @lang('message.release_info') </p>
                    </div>
                    <div class="modal-footer">
                      <form action="{{ route('product.release', $product->id) }}" method="POST">
                        @csrf @method('PUT')                        
                        <button type="submit" class="btn btn-success">{{ __('title.yes_release') }}</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('title.cancel') }}</button>
                      </form>
                    </div>
                  </div>
                </div>
              </div>
              @else
              <a href="#modelDeactivate" class="btn btn-danger btn-sm" data-toggle="modal" data-toggle="tooltip" title="Deactivate"><i class="fa fa-close"></i> {{ __('title.deactivate') }}</a>&nbsp;&nbsp;   
              <div class="modal fade" id="modelDeactivate" tabindex="-1" data-keyboard="false" data-backdrop="static" role="dialog" aria-hidden="true">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                      <h4 class="modal-title">{{ __('title.deactivate') }} ({{ $product->product_name }})</h4>
                    </div>
                    <div class="modal-body">
                    {{ __('message.deactivate_confirmation') }}
                    <p class="margin"><i class="fa fa-info"></i> @lang('message.deactivate_warning') </p>
                    </div>
                    <div class="modal-footer">
                      <form action="{{ route('product.deactivate', $product->id) }}" method="POST">
                        @csrf @method('PUT')                        
                        <button type="submit" class="btn btn-danger">{{ __('title.deactivate') }}</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('title.cancel') }}</button>
                      </form>
                    </div>
                  </div>
                </div>
              </div>                            
              @endif                                 
              <a href="{{ route('product.edit', $product->id) }}" class="btn btn-default btn-sm" data-toggle="tooltip" title="Edit"><i class="fa fa-pencil"></i> {{ __('title.edit') }}</a>
            </div>
          @endif                        
        </div>
        <!-- /.box-header -->
        <div class="box-body">            
          <form role="form" action="#" method="POST">
            <div class="box-body">    
             <div class="row">
              <div class="col-lg-6 col-md-12 col-sm-12">                 
                <div class="row">
                  <div class="col-lg-4 col-md-4 col-sm-12">
                    <div class="form-group">
                      <img class="img-upload" id="blah" src="{{ url($product->image_url) }}" alt="your image" />
                    </div>                    
                  </div>
                  <div class="col-lg-8 col-md-8 col-sm-12">      
                    <div class="form-group ">
                      <label class="control-label"><i class="fa fa-check"></i> {{ $product->product_type }}</label>
                      <input type="text" class="form-control big-input" id="name" name="product_name" value="{{ $product->product_name }}" readonly>
                    </div>
                    <div class="row">
                      <div class="col-lg-6 col-md-6 col-sm-12">
                        <div class="form-group">
                          <label class="control-label">{{ __('app.category_name') }}</label>
                          <input type="text" class="form-control" value="{{ $product->category->category_name }}" readonly>
                        </div> 
                      </div>
                      <div class="col-lg-6 col-md-6 col-sm-12">
                       <div class="form-group">
                        <label class="control-label">{{ __('app.dimension_group') }}</label>
                        <input type="text" class="form-control" value="{{ $product->dimensionGroup->dimension_group }}" readonly>
                      </div> 
                    </div>                        
                  </div>
                  <div class="form-group" style="margin-bottom:0px">
                    <label for="cost">{{ __('app.cost') }}: {{ $currency->symbol }} {{ $product->cost ? number_format($product->cost, $currency->digit): 'N/A' }}</label>
                  </div>                                   
                  </div>                    
                </div>
                <div class="form-group">
                  <label for="note">{{ __('app.notes') }} </label>
                  <textarea readonly type="text" rows="5" class="form-control" id="notes" name="note">{{ old('notes') ?: $product->notes }}</textarea>
                </div>     
              </div>
              <div class="col-lg-6 col-md-12 col-sm-12">                 
                <div class="row">
                  <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="form-group">
                      <label for="barcode">{{ __('app.barcode') }} </label>
                      <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-barcode"></i></span>
                        <input type="text" id="barcode" name="barcode" class="form-control" value="{{ $product->barcode }}" readonly>
                      </div>                                               
                    </div> 
                    <div class="form-group">
                      <label for="ref_number">{{ __('app.ref_number') }} </label>
                      <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-link"></i></span>
                        <input type="text" class="form-control" value="{{ $product->ref_number }}" readonly>
                      </div>                            
                    </div>   
                    <div class="form-group">
                      <label for="customer_tax">{{ __('app.customer_tax') }} </label>
                      <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-percent"></i></span>
                        <input type="number" class="form-control" value="{{ $product->customer_tax }}" readonly>
                      </div>                                                 
                    </div>                                                           
                    <div class="form-group">
                      <label for="sale_unit_id">{{ __('app.sale_unit') }}</label>
                      <input type="text" class="form-control" value="{{ $product->saleUnit->unit_name }}" readonly>
                    </div> 
                    <div class="form-group">
                      <label>
                        <input disabled type="checkbox" class="flat-red" value="1" name="is_pos" @if($product->is_pos == 1) checked @endif>
                        {{ __('app.pos_available') }}
                      </label>
                    </div>
                  </div>                        
                </div>  
              </div>           
            </div>
            <div class="row">
              <div class="col-md-9">      
                <!-- /.box -->
                <div class="box box-solid">
                  <div class="box-header with-border" style="padding-left: 0px">
                    <h3 class="box-title"><i class="fa fa-list"></i> {{ __('app.product_price_list') }}</h3>
                  </div>
                  <!-- /.box-header -->
                  <div class="box-body" style="padding: 0px">          
                      <table id="pricing-table" class="table table-striped table-hover">
                        <thead class="thead-dark">
                          <tr>
                            <th>{{ __('app.customer_group') }}</th>
                            <th>{{ __('app.price') }}</th>
                            <th>{{ __('app.minimum_qty') }}</th>
                            <!-- <th>{{ __('app.start_date') }}</th> -->
                            <!-- <th>{{ __('app.end_date') }}</th> -->
                            <th style="width: 70px"></th>
                          </tr>
                        </thead>
                        <tbody>
                          @foreach($product->prices as $item)
                            <tr>
                              <td> {{ $item->customerGroup->group_name }} </td>
                              <td><span class="label label-success">{{ $currency->symbol }} {{ number_format($item->price, $currency->digit) }}</span>  </td>
                              <td> {{ $item->minimum_qty }} </td>
                              <!-- <td> {{ $item->start_date }} </td> -->
                              <!-- <td> {{ $item->end_date ?: 'N/A' }} </td> -->
                              <td>
                                @if(Auth::user()->allowEdit(config('global.modules.product_price')))
                                <a href="#model-popup" data-price-id="{{ $item->id }}" class="edit-price" title="Edit"><i class="fa fa-pencil text-primary"></i></a>
                                @endif                               
                                  @if(Auth::user()->allowDelete(config('global.modules.product_price')))
                                    @if($item->is_default == 0)
                                      <span style="padding: 5px">|</span><a href="#model-delete-{{$item->id}}" data-toggle="modal" title="Remove"><i class="fa fa-trash text-danger remove"></i></a>
                                    @endif                                  
                                    <div class="modal fade" id="model-delete-{{$item->id}}" tabindex="-1" data-keyboard="false" data-backdrop="static" role="dialog" aria-hidden="true">
                                      <div class="modal-dialog">
                                        <div class="modal-content">
                                          <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                            <h4 class="modal-title">{{ __('title.delete') }}</h4>
                                          </div>
                                          <div class="modal-body">
                                            {{ __('message.delete_confirmation') }}
                                            <p class="margin"><i class="fa fa-warning"></i> @lang('message.delete_warning') </p>
                                          </div>
                                          <div class="modal-footer">
                                            <form role="form" action="{{ route('product-price.delete', $item->id) }}" method="POST">
                                              @csrf @method('DELETE')
                                              <button type="submit" class="btn btn-danger save-cancel">{{ __('title.delete') }}</button>
                                              <button type="button" class="btn btn-default save-cancel" data-dismiss="modal">{{ __('title.cancel') }}</button>
                                            </form>
                                          </div>
                                        </div>
                                      </div>
                                    </div>                                                             
                                @endif
                              </td>
                            </tr>
                          @endforeach
                          @if(Auth::user()->allowPriceList())
                            @if(Auth::user()->allowCreate(config('global.modules.product_price')))
                              <tr>
                                <td colspan="6">
                                  <a href="#model-popup" id="new-price" href="" title="Add New"><i class="fa fa-plus text-primary new"></i> {{ __('title.add_price') }}</a></td>
                              </tr>
                            @endif
                          @endif
                        </tbody>
                      </table>
                  </div>
                  <!-- /.box-body -->
                </div>
                <!-- /.box -->                
              </div>
            </div>
          </div>
        </form>
        </div>
      </div>      
    </div>
  </div>
</section>

<div class="modal fade" id="model-popup" tabindex="-1" data-keyboard="false" data-backdrop="static" role="dialog" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content" id="modal-content">
    </div>
  </div>
</div> 
@endsection
@section('js')
<script type="text/javascript">   
$(document).ready(function() {


  $('#variant').click(function () {
      var url = "{{ route('ajax.variant.list', [':product_id', ':dimension_group_id']) }}";
      url = url.replace(':product_id', "{{ $product->id }}");
      url = url.replace(':dimension_group_id', "{{ $product->dimension_group_id }}");
      console.log(url);
      showModal(url);
  });

  $('#new-price').click(function () {
      var url = "{{ route('ajax.price.create', [':product_id']) }}";
      url = url.replace(':product_id', "{{ $product->id }}");
      console.log(url);
      showModal(url);
  });  
    
  $('body').on('click', '.edit-price', function(){
      var priceId = $(this).data('price-id');
      var url = "{{ route('ajax.price.edit', [':price_id']) }}";
      url = url.replace(':price_id', priceId);
      console.log(url);
      showModal(url);    

  });   

  function showModal(url) {
    $.ajax({
        url: url,
        success: function(data){
          $("#modal-content").html(data);
          $("#model-popup").modal('show');
        }   
    });
  }

});  
</script>
@endsection