@extends('layouts.master')
@section('css')
<style type="text/css">
  
</style>
@endsection
@section('content')
<section class="content-header">
  <h1>
    {{ __('title.new_supplier') }}
  </h1>
  <ol class="breadcrumb">
    <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i>{{ __('title.dashboard') }}</a></li>
    <li><a href="{{ route('supplier.index') }}">{{ __('title.suppliers') }}</a></li>
    <li class="active">{{ __('title.create') }}</li>
  </ol>
</section>

@if(!Request::get('type'))
  <script>window.location = "/dashboard";</script>  
@endif 
  

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
          <h3 class="box-title"><i class="fa fa-arrows"></i> {{ __('title.supplier_information') }}</h3>            
        </div>
        <!-- /.box-header -->
        <div class="box-body">            
          <form role="form" action="{{ route('supplier.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="type" value="{{ Request::get('type') }}">
            <div class="box-body">    
             <div class="row">
              <div class="col-lg-6 col-md-8 col-sm-12">
                 
                <div class="row">
                  <div class="col-lg-4 col-md-4 col-sm-12">
                    <div class="form-group">
                      <img class="img-upload" id="blah" src="{{ url(config('global.paths.contact')) }}/contact-placeholder.jpg" alt="your image" />
                      <input type='file' id="imgInp" name="file_upload" class="hide-file-name" accept="image/png, image/jpeg"/>
                      <input class="btn-upload" type="button" value="Browse" onclick="document.getElementById('imgInp').click();" />
                    </div>                    
                  </div>
                  <div class="col-lg-8 col-md-8 col-sm-12">
                    <div id="#my-type" class="form-group @error('type') has-error @enderror">
                      <label >
                        <input id="radio-individual" type="radio" value="individual" name="type" class="flat-red" @if(Request::get('type') == "individual") checked @endif >
                        {{ __('app.individual') }}
                      </label>&nbsp;&nbsp;&nbsp;
                      <label >
                        <input type="radio" value="company" name="type" class="flat-red" @if(Request::get('type') == "company") checked @endif >
                        {{ __('app.company') }}
                      </label>
                    </div>          
                    <!-- we use company_name for both individual and company -->
                    <div class="form-group @error('company_name') has-error @enderror">
                      <input type="text" class="form-control big-input" id="name" name="company_name" value="{{ old('company_name')}}" placeholder="{{ __('app.name') }}" required>
                      @error('company_name')
                      <span class="help-block">{{ $message }}</span>
                      @enderror                      
                    </div>

                    @if(Request::get('type') == "individual")
                    <div class="form-group" >
                      <select class="form-control select2 " id="select-company" name="company" style="width: 250px">
                        <option value="0">{{ __('app.company') }}</option>
                        @foreach($companies as $item)
                        <option value="{{ $item->id }}" @if($item->id == old('company')) selected @endif>{{ $item->company_name }}</option>
                        @endforeach
                      </select>
                    </div>
                    <div class="form-group @error('gender') has-error @enderror">
                      <label>
                        <input type="radio" value="male" name="gender" class="flat-red" @if(old('gender') == "male") checked @endif >
                        {{ __('app.male') }}
                      </label>&nbsp;&nbsp;&nbsp;
                      <label>
                        <input type="radio" value="female" name="gender" class="flat-red" @if(old('gender') == "female") checked @endif >
                        {{ __('app.female') }}
                      </label>
                    </div>                     
                    @else
                    <div class="form-group @error('contact') has-error @enderror">
                      <select class="form-control select2" id="select-contact" id="contact-select" name="contact" style="width: 200px;">
                        <option value="0">{{ __('app.contact') }}</option>
                        @foreach($contacts as $item)
                        <option value="{{ $item->id }}" @if($item->id == old('contact')) selected @endif>{{ $item->contact_name }}</option>
                        @endforeach
                      </select>   
                      @error('contact')
                      <span class="help-block">{{ $message }}</span>
                      @enderror                                         
                    </div> 
                    @endif                   
                                   
                  </div>                    
                </div>
                <div class="form-group">
                  <label for="note">{{ __('app.notes') }} </label>
                  <textarea type="text" rows="8" class="form-control" id="notes" name="note">{{ old('notes') }}</textarea>
                </div>     
              </div>

              <div class="col-lg-6 col-md-4 col-sm-12">     
                <div class="row">
                  <div class="col-lg-6 col-md-6 col-sm-12">
                    @if(Request::get('type') == "individual")
                    <div class="form-group" id="position-div">
                      <label for="position">{{ __('app.position') }}</label>
                      <input type="text" class="form-control" id="position" name="position" value="{{ old('position')}}">
                    </div> 
                    @else
                    <div class="form-group" id="website-div">
                      <label for="website">{{ __('app.website') }}</label>
                      <input type="text" class="form-control" id="website" name="website" value="{{ old('website')}}">
                    </div>
                    @endif                                                                   
                  </div>
                </div> 

                <div class="form-group">
                  <label for="email">{{ __('app.email') }} </label>
                  <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                    <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="example@mail.com">
                  </div>                            
                </div>    
                @if(Request::get('type') == "individual")               
                <div id="primary-telephone-div" class="form-group @error('primary_telephone') has-error @enderror">
                  <label for="primary-telephone">{{ __('app.primary_telephone') }} <span class="required">*</span></label>
                  <div class="input-group">
                    <div class="input-group-addon">
                      <i class="fa fa-phone"></i>
                    </div>
                    <input type="text" class="form-control" id="primary-telephone" name="primary_telephone" value="{{ old('primary_telephone') }}" data-inputmask='"mask": "(999) 999-9999"' data-mask>
                  </div>
                  @error('primary_telephone')
                    <span class="help-block">{{ $message }}</span>
                  @enderror                   
                </div>
                @endif  
                <div class="form-group">
                  <label for="other-telephone">{{ __('app.other_telephone') }}</label>
                  <input type="text" class="form-control" id="other-telephone" name="other_telephone" value="{{ old('other_telephone')}}">
                </div>   

                <div class="form-group">
                  <label for="address">{{ __('app.address') }} </label>
                  <textarea type="text" rows="2" class="form-control" id="address" name="address">{{ old('address') }}</textarea>
                </div>
              </div>           
            </div>
          </div>

          <div class="box-footer">
            <a href="{{route('supplier.index')}}" class="btn btn-default">{{ __('title.cancel') }}</a>&nbsp;&nbsp;&nbsp;
            @if(Auth::user()->allowCreate(config('global.modules.supplier')))
            <button type="submit" class="btn btn-primary">{{ __('title.save') }}</button>
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
    var url   = window.location.href;
    arr = url.split('=');
    if(e.target.checked == true) {
      window.location.replace(arr[0] + '=individual');
    } else {
      window.location.replace(arr[0] + '=company');
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