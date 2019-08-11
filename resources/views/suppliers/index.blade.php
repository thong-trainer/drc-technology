@extends('layouts.master')
@section('css')
<style type="text/css">

</style>
@endsection
@section('content')
<section class="content-header">
  <h1>
    {{ __('title.dashboard') }}
    <small>{{ __('title.list_suppliers') }}</small>
  </h1>
  <ol class="breadcrumb">
    <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i>{{ __('title.dashboard') }}</a></li>
    <li class="active">{{ __('title.suppliers') }}</li>
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
              <h3 class="box-title">{{ __('title.list_suppliers') }}</h3>
              <!-- tools box - add new record-->
              @if(Auth::user()->allowCreate(config('global.modules.supplier')))
              <div class="pull-right box-tools">
                <a href="{{ route('supplier.create', ['type'=>'individual']) }}" class="btn btn-primary btn-sm" data-widget="add-new" data-toggle="tooltip"
                        title="Add New"><i class="fa fa-plus"></i> {{ __('title.add_new') }}</a>
              </div>
              @endif
              <!-- /. tools -->              
            </div>
            <!-- /.box-header -->
            <div class="box-body table-responsive no-padding">
              <!-- form search -->
              <form action="{{ route('supplier.index') }}" method="GET">
                <div class="m-container">
                  <div class="m-left">           
                    <div class="input-group" style="float: left">                                            
                      <select class="form-control" name="company">
                        <option value="">{{ __('app.company') }}</option>
                        @foreach($companies as $item)
                        <option value="{{ $item->id }}" @if(Request::get('company') == $item->id) selected @endif>{{ $item->company_name }}</option>
                        @endforeach
                      </select>
                    </div>     
                    <div class="input-group" style="float: left;">
                      <button type="submit" class="btn btn-default"><i class="fa fa-filter"></i> {{ __('title.filter') }}</button>
                    </div>
                  </div>
                  <div class="m-right">
                    <div class="input-group input-group pull-right" style="width: 300px;">
                      <input type="text" name="search" value="{{ Request::get('search')?:'' }}" class="form-control" placeholder="{{ __('title.search') }}">
                      <span class="input-group-btn">
                        <button type="submit" class="btn btn-info btn-flat"><i class="fa fa-search"></i></button>
                        <a href="{{ route('supplier.index') }}" class="btn btn-danger btn-flat"><i class="fa fa-refresh"></i></a>
                      </span>
                    </div>                    
                  </div>
                  <div id="center"></div>
                </div>
              </form>
              <!-- end form search -->
              <table class="table table-striped table-hover">
                <thead>
                  <tr>
                    <!-- <th style="width: 10px">#</th> -->
                    <th>{{ __('app.code') }}</th>
                    <th>{{ __('app.contact_name') }}</th>
                    <th>{{ __('app.gender') }}</th>
                    <th>{{ __('app.primary_telephone') }}</th>
                    <th>{{ __('app.company') }}</th>
                    <th style="width: 40px">{{ __('app.action') }}</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($suppliers as $key => $item)
                  <tr>
                    <!-- <td>{{ $key+1 }} </td> -->
                    <td>{{ $item->code }} </td>
                    <td>{{ $item->contact->contact_name }}</td>
                    <td>{{ $item->contact->gender }}</td>
                    <td>{{ $item->contact->primary_telephone }}</td>
                    <td>
                      @if($item->company)
                        <a href="{{ route('company.show', $item->company_id) }}" title="View Detail">{{ $item->company->company_name }} <i class="fa fa-external-link"></i></a>
                      @else
                        <a href="#" title="Nothing">N/A</a>
                      @endif                      
                    </td> 
                    <td>
                      @if(Auth::user()->allowEdit(config('global.modules.supplier')))
                      <a href="{{ route('supplier.edit', $item->id) }}" title="Edit"><i class="fa fa-pencil"></i></a>
                      @endif

                      @if(Auth::user()->allowDelete(config('global.modules.supplier')))
                      <span style="padding: 5px">|</span><a href="#modelDelete_{{$item->id}}" data-toggle="modal" title="Remove"><i class="fa fa-trash text-danger"></i></a>
                      <div class="modal fade" id="modelDelete_{{$item->id}}" tabindex="-1" data-keyboard="false" data-backdrop="static" role="dialog" aria-hidden="true">
                        <div class="modal-dialog">
                          <div class="modal-content">
                            <div class="modal-header">
                              <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                              <h4 class="modal-title">{{ __('title.delete') }} - {{ $item->contact->contact_name }}</h4>
                            </div>
                            <div class="modal-body">
                            {{ __('message.delete_confirmation') }}
                            </div>
                            <div class="modal-footer">
                              <form action="{{ route('supplier.delete', $item->id) }}" method="POST">
                                @csrf
                                <input type="hidden" name="_method" value="delete">
                                <button type="submit" class="btn btn-danger save-cancel">{{ __('title.delete') }}</button>
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
              @include('layouts.pagination', ['data'=>$suppliers])
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