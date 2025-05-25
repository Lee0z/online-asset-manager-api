<script setup lang="ts">
import { usePage } from '@inertiajs/vue3';
const company = (usePage().props.company as { company_name?: string }) ?? {};
</script>

<template>
    <div class="ml-1 grid flex-1 text-left text-sm">
        <span class="font-bold text-gray-700 dark:text-gray-200">{{ company.company_name }}</span>
    </div>
</template>
