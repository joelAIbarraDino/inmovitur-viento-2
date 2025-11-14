<script setup lang="ts">

import { AppPageProps, BreadcrumbItem, Condominiums, TableConfig } from '@/types';
import { Pencil, Trash2, CirclePlus, Sheet } from 'lucide-vue-next';
import { Head, usePage, Link, router } from '@inertiajs/vue3';
import { computed } from 'vue';

import DinamicTable from '@/components/dinamicTable/DinamicTable.vue';
import Button from '@/components/ui/button/Button.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import Swal from 'sweetalert2';

const breadcrumbs: BreadcrumbItem[] = [{title:'Condominios', href:'/condominiums'}];

interface CondomimiumsPageProps extends AppPageProps{
    condominiums: Condominiums[]
}

const {props} = usePage<CondomimiumsPageProps>();
const condominiums = computed(()=>props.condominiums);


const  deleteRecord = async(record: Condominiums) => {
    const result = await Swal.fire({
        title: '¿Estás seguro?',
        text: "¡No podrás revertir esto!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    });

    if(!result.isConfirmed)return;

    router.delete(`/condominiums/${record.id}`,{
        preserveScroll:true,
        onSuccess:()=>{
            router.visit('/condominiums', {replace:true});
        },
        onError:(errors)=>{
            console.error("Error al eliminar el registro: ", errors);
            Swal.fire('Error', 'No se pudo eliminar el condominio.', 'error');
        }
    });
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
            handle: '/condominiums/{id}/edit'
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
            <Button as-child class="bg-primary hover:bg-primary-hover text-white rounded-md gap-2 font-bold" size="lg"> 
                <Link href="/condominiums/create">
                    <CirclePlus/> Nuevo condominio
                </Link>
            </Button>
        
            <button class="bg-excel hover:bg-excel-hover text-white flex py-2 px-6 rounded-md gap-2 font-bold text-sm items-center justify-center ">
                <Sheet/> Cargar excel
            </button>
        </div>
        <DinamicTable :table="table"/>
    </AppLayout>
</template>