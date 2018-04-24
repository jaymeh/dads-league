<template>
	<div class="field">
		<div class="control">
			<div class="select" v-bind:class="{ 'is-danger': error }">
				<select id="team-select" v-model="selectedTeam">
					<option>Please Select...</option>
					<option v-for="team in teams" :value="team.id" :disabled="team.disabled">{{ team.name }}</option>
				</select>
				<input type="hidden" :name="playerFieldName" v-model="selectedTeam" />
			</div>
			<p class="help is-danger" v-if="error">{{ error }}</p>
		</div>
	</div>
</template>

<script>
	export default {
		props: [
			'playerId',
			'teamId',
			'messageError'
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
				return this.$store.getters['teams/teams'];
			},
			playerFieldName: function() {
				return 'players[' + this.player + ']';
			}
		},

		methods: {
			
		},

		mounted: function () {
			if(this.selectedTeam)
			{
				// Update the disabled team based on the games
				this.$store.commit('teams/addActivePick', {
					id: this.playerId, 
					team: parseInt(this.selectedTeam)
				});
				this.$store.commit('teams/disableTeam', this.selectedTeam);

				// Flag error if already picked before.
				let picks = this.$store.getters['picks/getPicksByPlayer'](this.playerId);

				let alreadyPickedMatch = _.findIndex(picks, pick => { return this.selectedTeam == pick });

				if(alreadyPickedMatch > -1)
				{
					this.error = 'This team has already been picked in the past.';
				}
			}
		},

		components: {},

		watch: {
			selectedTeam: function() {
				this.error = '';

				// Update the disabled team based on the games
				this.$store.commit('teams/addActivePick', {
					id: this.playerId, 
					team:this.selectedTeam
				});
				this.$store.commit('teams/disableTeam', this.selectedTeam);

				// Flag error if already picked before.
				let picks = this.$store.getters['picks/getPicksByPlayer'](this.playerId);

				console.log(picks);

				let alreadyPickedMatch = _.findIndex(picks, pick => { return this.selectedTeam == pick });

				if(alreadyPickedMatch > -1)
				{
					this.error = 'This team has already been picked in the past.';
				}
			}
		}
	};

</script>