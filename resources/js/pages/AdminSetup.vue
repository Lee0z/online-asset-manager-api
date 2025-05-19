<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import { ref } from 'vue';

const adminName = ref('');
const adminEmail = ref('');
const adminPassword = ref('');
const loading = ref(false);
const toast = ref('');
const errors = ref<{ [key: string]: string }>({});
const passwordAdvice =
    'Hasło musi mieć co najmniej 8 znaków, zawierać wielką i małą literę oraz cyfrę.';

function validateEmail(email: string) {
    return /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@(([^<>()[\]\\.,;:\s@"]+\.)+[^<>()[\]\\.,;:\s@"]{2,})$/.test(email);
}
function validatePassword(password: string) {
    return /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/.test(password);
}

function submitAdminAccount() {
    errors.value = {};
    if (!validateEmail(adminEmail.value)) {
        errors.value.email = 'Podaj poprawny adres e-mail.';
    }
    if (!validatePassword(adminPassword.value)) {
        errors.value.password = passwordAdvice;
    }
    if (!adminName.value) {
        errors.value.name = 'Podaj login.';
    }
    if (Object.keys(errors.value).length > 0) {
        loading.value = false;
        return;
    }
    loading.value = true;
    router.post('/admin-setup', {
        name: adminName.value,
        email: adminEmail.value,
        password: adminPassword.value,
    }, {
        onSuccess: () => {
            loading.value = false;
            toast.value = 'Konto administratora zostało utworzone!';
            setTimeout(() => { toast.value = ''; }, 3000);
        },
        onError: () => { loading.value = false; },
    });
}
</script>

<template>
    <Head title="Tworzenie konta administratora">
        <link rel="preconnect" href="https://rsms.me/" />
        <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />
    </Head>
    <div class="flex min-h-screen flex-col items-center justify-center bg-[#FDFDFC] p-6 text-[#1b1b18] dark:bg-[#0a0a0a] lg:justify-center lg:p-8">
        <header class="mb-6 w-full max-w-[335px] text-sm lg:max-w-4xl">
            <h1 class="text-center text-4xl font-bold text-gray-500">Utwórz konto administratora</h1>
        </header>
        <div class="flex flex-col items-center space-y-4">
            <input type="text" class="w-80 rounded border border-gray-900 p-2 text-sm text-gray-500" placeholder="Login" v-model="adminName" :disabled="loading" />
            <div v-if="errors.name" class="text-red-500 text-xs">{{ errors.name }}</div>
            <input type="email" class="w-80 rounded border border-gray-900 p-2 text-sm text-gray-500" placeholder="Adres e-mail" v-model="adminEmail" :disabled="loading" />
            <div v-if="errors.email" class="text-red-500 text-xs">{{ errors.email }}</div>
            <input type="password" class="w-80 rounded border border-gray-900 p-2 text-sm text-gray-500" placeholder="Hasło" v-model="adminPassword" :disabled="loading" />
            <div class="text-gray-400 text-xs">{{ passwordAdvice }}</div>
            <div v-if="errors.password" class="text-red-500 text-xs">{{ errors.password }}</div>
            <button class="w-80 rounded bg-gray-500 py-2 text-sm font-medium text-white hover:bg-gray-600" :disabled="loading" @click="submitAdminAccount">
                Zapisz administratora
            </button>
        </div>
        <div v-if="toast" class="fixed bottom-8 left-1/2 transform -translate-x-1/2 bg-green-500 text-white px-6 py-3 rounded shadow-lg z-50">
            {{ toast }}
        </div>
    </div>
</template>
