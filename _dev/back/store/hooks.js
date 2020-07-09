/* eslint-disable no-shadow, no-param-reassign */
import * as types from './mutation-types';

const state = () => ({
  originalData: [],
  data: [],
});

const actions = {
  data({commit}, hooks) {
    commit(types.HOOKS_DATA, hooks);
    commit(types.HOOKS_ORIGINAL_DATA, hooks);
  },
  search({commit, state}, text) {
    commit(
      types.HOOKS_DATA,
      state.originalData.filter(item => item.name.toLowerCase().includes(text.toLowerCase())),
    );
  },
};

const mutations = {
  [types.HOOKS_DATA](state, data) {
    state.data = data;
  },
  [types.HOOKS_ORIGINAL_DATA](state, data) {
    state.originalData = data;
  },
};

const getters = {
  data(state) {
    return state.data;
  },
};
export default {
  namespaced: true,
  state,
  actions,
  mutations,
  getters,
};
/* eslint-enable no-shadow, no-param-reassign */
