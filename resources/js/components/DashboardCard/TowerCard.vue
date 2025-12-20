<script setup>
import ChartDataCard from './ChartDataCard.vue';
import DountChart from './DountChart.vue';
import { Receipt, CircleDollarSign } from 'lucide-vue-next';


const props = defineProps({
  name: String,
  cobrados: Number,
  porCobrar: Number
})
</script>

<template>
  <div>

    <DountChart
      :cobrados="props.cobrados"
      :porCobrar="props.porCobrar"
      :title="props.name"
      class="mb-4"
    />

    <div class="space-y-4 text-center">
        <ChartDataCard bgColor="bg-primary" label="Por cobrar" :data="`$ ${porCobrar.toLocaleString()}`" :icon="Receipt"/>
        <ChartDataCard bgColor="bg-secondary" label="Cobrado" :data="`$ ${cobrados.toLocaleString()}`" :icon="CircleDollarSign"/>
        <a class="text-lg font-bold underline text-center" href="#">Ver más</a>

    </div>


  </div>
</template>
