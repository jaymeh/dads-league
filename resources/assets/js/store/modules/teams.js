var _ = require('lodash');

// initial state
const state = {
	teams: [],
	teamGames: [],
	activePick: ''
}

// getters
const getters = {
	teams: state => state.teams,
	teamGames: state => state.teamGames,
	getById: state => (teamId) => {
		let teamIndex = _.findIndex(state.teams, function(o) { return o.id == teamId; });
		return state.teams[teamIndex];
	},
	activePick: state => state.activePick,
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
		// Set all disabled
		_.each(store.teams, function(team, key) {
			Vue.set(store.teams[key], 'disabled', false);
		});

		// Loop though all active picks and disable all attached
		_.each(store.activePicks, function(team, key) {
			if(team) {
				let gameKey = _.findIndex(store.teamGames, function(o) { return o.indexOf(team) > -1; });
				
				if(gameKey > -1) {
					let teamsToDisable = store.teamGames[gameKey];

					_.each(teamsToDisable, function(disableTeamId, disableKey) {
						let teamKey = _.findIndex(store.teams, function(o) { return o.id == disableTeamId; });
						Vue.set(store.teams[teamKey], 'disabled', true);
					});
				}
			}
		});
	},
	disableById: function(store, teamId) {
		let teamIndex = _.findIndex(store.teams, function(o) { return o.id == teamId; });

		store.teams[teamIndex].disabled = true;
	},
	addActivePick: function(store, payload) {
		store.activePick = payload;
	}
}

export default {
	namespaced: true,

	state,
	getters,
	actions,
	mutations
}