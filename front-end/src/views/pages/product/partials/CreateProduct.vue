<script setup>
import { ref, onMounted, computed } from 'vue';
import { useStore } from 'vuex';
import { useToast } from 'primevue/usetoast';

const { toggleCreateModal } = defineProps(['toggleCreateModal']);
const emit = defineEmits();
const store = useStore();
const toast = useToast();
let loading = computed(() => store.getters['loading']);

const statuses = ref([
    { label: 'INSTOCK', value: '1' },
    { label: 'LOWSTOCK', value: '2' },
    { label: 'OUTOFSTOCK', value: '3' }
]);

let categories = computed(() => store.getters['category/categories']);

const hideDialog = () => {
    emit('hideCreateModal');
};
const hide = () => {
    hideDialog();
};

let submitted = ref(false);
const product = ref({});
const saveProduct = async () => {
    submitted.value = true;
    console.log(product.value);
    if (product.value.status && product.value.name && product.value.name.trim() && product.value.description && product.value.price && product.value.category_id) {
        try {
            product.value.status = product.value.status ? product.value.status.value : 3;
            store.dispatch('startLoading');
            let response = await store.dispatch('product/createProduct', product.value);
            store.dispatch('stopLoading');
            toast.add({ severity: 'success', summary: 'Success Message', detail: 'Product Has Been Added', life: 3000 });
            await store.dispatch('product/getProducts');
            hideDialog();
            product.value = {};
            submitted.value = false;
        } catch (e) {
            console.log(e);
            store.dispatch('stopLoading');
            toast.add({ severity: 'error', summary: 'Error Message', detail: 'Error Occur While Adding Product', life: 3000 });
        }
    }
};

onMounted(async () => {
    store.dispatch('category/getCategories');
});
</script>

<template>
    <!-- Create Modal -->
    <Dialog @update:visible="hide" :visible="toggleCreateModal" :style="{ width: '450px' }" header="Create New Product" :modal="true" class="p-fluid">
        <!-- <img :src="'demo/images/product/' + product.image" :alt="product.image" v-if="product.image" width="150" class="mt-0 mx-auto mb-5 block shadow-2" /> -->
        <div class="field">
            <label for="name">Name</label>
            <InputText id="name" v-model.trim="product.name" required="true" :class="{ 'p-invalid': submitted && !product.name }" />
            <small class="p-invalid" v-if="submitted && !product.name">Name is required.</small>
        </div>

        <div class="field">
            <label for="description">Description</label>
            <Textarea :class="{ 'p-invalid': submitted && !product.description }" id="description" v-model="product.description" required="true" rows="3" cols="20" />
            <small class="p-invalid" v-if="submitted && !product.description">Description is required.</small>
        </div>

        <div class="field">
            <label for="inventoryStatus" class="mb-3">Inventory Status</label>
            <Dropdown :class="{ 'p-invalid': submitted && !product.status }" id="inventoryStatus" v-model="product.status" :options="statuses" optionLabel="label" placeholder="Select a Status">
                <template #value="slotProps">
                    <div v-if="slotProps.value && slotProps.value.value">
                        <span :class="'product-badge status-' + slotProps.value.value">{{ slotProps.value.label }}</span>
                    </div>
                    <div v-else-if="slotProps.value && !slotProps.value.value">
                        <span :class="'product-badge status-' + slotProps.value">{{ slotProps.value }}</span>
                    </div>
                    <span v-else>
                        {{ slotProps.placeholder }}
                    </span>
                </template>
            </Dropdown>
            <small class="p-invalid" v-if="submitted && !product.status">Status is required.</small>
        </div>

        <div class="field">
            <label class="mb-3">Category</label>
            <div class="formgrid grid">
                <div v-for="category in categories" :key="category.id" class="field-radiobutton col-6">
                    <RadioButton :value="category.id" name="category" v-model="product.category_id" :class="{ 'p-invalid': submitted && !product.category_id }" />
                    <label :for="'category1' + category.id">{{ category.name }}</label>
                </div>
            </div>
        </div>

        <div class="formgrid grid">
            <div class="field col">
                <label for="price">Price</label>
                <InputNumber id="price" v-model="product.price" mode="currency" currency="USD" locale="en-US" :class="{ 'p-invalid': submitted && !product.price }" :required="true" />
                <small class="p-invalid" v-if="submitted && !product.price">Price is required.</small>
            </div>
            <div class="field col">
                <label for="quantity">Stock</label>
                <InputNumber id="quantity" v-model="product.stock" integeronly />
            </div>
        </div>

        <template #footer>
            <Button label="Cancel" icon="pi pi-times" class="p-button-text" @click="hideDialog" />
            <Button label="Save" icon="pi pi-check" :loading="loading" class="p-button-text" @click="saveProduct" />
        </template>
    </Dialog>
</template>
