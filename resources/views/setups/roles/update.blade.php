@extends('layouts.master')
@section('css')
<style type="text/css">
  
</style>
@endsection
@section('content')
<section class="content-header">
  <h1>
    {{ __('title.edit_role') }} <small>#{{ $role->id }}</small>
  </h1>
  <ol class="breadcrumb">
    <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i>{{ __('title.dashboard') }}</a></li>
    <li><a href="{{ route('role.index') }}">{{ __('title.roles') }}</a></li>
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
          <h3 class="box-title"><i class="fa fa-arrows"></i> {{ __('title.role_information') }}</h3>            
        </div>
        <!-- /.box-header -->
        <div class="box-body">            
          <form role="form" action="{{ route('role.update', $role->id) }}" method="POST">
            @csrf @method('PUT')
            <div class="box-body">    
             <div class="row">
              <div class="col-lg-6 col-md-8 col-sm-12">

                <div class="form-group @error('role_name') has-error @enderror">
                  <label for="role">{{ __('app.role') }} <span class="required">*</span></label>
                  <input type="text" class="form-control" id="role" name="role_name" value="{{ old('role_name')?:$role->role_name }}" required>
                  @error('role_name')
                    <span class="help-block">{{ $message }}</span>
                  @enderror                      
                </div>
                <div class="form-group @error('description') has-error @enderror">
                  <label for="description">{{ __('app.description') }} <span class="required">*</span></label>
                  <textarea type="text" rows="5" class="form-control" id="description" name="description" required>{{ old('description')?:$role->description }}</textarea>
                  @error('description')
                    <span class="help-block">{{ $message }}</span>
                  @enderror                      
                </div>                                  
              </div>                

              <div class="col-lg-6 col-md-4 col-sm-12">
                <div class="box">
                  <div class="box-header">
                    <h3 class="box-title">{{ __('title.permission') }}</h3>
                  </div>
                  <!-- /.box-header -->
                  <div class="box-body no-padding">
                    <table class="table table-striped table-bordered">
                      <thead>
                          <tr>
                            <th>{{ __('app.module') }}</th>
                            <th style="width: 40px">{{ __('app.view') }}</th>
                            <th style="width: 40px">{{ __('app.create') }}</th>
                            <th style="width: 40px">{{ __('app.edit') }}</th>
                            <th style="width: 40px">{{ __('app.delete') }}</th>
                            <th style="width: 40px">{{ __('app.export') }}</th>
                            <th style="width: 40px">{{ __('app.import') }}</th>
                          </tr>
                        </thead>
                        <tbody>
                          @foreach($permissions as $key => $item)
                          <tr>
                            <input type="hidden" name="modules[]" value="{{ $item->module_id }}">
                            <td>{{ $item->module->description }}</td>
                            <td>    
                              <input type="hidden" name="view_array[{{$key}}]" value="off" />               
                              <input name="view_array[{{$key}}]" type="checkbox" value="on" class="flat-red" @if($item->is_view) checked @endif>
                            </td>
                            <td>                
                              <input type="hidden" name="create_array[{{$key}}]" value="off" />   
                              <input name="create_array[{{$key}}]" type="checkbox" value="on" class="flat-red" @if($item->is_create) checked @endif>
                            </td>                      
                            <td>                
                              <input type="hidden" name="edit_array[{{$key}}]" value="off" />   
                              <input name="edit_array[{{$key}}]" type="checkbox" value="on" class="flat-red" @if($item->is_edit) checked @endif>
                            </td>                      
                            <td>                
                              <input type="hidden" name="delete_array[{{$key}}]" value="off" />   
                              <input name="delete_array[{{$key}}]" type="checkbox" value="on" class="flat-red" @if($item->is_delete) checked @endif>
                            </td>
                            <td> 
                              <input type="hidden" name="export_array[{{$key}}]" value="off" />   
                              <input name="export_array[{{$key}}]" type="checkbox" value="on" class="flat-red" @if($item->is_export) checked @endif>
                            </td>
                            <td>
                              <input type="hidden" name="import_array[{{$key}}]" value="off" />
                              <input name="import_array[{{$key}}]" type="checkbox" value="on" class="flat-red" @if($item->is_import) checked @endif>
                            </td>
                          </tr>                  
                          @endforeach   
                        </tbody>
                    </table>
                  </div>
                  <!-- /.box-body -->
                </div>

              </div>                  
            </div>

          </div>

          <div class="box-footer">
            <a href="{{route('role.index')}}" class="btn btn-default">{{ __('title.cancel') }}</a>&nbsp;&nbsp;&nbsp;
            @if(Auth::user()->allowEdit(config('global.modules.role')))
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