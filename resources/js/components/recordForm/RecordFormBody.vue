<script setup lang="ts">

defineProps<{
    handle : () => void
}>();

</script>

<template>
    <form class="space-y-6" @submit.prevent="handle">
        <slot></slot>
    </form>
</template>