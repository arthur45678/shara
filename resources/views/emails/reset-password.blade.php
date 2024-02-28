<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1"> 


</head>
<body>
		<p>@lang('emails.change_request')</p>
		<p>@lang('emails.reset_here')</p> 
		<a href="{{config('app.url') . '/'.$localization.'/reset-password/' .$email. '/' .$token}}">@lang('emails.click_to_reset')</a>
</body>
</html>