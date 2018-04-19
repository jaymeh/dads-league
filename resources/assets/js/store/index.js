import Vue from 'vue'
import Vuex from 'vuex'
import teams from './modules/teams'
import picks from './modules/picks'

Vue.use(Vuex);

export default new Vuex.Store({
  modules: {
    teams,
    picks
  },
})