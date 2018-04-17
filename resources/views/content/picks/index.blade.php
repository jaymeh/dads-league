@extends('layouts.app')

@section('content')
    
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
        <div class="columns is-marginless is-centered is-multiline">
            <div class="column is-12">
                @include('partials.message')
            </div>

            @foreach($players_with_picks as $player)

                <div class="column is-12">
                    <p>{{ $player->name }}</p>

                    @if($player->picks)

                        $player->picks->first()->id

                    @endif
                </div>

            @endforeach

        </div>
    </div>

    <team-picker :teams="{{ $all_teams->toJson() }}"></team-picker>

    @if($leagues_with_teams)
        <div class="container">
            <div class="columns is-centered is-multiline">
                @foreach($leagues_with_teams as $league_with_teams)
                    <div class="column is-half is-3">
                        <div class="text-center">
                            <img src="{{ $league_with_teams->logo }}" alt="{{ $league_with_teams->name }} logo" width="100" />
                        </div>
                         <table class="team-picks-table">
                                <tbody>
                                    @foreach($league_with_teams->availableTeams as $teams)
                                        <tr class="team-{{ $teams->homeTeam->id }}">
                                            <td class="text-center team-logo"><img class="logo-small" src="{{ $teams->homeTeam->logo }}" alt="{{ $teams->homeTeam->name }} logo" /></td>
                                            <td>{{ $teams->homeTeam->name }}</td>
                                            
                                        </tr>
                                        <tr class="team-{{ $teams->awayTeam->id }}">
                                            <td class="text-center team-logo"><img class="logo-small" src="{{ $teams->awayTeam->logo }}" alt="{{ $teams->homeTeam->name }} logo" /></td>
                                            <td>{{ $teams->awayTeam->name }}</td>
                                        </tr>
                                    @endforeach
                            </tbody>
                        </table>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

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