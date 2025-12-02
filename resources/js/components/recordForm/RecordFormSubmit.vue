<script setup lang="ts">
import type { LucideIcon } from "lucide-vue-next"
import { Button } from '@/components/ui/button';
import { Save } from 'lucide-vue-next';

const props = defineProps<{
    label?:string,
    color?:string,
    hover?:string,
    icon?:LucideIcon
}>();

const buttonClass = [
    props.color ?? 'bg-lime-600',
    props.hover ? `hover:${props.hover}` : 'hover:bg-lime-700',
    'text-md',
    'dark:text-white'
].join(' ');

</script>

<template>
    <div class="flex gap-4 justify-start">
        <Button type="submit" :class="buttonClass" size="lg">
            <component :is="icon??Save"/>
            {{ label??'Guardar' }}
        </Button>
    </div>
</template>