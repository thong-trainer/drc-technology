@extends('layouts.master')
@section('css')
<style type="text/css">
  
</style>
@endsection
@section('content')
<section class="content-header">
  <h1>
    {{ __('title.new_dimension_group') }}
  </h1>
  <ol class="breadcrumb">
    <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i>{{ __('title.dashboard') }}</a></li>
    <li><a href="{{ route('dimension-group.index') }}">{{ __('title.dimension_groups') }}</a></li>
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
          <h3 class="box-title"><i class="fa fa-arrows"></i> {{ __('title.dimension_group_information') }}</h3>            
        </div>
        <!-- /.box-header -->
        <div class="box-body">            
          <form role="form" action="{{ route('dimension-group.store') }}" method="POST">
            @csrf
            <div class="box-body">    
             <div class="row">
              <div class="col-lg-6 col-md-8 col-sm-12">

                <div class="form-group @error('dimension_group') has-error @enderror">
                  <label for="role">{{ __('app.dimension_group') }} <span class="required">*</span></label>
                  <input type="text" class="form-control" id="role" name="dimension_group" value="{{ old('dimension_group')}}" required>
                  @error('dimension_group')
                    <span class="help-block">{{ $message }}</span>
                  @enderror                      
                </div>
                <div class="form-group @error('dimensions') has-error @enderror">
                  <label for="dimensions">{{ __('app.dimension') }} <span class="required">*</span></label>
                  <select class="form-control select2" multiple="multiple" id="dimensions" data-placeholder="{{ __('app.select_dimensions') }}"
                          style="width: 100%;" name="dimensions[]">
                    @foreach($dimensions as $item)
                      @if( old('dimensions') != null && in_array($item->id, old('dimensions')) )
                        <option value="{{ $item->id }}" selected >{{ $item->dimension_name }}</option>
                      @else
                        <option value="{{ $item->id }}" >{{ $item->dimension_name }}</option>
                      @endif                    
                    @endforeach                    
                  </select>
                  @error('dimensions')
                    <span class="help-block">{{ $message }}</span>
                  @enderror                      
                </div>                
                <div class="form-group">
                  <label for="description">{{ __('app.description') }} </label>
                  <textarea type="text" rows="5" class="form-control" id="description" name="description">{{ old('description') }}</textarea>           
                </div>                                  
              </div>                

              <div class="col-lg-6 col-md-4 col-sm-12">

              </div>                  
            </div>
          </div>

          <div class="box-footer">
            <a href="{{route('dimension-group.index')}}" class="btn btn-default">{{ __('title.cancel') }}</a>&nbsp;&nbsp;&nbsp;
            @if(Auth::user()->allowCreate(config('global.modules.dimension')))
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