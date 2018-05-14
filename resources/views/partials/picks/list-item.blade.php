<div class="box">
	<article class="media">
		<div class="media-content">
			<div class="content">
				<h2>Picks - <a href="">{{ $date }}</a></h2>
				
				<div class="columns is-multiline is-mobile no-mb">
					<table class="table is-fullwidth fixture-table">
                        <thead>
                            <tr>
                            	<th class="has-text-centered">Player</th>
                                <th class="has-text-centered">Home</th>
                                <th class="has-text-centered">&nbsp;</th>
                                <th class="has-text-centered">&nbsp;</th>
                                <th class="has-text-centered">&nbsp;</th>
                                <th class="has-text-centered">Away</th>
                                <th class="has-text-centered">Player</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($player_teams as $player_team)
                                <tr class="{{ $player_team->gameStatusClass }}">
                                	<td class="has-text-centered person-name">
                                		@if($player_team->team_id == $player_team->fixture->home_team_id)
                                			<strong>{{ $player_team->player->name }}</strong>
                                		@endif
                                	</td>
                                    <td class="has-text-centered team-name">{{ $player_team->fixture->homeTeam->name }}</td>
                                    
                                    	@if($player_team->fixture->game)
	                                    	<td class="has-text-centered">
												<strong>{{ $player_team->fixture->game->home_team_score }}</strong>
											</td>
                                    	@else
                                    		<td></td>
                                    	@endif
                                    </td>
                                    <td class="has-text-centered">v</td>
                                    <td class="has-text-centered">
                                    	@if($player_team->fixture->game)
											<strong>{{ $player_team->fixture->game->away_team_score }}</strong>
                                    	@endif
                                    </td>
                                    <td class="has-text-centered team-name">{{ $player_team->fixture->awayTeam->name }}</td>
                                    <td class="has-text-centered person-name">
                                    	@if($player_team->team_id == $player_team->fixture->away_team_id)
                                			<strong>
                                				{{ $player_team->player->name }}
                                			</strong>
                                		@endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
				</div>
			</div>
		</div>
	</article>
</div>