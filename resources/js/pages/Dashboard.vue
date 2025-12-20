<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { dashboard } from '@/routes';
import { TowerCard } from '@/components/DashboardCard';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: dashboard().url,
    },
];

const towers = [
  { name: 'Torre A', cobrados: 168204101.11, porCobrar: 121009226.42 },
  { name: 'Torre B', cobrados: 1516084.83, porCobrar: 5635421.64 },
  { name: 'Torre C', cobrados: 10025940.02, porCobrar: 19524577.63 }
]
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-wrap flex-col md:flex-row items-center justify-evenly w-full md:w-9/10  mx-auto py-20 px-5 space-y-20 md:space-y-0">
            <TowerCard
                v-for="tower in towers"
                :key="tower.name"
                v-bind="tower"
            />
        </div>
    </AppLayout>
</template>
