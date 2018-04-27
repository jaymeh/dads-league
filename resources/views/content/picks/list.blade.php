@extends('layouts.app')

@section('content')
	<section class="hero is-primary">
        <div class="hero-body">
            <div class="container">
                <h1 class="title">
                    Pick List
                </h1>
            </div>
        </div>
    </section>

	{{ 
		// TODO: Fix layout of columns and widths.
	}}
    <section class="section">
    	<div class="container">
    		<div class="columns is-multiline is-centered">
    			@foreach($players as $player)
    				<div class="column">
    					<table class="table is-striped">
    						<thead>
    							<th>Week</th>
    							<th>&nbsp;</th>
    							<th>{{ $player->name }}</th>
    							<th>Date</th>
    						</thead>
    						<tbody>
    							@foreach($player->picks as $i => $team)
	    							<tr>
	    								<td class="has-text-centered">{{ $weeks[$i] }}</td>
	    								<td class="has-text-centered">
	    									<img class="logo-small" src="{{ $team->logo }}" alt="{{ $team->name }}" />
	    								</td>
	    								<td>
	    									{{ $team->name }}
	    								</td>
	    								<td>
	    									{{ $team->formattedPickGameDate }}
	    								</td>
	    							</tr>
	    						@endforeach
    						</tbody>
    					</table>
    				</div>
    			@endforeach
    		</div>
    	</div>
    </section>
@endsection