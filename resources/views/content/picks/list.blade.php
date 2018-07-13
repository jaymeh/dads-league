@extends('layouts.app')

@section('content')
	<section class="hero is-primary">
        <div class="hero-body">
            <div class="container">
                <h1 class="title">
                    Pick List
                </h1>
            </div>
        </div>
    </section>

    
    <section class="section">
    	<div class="container">
    		<div class="columns is-multiline">
    			@forelse($players as $player)
                    @if($player->picks->count())
                        <div class="column is-half-tablet is-one-third-desktop">
                            <table class="table is-striped is-fullwidth picks-table">
                                <thead>
                                    <th>Week</th>
                                    <th>&nbsp;</th>
                                    <th>{{ $player->name }}</th>
                                    <th>Date</th>
                                </thead>
                                <tbody>
                                    @foreach($player->picks as $i => $team)
                                        <tr>
                                            <td class="has-text-centered pick-week">{{ week_number($team->carbon_game_date) }}</td>
                                            <td class="has-text-centered">
                                                <img class="logo-small" src="{{ $team->logo }}" alt="{{ $team->name }}" />
                                            </td>
                                            <td>
                                                {{ $team->name }}
                                            </td>
                                            <td>
                                                {{ $team->formattedPickGameDate }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                @empty
                    <div class="column">
                        <p>There are currently no picks this week. Please check back later.</p>
                    </div>
                @endforelse
    		</div>
    	</div>
    </section>
    
@endsection