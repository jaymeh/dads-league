@extends('layouts.app')

@section('content')
    {{-- {{ dd($all_teams) }} --}}
    <vue-loader namespace="teams" :value="{{ $all_teams->toJson() }}"></vue-loader>
    <vue-loader namespace="picks" :value="{{ $previous_picks_by_player->toJson() }}"></vue-loader>

	<section class="hero is-primary">
        <div class="hero-body">
            <div class="container">
                <h1 class="title">
                    Picks - {{ $this_saturday->format('d/m/Y') }}
                </h1>
            </div>
        </div>
    </section>
    
    <div class="container">
        <section class="section">
            <div class="columns is-marginless is-centered is-multiline">
                <div class="column is-12">
                    @include('partials.message')
                </div>

                @foreach($players_with_picks as $player)

                    <div class="column is-3">
                        <div class="card">
                            <div class="card-image text-center">
                                <player-picked-team-image :player-id="{{ $player->id }}"></player-picked-team-image>
                            </div>
                        <div class="card-content">
                            <div class="media">
                                <div class="media-content text-center">
                                    <p class="title is-4">{{ $player->name }}</p>
                                </div>
                            </div>

                            @if($player->picks)
                                <div class="content">
                                    <team-picker :player-id="{{ $player->id }}"></team-picker>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                @endforeach

            </div>
        </section>
        <section class="section">
            @if($leagues_with_teams)
                <div class="columns is-centered is-multiline">
                    @foreach($leagues_with_teams as $league_with_teams)
                        <div class="column is-half is-3">
                            <div class="text-center">
                                <img src="{{ $league_with_teams->logo }}" alt="{{ $league_with_teams->name }} logo" width="100" />
                            </div>
                             <table class="team-picks-table">
                                <tbody>
                                    @foreach($league_with_teams->availableTeams as $teams)
                                        <tr :team-id="{{ $teams->homeTeam->id }}" is="team-row"></tr>

                                        <tr :team-id="{{ $teams->awayTeam->id }}" is="team-row"></tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endforeach
                </div>
            @endif
        </section>
    </div>

    

    {{-- <div class="container">
        @if($leagues_with_teams)
            <div class="columns is-marginless is-centered">
                <div class="column is-three-quarters-tablet">
                    @foreach($leagues_with_teams as $league_with_teams)
                        <div class="level flex-mobile">
                            <div class="level-left"></div>
                            <img src="{{ $league_with_teams->logo }}" alt="{{ $league_with_teams->name }} logo"/>
                            <div class="level-right"></div>
                        </div>
                        
                        <table class="table is-fullwidth is-hoverable is-striped">
                            <thead>
                                <tr>
                                    <th class="text-center">&nbsp;</th>
                                    <th class="text-center">Home Team</th>
                                    <th class="text-center">&nbsp;</th>
                                    <th class="text-center">Away Team</th>
                                    <th class="text-center">&nbsp;</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($league_with_teams->availableTeams as $teams)
                                    <tr>
                                        <td class="text-center"><img class="logo-small" src="{{ $teams->homeTeam->logo }}" alt="{{ $teams->homeTeam->name }} logo" /></td>
                                        <td class="text-center team-name">{{ $teams->homeTeam->name }}</td>
                                        <td class="text-center">v</td>
                                        <td class="text-center team-name">{{ $teams->awayTeam->name }}</td>
                                        <td class="text-center"><img class="logo-small" src="{{ $teams->awayTeam->logo }}" alt="{{ $teams->homeTeam->name }} logo" /></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endforeach
                </div>
            @endif
        </div>
	</div> --}}
@endsection