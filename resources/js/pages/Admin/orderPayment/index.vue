<script setup lang="ts">

import ButtonNewRegister from '@/components/ButtonNewRegister.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { TableActions, TableRecordButton, TableRecords, TablePagination } from '@/components/tableRecords';
import { AppPageProps, BreadcrumbItem, OrderPayment } from '@/types';
import { TableCell, TableRow } from '@/components/ui/table';
import { usePage, Head, router } from '@inertiajs/vue3';
import { formatCurrency } from '@/utils/formatCurrency';
import { Pencil, Trash, Eye } from 'lucide-vue-next';
import { formatDateTime } from '@/utils/formatDateTime'
import { computed } from 'vue';
import Swal from 'sweetalert2';

const breadcrumbs: BreadcrumbItem[] = [{title:'Pagos', href:'#'}]
const columnsName = ['Cliente', 'Monto', 'Pena', 'status', 'Fecha'];

interface CondominiumsPageProps extends AppPageProps{
    orderPayments:OrderPayment[];
}

const page = usePage<CondominiumsPageProps>();
const orderPayments = computed(() => page.props.orderPayments.data);
const pagination =computed(() => page.props.orderPayments.links);
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
    <Head title="Ordenes de pago"/>
    <AppLayout :breadcrumbs="breadcrumbs">

        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <ButtonNewRegister url="/order-payments/create" text="Nueva orden de pago"/>

            <TableRecords caption="Lista de ordenes de pagos" :columns-head="columnsName">
                <TableRow v-for="orderPayment in orderPayments":for="orderPayment.id">
                    <TableCell>{{ orderPayment.clients?.users?.name ?? 'Sin nombre' }}</TableCell>
                    <TableCell>$ {{ formatCurrency(orderPayment.amount) }} <span class="font-bold ">{{ orderPayment.currency.toUpperCase() }}</span></TableCell>
                    <TableCell>$ {{ formatCurrency(orderPayment.discount_condominium) }} <span class="font-bold ">{{ orderPayment.currency.toUpperCase() }}</span></TableCell>
                    <TableCell>{{ orderPayment.status}}</TableCell>
                    <TableCell>{{ formatDateTime(orderPayment.created_at) }}</TableCell>
                </TableRow>
            </TableRecords>

            <TablePagination :links="pagination"/>
        </div>
    </AppLayout>
</template>