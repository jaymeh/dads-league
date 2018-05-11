@extends('layouts.app')

@section('content')
	

	<div class="section">
		<div class="container">
			<div class="columns is-marginless is-centered is-multiline">
	            <div class="column is-12">
	                @if(Session::has('message'))
	                    @include('partials.message')
	                @endif
	            </div>
	        </div>
			@foreach($player_team_by_date as $date => $player_teams)
				@include('partials.picks.list-item')
			@endforeach
		</div>
	</div>
@endsection