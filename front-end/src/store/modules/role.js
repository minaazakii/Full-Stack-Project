import axios from 'axios';
let baseUrl = 'http://localhost:8000';
export default {
    namespaced:true,
    state() {
        roles: [];
    },
    actions: {
        async getRoles({ commit }) {
            let response = await axios.get(baseUrl + '/api/roles');
            commit('setRoles',response.data)
        }
    },
    mutations: {
        setRoles(state,data)
        {
            state.roles = data.roles
        }
    },
    getters: {
        roles:(state)=>state.roles
    }
};
