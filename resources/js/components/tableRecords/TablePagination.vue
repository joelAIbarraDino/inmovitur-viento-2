<script setup lang="ts">

import { Link } from '@inertiajs/vue3';

interface linkType {
    active:boolean;
    label:string;
    page:number;
    url:string;
}
const props = defineProps<{
    links:linkType[],
}>();

</script>

<template>
    <div class="flex flex-wrap items-center justify-end space-x-1 mt-2 mb-4">

        <Link
            as="a"
            v-for="(link, index) in props.links":key="index"
            :href="link.url??'#'"
            :enabled="link.active"
            v-html="link.label"
            :class="[
                link.active ? 'bg-primary text-white':'bg-white text-gray-700',
                link.url ? 'opacity-50 pointer-events-none':'hover:bg-gray-100',
                'py-2 px-4'
            ]"
        />
        
        

    </div>
</template>