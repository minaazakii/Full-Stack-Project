import axios from "axios"
let baseUrl = "http://localhost:8000"
export default {
    namespaced:true,

    state(){
        orders:[]
    },
    actions:{
        async getOrders({commit})
        {
            let response = await axios.get(baseUrl+'/api/orders')
            commit('setOrders',response.data)
        },
        async createOrder({commit},data){
            let response =  await axios.post(baseUrl+'/api/orders',data)
            return response;
        }
    },
    mutations:{
        setOrders(state,data)
        {
            state.orders = data.orders
        }
    },
    getters:{
        orders:(state)=>state.orders
    }
}