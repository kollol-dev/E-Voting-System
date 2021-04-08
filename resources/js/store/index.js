import Vue from "vue";
import Vuex from "vuex";
Vue.use(Vuex);

export default new Vuex.Store({
    state: {
        authUser: false,
    },
    getters: {
        getAuthUser(state) {
            return state.authUser;
        }
    },
    mutations: {
        setAuthUser(state, data) {
            state.authUser = data;
        }
    },
    actions: {
        setAuthUser(state, data) {
            state.commit("setAuthUser", data);
        }
    }
});
