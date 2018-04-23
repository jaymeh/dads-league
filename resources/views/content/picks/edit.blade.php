@extends('layouts.app')

@section('content')
    {{-- {{ dd($all_teams) }} --}}
    <vue-loader namespace="teams" :value="{{ $all_teams->toJson() }}"></vue-loader>
    <vue-loader namespace="picks" :value="{{ $previous_picks_by_player->toJson() }}"></vue-loader>

	<section class="hero is-primary">
        <div class="hero-body">
            <div class="container">
                <h1 class="title">
                    Picks - {{ $the_game_date->format('d/m/Y') }}
                </h1>
            </div>
        </div>
    </section>
    
    <div class="container">
        <div class="columns is-marginless is-centered is-multiline">
            <div class="column is-12">
                @if(Session::has('message'))
                    @include('partials.message')
                @endif
            </div>
        </div>
    
        <div class="columns is-centered is-multiline" v-cloak>
            <div class="column is-12">
                <form action="{{ route('picks.store') }}" method="POST">
                    {{ csrf_field() }}
                    <input type="hidden" name="game_date" value="{{ $the_game_date->format('Y-m-d') }}">
                    <div class="columns is-centered is-multiline">
                        @foreach($players_with_picks as $player)
                            <div class="column is-one-third-tablet is-one-quarter-desktop">
                                @include('partials.picks.pick-card')
                            </div>
                        @endforeach
                    </div>
                    <div class="level flex-mobile">
                        <div class="level-left"></div>
                        <div class="field">
                            <div class="control">
                                <button class="button is-primary">
                                    <span class="icon">
                                        <i class="fas fa-save"></i>
                                    </span>
                                    <span>Save</span>
                                </button>
                            </div>
                        </div>
                        <div class="level-right"></div>
                    </div>
                </form>
            </div>
        </div>
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
                                @foreach($league_with_teams->fixtures as $teams)
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