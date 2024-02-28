
@if(Session::has('error'))
<div class="alert alert-danger">
	{{Session::get('error')}}
</div>	
@endif

@if(Session::has('message'))
<div class="alert alert-success">
	{{Session::get('message')}}
</div>	
@endif

@if (Session::has('errors'))
<div class="col-sm-8">
    <div class="alert alert-danger">
       @foreach ($errors->all() as $error)
           {{ $error }}<BR>       
       @endforeach
   </div>
</div>
  <?php Session::forget('errors') ?>
@endif

@if(Session::has('error_danger'))
<div class="alert alert-danger">
	<button class="close" data-close="alert"></button>
	{{Session::get('error_danger')}}
</div>
@endif