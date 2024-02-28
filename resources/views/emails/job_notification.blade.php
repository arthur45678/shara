<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1"> 


</head>
<body>
		<p>@lang('emails.job_matches')</p>
		<a href="{{config('app.url').$jobUrl}}">@lang('emails.click_here')</a> @lang('emails.to_view')<br />
		<a class="btn btn-primary" href="{{config('app.url').'/email/remove-alert/'.$alertId}}">@lang('emails.deactivate') </a>

</body>
</html>