<template>
	<div>
		<select id="team-select" v-model="selectedTeam">
			<option>Please Select...</option>
			<option v-for="team in teams" :value="team.id" :disabled="team.disabled">{{ team.name }}</option>
		</select>
		<input type="hidden" :name="playerFieldName" v-model="selectedTeam" />
	</div>
</template>

<script>
	export default {
		props: [
			'playerId'
		],

		data: function() {
			return {
				player: this.playerId,
				selectedTeam: ''
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
			
		},

		components: {},

		watch: {
			selectedTeam: function() {
				// Update the disabled team based on the games
				this.$store.commit('teams/disableTeam', this.selectedTeam);
			}
		}
	};

</script>