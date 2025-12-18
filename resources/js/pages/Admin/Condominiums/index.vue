<script setup lang="ts">

import ButtonNewRegister from '@/components/ButtonNewRegister.vue';
import AppLayout from '@/layouts/AppLayout.vue';

import { TableActions, TableRecordButton, TableRecords } from '@/components/tableRecords';
import { AppPageProps, BreadcrumbItem, Condominium } from '@/types';
import { TableCell, TableRow } from '@/components/ui/table';
import { usePage, Head, router } from '@inertiajs/vue3';
import { formatCurrency } from '@/utils/formatCurrency';
import { Pencil, Trash, Eye } from 'lucide-vue-next';
import { computed } from 'vue';
import Swal from 'sweetalert2';

const breadcrumbs: BreadcrumbItem[] = [{title:'Condominios', href:'#'}]
const columnsName = ['Torre', 'Condominio', 'Cliente', 'Valor de piso', 'Acciones'];

interface CondominiumsPageProps extends AppPageProps{
    condominiums:Condominium[];
}

const page = usePage<CondominiumsPageProps>();
const condominiums = computed(() => page.props.condominiums);
const flash = computed(()=>page.props.flash);


const deleteCondominium = async(id:number)=>{
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

    router.delete(`/condominiums/${id}`, {
        preserveScroll:true,
        onSuccess:()=>{
            Swal.fire('Resultado', flash.value.message, 'info');
        },
    });
};

</script>

<template>
    <Head title="Condominios"/>
    <AppLayout :breadcrumbs="breadcrumbs">

        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <ButtonNewRegister url="/condominiums/create" text="Nuevo condominio"/>

            <TableRecords caption="Lista de condominios" :columns-head="columnsName">
                <TableRow v-for="condominium in condominiums":for="condominium.id">
                    <TableCell>{{ condominium.tower }}</TableCell>
                    <TableCell>{{ condominium.number }}</TableCell>
                    <TableCell>$ {{ formatCurrency(condominium.price) }} <span class="font-bold ">{{ condominium.currency.toUpperCase() }}</span></TableCell>
                    <TableCell>{{ condominium.clients?.users?.name ?? 'Disponible' }}</TableCell>

                    <TableActions>
                        <TableRecordButton
                            type="url"
                            color="bg-cyan-600"
                            hover="bg-cyan-700"
                            :icon=Pencil
                            :action="`/condominiums/${condominium.id}/edit`"
                        />

                        <TableRecordButton
                            type="url"
                            color="bg-orange-600"
                            hover="bg-orange-800"
                            :icon=Eye
                            :action="`/condominiums/${condominium.id}`"
                        />

                        <TableRecordButton
                            type="function"
                            color="bg-red-700"
                            hover="bg-red-600"
                            :icon=Trash
                            :action="() => deleteCondominium(condominium.id)"
                        />
                        
                    </TableActions>

                </TableRow>
            </TableRecords>
        </div>
    </AppLayout>
</template>