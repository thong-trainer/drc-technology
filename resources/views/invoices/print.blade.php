<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Invoice</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="{{ asset('bower_components/bootstrap/dist/css/bootstrap.min.css') }}">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ asset('bower_components/font-awesome/css/font-awesome.min.css') }}">
  <!-- Ionicons -->
  <link rel="stylesheet" href="{{ asset('bower_components/Ionicons/css/ionicons.min.css') }}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('dist/css/AdminLTE.min.css') }}">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<!-- <body id="content" > -->
<body onload="window.print();">
@php
$company = Auth::user()->companyInfo();
$currency = $item->currency;
@endphp

<div class="wrapper">
  <!-- Main content -->
<section class="invoice">  
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
    <div class="col-xs-6">

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
  <!-- /.content -->
</div>
<script src="{{ asset('bower_components/jquery/dist/jquery.min.js') }}"></script>
<script type="text/javascript">
  
</script>
<!-- ./wrapper -->
</body>
</html>
