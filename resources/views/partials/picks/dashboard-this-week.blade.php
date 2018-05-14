<h2 class="is-size-3 has-text-centered">Teams for <strong>{{ $picks_game_date }}</strong></h2>

<table class="table is-fullwidth is-striped mt">
	<thead>
		<tr>
			<td>Player Name</td>
			<td class="has-text-right">Team</td>
		</tr>
	</thead>
	<tbody>
		@foreach($weekly_picks as $pick)

			<tr>
				<td><strong>{{ $pick->player->name }}</strong></td>
				<td class="has-text-right">{{ $pick->team->name }}</td>
			</tr>

		@endforeach
		
	</tbody>
</table>