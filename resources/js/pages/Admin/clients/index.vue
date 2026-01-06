<script setup lang="ts">

import ButtonNewRegister from '@/components/ButtonNewRegister.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import axios from 'axios';

import { TableActions, TablePagination, TableRecordButton, TableRecords } from '@/components/tableRecords';
import { AppPageProps, BreadcrumbItem, Client } from '@/types';
import { TableCell, TableRow } from '@/components/ui/table';
import { usePage, Head, router, Link } from '@inertiajs/vue3';
import { Pencil, Trash, Eye, FilePenLine, FileSpreadsheet } from 'lucide-vue-next';
import { computed, ref } from 'vue';
import Swal from 'sweetalert2';

const breadcrumbs: BreadcrumbItem[] = [{title:'Clientes', href:'#'}]
const columnsName = ['No. de contrato', 'Nombre', 'Correo', 'Teléfono', 'Acciones'];

interface ClientsPageProps extends AppPageProps{
    clients:Client[];
}

const page = usePage<ClientsPageProps>();
const clients = computed(() => page.props.clients.data);
const pagination = computed(() => page.props.clients.links);
const flash = computed(()=>page.props.flash);

const fileInput = ref(null);
const processing = ref(false);

const triggerImport = () => {
    fileInput.value.click();
}

const handleFileUpload = async (e)=>{
    const file = event.target.files[0];
    if (!file) return;

    const formData = new FormData();
    formData.append('file', file);

    processing.value = true;

    try {
        // Importante: responseType 'blob' para manejar archivos
        const response = await axios.post('/clients/import', formData, {
            responseType: 'blob',
            headers: { 'Content-Type': 'multipart/form-data' }
        });

        // Crear un link temporal para disparar la descarga del Excel de respuesta
        const url = window.URL.createObjectURL(new Blob([response.data]));
        const link = document.createElement('a');
        link.href = url;
        link.setAttribute('download', 'reporte_usuarios_creados.xlsx');
        document.body.appendChild(link);
        link.click();
        
        alert('Proceso completado. Revisa el reporte descargado.');
    } catch (error) {
        console.error("Error en la carga:", error);
        alert('Hubo un error al procesar el archivo.');
    } finally {
        processing.value = false;
        event.target.value = null; // Limpiar input
    }
} 

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

            <div class="flex gap-2 items-center">
                <ButtonNewRegister url="/clients/create" text="Nuevo cliente"/>
                
                <div class="flex items-center space-x-1">
                    <input 
                        type="file" 
                        ref="fileInput" 
                        class="hidden" 
                        accept=".xlsx, .xls, .csv" 
                        @change="handleFileUpload" 
                    />
    
                    <button 
                        @click="triggerImport" 
                        :disabled="processing"
                        class="p-2 bg-green-600 text-white rounded hover:bg-green-700 disabled:opacity-50 text-sm flex gap-2 items-center justify-center "
                    >
                        <FileSpreadsheet :size="18"/>
                        <span v-if="processing">Procesando...</span>
                        <span v-else>Importar Excel</span>
                    </button>
                </div>

            </div>

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

            <TablePagination :links="pagination"/>
        </div>
    </AppLayout>
</template>