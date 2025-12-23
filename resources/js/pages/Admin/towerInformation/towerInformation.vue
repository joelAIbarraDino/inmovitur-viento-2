<script setup lang="ts">
import TowerInfoCard from '@/components/DashboardCard/TowerInfoCard.vue';
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/components/ui/tabs'
import AppLayout from '@/layouts/AppLayout.vue';
import { AppPageProps, BreadcrumbItem, DataTowers } from '@/types';
import { Head, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [
    {title:"Información de torres", href:"#"},
];

interface DashboardPageProps extends AppPageProps{
    towers:DataTowers[]
}

const page = usePage<DashboardPageProps>();
const towers = computed(()=>page.props.towers);

</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <Head title="Información de torres"/>
        <div class="p-4">
            <Tabs default-value="torreA">
                <TabsList>
                    <TabsTrigger value="torreA">Torre A</TabsTrigger>
                    <TabsTrigger value="torreB">Torre B</TabsTrigger>
                    <TabsTrigger value="torreC">Torre C</TabsTrigger>
                </TabsList>
                <TabsContent value="torreA">
                    <TowerInfoCard :data="towers[0]" />
                </TabsContent>

                <TabsContent value="torreB">
                    <TowerInfoCard :data="towers[1]" />
                </TabsContent>
                <TabsContent value="torreC">
                    <TowerInfoCard :data="towers[2]" />
                </TabsContent>
            </Tabs>

        </div>

    </AppLayout>
</template>