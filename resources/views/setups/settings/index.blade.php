@extends('layouts.master')
@section('css')
<style type="text/css">
  
</style>
@endsection
@section('content')
<section class="content-header">
  <h1>
    {{ __('title.settings') }}
  </h1>
  <ol class="breadcrumb">
    <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i>{{ __('title.dashboard') }}</a></li>
    <li class="active">{{ __('title.settings') }}</li>
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
          <form role="form" action="{{ route('setting.update', $company->id) }}" method="POST" enctype="multipart/form-data">
            @csrf @method('PUT')
            <div class="box-body">    
             <div class="row">
              <div class="col-lg-6 col-md-8 col-sm-12">
                <div class="form-group @error('role_name') has-error @enderror">
                  <label for="description">{{ __('app.company_name') }} <span class="required">*</span></label>
                  <input type="text" class="form-control" id="company_name" name="company_name" value="{{ old('company_name') ?: $company->company_name }}" required>
                  @error('role_name')
                    <span class="help-block">{{ $message }}</span>
                  @enderror                      
                </div>
                <div class="form-group @error('type') has-error @enderror">
                  <label for="type">{{ __('app.type') }} <span class="required">*</span></label>
                  <input type="text" class="form-control" id="type" name="type" value="{{ old('type') ?: $company->type }}" required>
                  @error('type')
                    <span class="help-block">{{ $message }}</span>
                  @enderror                      
                </div>       
                <div class="form-group @error('telephone') has-error @enderror">
                  <label for="telephone">{{ __('app.telephone') }} <span class="required">*</span></label>
                  <input type="text" class="form-control" id="telephone" name="telephone" value="{{ old('telephone') ?: $company->telephone }}" required>
                  @error('telephone')
                    <span class="help-block">{{ $message }}</span>
                  @enderror                      
                </div>     
                <div class="form-group @error('email') has-error @enderror">
                  <label for="email">{{ __('app.email') }} <span class="required">*</span></label>
                  <input type="email" class="form-control" id="email" name="email" value="{{ old('email') ?: $company->email }}" required>
                  @error('email')
                    <span class="help-block">{{ $message }}</span>
                  @enderror                      
                </div>     
                <div class="form-group @error('website') has-error @enderror">
                  <label for="website">{{ __('app.website') }} </label>
                  <input type="text" class="form-control" id="website" name="website" value="{{ old('website') ?: $company->website }}" >
                  @error('website')
                    <span class="help-block">{{ $message }}</span>
                  @enderror                      
                </div>                                                                   
                <div class="form-group @error('address') has-error @enderror">
                  <label for="address">{{ __('app.address') }} <span class="required">*</span></label>
                  <textarea type="text" rows="5" class="form-control" id="address" name="address" required>{{ old('address') ?: $company->address }}</textarea>
                  @error('address')
                    <span class="help-block">{{ $message }}</span>
                  @enderror                      
                </div>                 
                <div class="form-group @error('notes') has-error @enderror">
                  <label for="notes">{{ __('app.notes') }} </label>
                  <textarea type="text" rows="5" class="form-control" id="notes" name="notes">{{ old('notes') ?: $company->notes }}</textarea>
                  @error('notes')
                    <span class="help-block">{{ $message }}</span>
                  @enderror                      
                </div>                                  
              </div>                

              <div class="col-lg-6 col-md-4 col-sm-12">
                <div class="form-group">
                  <img class="img-upload" id="blah" src="{{ url($company->image_url) }}" alt="your image" />
                  <input type='file' id="imgInp" name="file_upload" class="hide-file-name" />
                  <input class="btn-upload" type="button" value="Browse" onclick="document.getElementById('imgInp').click();"/>
                </div>
              </div>                  
            </div>

          </div>

          <div class="box-footer">
            @if(Auth::user()->allowEdit(config('global.modules.setting')))
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
</script>
@endsection