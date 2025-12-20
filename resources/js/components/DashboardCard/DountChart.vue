<script setup lang="ts">

import { Doughnut } from 'vue-chartjs';
import { Chart as ChartJS, ArcElement, Tooltip, Legend } from 'chart.js'

ChartJS.register(ArcElement, Tooltip, Legend)

const props = defineProps<{
    cobrados: number,
    porCobrar: number,
    title: string,
}>();

const centerTextPlugin = {
  id: 'centerText',
  beforeDraw(chart: ChartJS<'doughnut'>) {
    const { ctx, width, height } = chart
    ctx.save()

    ctx.font = 'bold 30px sans-serif'
    ctx.fillStyle = '#1f2f4a'
    ctx.textAlign = 'center'
    ctx.textBaseline = 'middle'
    ctx.fillText(props.title, width / 2, height / 2)

    ctx.restore()
  }
}

const data = {
  labels: ['Cobrado', 'Por cobrar'],
  datasets: [
    {
      data: [props.cobrados, props.porCobrar],
      backgroundColor: ['#1f2f4a', '#5a78b8'],
      borderWidth: 2
    }
  ]
}

const options = {
  cutout: '80%',
  plugins: {
    legend: { display: false }
  }
}

</script>

<template>
    <Doughnut :data="data" :options="options" :plugins="[centerTextPlugin]"/>
</template>