<script setup lang="ts">

import ButtonNewRegister from '@/components/ButtonNewRegister.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { TableActions, TableRecordButton, TableRecords, TablePagination } from '@/components/tableRecords';
import { AppPageProps, BreadcrumbItem, Payment } from '@/types';
import { TableCell, TableRow } from '@/components/ui/table';
import { usePage, Head, router, useForm } from '@inertiajs/vue3';
import { formatCurrency } from '@/utils/formatCurrency';

import { formatDateTime } from '@/utils/formatDateTime'
import { computed, ref, watch } from 'vue';
import Swal from 'sweetalert2';
import { FileSpreadsheet } from 'lucide-vue-next';
import axios from 'axios';

const breadcrumbs: BreadcrumbItem[] = [{title:'Pagos', href:'#'}]
const columnsName = ['Cliente', 'Condominio', 'Monto', 'Pena', 'Fecha'];

interface CondominiumsPageProps extends AppPageProps{
    payments:Payment[];
}

const page = usePage<CondominiumsPageProps>();
const payments = computed(() => page.props.payments.data);
const pagination = computed(() => page.props.payments.links);
const flash = computed(()=>page.props.flash);
const fileInput = ref<HTMLInputElement | null>(null);
const isUploading = ref(false);

// 1. El Watch para mostrar el resultado
watch(() => flash.value.import_result, (res: any) => {
    if (!res) return;
    isUploading.value = false;

    let html = `<p>Filas Insertadas: <b>${res.inserted}</b></p>`;
    if (res.ignored > 0) {
        html += `<p>Filas existentes: <b>${res.ignored}</b></p>
        <small>Numero de fila ignorada(s): ${res.rows.join(', ')}</small>`;
    }

    Swal.fire({
        title: 'Importación Finalizada',
        html: html,
        icon: res.ignored > 0 ? 'warning' : 'success',
    });
}, { deep: true });

// 2. Función que se dispara al seleccionar el archivo
const handleFileUpload = (e: Event) => {
    const target = e.target as HTMLInputElement;
    if (!target.files?.length) return;

    const file = target.files[0];
    isUploading.value = true;

    // Usamos router.post para enviar el archivo directamente
    router.post('payments/import', { file: file }, {
        forceFormData: true,
        onFinish: () => {
            isUploading.value = false;
            if (fileInput.value) fileInput.value.value = ''; // Reset input
        },
        onError: () => {
            isUploading.value = false;
            Swal.fire('Error', 'Hubo un problema al subir el archivo', 'error');
        }
    });
};

// 3. Función para abrir el selector de archivos al hacer clic en el botón
const triggerSelect = () => {
    fileInput.value?.click();
};

const deletePayment = async(id:number)=>{
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

    router.delete(`/payments/${id}`, {
        preserveScroll:true,
        onSuccess:()=>{
            Swal.fire('Resultado', flash.value.message, 'info');
        },
    });
};

</script>

<template>
    <Head title="Pagos"/>
    <AppLayout :breadcrumbs="breadcrumbs">

        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">

            <div class="flex items-center space-x-1">
                <ButtonNewRegister url="/payments/create" text="Nuevo pago"/>


                <div class="flex items-center space-x-1">
                    <input 
                        type="file" 
                        ref="fileInput" 
                        class="hidden" 
                        accept=".xlsx, .xls, .csv" 
                        @change="handleFileUpload" 
                    />
    
                    <button 
                        @click="triggerSelect" 
                        :disabled="isUploading"
                        class="p-2 bg-green-600 text-white rounded-md hover:bg-green-700 disabled:opacity-50 text-sm flex gap-2 items-center justify-center "
                    >
                        <FileSpreadsheet :size="18"/>
                        <span v-if="isUploading">Procesando...</span>
                        <span v-else>Importar Excel</span>
                    </button>
                </div>
            </div>

            <TableRecords caption="Lista de pagos" :columns-head="columnsName">
                <TableRow v-for="payment in payments":for="payment.id">
                    <TableCell>{{ payment.condominiums?.clients?.users?.name ?? 'Sin nombre' }}</TableCell>
                    <TableCell>{{ payment.condominiums?.number }}</TableCell>
                    <TableCell>$ {{ formatCurrency(payment.amount) }} <span class="font-bold ">{{ payment.currency.toUpperCase() }}</span></TableCell>
                    <TableCell>$ {{ formatCurrency(payment.discount_condominium) }} <span class="font-bold ">{{ payment.currency.toUpperCase() }}</span></TableCell>
                    <TableCell>{{ formatDateTime(payment.created_at) }}</TableCell>
                </TableRow>
            </TableRecords>
            <TablePagination :links="pagination"/>
        </div>
    </AppLayout>
</template>