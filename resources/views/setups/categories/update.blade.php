@extends('layouts.master')
@section('css')
<style type="text/css">
  
</style>
@endsection
@section('content')
<section class="content-header">
  <h1>
    {{ __('title.edit_category') }} <small>#{{ $category->id }}</small>
  </h1>
  <ol class="breadcrumb">
    <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i>{{ __('title.dashboard') }}</a></li>
    <li><a href="{{ route('category.index') }}">{{ __('title.categories') }}</a></li>
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
          <h3 class="box-title"><i class="fa fa-arrows"></i> {{ __('title.category_information') }}</h3>            
        </div>
        <!-- /.box-header -->
        <div class="box-body">            
          <form role="form" action="{{ route('category.update', $category->id) }}" method="POST" enctype="multipart/form-data">
            @csrf @method('PUT')
            <div class="box-body">    
             <div class="row">
              <div class="col-lg-6 col-md-8 col-sm-12">
                <div class="form-group @error('category_name') has-error @enderror">
                  <label for="name">{{ __('app.category_name') }} <span class="required">*</span></label>
                  <input type="text" class="form-control" id="name" name="category_name" value="{{ old('category_name') ?:  $category->category_name }}" required>
                  @error('category_name')
                    <span class="help-block">{{ $message }}</span>
                  @enderror                      
                </div>
                <div class="form-group @error('parent') has-error @enderror">
                  <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-12">
                      <label>{{ __('app.parent') }} </label>
                      <select class="form-control select2" name="parent">
                        <option value="0">N/A</option>
                        @foreach($parents as $item)                      
                        <option value="{{ $item->id }}"  @if(old('parent') == $item->id || $category->parent_id == $item->id) selected @endif>{{ $item->category_name }}</option>
                        @endforeach
                      </select>  
                      @error('parent')
                        <span class="help-block">{{ $message }}</span>
                      @enderror                                            
                    </div>
                  </div>
                </div>                   
                <div class="form-group">
                  <label for="description">{{ __('app.description') }}</label>
                  <textarea type="text" rows="5" class="form-control" id="description" name="description" >{{ old('description') ?: $category->description }}</textarea>
                </div>                                  
              </div>                

              <div class="col-lg-6 col-md-4 col-sm-12">
                <div class="form-group">
                  <img class="img-upload" id="blah" src="{{ url('') }}{{ $category->image_url }}" alt="your image" />
                  <input type='file' id="imgInp" name="file_upload" class="hide-file-name" />
                  <input class="btn-upload" type="button" value="Browse" onclick="document.getElementById('imgInp').click();"/>
                </div>
              </div>                  
            </div>

          </div>

          <div class="box-footer">
            <a href="{{route('category.index')}}" class="btn btn-default">{{ __('title.cancel') }}</a>&nbsp;&nbsp;&nbsp;
            @if(Auth::user()->allowEdit(config('global.modules.category')))
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