var _ = require('lodash');

// initial state
const state = {
	fixtures: []
}

// getters
const getters = {
	fixtures: state => state.fixtures,
	fixtureById: state => (fixtureId) => state.fixtures[fixtureId]
}

// actions
const actions = {
	load: function ({ commit }, fixtures) {
		commit('load', fixtures);
	},
}

// mutations
const mutations = {
	load: function (store, fixtures) {
		Vue.set(store, 'fixtures', fixtures);
	}
}

export default {
	namespaced: true,

	state,
	getters,
	actions,
	mutations
}