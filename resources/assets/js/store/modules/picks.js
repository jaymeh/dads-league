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
  
}

// mutations
const mutations = {
  setTeams (state, teams) {
    state.teams = teams
  },
}

export default {
  state,
  getters,
  actions,
  mutations
}