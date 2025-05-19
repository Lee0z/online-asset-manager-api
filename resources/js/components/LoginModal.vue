<script setup lang="ts">
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';

defineProps<{ adminSetup?: boolean }>();

const email = ref('');
const password = ref('');
const loading = ref(false);
const toast = ref('');
const errors = ref<{ [key: string]: string }>({});

function validateEmail(email: string) {
    return /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@(([^<>()[\]\\.,;:\s@"]+\.)+[^<>()[\]\\.,;:\s@"]{2,})$/.test(email);
}

function submitLogin() {
    errors.value = {};
    if (!validateEmail(email.value)) {
        errors.value.email = 'Podaj poprawny adres e-mail.';
    }
    if (!password.value) {
        errors.value.password = 'Podaj hasło.';
    }
    if (Object.keys(errors.value).length > 0) {
        loading.value = false;
        return;
    }
    loading.value = true;
    router.post('/login', {
        email: email.value,
        password: password.value,
    }, {
        onSuccess: () => {
            loading.value = false;
            toast.value = 'Zalogowano pomyślnie!';
            setTimeout(() => { toast.value = ''; }, 2000);
        },
        onError: () => { loading.value = false; },
    });
}
</script>

<template>
  <div class="flex min-h-screen flex-col items-center justify-center bg-[#FDFDFC] p-6 text-[#1b1b18] dark:bg-[#0a0a0a] lg:justify-center lg:p-8 fixed inset-0 z-50">
    <header class="mb-6 w-full max-w-[335px] text-sm lg:max-w-4xl">
      <h1 class="text-center text-4xl font-bold text-gray-500">Zaloguj się</h1>
    </header>
    <div class="flex flex-col items-center space-y-4">
      <input type="email" class="w-80 rounded border border-gray-900 p-2 text-sm text-gray-500" placeholder="Adres e-mail" v-model="email" :disabled="loading" />
      <div v-if="errors.email" class="text-red-500 text-xs">{{ errors.email }}</div>
      <input type="password" class="w-80 rounded border border-gray-900 p-2 text-sm text-gray-500" placeholder="Hasło" v-model="password" :disabled="loading" />
      <div v-if="errors.password" class="text-red-500 text-xs">{{ errors.password }}</div>
      <button class="w-80 rounded bg-gray-500 py-2 text-sm font-medium text-white hover:bg-gray-600" :disabled="loading" @click="submitLogin">
        Zaloguj
      </button>
    </div>
    <div v-if="toast" class="fixed bottom-8 left-1/2 transform -translate-x-1/2 bg-green-500 text-white px-6 py-3 rounded shadow-lg z-50">
      {{ toast }}
    </div>
  </div>
</template>
