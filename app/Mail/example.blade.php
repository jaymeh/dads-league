@component('mail::message')
# Pick your team for {{ $week }}

@component('mail::panel')
	Please pick your team for this week by clicking the link below and selecting from the available options.
@endcomponent

@component('mail::promotion')
	A promotion would go here.
@endcomponent

@component('mail::promotion.button', ['url' => $url])
	A promotion button would go here.
@endcomponent

@component('mail::subcopy')
	Some subcopy
@endcomponent

@component('mail::table')
	A table with some stuff.
@endcomponent

@component('mail::button', ['url' => $url])
Pick My Team
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent