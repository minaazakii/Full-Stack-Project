<script setup>
import CreateOrderModal from './partials/CreateOrder.vue';
import EditOrderModal from './partials/EditOrder.vue';
import { FilterMatchMode } from 'primevue/api';
import { ref, onMounted, onBeforeMount } from 'vue';
import { useToast } from 'primevue/usetoast';
import { useStore } from 'vuex';
import { computed } from 'vue';

const toast = useToast();
const store = useStore();
const loading = computed(() => store.getters['loading'])

const orders = computed(() => store.getters['order/orders']);

const getSeverity = (order) => {
    switch (order.status) {
        case 'inprogress':
            return 'warning';

        case 'completed':
            return 'success';

        case 'cancelled':
            return 'danger';

        default:
            return null;
    }
};

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
const openEditModal = (editOrder) => {
    order.value = { ...editOrder };
    toggleEditModal.value = true;
};
const hideEditModal = () => {
    toggleEditModal.value = false;
};

const selectedOrders = ref(null);
const deleteOrderDialog = ref(false);
const deleteOrdersDialog = ref(false);
const order = ref({});
const selectedOrder = ref(null);
const dt = ref(null);
const filters = ref({});

onBeforeMount(() => {
    initFilters();
});
onMounted(async () => {
    store.dispatch('startLoading')
    await store.dispatch('order/getOrders');
    store.dispatch('stopLoading')
});
const formatCurrency = (value) => {
    return value.toLocaleString('en-US', { style: 'currency', currency: 'USD' });
};

const confirmDeleteOrder = (editOrder) => {
    order.value = editOrder;
    deleteOrderDialog.value = true;
};

const deleteOrder = async () => {
    try {
        let response = await store.dispatch('order/deleteOrder', order.value);
        deleteOrderDialog.value = false;
        order.value = {};
        await store.dispatch('order/getOrders');
        toast.add({ severity: 'success', summary: 'Successful', detail: 'order Deleted', life: 3000 });
    } catch (e) {
        toast.add({ severity: 'error', summary: 'Error', detail: 'Something Went Wrong', life: 3000 });
    }
};

const exportCSV = () => {
    dt.value.exportCSV();
};

const confirmDeleteSelected = () => {
    deleteOrdersDialog.value = true;
};
const deleteSelectedOrders = async () => {
    try {
        let response = await store.dispatch('order/deleteMultiOrders', selectedOrders.value)
        toast.add({ severity: 'success', summary: 'Success', detail: 'Orders Deleted', life: 3000 })
        await store.dispatch('order/getOrders')
    } catch (e) {
        toast.add({ severity: 'error', summary: 'Error', detail: 'Error Occurred', life: 3000 })
    }
    deleteOrdersDialog.value = false;
    selectedOrders.value = null;
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
                                :disabled="!selectedOrders || !selectedOrders.length" />
                        </div>
                    </template>

                    <template v-slot:end>
                        <FileUpload mode="basic" accept="image/*" :maxFileSize="1000000" label="Import" chooseLabel="Import"
                            class="mr-2 inline-block" />
                        <Button label="Export" icon="pi pi-upload" class="p-button-help" @click="exportCSV($event)" />
                    </template>
                </Toolbar>

                <DataTable :loading="loading" ref="dt" :value="orders" v-model:selection="selectedOrders" dataKey="id"
                    :paginator="true" :rows="10" :filters="filters"
                    paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
                    :rowsPerPageOptions="[5, 10, 25]"
                    currentPageReportTemplate="Showing {first} to {last} of {totalRecords} Orders"
                    responsiveLayout="scroll">
                    <template #header>
                        <div class="flex flex-column md:flex-row md:justify-content-between md:align-items-center">
                            <h5 class="m-0">Manage Orders</h5>
                            <span class="block mt-2 md:mt-0 p-input-icon-left">
                                <i class="pi pi-search" />
                                <InputText v-model="filters['global'].value" placeholder="Search..." />
                            </span>
                        </div>
                    </template>
                    <template #empty> No Orders found. </template>
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

                    <Column field="status" header="Status" :sortable="true" headerStyle="width:40%; min-width:10rem;">
                        <template #body="slotProps">
                            <span class="p-column-title">Status</span>
                            <Tag :value="slotProps.data.status" :severity="getSeverity(slotProps.data)" />
                        </template>
                    </Column>

                    <Column field="amount" header="Discount Amount" :sortable="true"
                        headerStyle="width:40%; min-width:10rem;">
                        <template #body="slotProps">
                            <span class="p-column-title">Discount Amount</span>
                            {{ slotProps.data.amount }}
                        </template>
                    </Column>

                    <Column field="discountType" header="Discount Type" :sortable="true"
                        headerStyle="width:40%; min-width:10rem;">
                        <template #body="slotProps">
                            <span class="p-column-title">Discount Type</span>
                            {{ slotProps.data.discountType }}
                        </template>
                    </Column>

                    <Column headerStyle="min-width:10rem;">
                        <template #body="slotProps">
                            <Button icon="pi pi-pencil" class="p-button-rounded p-button-warning mr-2"
                                @click="openEditModal(slotProps.data)" />
                            <Button icon="pi pi-trash" class="p-button-rounded p-button-danger mt-2"
                                @click="confirmDeleteOrder(slotProps.data)" />
                        </template>
                    </Column>

                </DataTable>

                <CreateOrderModal @hideCreateModal="hideCreateModal" :toggleCreateModal="toggleCreateModal">
                </CreateOrderModal>

                <EditOrderModal v-if="toggleEditModal" @hideEditModal="hideEditModal" :toggleEditModal="toggleEditModal"
                    :order="order">
                </EditOrderModal>

                <!-- Delete Single Category -->
                <Dialog v-model:visible="deleteOrderDialog" :style="{ width: '450px' }" header="Confirm" :modal="true">
                    <div class="flex align-items-center justify-content-center">
                        <i class="pi pi-exclamation-triangle mr-3" style="font-size: 2rem" />
                        <span v-if="order">Are you sure you want to delete <b>{{ order.name }}</b>?</span>
                    </div>
                    <template #footer>
                        <Button label="No" icon="pi pi-times" class="p-button-text" @click="deleteOrderDialog = false" />
                        <Button label="Yes" icon="pi pi-check" class="p-button-text" @click="deleteOrder" />
                    </template>
                </Dialog>


                <!-- Delete Multiple Category -->
                <Dialog v-model:visible="deleteOrdersDialog" :style="{ width: '450px' }" header="Confirm" :modal="true">
                    <div class="flex align-items-center justify-content-center">
                        <i class="pi pi-exclamation-triangle mr-3" style="font-size: 2rem" />
                        <span v-if="orders">Are you sure you want to delete the selected orders?</span>
                    </div>
                    <template #footer>
                        <Button label="No" icon="pi pi-times" class="p-button-text" @click="deleteOrdersDialog = false" />
                        <Button label="Yes" icon="pi pi-check" class="p-button-text" @click="deleteSelectedOrders" />
                    </template>
                </Dialog>
            </div>
        </div>
    </div>
</template>

<style scoped lang="scss"></style>
