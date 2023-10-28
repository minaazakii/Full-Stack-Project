<script setup>
import CreateUserModal from './partials/CreateUser.vue';
import EditUserModal from './partials/EditUser.vue';
import { FilterMatchMode } from 'primevue/api';
import { ref, onMounted, onBeforeMount } from 'vue';
import { useToast } from 'primevue/usetoast';
import { useStore } from 'vuex';
import { computed } from 'vue';

const toast = useToast();
const store = useStore();
const loading = computed(() => store.getters['loading']);
const authUser = computed(()=>store.getters['auth/user'])

const users = computed(() => store.getters['user/users']);

// Create Modal
const toggleCreateModal = ref(false);
const openCreateModal = () => {
    toggleCreateModal.value = true;
};
const hideCreateModal = () => {
    toggleCreateModal.value = false;
};

// Edit Modal
const toggleEditModal = ref(false);
const openEditModal = (editUser) => {
    user.value = { ...editUser };
    toggleEditModal.value = true;
};
const hideEditModal = () => {
    toggleEditModal.value = false;
};

const selectedUsers = ref(null);
const deleteUserDialog = ref(false);
const deleteUsersDialog = ref(false);
const user = ref({});
const selectedUser = ref(null);
const dt = ref(null);
const filters = ref({});

onBeforeMount(() => {
    initFilters();
});
onMounted(async () => {
    store.dispatch('startLoading')
    await store.dispatch('user/getUsers');
    store.dispatch('stopLoading')
});
const formatCurrency = (value) => {
    return value.toLocaleString('en-US', { style: 'currency', currency: 'USD' });
};

const confirmDeleteUser = (editUser) => {
    user.value = editUser;
    deleteUserDialog.value = true;
};

const deleteUser = async () => {
    try {
        let response = await store.dispatch('user/deleteUser', user.value);
        deleteUserDialog.value = false;
        user.value = {};
        await store.dispatch('user/getUsers');
        toast.add({ severity: 'success', summary: 'Successful', detail: 'User Deleted', life: 3000 });
    } catch (e) {
        toast.add({ severity: 'error', summary: 'Error', detail: 'Something Went Wrong', life: 3000 });
    }
};

const exportCSV = () => {
    dt.value.exportCSV();
};

const confirmDeleteSelected = () => {
    deleteUsersDialog.value = true;
};
const deleteSelectedUsers = async () => {
    try {
        let response = await store.dispatch('user/deleteMultiUsers', selectedUsers.value)
        toast.add({ severity: 'success', summary: 'Success', detail: 'Users Deleted', life: 3000 })
        await store.dispatch('user/getUsers')
    } catch (e) {
        toast.add({ severity: 'error', summary: 'Error', detail: 'Error Occurred', life: 3000 })
    }
    deleteUsersDialog.value = false;
    selectedUsers.value = null;
};

const initFilters = () => {
    filters.value = {
        global: { value: null, matchMode: FilterMatchMode.CONTAINS }
    };
};
</script>

<template>
    <div class="grid">
        <div class="col-12">
            <div class="card">
                <Toast />
                <Toolbar class="mb-4">
                    <template v-slot:start>
                        <div class="my-2">
                            <Button label="New" icon="pi pi-plus" class="p-button-success mr-2" @click="openCreateModal" />
                            <Button label="Delete" icon="pi pi-trash" class="p-button-danger" @click="confirmDeleteSelected"
                                :disabled="!selectedUsers || !selectedUsers.length" />
                        </div>
                    </template>

                    <template v-slot:end>
                        <FileUpload mode="basic" accept="image/*" :maxFileSize="1000000" label="Import" chooseLabel="Import"
                            class="mr-2 inline-block" />
                        <Button label="Export" icon="pi pi-upload" class="p-button-help" @click="exportCSV($event)" />
                    </template>
                </Toolbar>

                <DataTable :loading="loading" ref="dt" :value="users" v-model:selection="selectedUsers"
                    dataKey="id" :paginator="true" :rows="10" :filters="filters"
                    paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
                    :rowsPerPageOptions="[5, 10, 25]"
                    currentPageReportTemplate="Showing {first} to {last} of {totalRecords} Users"
                    responsiveLayout="scroll">
                    <template #header>
                        <div class="flex flex-column md:flex-row md:justify-content-between md:align-items-center">
                            <h5 class="m-0">Manage Users</h5>
                            <span class="block mt-2 md:mt-0 p-input-icon-left">
                                <i class="pi pi-search" />
                                <InputText v-model="filters['global'].value" placeholder="Search..." />
                            </span>
                        </div>
                    </template>

                    <template #empty> No Users found. </template>

                    <Column selectionMode="multiple" headerStyle="width: 3rem"></Column>
                    <Column field="id" header="Id" :sortable="true" headerStyle="width:14%; min-width:10rem;">
                        <template  #body="slotProps">
                            <span class="p-column-title">Code</span>
                            {{ slotProps.data.id }}
                        </template>
                    </Column>

                    <Column field="name" header="Name" :sortable="true" headerStyle="width:40%; min-width:10rem;">
                        <template #body="slotProps">
                            <span class="p-column-title">Name</span>
                            {{ slotProps.data.name }}
                        </template>
                    </Column>

                    <Column field="email" header="Email" :sortable="true" headerStyle="width:40%; min-width:10rem;">
                        <template #body="slotProps">
                            <span class="p-column-title">Email</span>
                            {{ slotProps.data.email }}
                        </template>
                    </Column>

                    
                    <Column field="created_at" header="Creation Date" :sortable="true" headerStyle="width:40%; min-width:10rem;">
                        <template #body="slotProps">
                            <span class="p-column-title">Created At</span>
                            {{ slotProps.data.created_at }}
                        </template>
                    </Column>

                    <Column headerStyle="min-width:10rem;">
                        <template #body="slotProps">
                            <Button icon="pi pi-pencil" class="p-button-rounded p-button-success mr-2"
                                @click="openEditModal(slotProps.data)" />
                            <Button icon="pi pi-trash" class="p-button-rounded p-button-warning mt-2"
                                @click="confirmDeleteUser(slotProps.data)" />
                        </template>
                    </Column>
                    
                </DataTable>

                <CreateUserModal @hideCreateModal="hideCreateModal" :toggleCreateModal="toggleCreateModal">
                </CreateUserModal>

                <EditUserModal @hideEditModal="hideEditModal" :toggleEditModal="toggleEditModal" :user="user">
                </EditUserModal>

                <!-- Delete Single User -->
                <Dialog v-model:visible="deleteUserDialog" :style="{ width: '450px' }" header="Confirm" :modal="true">
                    <div class="flex align-items-center justify-content-center">
                        <i class="pi pi-exclamation-triangle mr-3" style="font-size: 2rem" />
                        <span v-if="user">Are you sure you want to delete <b>{{ user.name }}</b>?</span>
                    </div>
                    <template #footer>
                        <Button label="No" icon="pi pi-times" class="p-button-text" @click="deleteUserDialog = false" />
                        <Button label="Yes" icon="pi pi-check" class="p-button-text" @click="deleteUser" />
                    </template>
                </Dialog>


                <!-- Delete Multiple User -->
                <Dialog v-model:visible="deleteUsersDialog" :style="{ width: '450px' }" header="Confirm" :modal="true">
                    <div class="flex align-items-center justify-content-center">
                        <i class="pi pi-exclamation-triangle mr-3" style="font-size: 2rem" />
                        <span v-if="users">Are you sure you want to delete the selected users?</span>
                    </div>
                    <template #footer>
                        <Button label="No" icon="pi pi-times" class="p-button-text"
                            @click="deleteUsersDialog = false" />
                        <Button label="Yes" icon="pi pi-check" class="p-button-text" @click="deleteSelectedUsers" />
                    </template>
                </Dialog>
            </div>
        </div>
    </div>
</template>

<style scoped lang="scss"></style>
