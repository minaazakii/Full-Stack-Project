import axios from 'axios';
let baseUrl = 'http://localhost:8000';
export default {
    namespaced: true,
    state() {
        user: JSON.parse(localStorage.getItem('user')) || null ;
    },
    actions: {
        async register({ commit }, user) {
            let response = await axios.post(baseUrl + '/api/auth/register', user);
            return response;
        },
        async login({ commit }, user) {
            let response = await axios.post(baseUrl + '/api/auth/login', user);
            commit('setUser', response.data);
        },
        async logout({ commit }, user) {
            commit('deleteUser');
        }
    },
    mutations: {
        setUser(state, data) {
            state.user = data.user;
            localStorage.setItem('user', JSON.stringify(data.user));
            localStorage.setItem('access-token', data.token);
            localStorage.setItem('auth', true);
            axios.defaults.headers.common['Authorization'] = 'Bearer ' + data.token;
        },
        deleteUser(state) {
            state.user = null;
            localStorage.removeItem('user');
            localStorage.removeItem('access-token');
            localStorage.removeItem('auth');
            axios.defaults.headers.common['Authorization'] = '';
        }
    },
    getters: {
        user: (state) => state.user
    }
};
