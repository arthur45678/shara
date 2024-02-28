@extends('admin/app_admin')
@section('content')
<link href="/css/admin/sectors.css" rel="stylesheet" type="text/css" />
    <div class="row-fluid">
        <div class="span12">
            <div class="portlet box blue-chambray">
                <div class="portlet-title">
                    <div class="caption"><i class="icon-users"></i>Contacts</div>          
                </div>
                <div class="portlet-body no-more-tables">
                    @if (count($contacts) > 0)
                    <div class="table-responsive">
                    <table class="table table-bordered table-hover responsive-table">
                            <thead>
                                <tr>
                                    <th> # </th>
                                    <th>Company Name</th>
                                    <th>Email</th>
                                    <th>Location</th>
                                    <th>Web Site</th>
                                    <th></th>
                                    <!-- <th>Action</th>
                                    <th></th> -->
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($contacts as $key => $contact)
                                    <tr>
                                        <td>{{$key +1}}</td>
                                        <td>{{$contact->company_name}}</td>
                                        <td>{{$contact->email}}</td>
                                        <td>{{$contact->location}}</td>
                                        <td>{{$contact->web_stie}}</td>
                                        <td>
                                        <a type="button" class="btn btn-danger show_modal_contact" data-toggle="modal" data-target = "#contact-delete" href="#small" alt="{{$contact->id}}" title="Delete">
                                            <i class="fa fa-trash-o"></i></a>
                                        </td>    
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        </div>
                        <span class="col-md-offset-5">{{$contacts->links()}}</span>
                    @else
                        <h3>No Contacts</h3>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade bs-modal-sm" id="contact-delete" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Delete Contact </h4>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button>
                    <a href=""  class="delete-contact"><button type="button" class="btn red">Delete</button></a>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

@endsection

@section('scripts')
<script type="text/javascript">
    $(document).ready(function(){
        $(".show_modal_contact" ).click(function() {
            var id = $(this).attr('alt');
            $('.delete-contact').attr('href', '/admin/delete-contact/'+id);
                
        });
    })
</script>
@endsection