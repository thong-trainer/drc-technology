@extends('layouts.master')
@section('css')
<style type="text/css">
  
</style>
@endsection
@section('content')
<section class="content-header">
  <h1>
    {{ __('title.edit_customer_group') }} <small>#{{ $group->id }}</small>
  </h1>
  <ol class="breadcrumb">
    <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i>{{ __('title.dashboard') }}</a></li>
    <li><a href="{{ route('customer-group.index') }}">{{ __('title.customer_groups') }}</a></li>
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
          <h3 class="box-title"><i class="fa fa-arrows"></i> {{ __('title.customer_group_information') }}</h3>            
        </div>
        <!-- /.box-header -->
        <div class="box-body">            
          <form role="form" action="{{ route('customer-group.update', $group->id) }}" method="POST">
            @csrf @method('PUT')
            <div class="box-body">    
             <div class="row">
              <div class="col-lg-6 col-md-8 col-sm-12">

                <div class="form-group @error('group_name') has-error @enderror">
                  <label for="group_name">{{ __('app.group_name') }} <span class="required">*</span></label>
                  <input type="text" class="form-control" name="group_name" value="{{ old('group_name')?:$group->group_name }}" required>
                  @error('group_name')
                    <span class="help-block">{{ $message }}</span>
                  @enderror                      
                </div>
                <div class="form-group @error('description') has-error @enderror">
                  <label for="description">{{ __('app.description') }} <span class="required">*</span></label>
                  <textarea type="text" rows="5" class="form-control" id="description" name="description" required>{{ old('description')?:$group->description }}</textarea>
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
            <a href="{{route('customer-group.index')}}" class="btn btn-default">{{ __('title.cancel') }}</a>&nbsp;&nbsp;&nbsp;
            @if(Auth::user()->allowEdit(config('global.modules.customer_group')))
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

</script>
@endsection