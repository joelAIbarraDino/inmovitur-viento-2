<script setup lang="ts">

import { AppPageProps, BreadcrumbItem, Condominiums, TableConfig } from '@/types';
import { Pencil, Trash2, CirclePlus, Sheet } from 'lucide-vue-next';
import { Head, usePage, Link } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { computed } from 'vue';

import DinamicTable from '@/components/dinamicTable/DinamicTable.vue';
import AppLayout from '@/layouts/AppLayout.vue';

const breadcrumbs: BreadcrumbItem[] = [{title:'Condominios', href:'/condominiums'}];

interface CondomimiumsPageProps extends AppPageProps{
    condominiums: Condominiums[]
}

const {props} = usePage<CondomimiumsPageProps>();
const condominiums = computed(()=>props.condominiums);


function deleteRecord(record: Condominiums){

}

const table: TableConfig<Condominiums> = {
    attributes: ['tower', 'number', 'id_client', { key: 'price', format: 'currency' }, {key:'currency', format:'uppercase'}],
    columns_name: ['Torre', 'Condominio', 'Cliente', 'Valor de piso', 'Moneda'],
    records: condominiums.value,
    caption: 'Registros de condominios',
    actions: [
        {
            type: 'link',
            tooltip:'Editar condominio',
            icon: Pencil,
            handle: '/condominiums/{id}'
        },
        {
            type: 'button',
            tooltip:'Eliminar condominio',
            icon: Trash2,
            handle: deleteRecord
        }
    ]
};

</script>


<template>
    <Head title="Condominios"/>

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="px-4 pt-6 flex justify-start items-center gap-3">
            
            <Link 
                class="bg-primary hover:bg-primary-hover text-white flex py-2 px-6 rounded-md gap-2 font-bold"
                href="/condominiums/create" 
            >
                <CirclePlus/> Nuevo condominio
            </Link>
        
            <button class="bg-excel hover:bg-excel-hover text-white flex py-2 px-6 rounded-md gap-2 font-bold">
                <Sheet/> Cargar excel
            </button>
        </div>
        <DinamicTable :table="table"/>
    </AppLayout>
</template>