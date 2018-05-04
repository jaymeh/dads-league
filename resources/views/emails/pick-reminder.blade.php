@component('mail::message')
<div style="text-align: center;">
	<img style="max-width: 100px; " src="{{ url('/assets/img/branding/logo/logo.png') }}" alt="Logo for Shelley Football League" />
</div>
<h1 style="margin-top: 35px;">Pick your team for Week {{ $week }}</h1>
@component('mail::promotion')
Please pick your team for this week by clicking the link below and selecting from the available options.
@component('mail::button', ['url' => $token_link])
Pick My Team
@endcomponent
You can pick a team until <strong>10:00am</strong> on <strong>{{ $date }}</strong>. 
If a team is not added by then your score will not be counted.
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent