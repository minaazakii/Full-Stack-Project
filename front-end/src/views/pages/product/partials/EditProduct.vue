<script setup>
import { toRef, ref, onMounted, computed } from 'vue';
import { useStore } from 'vuex';
import { useToast } from 'primevue/usetoast';

const props = defineProps(['product', 'toggleEditModal']);
const emit = defineEmits();
const store = useStore();
const toast = useToast();
let loading = computed(() => store.getters['loading']);
const statuses = ref([
    { label: 'INSTOCK', value: '1' },
    { label: 'LOWSTOCK', value: '2' },
    { label: 'OUTOFSTOCK', value: '3' }
]);

const hideDialog = () => {
    emit('hideEditModal');
};
const hide = () => {
    hideDialog();
};

const submitted = ref(false);
let editProduct = toRef(props, 'product');

const updateProduct = async () => {
    submitted.value = true;
    editProduct.value.status = editProduct.value.status ? editProduct.value.status.value : 3;
    if (editProduct.value.name && editProduct.value.name.trim() && editProduct.value.description && editProduct.value.price && editProduct.value.category.id) {
        try {
            editProduct.value.category_id = editProduct.value.category.id;
            store.dispatch('startLoading');
            let response = await store.dispatch('product/updateProduct', editProduct.value);
            store.dispatch('stopLoading');
            toast.add({ severity: 'success', summary: 'Success Message', detail: 'Product Has Been Added', life: 3000 });
            await store.dispatch('product/getProducts');
            hideDialog();
        } catch (e) {
            console.log(e);
            toast.add({ severity: 'error', summary: 'Error Message', detail: 'Error Occur While Adding Product', life: 3000 });
        }
    }
};

let categories = computed(() => store.getters['category/categories']);
onMounted(async () => {
    await store.dispatch('category/getCategories');
});
</script>

<template>
    <!-- Edit Modal -->
    <Dialog @update:visible="hide" :visible="toggleEditModal" :style="{ width: '450px' }" header="Edit Product" :modal="true" class="p-fluid">
        <!-- <img :src="'demo/images/product/' + product.image" :alt="product.image" v-if="product.image" width="150" class="mt-0 mx-auto mb-5 block shadow-2" /> -->
        <div class="field">
            <label for="name">Name</label>
            <InputText id="name" v-model.trim="editProduct.name" required="true" :class="{ 'p-invalid': submitted && !editProduct.name }" />
            <small class="p-invalid" v-if="submitted && !editProduct.name">Name is required.</small>
        </div>

        <div class="field">
            <label for="description">Description</label>
            <Textarea :class="{ 'p-invalid': submitted && !editProduct.description }" id="description" v-model="editProduct.description" required="true" rows="3" cols="20" />
            <small class="p-invalid" v-if="submitted && !editProduct.description">Description is required.</small>
        </div>

        <div class="field">
            <label for="inventoryStatus" class="mb-3">Inventory Status</label>
            <Dropdown id="inventoryStatus" v-model="editProduct.status" :options="statuses" optionLabel="label" placeholder="Select a Status">
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
        </div>

        <div class="field">
            <label class="mb-3">Category</label>
            <div class="formgrid grid">
                <div v-for="category in categories" :key="category.id" class="field-radiobutton col-6">
                    <RadioButton :value="category.id" name="category" v-model="editProduct.category.id" :class="{ 'p-invalid': submitted && !editProduct.category.id }" />
                    <label :for="'category1' + category.id">{{ category.name }}</label>
                </div>
            </div>
        </div>

        <div class="formgrid grid">
            <div class="field col">
                <label for="price">Price</label>
                <InputNumber id="price" v-model="editProduct.price" mode="currency" currency="USD" locale="en-US" :class="{ 'p-invalid': submitted && !editProduct.price }" :required="true" />
                <small class="p-invalid" v-if="submitted && !editProduct.price">Price is required.</small>
            </div>
            <div class="field col">
                <label for="quantity">Quantity</label>
                <InputNumber id="quantity" v-model="editProduct.quantity" integeronly />
            </div>
        </div>

        <template #footer>
            <Button label="Cancel" icon="pi pi-times" class="p-button-text" @click="hideDialog" />
            <Button label="Save" icon="pi pi-check" :loading="loading" class="p-button-text" @click="updateProduct" />
        </template>
    </Dialog>
</template>
