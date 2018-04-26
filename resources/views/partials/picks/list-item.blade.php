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
                            @foreach($fixture as $fixture)
                                <tr class="{{ $fixture->playerTeam->first()->gameStatusClass }}">
                                	<td class="has-text-centered">
                                		@if($fixture->playerTeam->first()->team_id == $fixture->home_team_id)
                                			<strong>{{ $fixture->playerTeam->first()->player->name }}</strong>
                                		@endif
                                	</td>
                                    <td class="has-text-centered team-name">{{ $fixture->homeTeam->name }}</td>
                                    
                                    	@if($fixture->game)
	                                    	<td class="has-text-centered">
												<strong>{{ $fixture->game->home_team_score }}</strong>
											</td>
                                    	@else
                                    		<td></td>
                                    	@endif
                                    </td>
                                    <td class="has-text-centered">v</td>
                                    <td class="has-text-centered">
                                    	@if($fixture->game)
											<strong>{{ $fixture->game->away_team_score }}</strong>
                                    	@endif
                                    </td>
                                    <td class="has-text-centered team-name">{{ $fixture->awayTeam->name }}</td>
                                    <td class="has-text-centered">
                                    	@if($fixture->playerTeam->first()->team_id == $fixture->away_team_id)
                                			<strong>
                                				{{ $fixture->playerTeam->first()->player->name }}
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