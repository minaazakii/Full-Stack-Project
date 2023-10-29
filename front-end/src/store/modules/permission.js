import axios from 'axios';
let baseUrl = 'http://localhost:8000';
export default {
    namespaced:true,
    state() {
        permissions: [];
    },
    actions: {
        async getPermissions({ commit }) {
            let response = await axios.get(baseUrl + '/api/permissions');
            commit('setPermission', response.data);
        },
        
        async updatePermission({commit},permission)
        {
            let response = await axios.put(baseUrl+'/api/permissions/'+permission.id,permission)
            return response;
        }
    },
    mutations: {
        setPermission(state, data) {
            state.permissions = data.permissions;
        }
    },
    getters: {
        permissions: (state) => state.permissions
    }
};
