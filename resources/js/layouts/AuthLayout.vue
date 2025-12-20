<script setup lang="ts">
import AuthLayout from '@/layouts/auth/AuthSplitLayout.vue';


defineProps<{
    title?: string;
    description?: string;
    titleLayout?:string;
    msgLayout?:string;
}>();
</script>

<template>
    <AuthLayout :title="title" :description="description" :title-layout="titleLayout" :msg-layout="msgLayout">
        <slot />
    </AuthLayout>
</template>
