<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import FixedAssetTable from '@/components/FixedAssetTable.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { usePage } from '@inertiajs/vue3';

const assets = (usePage().props.assets ?? []) as any[];
const breadcrumbs = [
  { title: 'Środki trwałe', href: '/dashboard' },
];
</script>

<template>
  <Head title="Środki trwałe" />
  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="flex flex-col gap-4 p-4">
      <FixedAssetTable :assets="assets" />
    </div>
  </AppLayout>
</template>
