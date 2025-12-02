<script setup lang="ts">
import TableRecordHeader from "./TableRecordHeader.vue";
import TableBody from "../ui/table/TableBody.vue";
import Table from "../ui/table/Table.vue";

const props = defineProps<{
    caption?: string,
    columnsHead: string[]
}>();

</script>

<template>
    <div class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 dark:border-sidebar-border md:min-h-min shadow-xl" >
        <Table>
            <TableRecordHeader :caption="props.caption??'Lista de registros'" :columns-name="props.columnsHead"></TableRecordHeader>
            <TableBody>
                <slot></slot>
            </TableBody>
        </Table>
    </div>
</template>