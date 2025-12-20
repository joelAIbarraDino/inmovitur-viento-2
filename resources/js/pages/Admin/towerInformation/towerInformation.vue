<script setup lang="ts">
import TowerInfoCard from '@/components/DashboardCard/TowerInfoCard.vue';
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/components/ui/tabs'
import AppLayout from '@/layouts/AppLayout.vue';
import { BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { Currency } from 'lucide-vue-next';

const breadcrumbs: BreadcrumbItem[] = [
    {title:"Información de torres", href:"#"},
];

const towers = [
  { towerName:"Torre A", towerValue: 16147880.48, condominiums: 70, soldCondominiums: 67, condominiumsToSell:3, charged:9339483.68 , pending:6719002.02 , sold:16058485.70, inventory:89394.78, penality:0, currency:'USD' },
  { towerName:"Torre B", towerValue: 397085.31 , condominiums: 1, soldCondominiums: 1, condominiumsToSell:0, charged:84180.17  , pending:312905.14 , sold:397085.31 , inventory:89394.78, penality:0, currency:'USD' },
  { towerName:"Torre C", towerValue: 16147880.48, condominiums: 70, soldCondominiums: 67, condominiumsToSell:3, charged:9339483.68 , pending:6719002.02 , sold:16058485.70, inventory:89394.78, penality:0, currency:'USD' },
]

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