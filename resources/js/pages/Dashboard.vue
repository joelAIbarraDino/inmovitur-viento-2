<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { AppPageProps, DataTowers, type BreadcrumbItem } from '@/types';
import { Head, usePage } from '@inertiajs/vue3';
import { dashboard } from '@/routes';
import { TowerCard } from '@/components/DashboardCard';
import { computed } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: dashboard().url,
    },
];

interface DashboardPageProps extends AppPageProps{
    towers:DataTowers[]
}

const page = usePage<DashboardPageProps>();
const towers = computed(()=>page.props.towers);

</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-wrap flex-col md:flex-row items-center justify-evenly w-full md:w-9/10  mx-auto py-20 px-5 space-y-20 md:space-y-0">
            <TowerCard v-for="tower in towers":key="tower.towerName" v-bind="tower"/>
        </div>
    </AppLayout>
</template>
