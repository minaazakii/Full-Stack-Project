<script setup>
import { ref } from 'vue';
import { useStore } from 'vuex';
import { useToast } from 'primevue/usetoast';

const { toggleCreateModal } = defineProps(['toggleCreateModal']);
const emit = defineEmits();
const store = useStore();
const toast = useToast();

const hideDialog = () => {
    emit('hideCreateModal');
};
const hide = () => {
    hideDialog();
};

const submitted = ref(false);
const role = ref({});
const saveRole = async () => {
    submitted.value = true;
    if (role.value.name && role.value.name.trim()) {
        try {
            let response = await store.dispatch('role/createRole', role);
            toast.add({ severity: 'success', summary: 'Success Message', detail: 'Role Has Been Added', life: 3000 });
            await store.dispatch('role/getRoles');
            hideDialog();
            role.value = {};
        } catch (e) {
            toast.add({ severity: 'error', summary: 'Error Message', detail: 'Error Occur While Adding Role', life: 3000 });
        }
    }
};
</script>

<template>
    <!-- Create Modal -->
    <Dialog @update:visible="hide" :visible="toggleCreateModal" :style="{ width: '450px' }" header="Create New Product" :modal="true" class="p-fluid">
        <!-- <img :src="'demo/images/product/' + product.image" :alt="product.image" v-if="product.image" width="150" class="mt-0 mx-auto mb-5 block shadow-2" /> -->
        <div class="field">
            <label for="name">Name</label>
            <InputText id="name" v-model.trim="role.name" required="true" :class="{ 'p-invalid': submitted && !role.name }" />
            <small class="p-invalid" v-if="submitted && !role.name">Name is required.</small>
        </div>

        <template #footer>
            <Button label="Cancel" icon="pi pi-times" class="p-button-text" @click="hideDialog" />
            <Button label="Save" icon="pi pi-check" class="p-button-text" @click="saveRole" />
        </template>
    </Dialog>
</template>
