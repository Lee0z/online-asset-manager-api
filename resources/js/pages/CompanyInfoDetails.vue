<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import { usePage } from '@inertiajs/vue3';

interface CompanyProps {
    company?: { id?: number | string };
    id?: number | string;
    [key: string]: any;
}
const page = usePage() as { props: CompanyProps };
const companyId = page.props.company?.id || page.props.id || null;

const companyTaxNumber = ref('');
const companyStreet = ref('');
const companyStreetNumber = ref('');
const companyZipCode = ref('');
const companyCity = ref('');
const companyState = ref('');
const companyCountry = ref('');
const loading = ref(false);
const toast = ref('');
const errors = ref<{ [key: string]: string }>({});

function validateNip(nip: string) {
    // Polish NIP: 10 digits
    return /^\d{10}$/.test(nip);
}
function validateZip(zip: string) {
    // Only 5 digits
    return /^\d{5}$/.test(zip);
}

function formatZipInput(e: Event) {
    let val = (e.target as HTMLInputElement).value.replace(/[^\d]/g, '');
    val = val.slice(0, 5); // Only allow 5 digits
    // Add dash for display after 2 digits, but only digits are sent to backend
    let displayVal = val;
    if (val.length > 2) displayVal = val.slice(0, 2) + '-' + val.slice(2);
    (e.target as HTMLInputElement).value = displayVal;
    companyZipCode.value = val; // Only digits stored
}

function submitCompanyDetails() {
    errors.value = {};
    // Require all fields
    if (!companyTaxNumber.value) {
        errors.value.nip = 'NIP jest wymagany.';
    } else if (!validateNip(companyTaxNumber.value)) {
        errors.value.nip = 'NIP powinien mieć dokładnie 10 cyfr.';
    }
    if (!companyStreet.value) {
        errors.value.street = 'Ulica jest wymagana.';
    }
    if (!companyStreetNumber.value) {
        errors.value.streetNumber = 'Nr budynku jest wymagany.';
    }
    if (!companyZipCode.value) {
        errors.value.zip = 'Kod pocztowy jest wymagany.';
    } else if (!validateZip(companyZipCode.value)) {
        errors.value.zip = 'Kod pocztowy powinien mieć 5 cyfr.';
    }
    if (!companyCity.value) {
        errors.value.city = 'Miasto jest wymagane.';
    }
    if (!companyState.value) {
        errors.value.state = 'Województwo jest wymagane.';
    }
    if (!companyCountry.value) {
        errors.value.country = 'Kraj jest wymagany.';
    }
    if (Object.keys(errors.value).length > 0) {
        loading.value = false;
        return; // Do not proceed if validation fails
    }
    loading.value = true;
    router.post('/company-info/' + companyId + '/details', {
        company_tax_number: companyTaxNumber.value,
        company_street: companyStreet.value,
        company_street_number: companyStreetNumber.value,
        company_zip_code: companyZipCode.value,
        company_city: companyCity.value,
        company_state: companyState.value,
        company_country: companyCountry.value,
    }, {
        onSuccess: () => {
            loading.value = false;
            toast.value = 'Dane firmy zostały zapisane!';
            setTimeout(() => { toast.value = ''; router.visit('/admin-setup'); }, 1500);
        },
        onError: () => { loading.value = false; },
    });
}
</script>

<template>
    <Head title="Dane firmy">
        <link rel="preconnect" href="https://rsms.me/" />
        <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />
    </Head>
    <div class="flex min-h-screen flex-col items-center justify-center bg-[#FDFDFC] p-6 text-[#1b1b18] dark:bg-[#0a0a0a] lg:justify-center lg:p-8">
        <header class="mb-6 w-full max-w-[335px] text-sm lg:max-w-4xl">
            <h1 class="text-center text-4xl font-bold text-gray-500">Uzupełnij dane firmy</h1>
        </header>
        <div class="text-center mt-6">
            <div class="flex flex-col items-center space-y-4">
                <input type="text" class="w-80 rounded border border-gray-900 p-2 text-sm text-gray-500" placeholder="NIP" v-model="companyTaxNumber" :disabled="loading" required />
                <div v-if="errors.nip" class="text-red-500 text-xs">{{ errors.nip }}</div>
                <input type="text" class="w-80 rounded border border-gray-900 p-2 text-sm text-gray-500" placeholder="Ulica" v-model="companyStreet" :disabled="loading" required />
                <div v-if="errors.street" class="text-red-500 text-xs">{{ errors.street }}</div>
                <input type="text" class="w-80 rounded border border-gray-900 p-2 text-sm text-gray-500" placeholder="Nr budynku" v-model="companyStreetNumber" :disabled="loading" required />
                <div v-if="errors.streetNumber" class="text-red-500 text-xs">{{ errors.streetNumber }}</div>
                <input type="text" class="w-80 rounded border border-gray-900 p-2 text-sm text-gray-500" placeholder="Kod pocztowy" :value="companyZipCode.length > 2 ? companyZipCode.slice(0,2)+'-'+companyZipCode.slice(2) : companyZipCode" @input="formatZipInput" maxlength="6" :disabled="loading" required />
                <div v-if="errors.zip" class="text-red-500 text-xs">{{ errors.zip }}</div>
                <input type="text" class="w-80 rounded border border-gray-900 p-2 text-sm text-gray-500" placeholder="Miasto" v-model="companyCity" :disabled="loading" required />
                <div v-if="errors.city" class="text-red-500 text-xs">{{ errors.city }}</div>
                <input type="text" class="w-80 rounded border border-gray-900 p-2 text-sm text-gray-500" placeholder="Województwo" v-model="companyState" :disabled="loading" required />
                <div v-if="errors.state" class="text-red-500 text-xs">{{ errors.state }}</div>
                <input type="text" class="w-80 rounded border border-gray-900 p-2 text-sm text-gray-500" placeholder="Kraj" v-model="companyCountry" :disabled="loading" required />
                <div v-if="errors.country" class="text-red-500 text-xs">{{ errors.country }}</div>
                <button class="w-80 rounded bg-gray-500 py-2 text-sm font-medium text-white hover:bg-gray-600" :disabled="loading" @click="submitCompanyDetails">
                    Zapisz dane
                </button>
            </div>
        </div>
        <div v-if="toast" class="fixed bottom-8 left-1/2 transform -translate-x-1/2 bg-green-500 text-white px-6 py-3 rounded shadow-lg z-50">
            {{ toast }}
        </div>
    </div>
</template>

<style scoped>
@keyframes fade-in {
  from { opacity: 0; }
  to { opacity: 1; }
}
.animate-fade-in {
  animation: fade-in 1s ease;
}
</style>
