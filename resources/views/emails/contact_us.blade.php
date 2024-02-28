<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1"> 


</head>
<body>
		<p>@lang('emails.company_name') : {{$company_name}}</p>
		<p>@lang('emails.email') : {{$email}}</p>
		@if(isset($location))<p>@lang('emails.location') : {{$location}}</p>@endif
		@if(isset($web_site))<p>@lang('emails.web_site') : {{$web_site}}</p>@endif
		<p>@lang('emails.message') : {{$mailMessage}}</p>
</body>
</html>