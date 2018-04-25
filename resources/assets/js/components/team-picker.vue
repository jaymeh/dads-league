<template>
	<div>
		<div class="field">
			<div class="control">
				<div class="select" v-bind:class="{ 'is-danger': error }">
					<select id="team-select" v-model="selectedTeam">
						<option>Please Select...</option>
						<optgroup v-for="(league, key) in teams" :label="league.key" :key="key">
							<option v-for="team in league.teams" :value="team.id" :disabled="team.disabled" :selected="activePick">{{ team.name }}</option>
						</optgroup>
					</select>
					<input type="hidden" name="pick" :value="selectedTeam" />
					<input type="hidden" name="fixture" :value="fixtureId" />
				</div>
				<p class="help is-danger" v-if="error">{{ error }}</p>
			</div>
			
		</div>
		<div class="field">
			<div class="control has-text-centered">
				<button type="submit" class="button is-success">Save</button>
			</div>
		</div>
	</div>
	
</template>

<script>
	export default {
		props: [
			'playerId',
			'teamId',
			'messageError',
			'activePick'
		],

		data: function() {
			return {
				player: this.playerId,
				selectedTeam: this.teamId,
				error: this.messageError
			}
		},

		computed: {
			teams: function() {
				let teams = this.$store.getters['teams/teams'];

				let grouped = _(teams).groupBy('league_name').map((leagueGrouping, key) => {
					return {
						'key': key,
						'teams': leagueGrouping
					};
				}).valueOf();

				// console.log(grouped);
				// Group with parent
				return grouped;
			},
			playerFieldName: function() {
				return 'players[' + this.player + ']';
			},
			fixtureId: function() {
				var fixtures = this.$store.getters['fixtures/fixtures'];

				let selected = 0;

				_.each(fixtures, (fixture, fixtureId) => {
					let index = _.findIndex(fixture, (team) => {
						return team == this.selectedTeam
					});

					if(index > -1)
					{
						selected = fixtureId;
					}
				})

				if(selected)
				{
					return selected;
				}
				else
				{
					return null;
				}
			}
		},

		methods: {
			
		},

		mounted: function () {
			// Update the disabled team based on the games
			this.$store.commit('teams/addActivePick', this.selectedTeam);
		},

		components: {},

		watch: {
			selectedTeam: function() {
				this.$store.commit('teams/addActivePick', this.selectedTeam);
			}
		}
	};

</script>