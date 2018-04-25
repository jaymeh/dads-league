<template>
	<img :src="logoImage" alt="Placeholder image" class="team-logo">
</template>

<script>
	export default {
		props: {
			'playerId': '',
			teamId: ''
		},

		data: function() {
			return {
				logo: '/assets/img/picks/placeholder.png'
			}
		},

		computed: {
			team: function() {
				return this.$store.getters['teams/getById'](this.teamId);
			},
			logoImage: function() {
				if(this.team)
				{
					return this.team.logo;
				}

				return this.logo;
			}
		}
	}
</script>