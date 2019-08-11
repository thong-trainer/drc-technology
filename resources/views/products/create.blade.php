@extends('layouts.master')
@section('css')
<style type="text/css">
  
</style>
@endsection
@section('content')
<section class="content-header">
  <h1>
    {{ __('title.new_product') }}
  </h1>
  <ol class="breadcrumb">
    <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i>{{ __('title.dashboard') }}</a></li>
    <li><a href="{{ route('product.index') }}">{{ __('title.products') }}</a></li>
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
  <div class="row">
    <div class="col-md-12">
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-arrows"></i> {{ __('title.product_information') }}</h3>            
        </div>
        <!-- /.box-header -->
        <div class="box-body">            
          <form role="form" action="{{ route('product.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="box-body">    
             <div class="row">
              <div class="col-lg-6 col-md-12 col-sm-12">
                 
                <div class="row">
                  <div class="col-lg-4 col-md-4 col-sm-12">
                    <div class="form-group">
                      <img class="img-upload" id="blah" src="{{ url(config('global.paths.product')) }}/no-image-placeholder.jpg" alt="your image" />
                      <input type='file' id="imgInp" name="file_upload" class="hide-file-name" accept="image/png, image/jpeg"/>
                      <input class="btn-upload" type="button" value="Browse" onclick="document.getElementById('imgInp').click();" />
                    </div>                    
                  </div>
                  <div class="col-lg-8 col-md-8 col-sm-12">
                    <div class="form-group @error('product_type') has-error @enderror">
                      <label>
                        @if(!old('product_type') && old('product_type') == 'storable' || old('product_type') != 'service')
                        <input type="radio" value="storable" name="product_type" class="flat-red" checked>
                        @else
                        <input type="radio" value="storable" name="product_type" class="flat-red">
                        @endif
                        {{ __('app.storable') }}
                      </label>&nbsp;&nbsp;&nbsp;                                      
                      <label>
                        @if(!old('product_type') && old('product_type') == 'service')
                        <input type="radio" value="service" name="product_type" class="flat-red" checked>
                        @else
                        <input type="radio" value="service" name="product_type" class="flat-red">
                        @endif
                        {{ __('app.service') }}
                      </label>
                    </div>          
                    <div class="form-group @error('product_name') has-error @enderror">
                      <input type="text" class="form-control big-input" id="name" name="product_name" value="{{ old('product_name')}}" placeholder="{{ __('app.name') }}" required>
                      @error('product_name')
                      <span class="help-block">{{ $message }}</span>
                      @enderror                      
                    </div>
                    <div class="row">
                      <div class="col-lg-6 col-md-6 col-sm-12">
                        <div class="form-group @error('category_id') has-error @enderror">
                          <select class="form-control select2" name="category_id">
                            <option value="0">{{ __('app.category') }}</option>
                            @foreach($categories as $item)
                            <option value="{{ $item->id }}" @if($item->id == old('category_id')) selected @endif>{{ $item->category_name }}</option>
                            @endforeach
                          </select>   
                          @error('category_id')
                          <span class="help-block">{{ $message }}</span>
                          @enderror                                         
                        </div> 
                      </div>

                      <div class="col-lg-6 col-md-6 col-sm-12">
                        <div class="form-group @error('dimension_group_id') has-error @enderror">
                          <select class="form-control select2" name="dimension_group_id">
                            <option value="0">{{ __('app.dimension_group') }}</option>
                            @foreach($dimension_groups as $item)
                            <option value="{{ $item->id }}" @if($item->id == old('dimension_group_id')) selected @endif>{{ $item->dimension_group }}</option>
                            @endforeach
                          </select>   
                          @error('dimension_group_id')
                          <span class="help-block">{{ $message }}</span>
                          @enderror                                         
                        </div> 
                      </div>                        
                    </div>
                    <!-- assign variable -->
                    @php 
                      $currency_symbol = Auth::user()->defaultCurrency()->symbol
                    @endphp
                    <!-- end assign variable -->
                    <div class="row">
                      <div class="col-lg-6 col-md-6 col-sm-12">
                        <div class="form-group @error('sale_price') has-error @enderror">
                          <label for="sale_price">{{ __('app.sale_price') }} <span class="required">*</span></label>
                          <div class="input-group">
                            <span class="input-group-addon"><b>{{ $currency_symbol }}</b></span>
                            <input type="number" step="any" id="sale_price" name="sale_price" class="form-control" value="{{ old('sale_price') ?: 0 }}" min="0" required>
                          </div>                         
                          @error('sale_price')
                          <span class="help-block">{{ $message }}</span>
                          @enderror                          
                        </div>                                                
                      </div>
                      <div class="col-lg-6 col-md-6 col-sm-12">
                        <div class="form-group @error('cost') has-error @enderror">
                          <label for="cost">{{ __('app.cost') }} <span class="required">*</span></label>
                          <div class="input-group">
                            <span class="input-group-addon"><b>{{ $currency_symbol }}</b></span>
                            <input type="number" step="any" id="cost" name="cost" class="form-control" value="{{ old('cost') ?: 0 }}" min="0" required>
                          </div>                         
                          @error('cost')
                          <span class="help-block">{{ $message }}</span>
                          @enderror                          
                        </div>                                                
                      </div>                                    
                    </div>
                  </div>                    
                </div>
                <div class="form-group">
                  <label for="note">{{ __('app.notes') }} </label>
                  <textarea type="text" rows="6" class="form-control" id="notes" name="note">{{ old('notes') }}</textarea>
                </div>     
              </div>

              <div class="col-lg-6 col-md-12 col-sm-12">                 
                <div class="row">
                  <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="form-group">
                      <label for="barcode">{{ __('app.barcode') }} </label>
                      <div class="input-group @error('barcode') has-error @enderror">
                        <span class="input-group-addon"><i class="fa fa-barcode"></i></span>
                        <input type="text" id="barcode" name="barcode" class="form-control" value="{{ old('barcode') }}">
                      </div>
                      @error('barcode')
                      <span class="help-block">{{ $message }}</span>
                      @enderror                                                    
                    </div> 
                    <div class="form-group">
                      <label for="ref_number">{{ __('app.ref_number') }} </label>
                      <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-link"></i></span>
                        <input type="text" id="ref_number" name="ref_number" class="form-control" value="{{ old('ref_number') }}">
                      </div>                            
                    </div>     
                    <div class="form-group @error('customer_tax') has-error @enderror">
                      <label for="customer_tax">{{ __('app.customer_tax') }} </label>
                      <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-percent"></i></span>
                        <input type="number" id="customer_tax" name="customer_tax" class="form-control" value="{{ old('customer_tax') ?: 0 }}" min="0" max="100">
                      </div>                         
                      @error('customer_tax')
                      <span class="help-block">{{ $message }}</span>
                      @enderror                          
                    </div>                                               
                    <div class="form-group">
                      <label for="sale_unit_id">{{ __('app.sale_unit') }}</label>
                      <select class="form-control select2" name="sale_unit_id">
                        @foreach($units as $item)
                        <option value="{{ $item->id }}" @if($item->id == old('sale_unit_id')) selected @endif>{{ $item->unit_name }}</option>
                        @endforeach
                      </select>                                           
                    </div>    
                    <div class="form-group">
                      <label>
                        <input type="checkbox" class="flat-red" value="1" name="is_pos" checked>
                        {{ __('app.pos_available') }}
                      </label>
                    </div>
                  </div>                        
                </div>  
              </div>           
            </div>
          </div>
          <div class="box-footer">
            <a href="{{route('product.index')}}" class="btn btn-default">{{ __('title.cancel') }}</a>&nbsp;&nbsp;&nbsp;
            @if(Auth::user()->allowCreate(config('global.modules.product')))
            <button type="submit" class="btn btn-primary">{{ __('title.save') }}</button>
            @endif
          </div>
        </form>
      </div>
      </div>      
    </div>
  </div>
</section>

@endsection
@section('js')
<script type="text/javascript">   
$(document).ready(function() {

  function readURL(input) {
    if (input.files && input.files[0]) {
      var reader = new FileReader();

      reader.onload = function (e) {
          $('#blah').attr('src', e.target.result);
      }
      reader.readAsDataURL(input.files[0]);
    }
  }

  $("#imgInp").change(function(){
    readURL(this);
  });    
});


</script>
@endsection