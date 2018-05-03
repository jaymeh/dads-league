@component('mail::message')
<div style="text-align: center;">
	<img style="max-width: 200px; " src="{{ url('/assets/img/branding/logo/logo.png') }}" alt="Logo for Shelley Football League" />
</div>
<h1 style="margin-top: 35px;">Results for <strong>{{ $results->first()->game_date->format('l dS F Y') }}</strong></h1>
<h2>League Table</h2>
@component('mail::table')
	|Player|W / D / L|Score|
	| :--- | :---: | ---: |
	@foreach($table as $player_name => $result)
		| {{ $player_name }} | {{ $result->wins }} / {{ $result->draws }} / {{ $result->losses }} | {{ $result->score }} | 
	@endforeach
@endcomponent

<h2>Last Weeks Result</h2>
@component('mail::table')
	|Player|Team Picked|Result|
	|:--- | :---: | ---: |
	@foreach($results as $key => $result)
		| {{ $result->player->name }} | {{ $result->team->name }} | {{ $result->gameStatus }} |
	@endforeach
@endcomponent

@endcomponent