<script setup>
import { toRef, ref, onMounted,computed } from 'vue';
import { useStore } from 'vuex';
import { useToast } from 'primevue/usetoast';

const props = defineProps(['order', 'toggleEditModal']);
const emit = defineEmits();
const store = useStore();
const toast = useToast();
const hideDialog = () => {
    emit('hideEditModal');
};
const hide = () => {
    hideDialog();
};

let products = computed(() => store.getters['product/products'])
let dropDowns = ref([{ product: null, quantity: 0 }]);
const addProduct = () => {
    dropDowns.value.push({ product: null, quantity: 0 });
}
const removeProduct = (index) => {
    dropDowns.value.splice(index, 1)

}
const discountTypes = ref([{ type: 'fixed' }, { type: 'percentage' }])

const submitted = ref(false);
let editOrder = toRef(props, 'order');

const updateOrder = async () => {
    try {
        let response = await store.dispatch('order/updateOrder', editOrder.value);
        toast.add({ severity: 'success', detail: 'Success Message', summary: 'Order Updated', life: 3000 });
        await store.dispatch('order/getCategories');
        hideDialog();
    } catch (e) {
        toast.add({ severity: 'error', detail: 'Error Message', summary: 'Error ocurred While Updating', life: 3000 });
    }
};

onMounted(async () => {
    await store.dispatch('product/getProducts');
    console.log(products.value)
    if(editOrder.value)
    {
        console.log(editOrder.value.products)
    }
   
    editOrder.value.discountType = {type:editOrder.value.discountType};
    products.value.forEach(product =>{
        editOrder.value.products.forEach(editProduct=>{
            if(product.id == editProduct.id)
            {
                product.pivot =editProduct.pivot
            }
        })
    })
    console.log(products.value)
})
</script>

<template>
    <!-- Edit Modal -->
    <Dialog @update:visible="hide" :visible="props.toggleEditModal" :style="{ width: '450px' }" header="Edit Product" :modal="true" class="p-fluid">
        <!-- <img :src="'demo/images/product/' + product.image" :alt="product.image" v-if="product.image" width="150" class="mt-0 mx-auto mb-5 block shadow-2" /> -->
        <div class="field">
            <label for="name">Name</label>
            <InputText id="name" v-model.trim="editOrder.name" required="true" :class="{ 'p-invalid': submitted && !editOrder.name }" />
            <small class="p-error" v-if="submitted && !editOrder.name">Name is required.</small>
        </div>

        <div class="field">
            <label for="description">Description</label>
            <Textarea :class="{ 'p-invalid': submitted && !editOrder.description }" id="description" v-model="editOrder.description" required="true" rows="3" cols="20" />
            <small class="p-error" v-if="submitted && !editOrder.description">Description is required.</small>
        </div>

        <div class="formgrid grid">
            <div class="field col">
                <label for="name">Discount</label>
                <InputNumber id="name" v-model.trim="editOrder.amount" class="" required="true" />
            </div>

            <div class="field col">
                <label for="name">Type</label>
                <div>
                    <Dropdown v-model="editOrder.discountType" :options="discountTypes" optionLabel="type" placeholder="Select Discount Type" class="w-full" />
                </div>
            </div>
        </div>

        <div class="field flex flex-row-reverse">
            <Button class="" icon="pi pi-plus" @click="addProduct"></Button>
        </div>

        <div v-for="(dropDown, index) in editOrder.products" :key="dropDown.id" class="formgrid grid">
            <div class="field col">
                <label for="name">Product</label>
                <div>
                    <Dropdown :options="products" v-model="dropDown.product" optionLabel="name" placeholder="Select Discount Type" class="w-full" :class="{ 'p-invalid': submitted && !dropDown.product }" />
                </div>
                <small class="p-error" v-if="submitted && !dropDown.product">Product is required.</small>
            </div>

            <div class="field col">
                <label for="name">Quantity</label>
                <InputNumber id="name" v-model.trim="dropDown.pivot.quantity" :class="{ 'p-invalid': submitted && !dropDown.quantity }" required="true" />
            </div>

            <div class="field col flex align-items-end justify-content-end">
                <Button v-if="dropDowns.length > 1" @click="removeProduct(index)" icon="pi pi-times"></Button>
            </div>
        </div>
        <template #footer>
            <Button label="Cancel" icon="pi pi-times" class="p-button-text" @click="hideDialog" />
            <Button label="Save" icon="pi pi-check" class="p-button-text" @click="saveOrder" />
        </template>
    </Dialog>
</template>
