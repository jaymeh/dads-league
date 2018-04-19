var _ = require('lodash');

// initial state
const state = {
	picks: []
}

// getters
const getters = {
	picks: state => state.picks,
	getPicksByPlayer: state => (playerId) => {
		return state.picks[playerId];
	}
}

// actions
const actions = {
	load: function ({ commit }, picks) {
		commit('load', picks);
	},
}

// mutations
const mutations = {
	load: function (store, picks) {
		Vue.set(store, 'picks', picks);
	}
}

export default {
	namespaced: true,

	state,
	getters,
	actions,
	mutations
}