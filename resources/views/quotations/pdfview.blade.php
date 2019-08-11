@php
$company = Auth::user()->companyInfo();
@endphp
<div id="content">
	<section class="invoice">
    <div class="row">
      <div class="col-xs-12">
        <h2 class="page-header">
          <img src="{{ url($company->image_url) }}" style="width: 50px; height: 50px"> <b>Test</b>
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
          {{ $item->customer->contact->main_address }}<br>
          Phone: {{ $item->customer->contact->primary_telephone }}<br>
          Email: {{ $item->customer->contact->email }}
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

    <div class="row">
      <div class="col-xs-12 table-responsive">
        <table class="table table-striped">
          <thead>
          <tr>
            <th>#</th>
            <th>{{ __('app.description') }}</th>
            <th>{{ __('app.qty') }}</th>
            <th>{{ __('app.price') }}</th>
            <th>{{ __('app.tax') }}</th>
            <th>{{ __('app.subtotal') }}</th>
          </tr>
          </thead>
          <tbody>
            @foreach($item->details as $key => $detail)
            <tr>
              <td>{{ $key + 1 }}</td>
              <td>{{ $detail->product_name }}</td>
              <td>{{ number_format($detail->unit_price, 2) }}</td>
              <td>{{ $detail->qty }}</td>
              <td>{{ $detail->tax }}%</td>
              <td>{{ number_format($detail->subtotal, 2) }}</td>
            </tr>            
            @endforeach            
          </tbody>
        </table>
      </div>
      <!-- /.col -->
    </div>
</section>
</div>
<div id="editor"></div>
<button id="cmd">generate PDF</button>
<script src="{{ asset('bower_components/jquery/dist/jquery.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.2/jspdf.min.js"></script>
<script type="text/javascript">
var doc = new jsPDF();
var specialElementHandlers = {
    '#editor': function (element, renderer) {
        return true;
    }
};

$('#cmd').click(function () {
    doc.fromHTML($('#content').html(), 15, 15, {
        'width': 170,
            'elementHandlers': specialElementHandlers
    });
    doc.save('sample-file.pdf');
});
	

</script>