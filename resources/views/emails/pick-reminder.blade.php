@component('mail::message')
# Pick your team for {Week}

Please pick your team for this week by clicking the link below and selecting from the available options.

@component('mail::button', ['url' => $url])
Pick My Team
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent