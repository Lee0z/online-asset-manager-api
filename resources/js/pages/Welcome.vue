<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import { ref } from 'vue';

const companyName = ref('');
const loading = ref(false);

function submitCompanyInfo() {
    if (!companyName.value) return;
    loading.value = true;
    router.post('/company-info', { company_name: companyName.value }, {
        onSuccess: (page) => {
            loading.value = false;
            if (page.props && page.props.id) {
                router.visit(`/company-info/${page.props.id}`);
            } else {
                router.visit(`/company-info/1`); // fallback
            }
        },
        onError: () => { loading.value = false; },
    });
}
</script>

<template>
    <Head title="Konfiguracja">
        <link rel="preconnect" href="https://rsms.me/" />
        <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />
    </Head>
    <div class="flex min-h-screen flex-col items-center justify-center bg-[#FDFDFC] p-6 text-[#1b1b18] dark:bg-[#0a0a0a] lg:justify-center lg:p-8">
        <header class="mb-6 w-full max-w-[335px] text-sm lg:max-w-4xl">
            <h1 class="text-center text-5xl font-bold text-gray-500">Online Asset Manager - Konfiguracja</h1>
        </header>
        <div class="text-center mt-6">
            <p class="text-xl font-medium text-gray-500">Jak nazywa się Twoja firma?</p>
            <input 
                type="text" 
                class="mt-4 w-80 rounded border border-gray-900 p-2 text-sm focus:outline-none focus:ring-2 focus:ring-gray-500 text-gray-500" 
                placeholder="Wprowadź nazwę firmy" 
                v-model="companyName"
                :disabled="loading"
            />
            <button 
                class="mt-6 w-20 rounded bg-gray-500 py-2 text-sm font-medium text-white hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500"
                :disabled="loading"
                @click="submitCompanyInfo"
            >
                Dalej -> 
            </button>
        </div>
    </div>
</template>
