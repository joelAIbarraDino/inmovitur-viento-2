<script setup lang="ts">
import {TableHead, TableHeader, TableRow, TableCaption} from "@/components/ui/table"

defineProps<{
    caption:string,
    columnsName:string[]
}>();
</script>

<template>
    <TableCaption>{{caption}}</TableCaption>
    <TableHeader>
        <TableRow>
            <TableHead v-for="columnName in columnsName" :key="columnName">
                {{ columnName }}
            </TableHead>
        </TableRow>    
    </TableHeader>
</template>