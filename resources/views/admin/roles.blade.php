@extends('admin/app_admin')
@section('content')
<link href="/css/admin/roles.css" rel="stylesheet" type="text/css" />
	<div class="row-fluid">
        <div class="span12">
        	<a href="{{url('/admin/create-role')}}" type="button" class="btn btn-success create-button">Create Role</a>
            <div class="portlet box blue-chambray">
                <div class="portlet-title">
                    <div class="caption"><i class="icon-users"></i>Roles</div>          
                </div>
                <div class="portlet-body no-more-tables">
                    @if (count($roles) > 0)
                    <div class="table-responsive">
                    <table class="table table-bordered table-hover responsive-table">
                            <thead>
                                <tr>
                                    <th> # </th>
                                    <th>Name</th>
                                    <th>Permissions</th>
                                    <th></th>
                                    <th>Action</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($roles as $key => $role)
                                    <tr>
                                        <td>{{$key + 1}}</td>
                                        <td>{{$role->name}}</td>
                                        <td>
                                        	@if(count($role->permissions) > 0)
                                                @foreach($role->permissions as $key => $permission)
                                                    @if($permission == 'true')
                                                            {{$key}}
                                                        @if($key != count($role->permissions) - 1),
                                                        @endif
                                                    @endif
                                                @endforeach
                                            @else
                                                None
                                            @endif
                                        </td>
                                        
                                        <td>
                                        <a href="{{URL::to('/admin/show-role',$role->id)}}" type="button" class="btn btn-info" title="Show">
                                            <i class="fa fa-book"></i></a>
                                        </td>
                                        <td>
                                        <a href = "{{URL::to('/admin/edit-role',$role->id)}}" type="button" title="Update" @if($role->slug == 'superadmin') class = "btn btn-primary not-active" disabled @else class="btn btn-primary" @endif>
                                        	<i class="fa fa-edit"></i></a>
                                        </td>
                                        <td>
                                        <a type="button"  data-toggle="modal" data-target = "#role_delete" href="#small" alt="{{$role->id}}" data-slug = "{{$role->slug}}" title="Delete" @if($role->slug == 'superadmin') class = "btn btn-danger show_modal_role not-active" disabled @else class="btn btn-danger show_modal_role" @endif>
                                        	<i class="fa fa-trash-o"></i></a>
                                        </td>
                                        

                                        
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        </div>
                        <span class="col-md-offset-5">{{$roles->links()}}</span>
                    @else
                        <h3>No Roles</h3>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade bs-modal-sm" id="role_delete" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Delete Role </h4>
                </div>
                <div class="modal-inner">
                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button>
                    <a href=""  class="delete_role"><button type="button" class="btn red">Delete</button></a>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

@endsection