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
			<table class="table is-fullwidth is-striped">
				<thead>
					<tr>
						<td>Player Name</td>
						<td class="has-text-centered">Points</td>
						<td class="has-text-centered">W / D / L</td>
					</tr>
				</thead>
				<tbody>
					@foreach($league_table as $player_name => $scores)

						<tr>
							<td><strong>{{ $player_name }}</strong></td>
							<td class="has-text-centered"><strong>{{ $scores->score }}</strong></td>
							<td class="has-text-centered">{{ $scores->wins }} / {{ $scores->draws }} / {{ $scores->losses }}</td>
						</tr>

					@endforeach
					
				</tbody>
			</table>
		</div>
	</section>
	
@endsection