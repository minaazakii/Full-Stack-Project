import axios from 'axios';
const baseUrl = 'http://localhost:8000';
export default {
    namespaced: true,
    state() {
        products: [];
    },
    actions: {
        async getProducts({ commit }) {
            let response = await axios.get(baseUrl + '/api/products');
            commit('setProducts', response.data.products);
            console.log(response.data.products);
        },
        async createProduct({ commit }, product) {
            let response = await axios.post(baseUrl + '/api/products', product);
            return response;
        },
        async updateProduct({ commit }, product) {
            let response = await axios.put(baseUrl + '/api/products/' + product.id, product);
            return response;
        },
        async deleteProduct({commit},product){
            let response = await axios.delete(baseUrl+'/api/products/'+product.id,product)
            return response; 
        },
        async deleteMultiProducts({commit},products)
        {
            let response = await axios.post(baseUrl+'/api/products/multi/delete',{products:products})
            return response;
        }
    },
    mutations: {
        setProducts(state, products) {
            state.products = products;
        }
    },
    getters: {
        products: (state) => state.products
    }
};
