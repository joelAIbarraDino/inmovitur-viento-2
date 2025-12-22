<script setup lang="ts">
import {Chart as ChartJS, BarElement, CategoryScale, LinearScale, Tooltip } from 'chart.js'
import { Bar } from 'vue-chartjs'

import type { ChartData, ChartOptions } from 'chart.js'

ChartJS.register(BarElement, CategoryScale, LinearScale, Tooltip)

const props = defineProps<{
    cobrados: number
    porCobrar: number
    label: string
}>()

const data: ChartData<'bar'> = {
    labels: [props.label],
    datasets: [
    {
        label: 'Cobrado',
        data: [props.cobrados],
        backgroundColor: '#1f2f4a',
        borderRadius: 6,
        barThickness: 20
    },
    {
        label: 'Por cobrar',
        data: [props.porCobrar],
        backgroundColor: '#5a78b8',
        borderRadius: 6,
        barThickness: 20
    }
  ]
}

const options: ChartOptions<'bar'> = {
    indexAxis: 'y',
    responsive: true,
    maintainAspectRatio: false,
    scales: {
        x: {
            stacked: true,
            display: false
        },
        y: {
            stacked: true,
            display: false
        }
    },
    plugins: {
        legend: { display: false },
        tooltip: {
            callbacks: {
                label: ctx =>
                `${ctx.dataset.label}: $${ctx.raw?.toLocaleString()}`
            }
        }
    }
}
</script>

<template>
    <div class="h-10 w-full mb-15 mt-5">
        <p class="text-xl font-bold text-primary dark:text-white mb-2">{{ props.label }}</p>
        <Bar :data="data" :options="options" />
    </div>
</template>
