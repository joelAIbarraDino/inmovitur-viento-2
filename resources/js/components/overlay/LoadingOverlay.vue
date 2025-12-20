<script setup lang="ts">

import { Loader } from 'lucide-vue-next';
defineProps<{ show: boolean; text?: string }>()

</script>

<template>
  <div
    v-if="show"
    class="absolute inset-0 z-50 bg-primary/30 flex items-center justify-center rounded-lg"
  >
    <div class="flex flex-col items-center gap-3">
        <Loader class="lucide lucide-loader-icon lucide-loader animate-spin size-24 text-white"/>
        <span class="text-lg font-bold text-white">{{ text ?? 'Procesando...' }}</span>
    </div>
  </div>
</template>
