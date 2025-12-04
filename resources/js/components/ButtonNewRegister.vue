<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Link } from '@inertiajs/vue3';
import { CirclePlus } from "lucide-vue-next"

defineProps({
    url: String,
    text: String,
});

</script>

<template>
    <div class="flex">
        <Button as-child class="bg-lime-600 text-white hover:bg-lime-700">
            <Link :href="url"><CirclePlus/>{{text}}</Link>
        </Button>
    </div>
</template>