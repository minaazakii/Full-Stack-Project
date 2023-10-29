import { createStore } from 'vuex';
import category from './modules/category';
import product from './modules/product';
import order from './modules/order';
import auth from './modules/auth';
import user from './modules/user';
import permission from './modules/permission';

const store = createStore({
    state() {
        return {
            loading: false
        };
    },
    modules: {
        category,
        product,
        order,
        auth,
        user,
        permission
    },
    actions: {
        startLoading({ commit }) {
            commit('initiateLoading');
        },
        stopLoading({ commit }) {
            commit('stopLoad');
        }
    },
    mutations: {
        initiateLoading(state) {
            state.loading = true;
        },
        stopLoad(state) {
            state.loading = false;
        }
    },
    getters: {
        loading: (state) => state.loading
    }
});

export default store;
