<template>
	<tr v-bind:class="{ disabled: team.disabled}" v-cloak>
        <td class="text-center team-logo"><img class="logo-small" :src="team.logo" :alt="imageAlt" /></td>
        <td>{{ team.name }}</td>
    </tr>
</template>

<script>
	export default {
		props: [
			'teamId'
		],

		data: function() {
			return {
				'team': false
			}
		},

		computed: {
			imageAlt: function() {
				return this.team.name + ' logo';
			}
		},

		mounted() {
			this.team = this.$store.getters['teams/getById'](this.teamId);
		}
	}
</script>