@extends('layouts.master')
@section('css')
<style type="text/css">
  
</style>
@endsection
@section('content')
<section class="content-header">
  <h1>
    {{ __('title.dashboard') }}
    <small>{{ __('title.list_users') }}</small>
  </h1>
  <ol class="breadcrumb">
    <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i>{{ __('title.dashboard') }}</a></li>
    <li class="active">{{ __('title.users') }}</li>
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
    <div class="col-lg-12 col-md-12">
          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title">{{ __('title.list_users') }}</h3>
              <!-- tools box - add new record-->
              @if(Auth::user()->allowCreate(config('global.modules.user')))
              <div class="pull-right box-tools">
                <a href="{{ route('user.create') }}" class="btn btn-primary btn-sm" data-widget="add-new" data-toggle="tooltip"
                        title="Add New"><i class="fa fa-plus"></i> {{ __('title.add_new') }}</a>
              </div>
              @endif
              <!-- /. tools -->              
            </div>
            <!-- /.box-header -->
            <div class="box-body table-responsive no-padding">
              <!-- form search -->
              <form action="{{ route('user.index') }}" method="GET">
                <div class="input-group input-group pull-right" style="width: 300px; padding: 10px">
                  <input type="text" name="search" value="{{Request::get('search')?:''}}" class="form-control" placeholder="{{ __('title.search') }}">
                  <span class="input-group-btn">
                    <button type="submit" class="btn btn-info btn-flat"><i class="fa fa-search"></i></button>
                    <a href="{{ route('user.index') }}" class="btn btn-danger btn-flat"><i class="fa fa-refresh"></i></a>
                  </span>
                </div>
              </form>
              <!-- end form search -->
              <table class="table table-striped table-hover">
                <thead>
                  <tr>
                    <th style="width: 10px">#</th>
                    <th style="width: 32px">{{ __('app.image') }}</th>
                    <th>{{ __('app.username') }}</th>
                    <th>{{ __('app.email') }}</th>
                    <th>{{ __('app.role') }}</th>
                    <th style="width: 40px">{{ __('app.action') }}</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($users as $key => $item)
                  <tr>
                    <td>{{ $key+1 }} </td>
                    <td><img class="img-table" src="{{ url($item->image_url ?: 'warning.png') }}"> </td>
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->email }}</td>
                    <td>{{ $item->role->role_name}}</td>
                    <td>
                      @if(Auth::user()->allowEdit(config('global.modules.user')))
                      <a href="{{ route('user.edit', $item->id) }}" title="Edit"><i class="fa fa-pencil"></i></a>
                      @endif

                      @if(Auth::user()->allowDelete(config('global.modules.user')))
                      <span style="padding: 5px">|</span><a href="#modelDelete_{{$item->id}}" data-toggle="modal" title="Remove"><i class="fa fa-trash text-danger"></i></a>
                      <div class="modal fade" id="modelDelete_{{$item->id}}" tabindex="-1" data-keyboard="false" data-backdrop="static" role="dialog" aria-hidden="true">
                        <div class="modal-dialog">
                          <div class="modal-content">
                            <div class="modal-header">
                              <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                              <h4 class="modal-title">{{ __('title.delete') }} - {{$item->name}}</h4>
                            </div>
                            <div class="modal-body">
                            {{ __('message.delete_confirmation') }}
                            <p class="margin"><i class="fa fa-warning"></i> @lang('message.delete_warning') </p>
                            </div>
                            <div class="modal-footer">
                              <form action="{{ route('user.delete', $item->id) }}" method="POST">
                                @csrf
                                <input type="hidden" name="_method" value="delete">
                                <button type="submit" class="btn btn-danger save-cancel">{{ __('title.yes_delete') }}</button>
                                <button type="button" class="btn btn-default save-cancel" data-dismiss="modal">{{ __('title.cancel') }}</button>
                              </form>
                            </div>
                          </div>
                        </div>
                      </div>
                      @endif
                    </td>
                  </tr>                  
                  @endforeach
                </tbody>                
              </table>              
            </div>
            <div class="box-footer clearfix">
              @include('layouts.pagination', ['data'=>$users])
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