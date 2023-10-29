<script setup>
import { ref ,onMounted,computed} from 'vue';
import { useStore } from 'vuex';
import { useToast } from 'primevue/usetoast';

const { toggleCreateModal } = defineProps(['toggleCreateModal']);
const emit = defineEmits();
const store = useStore();
const toast = useToast();

const users = computed(()=>store.getters['user/users'])

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
    if (permission.value.name && permission.value.name.trim()) {
        try {
            let response = await store.dispatch('permission/createPermission', permission);
            toast.add({ severity: 'success', summary: 'Success Message', detail: 'Permission Has Been Added', life: 3000 });
            await store.dispatch('permission/getPermissions');
            hideDialog();
            permission.value = {};
        } catch (e) {
            toast.add({ severity: 'error', summary: 'Error Message', detail: 'Error Occur While Adding Permission', life: 3000 });
        }
    }
};

onMounted(async()=>{
    await store.dispatch('user/getUsers')
})
</script>

<template>
    <!-- Create Modal -->
    <Dialog @update:visible="hide" :visible="toggleCreateModal" :style="{ width: '450px' }" header="Create New Product" :modal="true" class="p-fluid">
        <!-- <img :src="'demo/images/product/' + product.image" :alt="product.image" v-if="product.image" width="150" class="mt-0 mx-auto mb-5 block shadow-2" /> -->
        <div class="field">
            <label for="name">User</label>
            <Dropdown v-model="user.id" :options="users" filter optionLabel="name" optionValue="id" placeholder="Select a User" class="w-full">
            </Dropdown>
            <small class="p-invalid" v-if="submitted && !permission.name">Name is required.</small>
        </div>

        <div class="field">
            <label for="name">Permissions</label>
            <MultiSelect v-model="user.permissions" :options="cities" optionLabel="name" placeholder="Select Cities"
    :maxSelectedLabels="20" class="w-full" />
            <small class="p-invalid" v-if="submitted && !permission.name">Name is required.</small>
        </div>

        <template #footer>
            <Button label="Cancel" icon="pi pi-times" class="p-button-text" @click="hideDialog" />
            <Button label="Save" icon="pi pi-check" class="p-button-text" @click="savePermission" />
        </template>
    </Dialog>
</template>
