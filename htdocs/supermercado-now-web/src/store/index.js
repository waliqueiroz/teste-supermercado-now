import Vue from 'vue'
import Vuex from 'vuex'
import router from '../router'
import api from "@/api";

Vue.use(Vuex)

export default new Vuex.Store({
  state: {
    user: null,
    TOKEN_KEY: '@supermercado-now-token',
  },
  mutations: {
    setUser(state, user) {
      state.user = user;
    },
    logout(state) {
      if (state.user) {
        state.user = null
        window.localStorage.removeItem(state.TOKEN_KEY)
        router.push('/login');
      }
    }
  },
  actions: {
    async logout({ commit }) {
      await api.get('/logout');
      commit('logout');
    }
  },
  modules: {
  }
})
