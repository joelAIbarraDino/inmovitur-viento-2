<script setup lang="ts">

import ButtonNewRegister from '@/components/ButtonNewRegister.vue';
import AppLayout from '@/layouts/AppLayout.vue';

import { TableActions, TableRecordButton, TableRecords } from '@/components/tableRecords';
import { AppPageProps, BreadcrumbItem, Client } from '@/types';
import { TableCell, TableRow } from '@/components/ui/table';
import { usePage, Head, router } from '@inertiajs/vue3';
import { Pencil, Trash, Eye, FilePenLine } from 'lucide-vue-next';
import { computed } from 'vue';
import Swal from 'sweetalert2';

const breadcrumbs: BreadcrumbItem[] = [{title:'Clientes', href:'#'}]
const columnsName = ['No. de contrato', 'Nombre', 'Correo', 'Teléfono', 'Acciones'];

interface ClientsPageProps extends AppPageProps{
    clients:Client[];
}

const page = usePage<ClientsPageProps>();
const clients = computed(() => page.props.clients);
const flash = computed(()=>page.props.flash);

const deleteClient = async(id:number)=>{
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

    router.delete(`/clients/${id}`, {
        preserveScroll:true,
        onSuccess:()=>{
            Swal.fire('Resultado', flash.value.message, 'info');
        }
    });
};

</script>

<template>
    <Head title="Clientes"/>
    <AppLayout :breadcrumbs="breadcrumbs">

        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <ButtonNewRegister url="/clients/create" text="Nuevo cliente"/>

            <TableRecords caption="Lista de clientes" :columns-head="columnsName">
                <TableRow v-for="client in clients":for="client.id">
                    <TableCell>{{ client.no_contract }}</TableCell>
                    <TableCell>{{ client.users?.name }}</TableCell>
                    <TableCell>{{ client.users?.email }}</TableCell>
                    <TableCell>{{ client.phone }}</TableCell>

                    <TableActions>
                        <TableRecordButton
                            type="url"
                            color="bg-cyan-600"
                            hover="bg-cyan-700"
                            :icon=Pencil
                            :action="`/clients/${client.id}/edit`"
                        />

                        <TableRecordButton
                            type="url"
                            color="bg-orange-600"
                            hover="bg-orange-800"
                            :icon=Eye
                            :action="`/clients/${client.id}`"
                        />

                        <TableRecordButton
                            type="url"
                            color="bg-indigo-600"
                            hover="bg-indigo-800"
                            :icon=FilePenLine
                            :action="`/clients/new-contract/${client.id}`"
                        />

                        <TableRecordButton
                            type="function"
                            color="bg-red-700"
                            hover="bg-red-600"
                            :icon=Trash
                            :action="() => deleteClient(client.id)"
                        />
                        
                    </TableActions>

                </TableRow>
            </TableRecords>
        </div>
    </AppLayout>
</template>