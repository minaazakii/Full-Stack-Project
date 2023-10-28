import axios from 'axios';
let baseUrl = 'http://localhost:8000';
export default {
    namespaced:true,
    state() {
        users: [];
    },
    actions: {
        async getUsers({ commit }) {
            let response = await axios.get(baseUrl + '/api/users');
            commit('setUsers', response.data);
        },
        async updateUser({commit},user){
            let response = await axios.put(baseUrl+'/api/users/'+user.id,user);
            return response;
        }
    },
    mutations: {
        setUsers(state, data) {
            state.users = data.users;
        }
    },
    getters: {
        users: (state) => state.users
    }
};
