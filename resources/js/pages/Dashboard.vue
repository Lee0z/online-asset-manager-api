<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, usePage } from '@inertiajs/vue3';
import FixedAssetTable from '../components/FixedAssetTable.vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Środki trwałe',
        href: '/dashboard',
    },
];

const assets = (usePage().props.assets ?? []) as any[];
const companyName = (usePage().props.company as { company_name?: string })?.company_name ?? '';
</script>

<template>
    <Head :title="companyName || 'Online Asset Manager'" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">

            <div class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 dark:border-sidebar-border md:min-h-min">
                <FixedAssetTable :assets="assets" />
            </div>
        </div>
    </AppLayout>
</template>
