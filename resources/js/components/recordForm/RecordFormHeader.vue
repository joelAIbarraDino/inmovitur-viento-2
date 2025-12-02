<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { ArrowLeft } from 'lucide-vue-next';

defineProps<{
    titleForm?:string,
    returnUrl:string
}>();

</script>

<template>
    <div class="flex justify-between items-center  gap-2 flex-row ">
        <h1 class="text-xl md:text-4xl font-black text-left">{{ titleForm??'Nuevo registro' }}</h1>
        <Button as="a" :href="returnUrl" class="bg-cyan-600 hover:bg-cyan-700 dark:text-white"> <ArrowLeft/> Regresar</Button>
    </div>
    <hr>
</template>