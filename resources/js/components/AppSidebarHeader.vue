<script setup lang="ts">
import Breadcrumbs from '@/components/Breadcrumbs.vue';
import { SidebarTrigger } from '@/components/ui/sidebar';
import type { BreadcrumbItemType } from '@/types';
import { router, usePage } from '@inertiajs/vue3';
import { Banknote } from 'lucide-vue-next';
import { computed } from 'vue';
import { formatDate } from '@/utils/formatDate';

const page = usePage();

const exchange = computed(() => page.props.exchange ?? null);
const currency = computed(() => page.props.currency);

function setCurrency(value: 'mxn' | 'usd') {
    
    if (!value || value === currency.value) return

    router.post('/currency', {
        currency: value
    }, {
        preserveState: true,
        preserveScroll: true
    })
}

withDefaults(
    defineProps<{
        breadcrumbs?: BreadcrumbItemType[];
    }>(),
    {
        breadcrumbs: () => [],
    },
);
</script>

<template>
    <header
        class="flex h-16 shrink-0 items-center justify-between gap-2 border-b border-sidebar-border/70 px-6 transition-[width,height] ease-linear group-has-data-[collapsible=icon]/sidebar-wrapper:h-12 md:px-4"
    >
        <div class="flex items-center gap-2 w-full">
            <SidebarTrigger class="-ml-1" />

            <div class="flex items-center justify-between w-full">
                <template v-if="breadcrumbs && breadcrumbs.length > 0">
                    <Breadcrumbs :breadcrumbs="breadcrumbs" />
                </template>
                <div class="flex gap-2 items-center">
                    <button @click="setCurrency(currency==='mxn'?'usd':'mxn')" class="flex items-center justify-center gap-1">
                        <span class="text-xs font-medium">{{ currency.toUpperCase() }}</span>
                        <Banknote/>
                    </button>

                    <div v-if="exchange" class="flex flex-col text-xs">
                        <p>Dolar FIX: $ {{ exchange.rate.toFixed(2) }} <span class="font-bold">MXN</span></p>
                        <p>Actualizado: {{ formatDate(exchange.created_at) }}</p>
                    </div>
                </div>

            </div>

        </div>
    </header>
</template>
