<template>
	<select-box>
		<option>Please Select...</option>
		<option v-for="team in teams" :value="team.id" :disabled="team.disabled">{{ team.name }}</option>
	</select-box>
</template>

<script>

	export default {
		computed: {
			teams: function() {
				return this.$store.getters['teams/teams'];
			}
		},

		methods: {
			
		},

		mounted: function () {
			
		},

		components: {},
	};

</script>