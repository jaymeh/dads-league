var _ = require('lodash');

// initial state
const state = {
	teams: [],
	teamGames: []
}

// getters
const getters = {
	teams: state => state.teams,
	teamGames: state => state.teamGames,
	getById: state => (teamId) => {
		let teamIndex = _.findIndex(state.teams, function(o) { return o.id == teamId; });
		return state.teams[teamIndex];
	}
}

// actions
const actions = {
	load: function ({ commit }, teams) {
		commit('load', teams);

		// Split into 2's getting ids
		let games = _.chunk(_.map(teams, 'id'), 2);

		commit('loadTeamGames', games);
	},
}

// mutations
const mutations = {
	load: function (store, teams) {
		Vue.set(store, 'teams', teams);
	},
	loadTeamGames: function(store, games) {
		Vue.set(store, 'teamGames', games);
	},
	disableTeam: function(store, teamId) {
		// Enable the games again (Might need a tweak due to other select boxes)
		_.each(store.teams, function(team, key) {
			Vue.set(store.teams[key], 'disabled', false);
		});

		let gameKey = _.findIndex(store.teamGames, function(o) { return o.indexOf(teamId) > -1; });
		let teamsToDisable = store.teamGames[gameKey];

		_.each(teamsToDisable, function(team, key) {
			let teamKey = _.findIndex(store.teams, function(o) { return o.id == team; });
			Vue.set(store.teams[teamKey], 'disabled', true);
		});
	},
	disableById: function(store, teamId) {
		let teamIndex = _.findIndex(store.teams, function(o) { return o.id == teamId; });

		store.teams[teamIndex].disabled = true;
	}
}

export default {
	namespaced: true,

	state,
	getters,
	actions,
	mutations
}