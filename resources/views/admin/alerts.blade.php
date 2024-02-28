@extends('admin/app_admin')
@section('content')
<link href="/css/admin/sectors.css" rel="stylesheet" type="text/css" />
    <div class="row-fluid">
        <div class="span12">
            <div class="portlet box blue-chambray">
                <div class="portlet-title">
                    <div class="caption"><i class="icon-users"></i>Alerts</div>          
                </div>
                <div class="portlet-body no-more-tables">
                    @if (count($alerts) > 0)
                    <div class="table-responsive">
                    <table class="table table-bordered table-hover responsive-table">
                            <thead>
                                <tr>
                                    <th> # </th>
                                    <th>Keyword</th>
                                    <th>Category</th>
                                    <th>Sector</th>
                                    <th>Country</th>
                                    <th>City</th>
                                    <th>Email</th>
                                    <th></th>
                                    <!-- <th>Action</th>
                                    <th></th> -->
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($alerts as $key => $alert)
                                    <tr>
                                        <td>{{$key +1}}</td>
                                        <td>@if($alert->keyword){{$alert->keyword}}@else N/A @endif</td>
                                        <td>@if($alert->category){{$alert->category->name}}@else N/A @endif</td>
                                        <td>@if($alert->sector){{$alert->sector->name}}@else N/A @endif</td>
                                        <td>@if($alert->country){{$alert->country}}@else N/A @endif</td>
                                        <td>@if($alert->city){{$alert->city}}@else N/A @endif</td>
                                        <td>@if($alert->email){{$alert->email}}@else N/A @endif</td>
                                        <td>
                                        <a type="button" class="btn btn-danger show_modal_alert" data-toggle="modal" data-target = "#delete-alert" href="#small" alt="{{$alert->id}}" title="Delete">
                                            <i class="fa fa-trash-o"></i></a>
                                        </td>    
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        </div>
                        <span class="col-md-offset-5">{{$alerts->links()}}</span>
                    @else
                        <h3>No Alerts</h3>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade bs-modal-sm" id="delete-alert" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Delete Alert </h4>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button>
                    <a href=""  class="delete_alert"><button type="button" class="btn red">Delete</button></a>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

@endsection

@section('scripts')
<script src="/js/delete_alert.js" type="text/javascript"></script>

@endsection
