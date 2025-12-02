<script setup lang="ts">

import type { LucideIcon } from "lucide-vue-next"
import { Button } from '@/components/ui/button';
import { Link } from '@inertiajs/vue3';

defineProps<{
    type:string,
    color:string,
    hover:string,
    icon:LucideIcon,
    action: string | (() => void | Promise<void>)
}>();

</script>

<template>
    <template v-if="type === 'url' && typeof action === 'string'">
        <Button size="sm" :class="`${color} hover:${hover} text-white`"  >
            <Link :href="action">
                <component :is="icon"></component>
            </Link>
        </Button>
    </template>

    <template v-else>
        <Button size="sm" :class="`${color} hover:${hover} text-white`" @click="() => (action as Function)()">
            <component :is="icon"></component>
        </Button>
    </template>

</template>