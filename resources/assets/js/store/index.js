import Vue from 'vue'
import Vuex from 'vuex'
import teams from './modules/teams'
import picks from './modules/picks'
import fixtures from './modules/fixtures'

Vue.use(Vuex);

export default new Vuex.Store({
  modules: {
    teams,
    picks,
    fixtures
  },
})