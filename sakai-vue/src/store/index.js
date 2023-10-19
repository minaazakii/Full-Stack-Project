import { createStore } from 'vuex';
import category from './modules/category';
import product from './modules/product';
import auth from './modules/auth'

const store = createStore({
    state() {
        return {
            loading:false
        };
    },
    modules: {
        category,
        product,
        auth
    },
    actions:{
        startLoading({commit}){
            commit('initiateLoading')
        },
        stopLoading({commit}){
            commit('stopLoad')
        }
    },
    mutations: {
        initiateLoading(state){
            state.loading = true
        },
        stopLoad(state){
            state.loading = false
        }
    },
    getters: {
        loading:(state)=>state.loading
    }
});

export default store;
