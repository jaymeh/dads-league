@component('mail::message')
<div style="text-align: center;">
	<img style="max-width: 100px; " src="{{ url('/assets/img/branding/logo/logo.png') }}" alt="Logo for Shelley Football League" />
</div>
<h1 style="margin-top: 35px;">It's time to pick a new season</h1>
@component('mail::promotion')
Its time to pick a new season and add it to the site.
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent