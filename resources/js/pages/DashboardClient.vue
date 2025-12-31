<script setup lang="ts">
import { AppPageProps, Condominium, OrderPayment, Payment, ProfPayment, type BreadcrumbItem } from '@/types';
import TowerCard from '@/components/DashboardCard/TowerCard.vue';
import { TableRecords } from '@/components/tableRecords';
import { Button } from '@/components/ui/button';
import { TableCell, TableRow } from '@/components/ui/table';
import AppLayout from '@/layouts/AppLayout.vue';
import { formatCurrency } from '@/utils/formatCurrency';
import { formatDateTime } from '@/utils/formatDateTime';
import { Head, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const columnsName = ['Monto', 'Pena', 'Fecha'];
const columnsName2 = ['Monto', 'Pena', 'Fecha', 'Pago'];
const breadcrumbs: BreadcrumbItem[] = [{title: 'Mi cuenta',href:'#'},];

interface DashboardPageProps extends AppPageProps{
    condominium:Condominium;
    charged:number;
    pending:number;
    penality:number;
    currency:string;
    payments:Payment[];
    orderPayments:OrderPayment[];
    profPayments:ProfPayment[];
}

const page = usePage<DashboardPageProps>();
const condominium = computed(()=>page.props.condominium);
const charged = computed(()=>page.props.charged);
const pending = computed(()=>page.props.pending);
const penality = computed(()=>page.props.penality);
const currency = computed(()=>page.props.currency);
const payments = computed(()=>page.props.payments);
const orderPayments = computed(()=>page.props.orderPayments);
const profPayments = computed(()=>page.props.profPayments);

</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-col md:flex-row items-start justify-start md:justify-evenly w-full md:w-9/10 mx-auto py-20 px-5">
            
            <div class="w-9/10 md:w-1/4 mx-auto">
                <TowerCard 
                    :tower-name="condominium.number" 
                    :charged="charged"
                    :pending="pending"
                    :penality="penality"
                    :currency="currency"
                />
            </div>


            <div class="w-9/10 md:w-2/4 mt-10 md:mt-0 mx-auto space-y-4">

                <div class="space-y-2">
                    <h2 class="text-xl font-bold text-primary dark:text-white">Pagos registrados</h2>
                    <TableRecords caption="Lista de pagos" :columns-head="columnsName">
                        <TableRow v-for="payment in payments":for="payment.id">
                            <TableCell>$ {{ formatCurrency(payment.amount) }} <span class="font-bold ">{{ payment.currency.toUpperCase() }}</span></TableCell>
                            <TableCell>$ {{ formatCurrency(payment.discount_condominium) }} <span class="font-bold ">{{ payment.currency.toUpperCase() }}</span></TableCell>
                            <TableCell>{{ formatDateTime(payment.created_at) }}</TableCell>
                        </TableRow>
                    </TableRecords>

                    <Button class="my-4 bg-red-600  hover:bg-red-700" size="lg"> 
                        <a 
                            href="/voucher"
                            rel="noopener noreferrer"
                            class="text-white hover:underline"
                        >
                            Estado de cuenta
                        </a>
                        
                    </Button>

                </div>

                <div class="space-y-2">
                    <h2 class="text-xl font-bold text-primary dark:text-white">Ordenes de pago</h2>
                    <TableRecords v-if="condominium.currency==='usd'" caption="Lista de pagos" :columns-head="columnsName2">
                        <TableRow v-for="profPayment in profPayments":for="profPayment.id">
                            <TableCell>$ {{ formatCurrency(profPayment.amount) }} <span class="font-bold ">{{ profPayment.currency.toUpperCase() }}</span></TableCell>
                            <TableCell>$ {{ formatCurrency(profPayment.discount_condominium) }} <span class="font-bold ">{{ profPayment.currency.toUpperCase() }}</span></TableCell>
                            <TableCell>{{ formatDateTime(profPayment.created_at) }}</TableCell>
                            <TableCell>
                                <a 
                                    :href="`prof-upload/${profPayment.id}`" 
                                    rel="noopener noreferrer"
                                    class="text-primary hover:underline"
                                >
                                    subir comprobante
                                </a>
                                
                            </TableCell>
                        </TableRow>
                    </TableRecords>

                    <TableRecords v-else caption="Lista de pagos" :columns-head="columnsName2">
                        <TableRow v-for="orderPayment in orderPayments":for="orderPayment.id">
                            <TableCell>$ {{ formatCurrency(orderPayment.amount) }} <span class="font-bold ">{{ orderPayment.currency.toUpperCase() }}</span></TableCell>
                            <TableCell>$ {{ formatCurrency(orderPayment.discount_condominium) }} <span class="font-bold ">{{ orderPayment.currency.toUpperCase() }}</span></TableCell>
                            <TableCell>{{ formatDateTime(orderPayment.created_at) }}</TableCell>
                            <TableCell>
                                <a 
                                    :href="`${orderPayment.url_spei}`" 
                                    target="_blank" 
                                    rel="noopener noreferrer"
                                    class="text-primary hover:underline"
                                >
                                    datos SPEI
                                </a>
                                
                            </TableCell>
                        </TableRow>
                    </TableRecords>
              </div>
              
                
            </div>


        </div>
    </AppLayout>
</template>
