<script setup>
import CreateProductModal from './partials/CreateCategory.vue';
import EditProductModal from './partials/EditCategory.vue';
import { FilterMatchMode } from 'primevue/api';
import { ref, onMounted, onBeforeMount } from 'vue';
import { useToast } from 'primevue/usetoast';
import { useStore } from 'vuex';
import { computed } from 'vue';

const toast = useToast();
const store = useStore();

const categories = computed(() => store.getters['category/categories']);

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
const openEditModal = (editCategory) => {
    category.value = { ...editCategory };
    toggleEditModal.value = true;
};
const hideEditModal = () => {
    toggleEditModal.value = false;
};

const selectedCategories = ref(null);
const deleteCategoryDialog = ref(false);
const deleteCategoriesDialog = ref(false);
const category = ref({});
const selectedCategory = ref(null);
const dt = ref(null);
const filters = ref({});

onBeforeMount(() => {
    initFilters();
});
onMounted(async () => {
    await store.dispatch('category/getCategories');
});
const formatCurrency = (value) => {
    return value.toLocaleString('en-US', { style: 'currency', currency: 'USD' });
};

const confirmDeleteCategory = (editCategory) => {
    category.value = editCategory;
    deleteCategoryDialog.value = true;
};

const deleteCategory = async () => {
    try {
        let response = await store.dispatch('category/deleteCategory', category.value);
        deleteCategoryDialog.value = false;
        category.value = {};
        await store.dispatch('category/getCategories');
        toast.add({ severity: 'success', summary: 'Successful', detail: 'Category Deleted', life: 3000 });
    } catch (e) {
        toast.add({ severity: 'error', summary: 'Error', detail: 'Something Went Wrong', life: 3000 });
    }
};

const exportCSV = () => {
    dt.value.exportCSV();
};

const confirmDeleteSelected = () => {
    deleteCategoriesDialog.value = true;
};
const deleteSelectedCategories = async () => {
    try{
        let response = await store.dispatch('category/deleteMultiCategories',selectedCategories.value)
        toast.add({severity:'success',summary:'Success',detail:'Categories Deleted',life:3000})
        await store.dispatch('category/getCategories')
    }catch(e){
        toast.add({severity:'error',summary:'Error',detail:'Error Occurred',life:3000})
    }
    deleteCategoriesDialog.value = false;
    selectedCategories.value = null;
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
                            <Button label="Delete" icon="pi pi-trash" class="p-button-danger" @click="confirmDeleteSelected" :disabled="!selectedCategories || !selectedCategories.length" />
                        </div>
                    </template>

                    <template v-slot:end>
                        <FileUpload mode="basic" accept="image/*" :maxFileSize="1000000" label="Import" chooseLabel="Import" class="mr-2 inline-block" />
                        <Button label="Export" icon="pi pi-upload" class="p-button-help" @click="exportCSV($event)" />
                    </template>
                </Toolbar>

                <DataTable
                    ref="dt"
                    :value="categories"
                    v-model:selection="selectedCategories"
                    dataKey="id"
                    :paginator="true"
                    :rows="10"
                    :filters="filters"
                    paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
                    :rowsPerPageOptions="[5, 10, 25]"
                    currentPageReportTemplate="Showing {first} to {last} of {totalRecords} Categories"
                    responsiveLayout="scroll"
                >
                    <template #header>
                        <div class="flex flex-column md:flex-row md:justify-content-between md:align-items-center">
                            <h5 class="m-0">Manage Categories</h5>
                            <span class="block mt-2 md:mt-0 p-input-icon-left">
                                <i class="pi pi-search" />
                                <InputText v-model="filters['global'].value" placeholder="Search..." />
                            </span>
                        </div>
                    </template>

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
                            <Button icon="pi pi-pencil" class="p-button-rounded p-button-success mr-2" @click="openEditModal(slotProps.data)" />
                            <Button icon="pi pi-trash" class="p-button-rounded p-button-warning mt-2" @click="confirmDeleteCategory(slotProps.data)" />
                        </template>
                    </Column>
                </DataTable>

                <CreateProductModal @hideCreateModal="hideCreateModal" :toggleCreateModal="toggleCreateModal"></CreateProductModal>

                <EditProductModal @hideEditModal="hideEditModal" :toggleEditModal="toggleEditModal" :category="category"></EditProductModal>

                <!-- Delete Single Category -->
                <Dialog v-model:visible="deleteCategoryDialog" :style="{ width: '450px' }" header="Confirm" :modal="true">
                    <div class="flex align-items-center justify-content-center">
                        <i class="pi pi-exclamation-triangle mr-3" style="font-size: 2rem" />
                        <span v-if="category"
                            >Are you sure you want to delete <b>{{ category.name }}</b
                            >?</span
                        >
                    </div>
                    <template #footer>
                        <Button label="No" icon="pi pi-times" class="p-button-text" @click="deleteCategoryDialog = false" />
                        <Button label="Yes" icon="pi pi-check" class="p-button-text" @click="deleteCategory" />
                    </template>
                </Dialog>


                <!-- Delete Multiple Category -->
                <Dialog v-model:visible="deleteCategoriesDialog" :style="{ width: '450px' }" header="Confirm" :modal="true">
                    <div class="flex align-items-center justify-content-center">
                        <i class="pi pi-exclamation-triangle mr-3" style="font-size: 2rem" />
                        <span v-if="categories">Are you sure you want to delete the selected categories?</span>
                    </div>
                    <template #footer>
                        <Button label="No" icon="pi pi-times" class="p-button-text" @click="deleteCategoriesDialog = false" />
                        <Button label="Yes" icon="pi pi-check" class="p-button-text" @click="deleteSelectedCategories" />
                    </template>
                </Dialog>
            </div>
        </div>
    </div>
</template>

<style scoped lang="scss"></style>
