@extends('layouts.app')

@section('content')
	<section class="hero is-primary">
        <div class="hero-body">
            <div class="container">
                <h1 class="title">
                    No Fixtures
                </h1>
            </div>
        </div>
    </section>

	<section>
    <div class="container">
        <div class="columns is-marginless is-centered is-multiline">
            <div class="column is-12">
            	<div class="notification is-warning">
  					Sorry but no fixtures have been found this week. Please check back again soon.
				</div>
            </div>
        </div>
    </div>
@endsection