import Vue from 'vue';
import Vuex from 'vuex';
import hooks from './hooks';

Vue.use(Vuex);

export default new Vuex.Store({
  modules: {
    hooks,
  },
});
