<script setup>
import { toRef, ref, onMounted } from 'vue';
import { useStore } from 'vuex';
import { useToast } from 'primevue/usetoast';

const props = defineProps(['user', 'toggleEditModal']);
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
let editUser = toRef(props, 'user')

const updateUser = async () => {
    try {
        submitted.value = true
        if (editUser.value.name && editUser.value.email && editUser.value.password) {
            let response = await store.dispatch('user/updateUser', editUser.value);
            toast.add({ severity: 'success', detail: 'Success Message', summary: 'User Updated', life: 3000 });
            await store.dispatch('user/getUsers')
            hideDialog();
        }
    } catch (e) {
        toast.add({ severity: 'error', detail: 'Error Message', summary: 'Error ocurred While Updating', life: 3000 });
    }
};
</script>

<template>
    <!-- Edit Modal -->
    <Dialog @update:visible="hide" :visible="props.toggleEditModal" :style="{ width: '450px' }" header="Edit Product"
        :modal="true" class="p-fluid">
        <!-- <img :src="'demo/images/product/' + product.image" :alt="product.image" v-if="product.image" width="150" class="mt-0 mx-auto mb-5 block shadow-2" /> -->
        <div class="field">
            <label for="name">Name</label>
            <InputText id="name" v-model.trim="editUser.name" required="true"
                :class="{ 'p-invalid': submitted && !editUser.name }" />
            <small class="p-error" v-if="submitted && !editUser.name">Name is required.</small>
        </div>

        <div class="field">
            <label for="name">Email</label>
            <InputText id="name" v-model.trim="editUser.email" required="true"
                :class="{ 'p-invalid': submitted && !editUser.email }" />
            <small class="p-error" v-if="submitted && !editUser.email">Email is required.</small>
        </div>

        <div class="field">
            <label for="name">New Password</label>
            <InputText id="name" v-model.trim="editUser.password" required="true"
                :class="{ 'p-invalid': submitted && !editUser.password }" />
            <small class="p-error" v-if="submitted && !editUser.password">Password is required.</small>
        </div>

        <template #footer>
            <Button label="Cancel" icon="pi pi-times" class="p-button-text" @click="hideDialog" />
            <Button label="Save" icon="pi pi-check" class="p-button-text" @click="updateUser" />
        </template>
    </Dialog></template>
