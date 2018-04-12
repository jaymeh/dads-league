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
                        <p class="card-header-title">Create Player</p>
                    </header>

                    <div class="card-content">
                    	<div class="field is-horizontal">
                            <div class="field-label">
                                <label class="label"></label>
                            </div>
                            <div class="field-body">
                            	<p>Fill out the form below and press submit to add a new player to the league.</p>
                            </div>
                        </div>

                    	
                        <form class="player-create-form" method="POST" action="{{ route('players.create.post') }}">
                            {{ csrf_field() }}

                            <div class="field is-horizontal">
                                <div class="field-label">
                                    <label class="label">Name</label>
                                </div>

                                <div class="field-body">
                                    <div class="field">
                                        <p class="control">
                                            <input class="input {{ $errors->has('name') ? 'is-danger' : '' }}" id="name" type="text" name="name"
                                                   value="{{ old('name') }}" autofocus>
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
                                            <input class="input {{ $errors->has('name') ? 'is-danger' : '' }}" id="email" type="email" name="email"
                                                   value="{{ old('email') }}" autofocus>
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
    										    <span>Create</span>
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