@component('mail::message')
# Pick your team for Week {{ $week }}
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