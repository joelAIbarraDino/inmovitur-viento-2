<script setup lang="ts">

import ButtonNewRegister from '@/components/ButtonNewRegister.vue';
import AppLayout from '@/layouts/AppLayout.vue';

import { TableActions, TableRecordButton, TableRecords, TablePagination } from '@/components/tableRecords';
import { AppPageProps, BreadcrumbItem, User } from '@/types';
import { TableCell, TableRow } from '@/components/ui/table';
import { usePage, Head, router } from '@inertiajs/vue3';
import { Pencil, Trash, Eye } from 'lucide-vue-next';
import { computed } from 'vue';
import Swal from 'sweetalert2';

const breadcrumbs: BreadcrumbItem[] = [{title:'Supervisores', href:'#'}]
const columnsName = ['Nombre', 'Correo', 'Acciones'];

interface ClientsPageProps extends AppPageProps{
    supervisors:User[];
}

const page = usePage<ClientsPageProps>();
const supervisors = computed(() => page.props.supervisors.data);
const pagination = computed(() => page.props.supervisors.links);
const flash = computed(() => page.props.flash);


const deleteSupervisor = async(id:number)=>{
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

    router.delete(`/supervisors/${id}`, {
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
            <ButtonNewRegister url="/supervisors/create" text="Nuevo supervisor"/>

            <TableRecords caption="Lista de supervisores" :columns-head="columnsName">
                <TableRow v-for="supervisor in supervisors":for="supervisor.id">
                    <TableCell>{{ supervisor.name }}</TableCell>
                    <TableCell>{{ supervisor.email }}</TableCell>

                    <TableActions>
                        <TableRecordButton
                            type="url"
                            color="bg-cyan-600"
                            hover="bg-cyan-700"
                            :icon=Pencil
                            :action="`/supervisors/${supervisor.id}/edit`"
                        />

                        <TableRecordButton
                            type="function"
                            color="bg-red-700"
                            hover="bg-red-600"
                            :icon=Trash
                            :action="() => deleteSupervisor(supervisor.id)"
                        />
                        
                    </TableActions>

                </TableRow>
            </TableRecords>

            <TablePagination :links="pagination"/>
        </div>
    </AppLayout>
</template>