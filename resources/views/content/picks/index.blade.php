@extends('layouts.app')

@section('content')
	<section class="hero is-primary">
        <div class="hero-body">
            <div class="container">
                <h1 class="title">
                    Picks this Season
                </h1>
            </div>
        </div>
    </section>

	<div class="section">
		<div class="container">
			<div class="columns is-marginless is-centered is-multiline">
	            <div class="column is-12">
	                @if(Session::has('message'))
	                    @include('partials.message')
	                @endif
	            </div>
	        </div>
			@forelse($player_team_by_date as $date => $player_teams)
				@include('partials.picks.list-item')
			@empty
				<p>There are currently no picks for this season so far. Please check back at a later date.</p>
			@endforelse
		</div>
	</div>
@endsection