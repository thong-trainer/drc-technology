@extends('layouts.master')
@section('css')
<style type="text/css">
  
</style>
@endsection
@section('content')

<section class="content-header">
  <h1>
    {{ __('title.dashboard') }}
    <small>{{ __('title.list_invoices') }}</small>
  </h1>
  <ol class="breadcrumb">
    <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i>{{ __('title.dashboard') }}</a></li>
    <li class="active">{{ __('title.invoices') }}</li>
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
    $default_currency = Auth::user()->defaultCurrency()
  @endphp

  <div class="row">
    <div class="col-lg-12 col-md-12">
          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">{{ __('title.list_invoices') }}</h3>
              <!-- tools box - add new record-->
              <!-- /. tools -->                            
            </div>
            <!-- /.box-header -->
            <div class="box-body table-responsive no-padding">
              <!-- form search -->
              <form action="{{ route('invoice.index') }}" method="GET">
                <div class="m-container">
                  <div class="m-left">           
                    <div class="input-group" style="float: left">                                            
                      <select class="form-control" name="status">
                        <option value="">{{ __('app.status') }}</option>
                        @foreach ( config('global.invoice_status') as $status )
                        <option value="{{ $status }}">{{ $status }}</option>
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
                        <a href="{{ route('invoice.index') }}" class="btn btn-danger btn-flat"><i class="fa fa-refresh"></i></a>
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
                    <th>{{ __('app.invoice_no') }}</th>
                    <th>{{ __('app.issue_date') }}</th>
                    <th>{{ __('app.due_date') }}</th>
                    <th>{{ __('app.customer') }}</th>
                    <th>{{ __('app.created_by') }}</th>
                    <th>{{ __('app.grand_total') }}</th>
                    <th style="width: 100px">{{ __('app.status') }}</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($invoices as $key => $item)
                  <tr style="{{ $item->is_delete == 0 ? '' : 'color:#A8A8A8' }}">
                    <!-- <td>{{ $key+1 }} </td>                     -->
                    <td>
                      <a href="{{ route('invoice.show', $item->id) }}/show" title="View Detail">{{ $item->invoice_number }} <i class="fa fa-external-link"></i></a>
                    </td>
                    <td>{{ $item->issue_date }}</td>
                    <td>{{ $item->due_date }}</td>
                    <td>{{ $item->customer->contact->contact_name }}</td>
                    <td>{{ $item->user->name }}</td>
                    <td>{{ $default_currency->symbol }} {{ number_format($item->grand_total, $default_currency->digit) }}</td>
                    <td> 
                      <span class="{{ $item->statusColor($item->status) }} label-md">{{ $item->status }}</span>
                    </td>
                  </tr>                  
                  @endforeach
                </tbody>                
              </table>
            </div>
            <div class="box-footer clearfix">
              @include('layouts.pagination', ['data'=>$invoices])
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