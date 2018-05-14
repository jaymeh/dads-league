<h2 class="is-size-3 has-text-centered">Season: <strong>{{ $season->name }}</strong></h2>
<table class="table is-fullwidth is-striped mt">
	<thead>
		<tr>
			<td>Player Name</td>
			<td class="has-text-centered">Points</td>
			<td class="has-text-right">W / D / L</td>
		</tr>
	</thead>
	<tbody>
		@foreach($league_table as $player_name => $scores)

			<tr>
				<td><strong>{{ $player_name }}</strong></td>
				<td class="has-text-centered"><strong>{{ $scores->score }}</strong></td>
				<td class="has-text-right">{{ $scores->wins }} / {{ $scores->draws }} / {{ $scores->losses }}</td>
			</tr>

		@endforeach
		
	</tbody>
</table>