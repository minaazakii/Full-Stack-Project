<script setup>
import CreateProductModal from './partials/CreatePermission.vue';
import EditProductModal from './partials/EditPermission.vue';
import { FilterMatchMode } from 'primevue/api';
import { ref, onMounted, onBeforeMount } from 'vue';
import { useToast } from 'primevue/usetoast';
import { useStore } from 'vuex';
import { computed } from 'vue';

const toast = useToast();
const store = useStore();
const loading = computed(() => store.getters['loading']);

const permissions = computed(() => store.getters['permission/permissions']);

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
const openEditModal = (editPermission) => {
    permission.value = { ...editPermission };
    toggleEditModal.value = true;
};
const hideEditModal = () => {
    toggleEditModal.value = false;
};

const selectedPermissions = ref(null);
const deletePermissionDialog = ref(false);
const deletePermissionsDialog = ref(false);
const permission = ref({});
const selectedPermission = ref(null);
const dt = ref(null);
const filters = ref({});

onBeforeMount(() => {
    initFilters();
});
onMounted(async () => {
    store.dispatch('startLoading')
    await store.dispatch('permission/getPermissions');
    store.dispatch('stopLoading')
});
const formatCurrency = (value) => {
    return value.toLocaleString('en-US', { style: 'currency', currency: 'USD' });
};

const confirmDeletePermission = (editPermission) => {
    permission.value = editPermission;
    deletePermissionDialog.value = true;
};

const deletePermission = async () => {
    try {
        let response = await store.dispatch('permission/deletePermission', permission.value);
        deletePermissionDialog.value = false;
        permission.value = {};
        await store.dispatch('permission/getPermissions');
        toast.add({ severity: 'success', summary: 'Successful', detail: 'Permission Deleted', life: 3000 });
    } catch (e) {
        toast.add({ severity: 'error', summary: 'Error', detail: 'Something Went Wrong', life: 3000 });
    }
};

const exportCSV = () => {
    dt.value.exportCSV();
};

const confirmDeleteSelected = () => {
    deletePermissionsDialog.value = true;
};
const deleteSelectedPermissions = async () => {
    try {
        let response = await store.dispatch('permission/deleteMultiPermissions', selectedPermissions.value)
        toast.add({ severity: 'success', summary: 'Success', detail: 'Permissions Deleted', life: 3000 })
        await store.dispatch('permission/getPermissions')
    } catch (e) {
        toast.add({ severity: 'error', summary: 'Error', detail: 'Error Occurred', life: 3000 })
    }
    deletePermissionsDialog.value = false;
    selectedPermissions.value = null;
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
                                :disabled="!selectedPermissions || !selectedPermissions.length" />
                        </div>
                    </template>

                    <template v-slot:end>
                        <FileUpload mode="basic" accept="image/*" :maxFileSize="1000000" label="Import" chooseLabel="Import"
                            class="mr-2 inline-block" />
                        <Button label="Export" icon="pi pi-upload" class="p-button-help" @click="exportCSV($event)" />
                    </template>
                </Toolbar>

                <DataTable :loading="loading" ref="dt" :value="permissions" v-model:selection="selectedPermissions"
                    dataKey="id" :paginator="true" :rows="10" :filters="filters"
                    paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
                    :rowsPerPageOptions="[5, 10, 25]"
                    currentPageReportTemplate="Showing {first} to {last} of {totalRecords} Permissions"
                    responsiveLayout="scroll">
                    <template #header>
                        <div class="flex flex-column md:flex-row md:justify-content-between md:align-items-center">
                            <h5 class="m-0">Manage Permissions</h5>
                            <span class="block mt-2 md:mt-0 p-input-icon-left">
                                <i class="pi pi-search" />
                                <InputText v-model="filters['global'].value" placeholder="Search..." />
                            </span>
                        </div>
                    </template>

                    <template #empty> No Permissions found. </template>

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
                            {{ slotProps.data.display }}
                        </template>
                    </Column>
                    <Column headerStyle="min-width:10rem;">
                        <template #body="slotProps">
                            <Button icon="pi pi-pencil" class="p-button-rounded p-button-success mr-2"
                                @click="openEditModal(slotProps.data)" />
                            <Button icon="pi pi-trash" class="p-button-rounded p-button-warning mt-2"
                                @click="confirmDeletePermission(slotProps.data)" />
                        </template>
                    </Column>
                </DataTable>

                <CreateProductModal @hideCreateModal="hideCreateModal" :toggleCreateModal="toggleCreateModal">
                </CreateProductModal>

                <EditProductModal @hideEditModal="hideEditModal" :toggleEditModal="toggleEditModal" :permission="permission">
                </EditProductModal>

                <!-- Delete Single Permission -->
                <Dialog v-model:visible="deletePermissionDialog" :style="{ width: '450px' }" header="Confirm" :modal="true">
                    <div class="flex align-items-center justify-content-center">
                        <i class="pi pi-exclamation-triangle mr-3" style="font-size: 2rem" />
                        <span v-if="permission">Are you sure you want to delete <b>{{ permission.name }}</b>?</span>
                    </div>
                    <template #footer>
                        <Button label="No" icon="pi pi-times" class="p-button-text" @click="deletePermissionDialog = false" />
                        <Button label="Yes" icon="pi pi-check" class="p-button-text" @click="deletePermission" />
                    </template>
                </Dialog>


                <!-- Delete Multiple Permission -->
                <Dialog v-model:visible="deletePermissionsDialog" :style="{ width: '450px' }" header="Confirm" :modal="true">
                    <div class="flex align-items-center justify-content-center">
                        <i class="pi pi-exclamation-triangle mr-3" style="font-size: 2rem" />
                        <span v-if="permissions">Are you sure you want to delete the selected permissions?</span>
                    </div>
                    <template #footer>
                        <Button label="No" icon="pi pi-times" class="p-button-text"
                            @click="deletePermissionsDialog = false" />
                        <Button label="Yes" icon="pi pi-check" class="p-button-text" @click="deleteSelectedPermissions" />
                    </template>
                </Dialog>
            </div>
        </div>
    </div>
</template>

<style scoped lang="scss"></style>
