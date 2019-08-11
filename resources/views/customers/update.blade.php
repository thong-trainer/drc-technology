@extends('layouts.master')
@section('css')
<style type="text/css">
  
</style>
@endsection
@section('content')
<section class="content-header">
  <h1>
    {{ __('title.edit_customer') }} <small>#{{ $customer->id }}</small>
  </h1>
  <ol class="breadcrumb">
    <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i>{{ __('title.dashboard') }}</a></li>
    <li><a href="{{ route('customer.index') }}">{{ __('title.customers') }}</a></li>
    <li class="active">{{ __('title.edit') }}</li>
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
          <h3 class="box-title"><i class="fa fa-arrows"></i> {{ __('title.customer_information') }}</h3>            
        </div>
        <!-- /.box-header -->
        <div class="box-body">            
          <form role="form" action="{{ route('customer.update', $customer->id) }}" method="POST" enctype="multipart/form-data">
            @csrf @method('PUT')
            <div class="box-body">    
             <div class="row">
              <div class="col-lg-6 col-md-8 col-sm-12">
                <div class="row">
                  <div class="col-lg-4 col-md-4 col-sm-12">
                    <div class="form-group">
                      <img class="img-upload" id="blah" src="{{ url($customer->contact->image_url) }}" alt="your image" />
                      <input type='file' id="imgInp" name="file_upload" class="hide-file-name" accept="image/png, image/jpeg"/>
                      <input class="btn-upload" type="button" value="Browse" onclick="document.getElementById('imgInp').click();" />
                    </div>                    
                  </div>
                  <div class="col-lg-8 col-md-8 col-sm-12">                    
                    <div class="form-group @error('contact_name') has-error @enderror">
                      <label class="control-label"><i class="fa fa-check"></i> {{ $customer->type }}</label>
                      <input type="text" class="form-control big-input" name="contact_name" value="{{ old('contact_name')?: $customer->contact->contact_name }}" placeholder="{{ __('app.customer_name') }}" required>
                      @error('contact_name')
                      <span class="help-block">{{ $message }}</span>
                      @enderror                      
                    </div>
                    <div id="company-div" class="form-group" style="width: 250px">
                      <select class="form-control select2" id="select-company" name="company">
                        <option value="0">{{ __('app.company') }}</option>
                        @foreach($companies as $item)
                        <option value="{{ $item->id }}" @if($item->id == old('company') || $customer->company_id == $item->id) selected @endif >{{ $item->company_name }}</option>
                        @endforeach
                      </select>
                    </div>

                    <div class="form-group @error('gender') has-error @enderror">
                      <label>
                        <input type="radio" value="male" name="gender" class="flat-red" @if($customer->contact->gender == 'male') checked @endif >
                        {{ __('app.male') }}
                      </label>&nbsp;&nbsp;&nbsp;
                      <label>
                        <input type="radio" value="female" name="gender" class="flat-red" @if($customer->contact->gender == 'female') checked @endif >
                        {{ __('app.female') }}
                      </label>
                    </div>                                   
                  </div>                    
                </div>
                <div class="form-group">
                  <label for="notes">{{ __('app.notes') }} </label>
                  <textarea type="text" rows="8" class="form-control" id="notes" name="notes">{{ old('note') ?: $customer->contact->notes }}</textarea>
                </div>                    
              </div>

              <div class="col-lg-6 col-md-4 col-sm-12">

                <div class="row">
                  <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="form-group @error('group') has-error @enderror">

                      <label>{{ __('app.customer_group') }} <span class="required">*</span></label>
                      <select class="form-control" name="group">
                        <option value="0">{{ __('app.choose_option') }}</option>
                        @foreach($groups as $item)
                        <option value="{{ $item->id }}" @if($item->id == old('group') || $item->id == $customer->group_id) selected @endif>{{ $item->group_name }}</option>
                        @endforeach
                      </select>  
                      @error('group')
                      <span class="help-block">{{ $message }}</span>
                      @enderror                                            

                    </div>  
                  </div>

                  <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="form-group">
                      <label for="position">{{ __('app.position') }}</label>
                      <input type="text" class="form-control" id="position" name="position" value="{{ old('position') ?: $customer->contact->position }}">
                    </div>                                               
                  </div>
                </div> 



                <div class="form-group">
                  <label for="email">{{ __('app.email') }} </label>
                  <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                    <input type="email" id="email" name="email" class="form-control" placeholder="example@mail.com" value="{{ old('email') ?: $customer->contact->email }}">
                  </div>                            
                </div>                                               
                <div class="form-group @error('primary_telephone') has-error @enderror">
                  <label for="primary-telephone">{{ __('app.primary_telephone') }} <span class="required">*</span></label>
                  <div class="input-group">
                    <div class="input-group-addon">
                      <i class="fa fa-phone"></i>
                    </div>
                    <input type="text" class="form-control" id="primary-telephone" name="primary_telephone" value="{{ old('primary_telephone') ?: $customer->contact->primary_telephone }}" data-inputmask='"mask": "(999) 999-9999"' data-mask>
                  </div>
                  @error('primary_telephone')
                    <span class="help-block">{{ $message }}</span>
                  @enderror                   
                </div>
                <div class="form-group">
                  <label for="other-telephone">{{ __('app.other_telephone') }}</label>
                  <input type="text" class="form-control" id="other-telephone" name="other_telephone" value="{{ old('other_telephone') ?: $customer->contact->other_telephone }}">
                </div>         
              

                <div class="form-group">
                  <label for="address">{{ __('app.address') }} </label>
                  <textarea type="text" rows="2" class="form-control" id="address" name="address">{{ old('address') ?: $customer->contact->main_address }}</textarea>
                </div> 
                
              </div>
               
            </div>
          </div>

          <div class="box-footer">
            <a href="{{route('customer.index')}}" class="btn btn-default">{{ __('title.cancel') }}</a>&nbsp;&nbsp;&nbsp;
            @if(Auth::user()->allowEdit(config('global.modules.customer')))
            <button type="submit" class="btn btn-primary">{{ __('title.save_changes') }}</button>
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
  $('#radio-individual').on('ifChanged', function(e){
    if(e.target.checked == true) {
      $('#company-div').show();
    } else {
      $('#company-div').hide();
      $('#select-company').val(0).trigger('change');
    }
  });

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