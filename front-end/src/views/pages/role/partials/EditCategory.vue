<script setup>
import {toRef, ref , onMounted} from 'vue';
import { useStore } from 'vuex';
import { useToast } from 'primevue/usetoast';

const props = defineProps(['category', 'toggleEditModal']);
const emit = defineEmits();
const store = useStore();
const toast = useToast();
const hideDialog = () => {
    emit('hideEditModal');
};
const hide = () => {
    hideDialog();
};

const submitted = ref(false);
let editCategory = toRef(props,'category')

const updateCategory = async () => {
    try {
        let response = await store.dispatch('category/updateCategory', editCategory.value);
        toast.add({ severity: 'success', detail: 'Success Message', summary: 'Category Updated', life: 3000 });
        await store.dispatch('category/getCategories');
        hideDialog();
    } catch (e) {
        toast.add({ severity: 'error', detail: 'Error Message', summary: 'Error ocurred While Updating', life: 3000 });
    }
};
</script>

<template>
    <!-- Edit Modal -->
    <Dialog @update:visible="hide" :visible="props.toggleEditModal" :style="{ width: '450px' }" header="Edit Product" :modal="true" class="p-fluid">
        <!-- <img :src="'demo/images/product/' + product.image" :alt="product.image" v-if="product.image" width="150" class="mt-0 mx-auto mb-5 block shadow-2" /> -->
        <div class="field">
            <label for="name">Name</label>
            <InputText id="name" v-model.trim="editCategory.name" required="true" :class="{ 'p-invalid': submitted && !editCategory.name }" />
            <small class="p-invalid" v-if="submitted && !editCategory.name">Name is required.</small>
        </div>

        <template #footer>
            <Button label="Cancel" icon="pi pi-times" class="p-button-text" @click="hideDialog" />
            <Button label="Save" icon="pi pi-check" class="p-button-text" @click="updateCategory" />
        </template>
    </Dialog>
</template>
