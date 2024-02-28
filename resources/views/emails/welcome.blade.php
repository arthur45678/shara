<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1"> 


</head>
<body>
		<p>@lang('emails.welcome_to_sharado')</p>
		@lang('emails.go_to') <a href="{{config('app.url') . '/email/welcome/' . $user_id}}">@lang('emails.sharado_log_in')</a> @lang('emails.search_for_opportunities')
</body>
</html>