// initial state
const state = {
	teams: []
}

// getters
const getters = {
	teams: state => state.teams
}

// actions
const actions = {
	load: function ({ commit }, teams) {
		commit('load', teams);
	},
}

// mutations
const mutations = {
	load: function (store, teams) {
		Vue.set(store, 'teams', teams);
	},
}

export default {
	namespaced: true,

	state,
	getters,
	actions,
	mutations
}