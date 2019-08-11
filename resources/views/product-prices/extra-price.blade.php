@extends('layouts.master')
@section('css')
<style type="text/css">
  
</style>
@endsection
@section('content')
<section class="content-header">
  <h1>
    {{ __('title.extra_prices') }}
    <small>#{{ $product->barcode }}</small>
  </h1>
  <ol class="breadcrumb">
    <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i>{{ __('title.dashboard') }}</a></li>
    <li><a href="{{ route('product.show', $product->id) }}/show">{{ $product->barcode }}</a></li>
    <li class="active">{{ __('title.extra_prices') }}</li>
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

  @php 
  $currency = Auth::user()->defaultCurrency()
  @endphp 
  <div class="row">
    <div class="col-lg-12 col-md-12">
          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">{{ $product->product_name }}</h3>
              <!-- tools box - add new record-->
              <!-- /. tools -->                            
            </div>
            <!-- /.box-header -->
            <div class="box-body table-responsive no-padding">
              <!-- form search -->
              <!-- end form search -->
              <table class="table table-striped table-hover">
                <thead>
                  <tr>
                    <th style="width: 10px">#</th>
                    <th style="width: 250px">{{ __('app.dimension') }}</th>                    
                    <th>{{ __('app.value') }}</th>
                    <th>{{ __('app.extra_price') }}</th>
                    <th style="width: 40px">{{ __('app.action') }}</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($data as $key => $item)
                  <tr>
                    <td>{{ $key+1 }} </td>
                    <td>{{ $item->dimension->dimension_name }}</td>
                    <td>{{ $item->value }}</td>
                    <td><span class="label label-success">{{ $currency->symbol }} {{ number_format($item->extra_price, $currency->digit) }}</span>  </td>
                    <td>
                      @if(Auth::user()->allowEdit(config('global.modules.product_price')))
                      <a href="#modelUpdate_{{$item->id}}" data-toggle="modal" title="Edit"><i class="fa fa-pencil"></i></a>
                      <div class="modal fade" id="modelUpdate_{{$item->id}}" tabindex="-1" data-keyboard="false" data-backdrop="static" role="dialog" aria-hidden="true">
                        <div class="modal-dialog">
                          <form action="{{ route('variant.update', $item->id) }}" method="POST">
                            @csrf @method('PUT')                                
                            <div class="modal-content">
                              <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                <h4 class="modal-title">{{ __('title.extra_price') }} - {{ $item->dimension->dimension_name }} ({{ $item->value }})</h4>
                              </div>
                              <div class="modal-body">
                                <div class="input-group">
                                  <span class="input-group-addon"><b>{{ $currency->symbol }}</b></span>
                                  <input type="number" step="any" id="price" name="price" class="form-control" min="0" value="{{ $item->extra_price}}" required>
                                </div> 
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
                    </td>
                  </tr>                  
                  @endforeach
                </tbody>                
              </table>
            </div>
          </div>      
    </div>
  </div>
</section>

@endsection
@section('js')
<script type="text/javascript">
    
</script>
@endsection