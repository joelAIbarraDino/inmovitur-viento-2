<script setup lang="ts">

import { Table, TableBody, TableCaption, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table'
import { TableAttribute, TableConfig } from '@/types';
import { Link } from '@inertiajs/vue3';

function getNestedValue(obj: any, path: string) {
  return path.split('.').reduce((acc, key) => acc?.[key], obj);
}

function formatValue(value: any, attr: TableAttribute | string, record: any) {
    if (value == null) return '';

    if (typeof attr === 'string') return value;

    let formatted = value;

    if (attr.format === 'currency') {
        formatted = new Intl.NumberFormat('es-MX', {
            style: 'currency',
            currency: 'MXN',
            minimumFractionDigits: 2
        }).format(value);

    } else if (attr.format === 'date') {
        formatted = new Date(value).toLocaleDateString('es-MX', {
            year: 'numeric',
            month: 'short',
            day: 'numeric',
    });
    } else if (attr.format === 'uppercase') {
        formatted = String(value).toUpperCase();
    } else if (typeof attr.format === 'function') {
        formatted = attr.format(value, record);
    }

    return `${attr.prefix ?? ''} ${formatted} ${attr.suffix ?? ''}`;
}

const props = defineProps<{
    table:TableConfig<any>
}>();

</script>

<template>

    <div class="h-full p-4 rounded-2xl">
        <Table>
            <TableCaption>{{ props.table.caption??'Lista de registros' }}</TableCaption>

            <TableHeader>
                <TableRow class="bg-primary hover:bg-primary">
                    <TableHead class="text-white text-center" v-for="column_name in props.table.columns_name":key="column_name" >
                        {{ column_name }}
                    </TableHead>

                    <TableHead class="text-white text-center" v-if="props.table.actions.length > 0">
                        Acciones
                    </TableHead>
                </TableRow>
            </TableHeader>

            <TableBody>
                <TableRow class="odd:bg-[#E1E7F2] even:bg-[#BCCAE4]" v-for="record in props.table.records":key="record.id">

                    <TableCell v-for="(attribute, index) in props.table.attributes":key="index" class="text-center">
                        {{ formatValue(getNestedValue(record, typeof attribute === 'string' ? attribute : attribute.key), attribute, record) }}
                    </TableCell>

                    
                    <TableCell v-if="props.table.actions.length > 0" class="flex gap-2 justify-center">
                        <template v-for="(action, index) in props.table.actions" :key="index">
                            <button v-if="action.type === 'link' && typeof action.handle === 'string'">
                                <Link 
                                    class="rounded-full p-3 transition text-white flex items-center justify-center bg-primary hover:bg-primary-hover"
                                    :href="action.handle.replace('{id}', record.id)"
                                    :title="action.tooltip"
                                >
                                    <component :is="action.icon" class="w-4 h-4" />
                                </Link>
                            </button>

                            <button v-else
                                class="rounded-full p-3 transition text-white flex items-center justify-center bg-primary hover:bg-primary-hover"
                                @click="() => (typeof action.handle === 'function' ? action.handle(record) : null)"
                                :title="action.tooltip"
                            >
                                <component :is="action.icon" class="w-4 h-4" />
                            </button>
                        </template>
                    </TableCell>
                </TableRow>
            </TableBody>

        </Table>
    </div>


</template>