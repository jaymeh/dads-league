@extends('layouts.app')

@section('content')
	<section class="hero is-primary">
        <div class="hero-body">
            <div class="container">
                <h1 class="title">
                    League Table
                </h1>
            </div>
        </div>
    </section>
	
    <section class="section">
    	<div class="container">
    		@include('partials.league.results')
    	</div>
    </section>
	
@endsection