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
				let teamId = this.$store.getters['teams/activePick'];
				return this.$store.getters['teams/getById'](teamId);
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