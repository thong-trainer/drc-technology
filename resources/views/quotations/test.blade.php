@extends('layouts.master')
@section('css')
<style type="text/css">
  
</style>
@endsection
@section('content')
@php
$company = Auth::user()->companyInfo();
$currency = $item->currency;
@endphp

<section class="content-header no-print">
  <h1>
    {{ __('app.quotation') }}
    <small>#{{ $item->quotation_number }}</small>
  </h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> {{ __('title.dashboard')}}</a></li>
    <li><a href="#">{{ __('title.quotations') }}</a></li>
    <li class="active">{{ __('title.show') }}</li>
  </ol>
</section>
<section class="content">
  <div class="row pull-left no-print" style="padding-bottom: 10px">
    <div class="col-sm-12">      
        @if(Auth::user()->allowCreate(config('global.modules.quotation')))
          <a onclick="window.print();" class="btn btn-success"><i class="fa fa-print"></i>&nbsp; {{ __('title.print') }}</a>
        @endif  
        &nbsp;           
        @if(Auth::user()->allowExport(config('global.modules.quotation')))
          <button id="download-button" type="button" class="btn btn-info"><i class="fa fa-file-pdf-o"></i>&nbsp; {{ __('title.download') }}</button>
        @endif                              
          <button id="cancel-button" type="button" class="btn btn-default"><i class="fa fa-pencil"></i>&nbsp; {{ __('title.edit') }}</button>        
    </div>
  </div>  
<section class="invoice" style="margin: 50px 0px;">
  <div class="row">
    <div class="col-xs-12">
      <h2 class="page-header">
        <img src="{{ url($company->image_url) }}" style="width: 50px; height: 50px"/>&nbsp;<b>{{ $company->company_name }}</b>
        <small class="pull-right">{{ __('app.date') }}: {{ $item->quotation_date }}</small>
      </h2>
    </div>
    <!-- /.col -->
  </div>
  <!-- info row -->
  <div class="row invoice-info">
    <div class="col-sm-4 invoice-col">
      {{ __('app.from') }}
      <address>
        <strong>{{ $company->company_name }}</strong><br>
        {{ $company->address }}<br>
        {{ __('app.phone') }}: {{ $company->telephone }}<br>
        {{ __('app.email') }}: {{ $company->email }}
      </address>
    </div>
    <!-- /.col -->
    <div class="col-sm-4 invoice-col">
      {{ __('app.to') }}
      <address>
        <strong>{{ $item->customer->contact->contact_name }}</strong><br>
        {{ $item->customer->contact->main_address ?: 'N/A' }}<br>
        Phone: {{ $item->customer->contact->primary_telephone }}<br>
        Email: {{ $item->customer->contact->email ?: 'N/A' }}
      </address>
    </div>
    <!-- /.col -->
    <div class="col-sm-4 invoice-col">
      <!-- <b>Invoice: #12022</b><br> -->
      <br>
      <b>{{ __('app.quotation_no') }}:</b> {{ $item->quotation_number }}<br>
      <b>{{ __('app.validity') }}:</b> {{ $item->validity_date }}<br>
      <b>{{ __('app.account_no') }}:</b> {{ $item->customer->code }}
    </div>
    <!-- /.col -->
  </div>
  <!-- /.row -->

  <!-- Table row -->
  <div class="row">
    <div class="col-xs-12 table-responsive">
      <table class="table table-striped">
        <thead>
        <tr>
          <th>#</th>
          <th>{{ __('app.description') }}</th>
          <th>{{ __('app.price') }}</th>
          <th>{{ __('app.qty') }}</th>
          <th>{{ __('app.tax') }}</th>
          <th>{{ __('app.subtotal') }}</th>
        </tr>
        </thead>
        <tbody>
          @foreach($item->details as $key => $detail)
          <tr>
            <td>{{ $key + 1 }}</td>
            <td>{{ $detail->product_name }}</td>
            <td>{{ $currency->symbol }} {{ number_format($detail->unit_price * $item->rate, $currency->digit) }}</td>
            <td>{{ $detail->qty }}</td>
            <td>{{ $detail->tax }}%</td>
            <td>{{ $currency->symbol }} {{ number_format($detail->subtotal * $item->rate, $currency->digit) }}</td>
          </tr>            
          @endforeach            
        </tbody>
      </table>
    </div>
    <!-- /.col -->
  </div>
  <!-- /.row -->

  <div class="row">
    <!-- accepted payments column -->
    <div class="col-xs-6">
      <p class="lead">Payment Terms:</p>

      <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">
        Etsy doostang zoodles disqus groupon greplin oooj voxy zoodles, weebly ning heekya handango imeem plugg dopplr
        jibjab, movity jajah plickers sifteo edmodo ifttt zimbra.
      </p>
    </div>
    <!-- /.col -->
    <div class="col-xs-6">
      <div class="table-responsive">
        <table class="table">
          <tr>
            <th style="width:50%">{{ __('app.subtotal') }}:</th>
            <td>{{ $currency->symbol }} {{ number_format($item->amount * $item->rate, $currency->digit) }}</td>
          </tr>
          <tr>
            <th>{{ __('app.tax') }}:</th>
            <td>{{ $currency->symbol }} {{ number_format($item->tax * $item->rate, $currency->digit) }}</td>
          </tr>
          <tr>
            <th>{{ __('app.discount') }}:</th>
            <td>{{ $currency->symbol }} {{ number_format($item->discount_amount * $item->rate, $currency->digit) }}</td>
          </tr>
          <tr>
            <th>{{ __('app.grand_total') }}:</th>
            <td>{{ $currency->symbol }} {{ number_format($item->grand_total * $item->rate, $currency->digit) }}</td>
          </tr>
        </table>
      </div>
    </div>
    <!-- /.col -->
  </div>

  <!-- this row will not appear when printing -->
</section>  
</section>


    <!-- /.content -->
<!-- <div class="clearfix"></div> -->

@endsection
@section('js')

<script type="text/javascript">   

</script>
@endsection