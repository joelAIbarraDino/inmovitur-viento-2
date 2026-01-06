<script setup lang="ts">

import ButtonNewRegister from '@/components/ButtonNewRegister.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { TableActions, TableRecordButton, TableRecords, TablePagination } from '@/components/tableRecords';
import { AppPageProps, BreadcrumbItem, ProfPayment } from '@/types';
import { TableCell, TableRow } from '@/components/ui/table';
import { usePage, Head, router } from '@inertiajs/vue3';
import { formatCurrency } from '@/utils/formatCurrency';
import { Pencil, Trash, Eye } from 'lucide-vue-next';
import { formatDateTime } from '@/utils/formatDateTime'
import { computed } from 'vue';
import { Button } from '@/components/ui/button';
import { Link } from '@inertiajs/vue3';

const breadcrumbs: BreadcrumbItem[] = [{title:'Pagos', href:'#'}]
const columnsName = ['Cliente', 'Monto', 'Pena', 'status', 'Fecha', 'Acciones'];

interface CondominiumsPageProps extends AppPageProps{
    profPayments:ProfPayment[];
}

const page = usePage<CondominiumsPageProps>();
const orderPayments = computed(() => page.props.profPayments.data);
const pagination =computed(() => page.props.profPayments.links);

</script>

<template>
    <Head title="Ordenes de pago"/>
    <AppLayout :breadcrumbs="breadcrumbs">

        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="flex gap-1">
                <ButtonNewRegister url="/prof-payments/create" text="Nueva prueba de pago"/>

                <Button as-child class="bg-blue-600  text-white hover:bg-blue-700">
                    <Link href="/prof-payments/orders/create">Generar ordenes</Link>
                </Button>
            </div>

            <TableRecords caption="Lista de pruebas de pagos" :columns-head="columnsName">
                <TableRow v-for="orderPayment in orderPayments":for="orderPayment.id">
                    <TableCell>{{ orderPayment.condominiums?.clients?.users?.name ?? 'Sin nombre' }}</TableCell>
                    <TableCell>$ {{ formatCurrency(orderPayment.amount) }} <span class="font-bold ">{{ orderPayment.currency.toUpperCase() }}</span></TableCell>
                    <TableCell>$ {{ formatCurrency(orderPayment.discount_condominium) }} <span class="font-bold ">{{ orderPayment.currency.toUpperCase() }}</span></TableCell>
                    <TableCell>{{ orderPayment.status }}</TableCell>
                    <TableCell>{{ formatDateTime(orderPayment.created_at) }}</TableCell>
                    <TableActions>
                         <TableRecordButton
                            type="url"
                            color="bg-orange-600"
                            hover="bg-orange-800"
                            :icon=Pencil
                            :action="`/profDocument/${orderPayment.id}`"
                        />
                    </TableActions>
                </TableRow>
            </TableRecords>

            <TablePagination :links="pagination"/>
        </div>
    </AppLayout>
</template>