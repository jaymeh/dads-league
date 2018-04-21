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

	<div class="section">
		<div class="container">
			@if($this_week_selection)
				<div class="box">
					<article class="media">
						<div class="media-content">
							<div class="content">
								<h2>Picks - <a href="{{ route('picks.edit', $this_week_selection) }}">This Week</a></h2>
								<div class="columns is-multiline is-mobile no-mb">
									<div class="column">
										No picks for this week have been set.
									</div>
								</div>
							</div>
						</div>
					</article>
				</div>
			@endif
			@foreach($picks_by_date as $date => $picks)
				@include('partials.picks.list-item')
			@endforeach
		</div>
	</div>
@endsection