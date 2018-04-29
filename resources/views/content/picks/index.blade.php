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
			@foreach($fixtures_by_player_teams as $date => $fixture)
				@include('partials.picks.list-item')
			@endforeach
		</div>
	</div>
@endsection