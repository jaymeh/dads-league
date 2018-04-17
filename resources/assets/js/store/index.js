import Vue from 'vue'
import Vuex from 'vuex'
import picks from './modules/picks'

Vue.use(Vuex);

export default new Vuex.Store({
  modules: {
    picks
  },
})