<script setup lang="ts">
import { Hotel } from 'lucide-vue-next';
import type { HTMLAttributes } from 'vue';

defineOptions({
    inheritAttrs: false,
});

interface Props {
    className?: HTMLAttributes['class'];
}
defineProps<Props>();

</script>

<template>
    <img src="@/assets/logoViento.png" class="w-full max-w-[150px]">
</template>
