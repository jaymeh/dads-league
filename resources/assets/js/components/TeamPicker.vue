<template>
	<select-box></select-box>
</template>

<script>

	export default {
		props: {
			teams: {

			}
		},

		data: function () {
			return {
				allTeams: this.$store.getters.teams
			};
		},

		computed: {
			
		},

		methods: {
			
		},

		mounted: function () {
			this.$store.commit('setTeams', this.teams)
		},

		components: {},
	};

</script>