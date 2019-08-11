@extends('layouts.master')
@section('css')
<style type="text/css">
  
</style>
@endsection
@section('content')
@php
$company = Auth::user()->companyInfo();
$currency = $item->currency;
$default_currency = Auth::user()->defaultCurrency();
@endphp

<section class="content-header no-print">
  <h1>
    {{ __('app.invoice') }}
    <small>#{{ $item->invoice_number }}</small>
  </h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> {{ __('title.dashboard')}}</a></li>
    <li><a href="#">{{ __('title.invoices') }}</a></li>
    <li class="active">{{ __('title.show') }}</li>
  </ol>
</section>
  <div class="modal fade" id="modal-payment" data-keyboard="false" data-backdrop="static" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
      <div>
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
            <h4 class="modal-title"><i class="fa fa-credit-card"></i> {{ __('title.submit_payment') }} </h4>
          </div>
          <form role="form" action="{{ route('invoice.update', $item->id) }}" method="POST">
            @csrf @method('PUT')            
            <div class="modal-body">
              <div class="form-group">
                <label for="amount">{{ __('app.payment_amount') }} <span class="required">*</span></label>               
                <div class="input-group">
                  <span class="input-group-addon"><b>{{ $default_currency->symbol }}</b></span>
                  <input type="number" step="any" id="amount" name="amount" class="form-control" min="0" value="{{ $item->grand_total - $item->payments->sum('amount') }}" max="{{ $item->grand_total - $item->payments->sum('amount') }}" required>
                </div>
                <p class="text-yellow "><i class="fa fa-info-circle"></i>{{ __('message.payment_note') }} ({{$default_currency->currency}}) </p>
              </div>
              <div class="row">
                <div class="col-md-6"> 
                  <div class="form-group">
                    <label for="payment-date">{{ __('app.payment_date') }}  <span class="required">*</span></label>
                    <div class="input-group date">
                      <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                      </div>
                      <input id="payment-date" type="date" class="form-control datepicker" name="payment_date" required>
                    </div> 
                  </div> 
                </div>
                <div class="col-md-6"> 
                  <div class="form-group">
                    <label for="payment-method">{{ __('app.payment_amount') }}</label>
                    <select class="form-control" id="payment-method" name="payment_method">              
                      <option value="Cash" >{{ __('app.cash') }}</option>
                      <option value="Bank" >{{ __('app.bank') }}</option>
                    </select>  
                  </div>                   
                </div>
              </div>  

              <div class="form-group">
                <label for="name">{{ __('app.notes') }} </label>
                <textarea class="form-control" name="notes"></textarea>
              </div>                      
            </div>                   
            <div class="modal-footer">         
              <button type="submit" class="btn btn-primary">{{ __('title.submit') }}</button>
              <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('title.cancel') }}</button>
            </div>
          </form>
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

  <div class="row">
    <div class="col-sm-12">      
        @if(Auth::user()->allowEdit(config('global.modules.invoice')))
          @if($item->status != config('global.invoice_status.paid'))
          <a href="#modal-payment"  data-toggle="modal" class="btn btn-success"><i class="fa fa-credit-card"></i>&nbsp; {{ __('title.submit_payment') }}</a>
          @endif
        @endif       
        &nbsp;
        @if(Auth::user()->allowExport(config('global.modules.invoice')))
          <a href="{{ route('invoice.print', $item->id) }}" class="btn btn-default"><i class="fa fa-print" title="Print/Saved PDF"></i>&nbsp; {{ __('title.print') }}</a>
        @endif 
    </div>
  </div>  

<section class="invoice" style="margin: 10px 0px;">  
  <div class="row">
    <div class="col-xs-12">
      <h2 class="page-header">
        <img src="{{ url($company->image_url) }}" style="width: 50px; height: 50px"/>&nbsp;<b>{{ $company->company_name }}</b>
        <small class="pull-right">{{ __('app.date') }}: {{ $item->issue_date }}</small>
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
      <b>{{ __('app.invoice_no') }}:</b> {{ $item->invoice_number }}<br>
      <b>{{ __('app.due_date') }}:</b> {{ $item->due_date }}<br>
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
            <td>{{ $detail->product_name }} <br> {{ $detail->notes }}</td>
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
    <div class="col-md-6 col-sm-6 col-xs-12">

    </div>
    <!-- /.col -->
    <div class="col-md-2 scol-sm-2 col-xs-12">
    </div>
    <div class="col-md-4 col-sm-4 col-xs-12">
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
            <th style="font-size: 18px">{{ __('app.grand_total') }}:</th>
            <td style="font-size: 20px; font-weight: bold">{{ $currency->symbol }} {{ number_format($item->grand_total * $item->rate, $currency->digit) }}</td>
          </tr>
          <tbody>
            @foreach($item->payments as $payment)
            <tr>
              <td> <em> {{ __('app.paid_on') }} {{ $payment->payment_date }} </em></td>
              <td>{{ $currency->symbol }} {{ number_format($payment->amount * $item->rate, $currency->digit) }} ({{ $payment->payment_method }})</td>
            </tr>            
            @endforeach
          </tbody>
          <tr>
            <th style="font-size: 18px">{{ __('app.amount_due') }}:</th>
            <td style="font-size: 20px; font-weight: bold">{{ $currency->symbol }} {{ number_format($item->grand_total - $item->payments->sum('amount'), $currency->digit) }}</td>
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