@extends('layouts.app')

@section('content')
	<section class="hero is-primary">
        <div class="hero-body">
            <div class="container">
                <h1 class="title">
                    Picks
                </h1>
            </div>
        </div>
    </section>

	<div class="section">
		<div class="container">
			@foreach($fixtures_by_player_teams as $date => $fixture)
				@include('partials.picks.list-item')
			@endforeach
		</div>
	</div>
@endsection