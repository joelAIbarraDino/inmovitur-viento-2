<script setup lang="ts">

import ButtonNewRegister from '@/components/ButtonNewRegister.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { TableActions, TableRecordButton, TableRecords, TablePagination } from '@/components/tableRecords';
import { AppPageProps, BreadcrumbItem, Payment } from '@/types';
import { TableCell, TableRow } from '@/components/ui/table';
import { usePage, Head, router } from '@inertiajs/vue3';
import { formatCurrency } from '@/utils/formatCurrency';

import { formatDateTime } from '@/utils/formatDateTime'
import { computed } from 'vue';
import Swal from 'sweetalert2';

const breadcrumbs: BreadcrumbItem[] = [{title:'Pagos', href:'#'}]
const columnsName = ['Cliente', 'Condominio', 'Monto', 'Pena', 'Fecha'];

interface CondominiumsPageProps extends AppPageProps{
    payments:Payment[];
}

const page = usePage<CondominiumsPageProps>();
const payments = computed(() => page.props.payments.data);
const pagination = computed(() => page.props.links);
const flash = computed(()=>page.props.flash);


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
            <ButtonNewRegister url="/payments/create" text="Nuevo pago"/>

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