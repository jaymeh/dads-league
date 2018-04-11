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
	        	<div class="level">
	        		<div class="level-left"></div>
		        	<div class="level-right">
		        		<a class="button is-link is-rounded" href="{{ route('players.create') }}">Add Player</a>
		        	</div>
	        	</div>

	        	<table class="table is-fullwidth is-hoverable is-striped is-bordered">
	        		<thead>
	        			<tr>
	        				<th>Name</th>
	        				<th>Email</th>
	        				<th>&nbsp;</th>
	        			</tr>
	        		</thead>
	        		<tbody>
	        			<tr>
	        				<td>A name</td>
	        				<td>An email</td>
	        				<td>
	        					<div class="is-pulled-right">
		        					<a class="button is-small is-rounded is-primary" href="#">
		        						<span class="icon is-small">
	      									<i class="fas fa-pencil-alt"></i>
	    								</span>
	    								<span>Edit</span>
	    							</a>
		        					<a class="button is-small is-rounded is-danger" href="#">
		        						<span class="icon is-small">
	      									<i class="fas fa-trash"></i>
	    								</span>
		        						<span>Delete</span>
		        					</a>
		        				</div>
	        				</td>
	        			</tr>
	        		</tbody>
	        	</table>
	        </div>
	    </div>
    </div>
@endsection