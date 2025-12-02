<script setup lang="ts">
import {TableCell} from "@/components/ui/table"

</script>

<template>
    <TableCell class="flex gap-2 justify-start items-center ">
        <slot></slot>
    </TableCell>
</template>