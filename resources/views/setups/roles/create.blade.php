@extends('layouts.master')
@section('css')
<style type="text/css">
  
</style>
@endsection
@section('content')
<section class="content-header">
  <h1>
    {{ __('title.new_role') }}
  </h1>
  <ol class="breadcrumb">
    <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i>{{ __('title.dashboard') }}</a></li>
    <li><a href="{{ route('role.index') }}">{{ __('title.roles') }}</a></li>
    <li class="active">{{ __('title.create') }}</li>
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
          <h3 class="box-title"><i class="fa fa-arrows"></i> {{ __('title.role_information') }}</h3>            
        </div>
        <!-- /.box-header -->
        <div class="box-body">            
          <form role="form" action="{{ route('role.store') }}" method="POST">
            @csrf
            <div class="box-body">    
             <div class="row">
              <div class="col-lg-6 col-md-8 col-sm-12">

                <div class="form-group @error('role_name') has-error @enderror">
                  <label for="role">{{ __('app.role') }} <span class="required">*</span></label>
                  <input type="text" class="form-control" id="role" name="role_name" value="{{ old('role_name')}}" required>
                  @error('role_name')
                    <span class="help-block">{{ $message }}</span>
                  @enderror                      
                </div>
                <div class="form-group @error('description') has-error @enderror">
                  <label for="description">{{ __('app.description') }} <span class="required">*</span></label>
                  <textarea type="text" rows="5" class="form-control" id="description" name="description" required>{{ old('description') }}</textarea>
                  @error('description')
                    <span class="help-block">{{ $message }}</span>
                  @enderror                      
                </div>                                  
              </div>                

              <div class="col-lg-6 col-md-4 col-sm-12">

              </div>                  
            </div>

          </div>

          <div class="box-footer">
            <a href="{{route('role.index')}}" class="btn btn-default">{{ __('title.cancel') }}</a>&nbsp;&nbsp;&nbsp;
            @if(Auth::user()->allowCreate(config('global.modules.role')))
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

</script>
@endsection