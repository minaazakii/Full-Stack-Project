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
const user = ref({});
const saveUser = async () => {
    submitted.value = true;
    if (user.value.name.trim() && user.value.email.trim() && user.value.password) {
        try {
            let response = await store.dispatch('user/createUser', user.value);
            toast.add({ severity: 'success', summary: 'Success Message', detail: 'User Has Been Added', life: 3000 });
            await store.dispatch('user/getUsers');
            hideDialog();
            user.value = {};
            submitted.value = true;
        } catch (e) {
            toast.add({ severity: 'error', summary: 'Error Message', detail: 'Error Occur While Adding User', life: 3000 });
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
            <InputText id="name" v-model.trim="user.name" required="true" :class="{ 'p-invalid': submitted && !user.name }" />
            <small class="p-error" v-if="submitted && !user.name">Name is required.</small>
        </div>

        <div class="field">
            <label for="name">Email</label>
            <InputText id="name" v-model.trim="user.email" required="true" :class="{ 'p-invalid': submitted && !user.email }" />
            <small class="p-error" v-if="submitted && !user.email">Email is required.</small>
        </div>
        <div class="field">
            <label for="name">Password</label>
            <InputText id="name" v-model.trim="user.password" required="true" :class="{ 'p-invalid': submitted && !user.password }" />
            <small class="p-error" v-if="submitted && !user.password">Password is required.</small>
        </div>

        <template #footer>
            <Button label="Cancel" icon="pi pi-times" class="p-button-text" @click="hideDialog" />
            <Button label="Save" icon="pi pi-check" class="p-button-text" @click="saveUser" />
        </template>
    </Dialog>
</template>
