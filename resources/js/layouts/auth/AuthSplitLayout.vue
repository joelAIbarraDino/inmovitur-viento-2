<script setup lang="ts">
import { usePage } from '@inertiajs/vue3';

const page = usePage();

defineProps<{
    title?: string;
    description?: string;
    titleLayout?:string;
    msgLayout?:string;
}>();
</script>

<template>
    <div class="relative grid h-dvh flex-col items-center justify-center px-8 sm:px-0 lg:max-w-none lg:grid-cols-2 lg:px-0">
        <div class="relative hidden h-full flex-col bg-muted p-10 text-white lg:flex dark:border-r">
            <div class="absolute inset-0  bg-[url(@/assets/loginViento.png)] bg-no-repeat bg-cover bg-center" />
            <div class="absolute inset-0 bg-neutral-800/75 backdrop-blur-xs"/>
            <div class="absolute inset-y-0 inset-x-20 space-y-8 h-full w-full md:w-7/10 flex flex-col items-start justify-center">
                <p class="text-4xl font-extrabold">{{ titleLayout }}</p>
                <p class="text-2xl">{{ msgLayout }}</p>

            </div>
        </div>

        <div class="lg:p-8">
            <div class="mx-auto flex w-full flex-col justify-center space-y-6 sm:w-[350px]">
                <div class="flex flex-col space-y-2 text-center">
                    <h1 class="text-xl font-medium tracking-tight" v-if="title">{{ title }}</h1>
                    <p class="text-sm text-muted-foreground" v-if="description">{{ description }}</p>
                </div>
                <slot />
            </div>
        </div>
    </div>
</template>
