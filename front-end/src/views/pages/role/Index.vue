<script setup>
import CreateProductModal from './partials/CreateRole.vue';
import EditProductModal from './partials/EditRole.vue';
import { FilterMatchMode } from 'primevue/api';
import { ref, onMounted, onBeforeMount } from 'vue';
import { useToast } from 'primevue/usetoast';
import { useStore } from 'vuex';
import { computed } from 'vue';

const toast = useToast();
const store = useStore();
const loading = computed(() => store.getters['loading']);

const roles = computed(() => store.getters['role/roles']);

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
const openEditModal = (editRole) => {
    role.value = { ...editRole };
    toggleEditModal.value = true;
};
const hideEditModal = () => {
    toggleEditModal.value = false;
};

const selectedRoles = ref(null);
const deleteRoleDialog = ref(false);
const deleteRolesDialog = ref(false);
const role = ref({});
const selectedRole = ref(null);
const dt = ref(null);
const filters = ref({});

onBeforeMount(() => {
    initFilters();
});
onMounted(async () => {
    store.dispatch('startLoading')
    await store.dispatch('role/getRoles');
    store.dispatch('stopLoading')
});
const formatCurrency = (value) => {
    return value.toLocaleString('en-US', { style: 'currency', currency: 'USD' });
};

const confirmDeleteRole = (editRole) => {
    role.value = editRole;
    deleteRoleDialog.value = true;
};

const deleteRole = async () => {
    try {
        let response = await store.dispatch('role/deleteRole', role.value);
        deleteRoleDialog.value = false;
        role.value = {};
        await store.dispatch('role/getRoles');
        toast.add({ severity: 'success', summary: 'Successful', detail: 'Role Deleted', life: 3000 });
    } catch (e) {
        toast.add({ severity: 'error', summary: 'Error', detail: 'Something Went Wrong', life: 3000 });
    }
};

const exportCSV = () => {
    dt.value.exportCSV();
};

const confirmDeleteSelected = () => {
    deleteRolesDialog.value = true;
};
const deleteSelectedRoles = async () => {
    try {
        let response = await store.dispatch('role/deleteMultiRoles', selectedRoles.value)
        toast.add({ severity: 'success', summary: 'Success', detail: 'Roles Deleted', life: 3000 })
        await store.dispatch('role/getRoles')
    } catch (e) {
        toast.add({ severity: 'error', summary: 'Error', detail: 'Error Occurred', life: 3000 })
    }
    deleteRolesDialog.value = false;
    selectedRoles.value = null;
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
                            <Button label="New Role" icon="pi pi-plus" class="p-button-success mr-2" @click="openCreateModal" />
                            <Button label="Assign Role To User" icon="pi pi-user-plus" class="p-button-success mr-2" @click="openCreateModal" />
                            <Button label="Delete" icon="pi pi-trash" class="p-button-danger" @click="confirmDeleteSelected"
                                :disabled="!selectedRoles || !selectedRoles.length" />
                        </div>
                    </template>

                    <template v-slot:end>
                        <FileUpload mode="basic" accept="image/*" :maxFileSize="1000000" label="Import" chooseLabel="Import"
                            class="mr-2 inline-block" />
                        <Button label="Export" icon="pi pi-upload" class="p-button-help" @click="exportCSV($event)" />
                    </template>
                </Toolbar>

                <DataTable :loading="loading" ref="dt" :value="roles" v-model:selection="selectedRoles"
                    dataKey="id" :paginator="true" :rows="10" :filters="filters"
                    paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
                    :rowsPerPageOptions="[5, 10, 25]"
                    currentPageReportTemplate="Showing {first} to {last} of {totalRecords} Roles"
                    responsiveLayout="scroll">
                    <template #header>
                        <div class="flex flex-column md:flex-row md:justify-content-between md:align-items-center">
                            <h5 class="m-0">Manage Roles</h5>
                            <span class="block mt-2 md:mt-0 p-input-icon-left">
                                <i class="pi pi-search" />
                                <InputText v-model="filters['global'].value" placeholder="Search..." />
                            </span>
                        </div>
                    </template>

                    <template #empty> No Roles found. </template>

                    <Column selectionMode="multiple" headerStyle="width: 3rem"></Column>
                    <Column field="id" header="Id" :sortable="true" headerStyle="width:14%; min-width:10rem;">
                        <template #body="slotProps">
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
                    <Column headerStyle="min-width:10rem;">
                        <template #body="slotProps">
                            <Button icon="pi pi-pencil" class="p-button-rounded p-button-success mr-2"
                                @click="openEditModal(slotProps.data)" />
                            <Button icon="pi pi-trash" class="p-button-rounded p-button-warning mt-2"
                                @click="confirmDeleteRole(slotProps.data)" />
                        </template>
                    </Column>
                </DataTable>

                <CreateProductModal @hideCreateModal="hideCreateModal" :toggleCreateModal="toggleCreateModal">
                </CreateProductModal>

                <EditProductModal @hideEditModal="hideEditModal" :toggleEditModal="toggleEditModal" :role="role">
                </EditProductModal>

                <!-- Delete Single Role -->
                <Dialog v-model:visible="deleteRoleDialog" :style="{ width: '450px' }" header="Confirm" :modal="true">
                    <div class="flex align-items-center justify-content-center">
                        <i class="pi pi-exclamation-triangle mr-3" style="font-size: 2rem" />
                        <span v-if="role">Are you sure you want to delete <b>{{ role.name }}</b>?</span>
                    </div>
                    <template #footer>
                        <Button label="No" icon="pi pi-times" class="p-button-text" @click="deleteRoleDialog = false" />
                        <Button label="Yes" icon="pi pi-check" class="p-button-text" @click="deleteRole" />
                    </template>
                </Dialog>


                <!-- Delete Multiple Role -->
                <Dialog v-model:visible="deleteRolesDialog" :style="{ width: '450px' }" header="Confirm" :modal="true">
                    <div class="flex align-items-center justify-content-center">
                        <i class="pi pi-exclamation-triangle mr-3" style="font-size: 2rem" />
                        <span v-if="roles">Are you sure you want to delete the selected roles?</span>
                    </div>
                    <template #footer>
                        <Button label="No" icon="pi pi-times" class="p-button-text"
                            @click="deleteRolesDialog = false" />
                        <Button label="Yes" icon="pi pi-check" class="p-button-text" @click="deleteSelectedRoles" />
                    </template>
                </Dialog>
            </div>
        </div>
    </div>
</template>

<style scoped lang="scss"></style>
