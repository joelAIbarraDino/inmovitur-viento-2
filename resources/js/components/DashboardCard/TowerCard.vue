<script setup>
import ChartDataCard from './ChartDataCard.vue';
import DountChart from './DountChart.vue';
import { Receipt, CircleDollarSign, TrendingDown } from 'lucide-vue-next';


const props = defineProps({
  towerName: String,
  charged: Number,
  pending: Number,
  penality: Number,
  currency:String,
  isAdmin:Boolean,
})
</script>

<template>
  <div>

    <DountChart
      :cobrados="props.charged"
      :porCobrar="props.pending"
      :title="props.towerName"
      class="mb-4"
    />

    <div class="space-y-4 text-center">
        <ChartDataCard bgColor="bg-primary" label="Cobrado" :data="`$ ${charged.toLocaleString()} ${currency.toUpperCase()}`" :icon="Receipt"/>
        <ChartDataCard bgColor="bg-secondary" label="Por cobrar" :data="`$ ${pending.toLocaleString()} ${currency.toUpperCase()}`" :icon="CircleDollarSign"/>
        <ChartDataCard bgColor="bg-orange-700" label="Penas" :data="`$ ${penality.toLocaleString()} ${currency.toUpperCase()}`" :icon="TrendingDown"/>
        <a v-if="isAdmin" class="text-lg font-bold underline text-center" href="/towers">Ver más</a>

    </div>


  </div>
</template>
