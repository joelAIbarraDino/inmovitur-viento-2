<script setup lang="ts">
import { LucideIcon } from 'lucide-vue-next';


const props = defineProps<{
    bgColor:string,
    label:string,
    data:string
    icon:LucideIcon
}>();

let finalClass = "p-4 rounded-lg flex items-center justify-between "
finalClass += props.bgColor; 

</script>

<template>
    <div :class="finalClass">
        <div class="text-left space-y-2">
            <p class="text-white text-sm">{{ label }}</p>
            <p class="text-white text-xl font-bold">{{ data }}</p>
        </div>
        <component :is="icon" class="text-white" :size="40"/>
    </div>
</template>