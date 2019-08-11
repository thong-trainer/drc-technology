@extends('layouts.master')
@section('css')
<style type="text/css">
  
</style>
@endsection
@section('content')
<section class="content-header">
  <h1>
    {{ __('title.dashboard') }}
    <small>{{ __('title.list_stock_movements') }}</small>
  </h1>
  <ol class="breadcrumb">
    <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i>{{ __('title.dashboard') }}</a></li>
    <li class="active">{{ __('title.stock_movements') }}</li>
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
    <div class="col-lg-12 col-md-12">
          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">{{ __('title.list_stock_movements') }}</h3>
              <!-- tools box - add new record-->
              @if(Auth::user()->allowCreate(config('global.modules.stock')))
              <div class="pull-right box-tools">
                <a href="{{ route('stock.create', ['label' => config('global.stock_status.stock_in')]) }}" class="btn btn-primary btn-sm" data-toggle="tooltip"title="Purchase Order / Product Return"><i class="fa fa-plus"></i> {{ __('title.stock_in') }}</a>
                &nbsp;
                <a href="{{ route('stock.create', ['label' => config('global.stock_status.stock_out')]) }}" class="btn btn-warning btn-sm" data-toggle="tooltip"title="Return to Suppliers"><i class="fa fa-plus"></i> {{ __('title.stock_out') }}</a>
              </div>
              @endif
              <!-- /. tools -->                            
            </div>
            <!-- /.box-header -->
            <div class="box-body table-responsive no-padding">
            <div class="box-body table-responsive no-padding">
              <!-- form search -->
              <form action="{{ route('stock.index') }}" method="GET">
                <div class="m-container">
                  <div class="m-left">           
                    <div class="input-group" style="float: left">                                            
                      <select class="form-control" name="movement_type_id">
                        <option value="">{{ __('app.movement_type') }}</option>
                        @foreach ( $types as $item )
                        <option value="{{ $item->id }}" @if($item->id == Request::get('movement_type_id')) selected @endif>{{ $item->movement_type }}</option>
                        @endforeach
                      </select>
                    </div>                    
                    <div class="input-group" style="float: left;">
                      <button type="submit" class="btn btn-default"><i class="fa fa-filter"></i> {{ __('title.filter') }}</button>
                    </div>
                  </div>
                  <div class="m-right">
                    <div class="input-group input-group pull-right" style="width: 300px;">
                      <input type="text" name="search" value="{{ Request::get('search')?:'' }}" class="form-control" placeholder="{{ __('title.search') }}">
                      <span class="input-group-btn">
                        <button type="submit" class="btn btn-info btn-flat"><i class="fa fa-search"></i></button>
                        <a href="{{ route('stock.index') }}" class="btn btn-danger btn-flat"><i class="fa fa-refresh"></i></a>
                      </span>
                    </div>                    
                  </div>
                  <div id="center"></div>
                </div>
              </form>              
              <!-- end form search -->
              <table class="table table-striped table-hover">
                <thead>
                  <tr>
                    <!-- <th style="width: 10px">#</th> -->
                    <th>{{ __('app.reference_code') }}</th>
                    <th>{{ __('app.movement_date') }}</th>
                    <th>{{ __('app.movement_type') }}</th>
                    <th>{{ __('app.location') }}</th>
                    <th>{{ __('app.remark') }}</th>
                    <th style="width: 40px">{{ __('app.action') }}</th>
                  </tr>
                </thead>
                <tbody>
                @foreach($stock_movements as $key => $item)
                  @php
                    $is_stock_in = ($item->remark == config('global.stock_status.stock_in')) ? true : false
                  @endphp                
                  <tr style="{{ $is_stock_in ? '' : 'color:red' }}">
                    <!-- <td>{{ $key+1 }} </td> -->
                    <td>{{ $item->reference_code }}</td>
                    <td>{{ $item->movement_date }}</td>
                    <td>{{ $item->movementType->movement_type }}</td>
                    <td>{{ $item->location->location_name }}</td>
                    <td>
                      <span class="{{ $item->statusColor($item->remark) }} label-md">{{ $item->remark }}</span>
                    </td>
                    <td>
                      @if(Auth::user()->allowEdit(config('global.modules.stock')))
                      <a href="{{ route('stock.edit', $item->id) }}" title="Edit"><i class="fa fa-pencil"></i></a>
                      @endif                      
                      @if(Auth::user()->allowDelete(config('global.modules.stock')))                      
                      <span style="padding: 5px">|</span><a href="#modelDelete_{{$item->id}}" data-toggle="modal" title="Remove"><i class="fa fa-trash text-danger"></i></a>
                      <div style="color: #333333" class="modal fade" id="modelDelete_{{$item->id}}" tabindex="-1" data-keyboard="false" data-backdrop="static" role="dialog" aria-hidden="true">
                        <div class="modal-dialog">
                          <div class="modal-content">
                            <div class="modal-header">
                              <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                              <h4 class="modal-title">{{ __('title.delete') }} - {{$item->reference_code}}</h4>
                            </div>
                            <div class="modal-body">
                            {{ __('message.delete_confirmation') }}
                            <p class="margin"><i class="fa fa-warning"></i> @lang('message.delete_warning') </p>
                            </div>
                            <div class="modal-footer">
                              <form action="{{ route('stock.delete', $item->id) }}" method="POST">
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
                    </td>
                  </tr>                  
                  @endforeach
                </tbody>                
              </table>
            </div>
            <div class="box-footer clearfix">
              @include('layouts.pagination', ['data'=>$stock_movements])
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