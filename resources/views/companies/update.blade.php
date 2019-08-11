@extends('layouts.master')
@section('css')
<style type="text/css">
  
</style>
@endsection
@section('content')
<section class="content-header">
  <h1>
    {{ __('title.edit_company') }} <small>#{{ $company->id }}</small>
  </h1>
  <ol class="breadcrumb">
    <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i>{{ __('title.dashboard') }}</a></li>
    <li><a href="{{ route('company.index') }}">{{ __('title.companies') }}</a></li>
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
          <h3 class="box-title"><i class="fa fa-arrows"></i> {{ __('title.company_information') }}</h3>            
        </div>
        <!-- /.box-header -->
        <div class="box-body">            
          <form role="form" action="{{ route('company.update', $company->id) }}" method="POST" enctype="multipart/form-data">
            @csrf @method('PUT')
            <div class="box-body">    
             <div class="row">
              <div class="col-lg-6 col-md-8 col-sm-12">
                 
                <div class="row">
                  <div class="col-lg-4 col-md-4 col-sm-12">
                    <div class="form-group">
                      <img class="img-upload" id="blah" src="{{ url($company->image_url) }}" alt="your image" />
                      <input type='file' id="imgInp" name="file_upload" class="hide-file-name" accept="image/png, image/jpeg"/>
                      <input class="btn-upload" type="button" value="Browse" onclick="document.getElementById('imgInp').click();" />
                    </div>                    
                  </div>
                  <div class="col-lg-8 col-md-8 col-sm-12">      
                    <div class="form-group @error('company_name') has-error @enderror">
                      <label for="website">{{ __('app.company_name') }}</label>
                      <input type="text" class="form-control" id="name" name="company_name" value="{{ old('company_name') ?: $company->company_name }}" placeholder="{{ __('app.name') }}" required>
                      @error('company_name')
                      <span class="help-block">{{ $message }}</span>
                      @enderror                      
                    </div>    
                    <div class="form-group">
                      <label for="industry">{{ __('app.industry') }}</label>
                      <input type="text" class="form-control" id="industry" name="industry" value="{{ old('website') ?: $company->type }}">
                      <span class="help-block">eg: (Designer, Mart, Restaurant, etc.)</span>
                    </div>                                                                
                  </div>                    
                </div>
                <div class="form-group">
                  <label for="note">{{ __('app.notes') }} </label>
                  <textarea type="text" rows="5" class="form-control" id="notes" name="note">{{ old('notes') ?: $company->notes }}</textarea>
                </div>     
              </div>

              <div class="col-lg-6 col-md-4 col-sm-12">     

                <div class="form-group">
                  <label for="website">{{ __('app.website') }} </label>
                  <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-wordpress"></i></span>
                    <input type="website" id="website" name="website" class="form-control" value="{{ old('website') ?: $company->website }}" placeholder="www.example.com">
                  </div>                            
                </div>                                   


                <div class="form-group">
                  <label for="email">{{ __('app.email') }} </label>
                  <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                    <input type="email" id="email" name="email" class="form-control" value="{{ old('email') ?: $company->email }}" placeholder="example@mail.com">
                  </div>                            
                </div>    

                <div class="form-group">
                  <label for="other-telephone">{{ __('app.other_telephone') }}</label>
                  <input type="text" class="form-control" id="other-telephone" name="other_telephone" value="{{ old('other_telephone') ?: $company->telephone }}">
                </div>   

                <div class="form-group">
                  <label for="address">{{ __('app.address') }} </label>
                  <textarea type="text" rows="2" class="form-control" id="address" name="address">{{ old('address') ?: $company->address }}</textarea>
                </div>
              </div>           
            </div>
          </div>
          <div class="box-footer">
            <a href="{{route('company.index')}}" class="btn btn-default">{{ __('title.cancel') }}</a>&nbsp;&nbsp;&nbsp;
            @if(Auth::user()->allowEdit(config('global.modules.company')))
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