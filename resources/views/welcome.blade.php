@extends('layouts.app')

@section('content')

	<div class="container">
		<section class="section">
	        <div class="columns">
	            <div class="column is-full-desktop">
	            	<div class="box">
	               		@include('partials.league.results')
	                </div>
	            </div>
	        </div>
		</section>
		
		<section class="section">
		        <div class="columns">
		            <div class="column is-half-desktop">
		            	<div class="box">
		               		@include('partials.picks.dashboard-last-week')
		                </div>
		            </div>
		        	<div class="column is-half-desktop">
		            	<div class="box">
		               		@include('partials.picks.dashboard-this-week')
		                </div>
		            </div>
		        </div>
	    </section>
	</div>

@endsection