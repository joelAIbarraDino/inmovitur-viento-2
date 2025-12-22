<script setup lang="ts">
import { ChartLine, DollarSign, DoorOpen, Hotel, Receipt, TableProperties, TrendingDown } from 'lucide-vue-next';
import ChartDataCard from './ChartDataCard.vue';
import HorizontalStackBar from './HorizontalStackBar.vue';

const props = defineProps<{
    data:{
        'towerName':string;
        'towerValue':number;
        'condominiums':number;
        'soldCondominiums':number;
        'condominiumsToSell':number;
        'charged':number;
        'pending':number;
        'sold':number;
        'inventory':number;
        'penality':number;
        'currency':string;
    },
}>();

</script>

<template>
    <HorizontalStackBar  :label="props.data.towerName" :cobrados="props.data.charged" :porCobrar="props.data.pending"/>

    <div class="flex flex-col md:flex-row gap-4">
        <div class="border border-primary rounded-sm p-4 w-full md:w-1/3 space-y-4">
            <p class="text-xl font-bold mb-2 text-primary dark:text-white">Información de departamentos</p>
            <ChartDataCard bg-color="bg-green-700" label="Valor de torre" :data="`$ ${props.data.towerValue.toLocaleString()} ${props.data.currency.toUpperCase()}`" :icon="Hotel"/>
            <ChartDataCard bg-color="bg-cyan-400" label="Condominios" :data="props.data.condominiums.toString()" :icon="DoorOpen"/>
            <ChartDataCard bg-color="bg-cyan-400" label="Departamentos vendidos" :data="props.data.soldCondominiums.toString()" :icon="Receipt"/>
            <ChartDataCard bg-color="bg-cyan-400" label="Departamentos por vender" :data="props.data.condominiumsToSell.toString()" :icon="TableProperties"/>
        </div>

        <div class="border border-primary rounded-sm p-4 w-full md:w-2/3 space-y-4">
            <p class="text-xl font-bold mb-2 text-primary dark:text-white">Información de ventas</p>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <ChartDataCard bg-color="bg-primary" label="Cobrado" :data="`$ ${props.data.charged.toLocaleString()} ${props.data.currency.toUpperCase()}`" :icon="DollarSign"/>
                <ChartDataCard bg-color="bg-secondary" label="Por cobrar" :data="`$ ${props.data.pending.toLocaleString()} ${props.data.currency.toUpperCase()}`" :icon="Receipt"/>
                <ChartDataCard bg-color="bg-green-700" label="Ventas efectuadas" :data="`$ ${props.data.sold.toLocaleString()} ${props.data.currency.toUpperCase()}`" :icon="ChartLine"/>
                <ChartDataCard bg-color="bg-green-700" label="Ventas de inventario" :data="`$ ${props.data.inventory.toLocaleString()} ${props.data.currency.toUpperCase()}`" :icon="TableProperties"/>
                <ChartDataCard bg-color="bg-orange-700" label="Penas" :data="`$ ${props.data.penality.toLocaleString()} ${props.data.currency.toUpperCase()}`" :icon="TrendingDown"/>

            </div>
        </div>
    </div>
</template>