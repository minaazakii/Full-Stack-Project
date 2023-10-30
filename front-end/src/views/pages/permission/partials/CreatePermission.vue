<script setup>
import { ref, onMounted, computed } from 'vue';
import { useStore } from 'vuex';
import { useToast } from 'primevue/usetoast';

const { toggleCreateModal } = defineProps(['toggleCreateModal']);
const emit = defineEmits();
const store = useStore();
const toast = useToast();

const permissions = computed(() => store.getters['permission/permissions']);
const users = computed(() => store.getters['user/users']);

const hideDialog = () => {
    emit('hideCreateModal');
};
const hide = () => {
    hideDialog();
};

const submitted = ref(false);
const user = ref({});
const savePermission = async () => {
    submitted.value = true;
    if (user.value.id && user.value.permissions) {
        try {
            let response = await store.dispatch('permission/assignPermissions', user.value);
            toast.add({ severity: 'success', summary: 'Success Message', detail: 'Permission Has Been Added', life: 3000 });
            await store.dispatch('permission/getPermissions');
            hideDialog();
            user.value = {};
            submitted.value = false;
        } catch (e) {
            toast.add({ severity: 'error', summary: 'Error Message', detail: 'Error Occur While Adding Permission', life: 3000 });
        }
    }
};

onMounted(async () => {
    await store.dispatch('user/getUsers');
    await store.dispatch('permission/getPermissions');
});
</script>

<template>
    <!-- Create Modal -->
    <Dialog @update:visible="hide" :visible="toggleCreateModal" :style="{ width: '450px' }" header="Create New Product" :modal="true" class="p-fluid">
        <!-- <img :src="'demo/images/product/' + product.image" :alt="product.image" v-if="product.image" width="150" class="mt-0 mx-auto mb-5 block shadow-2" /> -->
        <div class="field">
            <label for="name">User</label>
            <Dropdown :class="{ 'p-invalid': submitted && !user.id }" v-model="user.id" :options="users" filter optionLabel="name" optionValue="id" placeholder="Select a User" class="w-full"> </Dropdown>
            <small class="p-error" v-if="submitted && !user.id">User is required.</small>
        </div>

        <div class="field">
            <label for="name">Permissions</label>
            <MultiSelect :class="{ 'p-invalid': submitted && !user.permissions }" v-model="user.permissions" filter :options="permissions" display="chip" optionLabel="display" optionValue="id" placeholder="Select Permissions" :maxSelectedLabels="20" class="w-full" />
            <small class="p-error" v-if="submitted && !user.permissions">Permissions is required.</small>
        </div>

        <template #footer>
            <Button label="Cancel" icon="pi pi-times" class="p-button-text" @click="hideDialog" />
            <Button label="Save" icon="pi pi-check" class="p-button-text" @click="savePermission" />
        </template>
    </Dialog>
</template>
