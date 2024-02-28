@extends('admin/app_admin')
@section('content')
<link href="/css/admin/companies.css" rel="stylesheet" type="text/css" />
    <div class="row-fluid">
        <div class="span12">
            <div class="portlet box blue-chambray">
                <div class="portlet-title">
                    <div class="caption"><i class="icon-users"></i>Subsidiaries</div>          
                </div>
                <div class="portlet-body no-more-tables">
                <div class="create-subsidiary-buttons col-md-3">
                    <a type="button" class="btn btn-success" href="/admin/add-subsidiary/{{$company->id}}">Add New</a>
                    <a type="button" class="btn btn-success" href="">Clone from existing</a>
                </div>
                    @if (count($company->subsidiaries) > 0)
                    <div class="table-responsive">
                    <table class="table table-bordered table-hover responsive-table">
                            <thead>
                                <tr>
                                    <th> # </th>
                                    <th>Name</th>
                                    <th></th>
                                    <th></th>
                                    <th>Action</th>
                                    <th></th>

                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($company->subsidiaries as $key => $subsidiary)
                                    <tr>
                                        <td>{{$key + 1}}</td>
                                        <td>{{$subsidiary->country->name}}</td>  
                                        <td></td>
                                        <td></td>                                      
                                        <td>
                                        <a href = "" type="button" class="btn btn-primary" >
                                            <i class="fa fa-edit"></i> Update </a>
                                        </td>
                                        <td>
                                        <a type="button" data-toggle="modal" data-target = "#country_delete" href="#small" alt="{{$subsidiary->id}}" class="btn btn-danger show_modal_company">
                                            <i class="fa fa-trash-o"></i> Delete </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        </div>
                    @else
                        <h3>No Subsidiaries</h3>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <div class="modal fade bs-modal-sm" id="company_delete" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Delete Company </h4>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button>
                    <a href=""  class="delete_company"><button type="button" class="btn red">Delete</button></a>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

@endsection
	

			
            


