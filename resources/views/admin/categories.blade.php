@extends('admin/app_admin')
@section('content')
<link href="/css/admin/categories.css" rel="stylesheet" type="text/css" />
	<div class="row-fluid">
        <div class="span12">
        	<a href="{{url('/admin/create-category')}}" type="button" class="btn btn-success create-button">Create Category</a>
            <div class="portlet box blue-chambray">
                <div class="portlet-title">
                    <div class="caption"><i class="icon-users"></i>Categories</div>          
                </div>
                <div class="portlet-body no-more-tables">
                    @if (count($categories) > 0)
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
                                @foreach ($categories as $key => $category)
                                    <tr>
                                        <td>{{$index = $key+1}}</td>
                                        <td>{{$category->categoryName}}</td>
                                        <td>
                                        	@if(count($category->translations) > 0)
                                                @foreach($category->translations as $vkey => $trans) 
                                                    
                                                            {{$vkey}} - {{$trans}}
                                                   
                                                @endforeach
                                            @else
                                                None
                                            @endif
                                        </td>
                                        
                                        <td>
                                        <a href="{{URL::to('/admin/show-category',$category->category_id)}}" type="button" class="btn btn-info" title="Show">
                                            <i class="fa fa-book"></i></a>
                                        </td>
                                        <td>
                                        <a href = "{{URL::to('/admin/edit-category',$category->category_id)}}" type="button" class="btn btn-primary" title="Update">
                                        	<i class="fa fa-edit"></i></a>
                                        </td>
                                        <td>
                                        <a type="button" class="btn btn-danger show_modal_category" data-toggle="modal" data-target = "#category_delete" href="#small" alt="{{$category->category_id}}" title="Delete">
                                        	<i class="fa fa-trash-o"></i></a>
                                        </td>      
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        </div>
                        <span class="col-md-offset-5">{{$categories->links()}}</span>
                    @else
                        <h3>No Categories</h3>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade bs-modal-sm" id="category_delete" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Delete Category </h4>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button>
                    <a href=""  class="delete_category"><button type="button" class="btn red">Delete</button></a>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

@endsection