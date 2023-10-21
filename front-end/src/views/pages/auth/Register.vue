<script setup>
import { useLayout } from '@/layout/composables/layout';
import { ref, computed } from 'vue';
import AppConfig from '@/layout/AppConfig.vue';
import { useToast } from 'primevue/usetoast';
import { useStore } from 'vuex';
import router from '../../../router';

const { layoutConfig } = useLayout();
const toast = useToast();
const store = useStore();

const loading = computed(() => store.getters['loading']);
const logoUrl = computed(() => {
    return `layout/images/${layoutConfig.darkTheme.value ? 'logo-white' : 'logo-dark'}.svg`;
});

let submitted = ref(false);
const user = ref({});

const submitForm = async () => {
    submitted.value = true;
    try {
        if (user.value.email && user.value.password && user.value.name) {
            store.dispatch('startLoading');
            await store.dispatch('auth/register', user.value);
            toast.add({ severity: 'success', summary: 'Success', detail: 'Registered Successfully', life: 3000 });
            store.dispatch('stopLoading')
            submitted.value = false;
            user.value = {};
            router.push({ name: 'login' })
        }
    } catch (e) {
        toast.add({ severity: 'error', summary: 'Error', detail: 'Error Occurred', life: 3000 });
        store.dispatch('stopLoading')
    }
};
</script>

<template>
    <Toast />
    <div class="surface-ground flex align-items-center justify-content-center min-h-screen min-w-screen overflow-hidden">
        <div class="flex flex-column align-items-center justify-content-center">
            <img :src="logoUrl" alt="Sakai logo" class="mb-5 w-6rem flex-shrink-0" />
            <div
                style="border-radius: 56px; padding: 0.3rem; background: linear-gradient(180deg, var(--primary-color) 10%, rgba(33, 150, 243, 0) 30%)">
                <div class="w-full surface-card py-8 px-5 sm:px-8" style="border-radius: 53px">
                    <div class="text-center mb-5">
                        <img src="/demo/images/login/avatar.png" alt="Image" height="50" class="mb-3" />
                        <div class="text-900 text-3xl font-medium mb-3">Welcome, Isabel!</div>
                        <span class="text-600 font-medium">Sign Up to continue</span>
                    </div>

                    <div>
                        <label for="email1" class="block text-900 text-xl font-medium mb-2">Name</label>
                        <InputText v-model.trim="user.name" :class="{ 'p-invalid': submitted && !user.name }" id="email1"
                            type="text" placeholder="Name" class="w-full md:w-30rem mb-5" style="padding: 1rem" />

                        <label for="email1" class="block text-900 text-xl font-medium mb-2">Email</label>
                        <InputText required="true" v-model.trim="user.email"
                            :class="{ 'p-invalid': submitted && !user.email }" type="email" placeholder="Email address"
                            class="w-full md:w-30rem mb-5" style="padding: 1rem" />


                        <label for="password1" class="block text-900 font-medium text-xl mb-2">Password</label>
                        <Password id="password1" :class="{ 'p-invalid': submitted && !user.password }"
                            v-model.trim="user.password" placeholder="Password" class="w-full mb-3" inputClass="w-full"
                            :inputStyle="{ padding: '1rem' }"></Password>

                        <Button @click="submitForm()" :loading="loading" label="Sign Up"
                            class="w-full p-3 text-xl mt-5"></Button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <AppConfig simple />
</template>

<style scoped>
.pi-eye {
    transform: scale(1.6);
    margin-right: 1rem;
}

.pi-eye-slash {
    transform: scale(1.6);
    margin-right: 1rem;
}</style>
