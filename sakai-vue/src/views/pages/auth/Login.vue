<script setup>
import { useLayout } from '@/layout/composables/layout';
import { ref, computed } from 'vue';
import AppConfig from '@/layout/AppConfig.vue';
import router from '../../../router/index';
import InputText from 'primevue/inputtext';
import { useStore } from 'vuex';
import { useToast } from 'primevue/usetoast';

const { layoutConfig } = useLayout();
const store = useStore();
const toast = useToast();
let loading = computed(() => store.getters['loading']);

const submitted = ref(false);
let user = ref({});
const attemptLogin = async () => {
    submitted.value = true;
    if (user.value.email && user.value.password) {
        try {
            store.dispatch('startLoading');
            await store.dispatch('auth/login', user.value);
            toast.add({ severity: 'success', summary: 'Success', detail: 'Logged in Successfully', life: 3000 });
            store.dispatch('stopLoading');
            submitted.value=false
            user.value = {}
            router.push({ name: 'dashboard' });
        } catch (e) {
            toast.add({ severity: 'error', summary: 'Error', detail: 'Wrong Email or Password', life: 3000 });
            store.dispatch('stopLoading');
        }
    }
    
};

const logoUrl = computed(() => {
    return `layout/images/${layoutConfig.darkTheme.value ? 'logo-white' : 'logo-dark'}.svg`;
});
</script>

<template>
    <Toast />
    <div class="surface-ground flex align-items-center justify-content-center min-h-screen min-w-screen overflow-hidden">
        <div class="flex flex-column align-items-center justify-content-center">
            <img :src="logoUrl" alt="Sakai logo" class="mb-5 w-6rem flex-shrink-0" />
            <div style="border-radius: 56px; padding: 0.3rem; background: linear-gradient(180deg, var(--primary-color) 10%, rgba(33, 150, 243, 0) 30%)">
                <div class="w-full surface-card py-8 px-5 sm:px-8" style="border-radius: 53px">
                    <div class="text-center mb-5">
                        <img src="/demo/images/login/avatar.png" alt="Image" height="50" class="mb-3" />
                        <div class="text-900 text-3xl font-medium mb-3">Welcome, Isabel!</div>
                        <span class="text-600 font-medium">Sign in to continue</span>
                    </div>

                    <div>
                        <label for="email1" class="block text-900 text-xl font-medium mb-2">Email</label>
                        <InputText :class="{ 'p-invalid': submitted && !user.email }" id="email1" type="email" placeholder="Email address" class="w-full md:w-30rem mb-5" style="padding: 1rem" v-model.trim="user.email" />

                        <label for="email1" class="block text-900 text-xl font-medium mb-2">Password</label>
                        <InputText :class="{ ' p-invalid': submitted && !user.password }" id="password" type="password" placeholder="Password" class="w-full md:w-30rem mb-5" style="padding: 1rem" v-model.trim="user.password" />

                        <div class="flex align-items-center justify-content-between mb-5 gap-5">
                            <div class="flex align-items-center">
                                <Checkbox v-model="checked" id="rememberme1" binary class="mr-2"></Checkbox>
                                <label for="rememberme1">Remember me</label>
                            </div>
                            <router-link :to="{ name: 'register' }" class="font-medium no-underline ml-2 text-right cursor-pointer" style="color: var(--primary-color)">Don't Have account?</router-link>
                        </div>
                        <Button :loading="loading" label="Sign In" @click="attemptLogin()" class="w-full p-3 text-xl"></Button>
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
}
</style>
