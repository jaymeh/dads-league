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

	        	@include('partials.message')

	        	<table class="table is-fullwidth is-hoverable is-striped is-bordered">
	        		<thead>
	        			<tr>
	        				<th>Name</th>
	        				<th>Email</th>
	        				<th>&nbsp;</th>
	        			</tr>
	        		</thead>
	        		<tbody>
	        			@foreach($players as $player)
		        			<tr>
		        				<td>{{ $player->name }}</td>
		        				<td>{{ $player->email }}</td>
		        				<td>
		        					<div class="is-pulled-right">
			        					<a class="button is-small is-rounded is-primary" href="{{ route('players.edit', $player) }}">
			        						<span class="icon is-small">
		      									<i class="fas fa-pencil-alt"></i>
		    								</span>
		    								<span>Edit</span>
		    							</a>
		    							<a class="button is-small is-rounded is-danger" href="#" style="cursor:pointer;" onclick="$(this).find('form').submit();">
		    							
			        						<span class="icon is-small">
		      									<i class="fas fa-trash"></i>
		    								</span>
			        						<span>Delete</span>
			        						<form action="{{ route('players.destroy', $player) }}" method="POST" name="delete_item" style="display:none">
											   <input type="hidden" name="_method" value="DELETE">
											   {{ csrf_field() }}
										</form>
			        					</a>
			        				</div>
		        				</td>
		        			</tr>
		        		@endforeach
	        		</tbody>
	        	</table>

	        	<div class="level">
	        		<div class="level-left"></div>
	        		<a class="button is-link is-rounded" href="{{ route('players.create') }}">
	        			<span class="icon">
							<i class="fas fa-user-plus"></i>
						</span>
	        			<span>Add Player</span>
	        		</a>
		        	<div class="level-right"></div>
	        	</div>

	        </div>
	    </div>
    </div>
@endsection