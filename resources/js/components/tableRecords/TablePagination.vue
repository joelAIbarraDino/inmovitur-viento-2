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
            v-for="(link, index) in props.links":key="index"
            :href="link.url||'#'"
            v-html="link.label"
            :class="[
                link.active ? 'bg-primary text-white':'bg-white text-gray-700',
                'py-2 px-4 hover:bg-gray-100 hover:text-gray-700'
            ]"
        />
    </div>
</template>