/* eslint-disable no-shadow, no-param-reassign */
import api from '@/lib/api';
import * as types from './mutation-types';

const state = () => ({
  originalData: [],
  data: [],
});

const actions = {
  search({commit, state}, text) {
    commit(
      types.HOOKS_DATA,
      state.originalData.filter(item => item.name.toLowerCase().includes(text.toLowerCase())),
    );
  },
  async getAll({commit}) {
    await api.getHooks().then((res) => {
      commit(types.HOOKS_ORIGINAL_DATA, res.data);
    }).catch((res) => {
      console.log(res);
    });
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
