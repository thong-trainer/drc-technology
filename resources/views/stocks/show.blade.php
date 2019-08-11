@extends('layouts.master')
@section('css')
<style type="text/css">
  
</style>
@endsection
@section('content')
<section class="content-header">
  <h1>
    {{ __('title.stock_on_hand') }}
    <small>#{{ $product->barcode }}</small>
  </h1>
  <ol class="breadcrumb">
    <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i>{{ __('title.dashboard') }}</a></li>
    <li><a href="{{ route('product.show', $product->id) }}/show">{{ $product->barcode }}</a></li>
    <li class="active">{{ __('title.on_hand') }}</li>
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
    <div class="col-md-4 col-sm-12">
      <div class="small-box bg-aqua">
        <div class="inner">
          <h3>{{ $product->stockIn() }} <sup style="font-size: 20px">({{ $product->saleUnit->unit_name }})</sup></h3>
          <p>{{ __('title.total') }}</p>
        </div>
        <div class="icon">
          <i class="ion ion-pie-graph"></i>
        </div>
      </div>
    </div>
    <div class="col-md-4 col-sm-12">
      <div class="small-box bg-green">
        <div class="inner">
          <h3>{{ $product->stockOut() }} <sup style="font-size: 20px">({{ $product->saleUnit->unit_name }})</sup></h3>
          <p>{{ __('title.stock_out') }}</p>
        </div>
        <div class="icon">
          <i class="fa fa-shopping-cart"></i>
        </div>
      </div>
    </div>
    <div class="col-md-4 col-sm-12">
      <div class="small-box bg-red">
        <div class="inner">
          <h3>{{ $product->onHand() }} <sup style="font-size: 20px">({{ $product->saleUnit->unit_name }})</sup></h3>
          <p>{{ __('title.on_hand') }}</p>
        </div>
        <div class="icon">
          <i class="ion ion-stats-bars"></i>
        </div>
      </div>
    </div>        
  </div>
  <div class="row">
    <div class="col-lg-12 col-md-12">
          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">{{ __('title.stock_transaction') }}</h3>
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
                    <th>{{ __('app.product_name') }}</th>
                    <th>{{ __('app.location') }}</th>
                    <th>{{ __('app.quantity') }}</th>
                    <th>{{ __('app.movement_type') }}</th>
                    <th style="width: 140px">{{ __('app.date') }}</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($stocks as $key => $item)
                  @php
                    $is_stock_in = ($item->stockMovement->remark == config('global.stock_status.stock_in')) ? true : false
                  @endphp
                  <tr style="{{ $is_stock_in ? '' : 'color:red' }}">
                    <td>{{ $key+1 }} </td>
                    <td>{{ $item->product->product_name }}</td>
                    <td>{{ $item->stockMovement->location->location_name }}</td>
                    <td>{{ $item->initial_qty }}</td>
                    <td>{{ $item->stockMovement->movementType->movement_type }}</td>
                    <td>{{ $item->created_at }}</td>
                  </tr>                  
                  @endforeach
                </tbody>                
              </table>
            </div>
            <div class="box-footer clearfix">
              @include('layouts.pagination', ['data'=>$stocks])
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