@extends('layouts.master')
@section('css')
<style type="text/css">
  
</style>
@endsection
@section('content')
<section class="content-header">
  <h1>
    {{ __('title.edit_user') }} <small>#{{ $user->id }}</small>
  </h1>
  <ol class="breadcrumb">
    <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i>{{ __('title.dashboard') }}</a></li>
    <li><a href="{{ route('user.index') }}">{{ __('title.users') }}</a></li>
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
          <h3 class="box-title"><i class="fa fa-arrows"></i> {{ __('title.user_information') }}</h3>            
        </div>
        <!-- /.box-header -->
        <div class="box-body">            
          <form role="form" action="{{ route('user.update', $user->id) }}" method="POST" enctype="multipart/form-data">
          
            @csrf @method('PUT')

            <div class="box-body">    
             <div class="row">
              <div class="col-lg-6 col-md-8 col-sm-12">

                <div class="form-group @error('name') has-error @enderror">
                  <label for="name">{{ __('app.username') }} <span class="required">*</span></label>
                  <input type="text" class="form-control" id="name" name="name" value="{{ old('name')?:$user->name }}" required>
                  @error('name')
                    <span class="help-block">{{ $message }}</span>
                  @enderror                      
                </div>                  
                <div class="form-group @error('email') has-error @enderror">
                  <label for="email">{{ __('app.email') }} <span class="required">*</span></label>
                  <input type="email" class="form-control" id="email" name="email" value="{{ old('email')?:$user->email }}" readonly>
                  @error('email')
                    <span class="help-block">{{ $message }}</span>
                  @enderror                  
                </div>
                <div class="form-group">
                  <label for="telephone">{{ __('app.telephone') }}</label>
                  <input type="text" class="form-control" id="telephone" name="telephone" value="{{ old('telephone')?:$user->telephone }}">
                </div>                     
                <div class="form-group @error('role') has-error @enderror">
                  <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-12">
                      <label>{{ __('app.role') }} <span class="required">*</span></label>
                      <select class="form-control" name="role">
                        <option value="0">{{ __('app.choose_option') }}</option>
                        @foreach($roles as $item)
                        <option value="{{ $item->id }}" @if($item->id == old('role') || $user->role_id == $item->id) selected @endif>{{ $item->role_name }}</option>
                        @endforeach
                      </select>  
                      @error('role')
                        <span class="help-block">{{ $message }}</span>
                      @enderror                                            
                    </div>
                  </div>
                </div>
                @if(Auth::user()->allowMultiStorageLocations())
                <div class="form-group @error('location_id') has-error @enderror">
                  <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-12">
                      <label>{{ __('app.location') }} <span class="required">*</span></label>
                      <select class="form-control" name="location_id">
                        <option value="0">{{ __('app.choose_option') }}</option>
                        @foreach($locations as $item)
                        <option value="{{ $item->id }}" @if($item->id == old('location_id') || $user->location_id == $item->id) selected @endif >{{ $item->location_name }}</option>
                        @endforeach
                      </select>  
                      @error('location_id')
                        <span class="help-block">{{ $message }}</span>
                      @enderror                                            
                    </div>
                  </div>
                </div>   
                @else
                <input type="hidden" name="location_id" value="{{ $user->location_id }}">
                @endif                     
                <div class="form-group">
                  <label>
                    <input type="radio" value="male" name="gender" class="flat-red" @if(old('gender') == 'male' || $user->gender == 'male') checked @endif>
                    {{ __('app.male') }}
                  </label>&nbsp;&nbsp;&nbsp;
                  <label>
                    <input type="radio" value="female" name="gender" class="flat-red" @if(old('gender') == 'female' || $user->gender == 'female') checked @endif>
                    {{ __('app.female') }}
                  </label>
                </div>                  

              </div>                

              <div class="col-lg-6 col-md-4 col-sm-12">
                <div class="form-group">
                  <img id="blah" src="{{ url($user->image_url) }}" width="150px" height="150px;" alt="your image" /> <br/><br/>
                  <input type='file' id="imgInp" name="file_upload" />
                </div>
              </div>                  
            </div>

          </div>

          <div class="box-footer">
            <a href="{{route('user.index')}}" class="btn btn-default">{{ __('title.cancel') }}</a>&nbsp;&nbsp;&nbsp;
            @if(Auth::user()->allowEdit(config('global.modules.user')))
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