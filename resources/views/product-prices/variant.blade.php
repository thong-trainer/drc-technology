<div class="row">
	<div class="col-md-12">	  
    <form role="form" action="{{ route('variant.list.update', $product_id) }}" method="POST">
      @csrf
      <input type="hidden" name="product_id" value="{{ $product_id }}">
      <input type="hidden" name="_method" value="put">
  	  <div class="nav-tabs-custom" style="margin-bottom: 10px">
  	    <ul class="nav nav-tabs pull-right">
  	    	@foreach($dimension_details as $key => $detail)
  	    		<li class="{{ $key == 0 ? 'active' : '' }}"><a href="#test{{$key}}" data-toggle="tab">{{ $detail->dimension->dimension_name }}</a></li>
  	    	@endforeach	   
          <li class="pull-left header"><i class="fa fa fa-sitemap"></i> {{ __('title.variants') }}</li>       
  	    </ul>
  	    <div class="tab-content">
  	    	@foreach($dimension_details as $key => $detail)
  		    	<div class="tab-pane {{ $key == 0 ? 'active' : '' }}" id="test{{$key}}">
              <a data-table-id="{{ $detail->dimension_id }}" class="btn btn-md text-primary new-record" title="Add New"><i class="fa fa-plus-circle text-primary"></i> {{ __('title.add_new') }} </a>
                <table id="{{ $detail->dimension_id }}" class="table table-bordered table-striped table-hover">
                  <thead>
                    <tr>
                      <th>{{ __('app.dimension_value') }} ({{$detail->dimension->dimension_name}})</th>
                      <th style="width: 20px"></th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($product_variants as $item)
                      @if($item->dimension_id == $detail->dimension_id)
                        <tr>
                          <td>
                            <input class="input-table" type="text" name="{{ $detail->dimension_id }}_values[]" value="{{ $item->value }}">
                          </td>
                          <td>
                            <a href="#" title="Remove"><i class="fa fa-trash text-danger remove"></i></a>
                          </td>
                        </tr>
                      @endif
                    @endforeach
                  </tbody>                
                </table>
  			    </div>
  	    	@endforeach	   
  	    </div>
  	  </div>
      <div class="row pull-right" style="padding-right: 10px; padding-bottom: 10px">
        <div class="col-md-12"> 
            @if(Auth::user()->allowCreate(config('global.modules.product')))
            <button type="submit" class="btn btn-primary">{{ __('title.save_changes') }}</button>&nbsp;&nbsp;&nbsp;
            @endif                  
            <button id="close-modal" type="button" class="btn btn-default">{{ __('title.cancel') }}</a>
          </div>
      </div>
         
    </form>
	</div>
</div>

<script type="text/javascript">   
$(document).ready(function() {
    
    // to close the modal popup
    $('#close-modal').click(function () {
      $("#model-popup").modal('hide');
    });   


    // click on button add new record    
    $('body').on('click', '.new-record', function(){

      // get the table id from button add new
      var tableId = $(this).data('table-id');
      var tr = getLastRow(tableId);
      var hasRecord = false;

      // add new row or focus on the input text
      tr.find("input").each(function() {
        hasRecord = true;
        if(this.value != "") {
          addRow(tableId, tr);
        } else {
          this.focus();
        }
      });

      // add a new row when the table doesn't has the record
      if(hasRecord == false) {
        addRow(tableId, tr);
      }

    });   

    // remove row from the table
    $('body').on('click', '.remove', function(){
      $(this).parents("tr").remove();
    });   
    
    // reterive the last row based on the table id
    function getLastRow(tableId) {
      return $('#'+tableId+' tr:last');
    }

    // add a new row to the table by table id
    function addRow(tableId, tr) {
      tr.after('<tr><td><input class="input-table" type="text" name="'+tableId+'_values[]"></td><td><a href="#" title="Remove"><i class="fa fa-trash text-danger remove"></i></a></td></tr>');  
      var lastRow = getLastRow(tableId);
      lastRow.find("input").each(function() { this.focus(); });          
    }

});  
</script>