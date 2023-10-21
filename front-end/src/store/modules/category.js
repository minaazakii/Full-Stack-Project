import axios from 'axios';

const baseUrl = 'http://localhost:8000';
export default {
    namespaced: true,
    state() {
        categories: [];
    },
    actions: {
        async getCategories({ commit }) {
            let response = await axios.get(baseUrl + '/api/categories');
            commit('setCategories', response.data.categories);
        },
        async createCategory({ commit }, category) {
            let response = await axios.post(baseUrl + '/api/categories', category.value);
            return response;
        },
        async updateCategory({ commit }, category) {
            console.log(category)
            let response = await axios.put(baseUrl + '/api/categories/' + category.id, category);
            return response;
        },
        async deleteCategory({ commit }, category) {
            let response = await axios.delete(baseUrl + '/api/categories/' + category.id, category);
            return response;
        },
        async deleteMultiCategories({commit},categories)
        {
            let response = await axios.post(baseUrl+'/api/categories/multi/delete',{categories:categories})
            return response;
        }
    },
    mutations: {
        setCategories(state, categories) {
            state.categories = categories;
        }
    },
    getters: {
        categories: (state) => state.categories
    }
};
