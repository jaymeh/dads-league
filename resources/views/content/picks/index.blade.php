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

    <div class="container">

		<div class="columns is-marginless is-centered">
            <div class="column is-three-quarters-tablet">
                
            </div>

	        <div class="column is-three-quarters-tablet">

	        	@include('partials.message')


	        </div>
	    </div>
	</div>
@endsection