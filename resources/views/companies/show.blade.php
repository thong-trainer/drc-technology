@extends('layouts.master')
@section('css')
<style type="text/css">
  
</style>
@endsection
@section('content')
<section class="content-header">
  <h1>
    {{ __('title.view_company') }} <small>#{{ $company->id }}</small>
  </h1>
  <ol class="breadcrumb">
    <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i>{{ __('title.dashboard') }}</a></li>
    <li><a href="{{ route('company.index') }}">{{ __('title.companies') }}</a></li>
    <li class="active">{{ __('title.view') }}</li>
  </ol>
</section>
  
<section class="content">
  <div class="row">
    <div class="col-md-12">
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-arrows"></i> {{ __('title.company_information') }}</h3>    
          @if(Auth::user()->allowEdit(config('global.modules.company')))
            <div class="pull-right box-tools">
              <a href="{{ route('company.edit', $company->id) }}" class="btn btn-default btn-sm" data-widget="edit" data-toggle="tooltip" title="Edit"><i class="fa fa-pencil"></i> {{ __('title.edit') }}</a>
            </div>
          @endif                  
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
                      <img class="img-upload" id="blah" src="{{ url($company->image_url) }}" alt="your image"/>
                    </div>                    
                  </div>
                  <div class="col-lg-8 col-md-8 col-sm-12">      
                    <div class="form-group @error('company_name') has-error @enderror">
                      <label for="website">{{ __('app.company_name') }}</label>
                      <input type="text" class="form-control" id="name" name="company_name" value="{{ old('company_name') ?: $company->company_name }}" placeholder="{{ __('app.name') }}" required readonly>
                      @error('company_name')
                      <span class="help-block">{{ $message }}</span>
                      @enderror                      
                    </div>    
                    <div class="form-group">
                      <label for="industry">{{ __('app.industry') }}</label>
                      <input type="text" class="form-control" id="industry" name="industry" value="{{ old('website') ?: $company->type }}" readonly>
                    </div>                                                                
                  </div>                    
                </div>
                <div class="form-group">
                  <label for="note">{{ __('app.notes') }} </label>
                  <textarea type="text" rows="6" class="form-control" id="notes" name="note" readonly>{{ old('notes') ?: $company->notes }}</textarea>
                </div>     
              </div>

              <div class="col-lg-6 col-md-4 col-sm-12">     

                <div class="form-group">
                  <label for="website">{{ __('app.website') }} </label>
                  <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-wordpress"></i></span>
                    <input type="website" id="website" name="website" class="form-control" value="{{ old('website') ?: $company->website }}" placeholder="www.example.com" readonly>
                  </div>                            
                </div>                                   
                <div class="form-group">
                  <label for="email">{{ __('app.email') }} </label>
                  <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                    <input type="email" id="email" name="email" class="form-control" value="{{ old('email') ?: $company->email }}" placeholder="example@mail.com" readonly>
                  </div>                            
                </div>    

                <div class="form-group">
                  <label for="other-telephone">{{ __('app.other_telephone') }}</label>
                  <input type="text" class="form-control" id="other-telephone" name="other_telephone" value="{{ old('other_telephone') ?: $company->telephone }}" readonly>
                </div>   

                <div class="form-group">
                  <label for="address">{{ __('app.address') }} </label>
                  <textarea type="text" rows="2" class="form-control" id="address" name="address" readonly>{{ old('address') ?: $company->address }}</textarea>
                </div>
              </div>           
            </div>
          </div>
          <div class="box-footer">            
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
   
});


</script>
@endsection