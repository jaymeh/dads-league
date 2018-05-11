@extends('layouts.app')

@section('content')
	{{-- {{ dd($all_teams) }} --}}
	<vue-loader namespace="teams" :value="{{ $all_teams->toJson() }}"></vue-loader>
    <vue-loader namespace="fixtures" :value="{{ $fixtures->toJson() }}"></vue-loader>

	<div class="container">
        <div class="columns is-marginless is-centered is-multiline">
            <div class="column is-12">
                @if(Session::has('message'))
                    @include('partials.message')
                @endif
            </div>
        </div>

        <form action="{{ route('picks.store') }}" method="POST">
            {{ csrf_field() }}
            <input type="hidden" name="game_date" value="{{ $fixture_date->format('Y-m-d') }}">
            <input type="hidden" name="player_token" value="{{ $token }}">

			<div class="columns is-marginless is-centered">
				<div class="column is-half-tablet is-one-third-desktop">
					@include('partials.picks.pick-card')
				</div>
			</div>
        </form>
    </div>

    <section class="section">
    	<div class="container">
	    	@foreach($grouped_fixtures as $league)
	    		<div class="text-center">
					<img src="{{ $league->logo }}" />
				</div>
				
				<div class="columns is-marginless is-centered is-multiline">
					<div class="column">
						
						<table class="table is-fullwidth is-striped fixture-table">
                            <thead>
                                <tr>
                                    <th class="text-center is-hidden-mobile">&nbsp;</th>
                                    <th class="text-center">Home</th>
                                    <th class="text-center">&nbsp;</th>
                                    <th class="text-center">Away</th>
                                    <th class="text-center is-hidden-mobile">&nbsp;</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($league->fixtures as $fixture)
                                    <tr class="{{ $fixture->disabled ? 'disabled' : '' }}">
                                        <td class="text-center is-hidden-mobile team-logo {{ $fixture->homeTeam->disabled ? 'disabled' : '' }}">
                                            <img class="logo-small" src="{{ $fixture->homeTeam->logo }}" alt="{{ $fixture->homeTeam->name }} logo" />
                                        </td>
                                        <td class="text-center team-name {{ $fixture->homeTeam->disabled ? 'disabled' : '' }}">{{ $fixture->homeTeam->name }}</td>
                                        <td class="text-center">v</td>
                                        <td class="text-center team-name {{ $fixture->awayTeam->disabled ? 'disabled' : '' }}">{{ $fixture->awayTeam->name }}</td>
                                        <td class="text-center is-hidden-mobile team-logo {{ $fixture->awayTeam->disabled ? 'disabled' : '' }}">
                                            <img class="logo-small" src="{{ $fixture->awayTeam->logo }}" alt="{{ $fixture->homeTeam->name }} logo" />
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
					</div>
				</div>
	    	@endforeach
    	</div>
    </section>
@endsection