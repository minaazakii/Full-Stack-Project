<script setup>
import { ref, onMounted, computed } from 'vue';
import { useStore } from 'vuex';
import { useToast } from 'primevue/usetoast';

const { toggleCreateModal } = defineProps(['toggleCreateModal']);
const emit = defineEmits();
const store = useStore();
const toast = useToast();

let selectedProducts = ref([]);
const products = computed(() => store.getters['product/products'])
const discountTypes = ref([{ type: 'Fixed' }, { type: 'Percentage' }])

const hideDialog = () => {
    emit('hideCreateModal');
};
const hide = () => {
    hideDialog();
};

const submitted = ref(false);
const order = ref({ discount: 0 });
const saveOrder = async () => {
    submitted.value = true;
    console.log(order.value)
    if (order.value.name && order.value.description && order.value.products) {
        try {
            let response = await store.dispatch('order/createOrder', order);
            toast.add({ severity: 'success', summary: 'Success Message', detail: 'order Has Been Added', life: 3000 });
            await store.dispatch('order/getCategories');
            hideDialog();
            order.value = {};
        } catch (e) {
            toast.add({ severity: 'error', summary: 'Error Message', detail: 'Error Occur While Adding order', life: 3000 });
        }
    }
};

onMounted(async () => {
    await store.dispatch('product/getProducts');
})
</script>

<template>
    <!-- Create Modal -->
    <Dialog @update:visible="hide" :visible="toggleCreateModal" :style="{ width: '450px' }" header="Create New Order"
        :modal="true" class="p-fluid">
        <!-- <img :src="'demo/images/product/' + product.image" :alt="product.image" v-if="product.image" width="150" class="mt-0 mx-auto mb-5 block shadow-2" /> -->
        <div class="field">
            <label for="name">Name</label>
            <InputText id="name" v-model.trim="order.name" required="true"
                :class="{ 'p-invalid': submitted && !order.name }" />
            <small class="p-invalid" v-if="submitted && !order.name">Name is required.</small>
        </div>


        <div class="field">
            <label for="description">Description</label>
            <Textarea :class="{ 'p-invalid': submitted && !order.description }" id="description" v-model="order.description"
                required="true" rows="3" cols="20" />
            <small class="p-invalid" v-if="submitted && !order.description">Description is required.</small>
        </div>

        <div class="formgrid grid">

            <div class="field col">
                <label for="name">Discount</label>
                <InputNumber id="name" v-model.trim="order.discount" class="" required="true" />

            </div>

            <div class="field col">
                <label for="name">Type</label>
                <div>
                    <Dropdown v-model="order.discountType" :options="discountTypes" optionLabel="type"
                        placeholder="Select Discount Type" class="w-full" />
                </div>
            </div>

        </div>

        <div class="field">
            <label for="name">Products</label>
            <MultiSelect :class="{'p-invalid':submitted && !order.selectedProducts}" v-model="order.selectedProducts" :options="products" filter optionLabel="name"
                placeholder="Select Products" class="w-full " />
                <small class="p-error" v-if="submitted && !order.selectedProducts">Product is required.</small>
        </div>

        <template #footer>
            <Button label="Cancel" icon="pi pi-times" class="p-button-text" @click="hideDialog" />
            <Button label="Save" icon="pi pi-check" class="p-button-text" @click="saveOrder" />
        </template>
    </Dialog>
</template>
