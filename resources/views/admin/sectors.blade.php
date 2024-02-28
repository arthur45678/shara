@extends('admin/app_admin')
@section('content')
<link href="/css/admin/sectors.css" rel="stylesheet" type="text/css" />
	<div class="row-fluid">
        <div class="span12">
        	<a href="{{url('/admin/create-sector')}}" type="button" class="btn btn-success create-button">Create Industry</a>
            <div class="portlet box blue-chambray">
                <div class="portlet-title">
                    <div class="caption"><i class="icon-users"></i>Industries</div>          
                </div>
                <div class="portlet-body no-more-tables">
                    @if (count($sectors) > 0)
                    <div class="table-responsive">
                    <table class="table table-bordered table-hover responsive-table">
                            <thead>
                                <tr>
                                    <th> # </th>
                                    <th>Name</th>
                                    <th>Translations</th>
                                    <th></th>
                                    <th>Action</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($sectors as $key => $sector)
                                    <tr>
                                        <td>{{$key}}</td>
                                        <td>{{$sector->sectorName}}</td>
                                        <td>
                                        	@if(count($sector->translations) > 0)
                                                @foreach($sector->translations as $vkey => $translation)
                                                    
                                                            {{$vkey}} - {{$translation}}
                                                   
                                                @endforeach
                                            @else
                                                None
                                            @endif
                                        </td>
                                        
                                        <td>
                                        <a href="{{URL::to('/admin/show-sector',$sector->sector_id)}}" type="button" class="btn btn-info" title="Show">
                                            <i class="fa fa-book"></i></a>
                                        </td>
                                        <td>
                                        <a href = "{{URL::to('/admin/edit-sector',$sector->sector_id)}}" type="button" class="btn btn-primary" title="Update">
                                        	<i class="fa fa-edit"></i></a>
                                        </td>
                                        <td>
                                        <a type="button" class="btn btn-danger show_modal_sector" data-toggle="modal" data-target = "#sector_delete" href="#small" alt="{{$sector->sector_id}}" title="Delete">
                                        	<i class="fa fa-trash-o"></i></a>
                                        </td>      
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        </div>
                        <span class="col-md-offset-5">{{$sectors->links()}}</span>
                    @else
                        <h3>No Industires</h3>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade bs-modal-sm" id="sector_delete" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Delete Sector </h4>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button>
                    <a href=""  class="delete_sector"><button type="button" class="btn red">Delete</button></a>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

@endsection