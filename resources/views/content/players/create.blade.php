@extends('layouts.app')

@section('content')
	<section class="hero is-primary">
        <div class="hero-body">
            <div class="container">
                <h1 class="title">
                    Players
                </h1>
            </div>
        </div>
    </section>

    <div class="container">
    	<div class="columns is-marginless is-centered">
            <div class="column is-three-quarters-tablet">
            	
    		    <div class="card">
    		    	<header class="card-header">
                        <p class="card-header-title">{{ isset($player) ? 'Edit' : 'Create' }} Player</p>
                    </header>

                    <div class="card-content">
                    	<div class="field is-horizontal">
                            <div class="field-label">
                                <label class="label"></label>
                            </div>
                            <div class="field-body">
                                @if(isset($player))
                                    <p>Edit form below and press submit to update the existing player.</p>
                                @else
                            	   <p>Fill out the form below and press submit to add a new player to the league.</p>
                                @endif
                            </div>
                        </div>

                    	
                        <form class="player-create-form" method="POST" action="{{ isset($player) ? route('players.update', $player->id) : route('players.store') }}">
                            {{ csrf_field() }}

                            @if(isset($player))
                                <input type="hidden" name="_method" value="PUT">
                            @endif

                            <div class="field is-horizontal">
                                <div class="field-label">
                                    <label class="label">Name</label>
                                </div>

                                <div class="field-body">
                                    <div class="field">
                                        <p class="control">
                                            <input class="input {{ $errors->has('name') ? 'is-danger' : '' }}" id="name" type="text" name="name"
                                                   value="{{ isset($player) ? $player->name : old('name') }}" autofocus>
                                        </p>

                                        @if ($errors->has('name'))
                                            <p class="help is-danger">
                                                {{ $errors->first('name') }}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="field is-horizontal">
                                <div class="field-label">
                                    <label class="label">E-Mail</label>
                                </div>

                                <div class="field-body">
                                    <div class="field">
                                        <p class="control">
                                            <input class="input {{ $errors->has('email') ? 'is-danger' : '' }}" id="email" type="email" name="email"
                                                   value="{{ isset($player) ? $player->email : old('email') }}" autofocus>
                                        </p>

                                        @if ($errors->has('email'))
                                            <p class="help is-danger">
                                                {{ $errors->first('email') }}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="field is-horizontal">
                                <div class="field-label"></div>

                                <div class="field-body">
                                    <div class="field is-clearfix">
                                        <div class="is-pulled-left">
                                            <button type="submit" class="button is-primary">
    	                                        <span class="icon">
    										    	<i class="fas fa-file"></i>
    										    </span>
                                                @if(isset($player))
                                                    <span>Update</span>
                                                @else
                                                    <span>Create</span>
                                                @endif
    	                                    </button>
                                        </div>

                                        <div class="is-pulled-right">
                                            <a href="{{ route('players.index') }}" class="button is-danger">
                                            	<span class="icon">
    										    	<i class="fas fa-times"></i>
    										    </span>
    										    <span>Cancel</span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
    		    </div>
    		</div>
    	</div>
    </div>
@endsection