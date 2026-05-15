<script setup lang="ts">

import { Empty,EmptyDescription, EmptyHeader, EmptyMedia, EmptyTitle } from '@/components/ui/empty';
import { FolderSearch } from 'lucide-vue-next';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { AppPageProps, BreadcrumbItem, Client, Condominium, Payment } from '@/types';;
import { Head, router, usePage } from '@inertiajs/vue3';

import AppLayout from '@/layouts/AppLayout.vue';
import { computed } from 'vue';
import TowerCard from '@/components/DashboardCard/TowerCard.vue';
import { formatCurrency } from '@/utils/formatCurrency';
import { formatDateTime } from '@/utils/formatDateTime';
import TableRow from '@/components/ui/table/TableRow.vue';
import TableRecords from '@/components/tableRecords/TableRecords.vue';
import TableCell from '@/components/ui/table/TableCell.vue';

const breadcrumbs: BreadcrumbItem[] = [{title:'Buscar clientes', href:'#'}]

interface PageProps extends AppPageProps{
    cliente: Client;
    condominio: Condominium;
    pagos: Payment[];
    valor_condominio:number;
    moneda_condominio:string;
    pagado:number;
    pendiente:number;
    penas:number;

}

const columnsName = ['Monto', 'Pena', 'Fecha'];

const page = usePage<PageProps>();

const cliente = computed(() => page.props.cliente);
const condominio = computed(() => page.props.condominio);
const pagos = computed(() => page.props.pagos);
const valor_condominio = computed(() => page.props.valor_condominio);
const moneda_condominio = computed(() => page.props.moneda_condominio);
const pagado = computed(() => page.props.pagado);
const pendiente = computed(() => page.props.pendiente);
const penas = computed(() => page.props.penas);

const search = (e:any) => {
    const query = e.target.value;
    
    router.get('/query-client', {
        q:query
    });

}

</script>

<template>
    <Head title="Clientes"/>
    <AppLayout :breadcrumbs="breadcrumbs">

        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="w-full md:w-1/3 space-y-2">
                <Label>Ingresa numero de condominio o nombre de cliente</Label>
                <Input placeholder="Presione enter para buscar el cliente o condominio" @keyup.enter="search"></Input>
            </div>

            <div v-if="!condominio" class="flex-1 border rounded-md">
                <Empty>
                    <EmptyHeader>
                        <EmptyMedia variant="icon">
                            <FolderSearch />
                        </EmptyMedia>
                        <EmptyTitle>Buscar expediente de cliente</EmptyTitle>
                        <EmptyDescription>
                            Ingresa el nombre o numero de condominio y presione enter para buscar a un cliente o condominio
                        </EmptyDescription>
                    </EmptyHeader>
                </Empty>
            </div>

            <div v-else class="flex-1 border p-2 rounded-md overflow-auto">
                <p class="text-xl">Nombre de cliente: <span class="font-bold">{{ cliente.users?.name }}</span></p>
                <p class="text-xl">Valor de condominio: <span class="font-bold">$ {{ formatCurrency(valor_condominio) }} {{ moneda_condominio }}</span></p>
                <div class="flex flex-col md:flex-row items-start justify-start md:justify-evenly w-full md:w-9/10 mx-auto py-20 px-2">
                    
                    <div class="w-9/10 md:w-1/4 mx-auto">
                        <TowerCard
                            :tower-name="condominio.number" 
                            :charged="pagado"
                            :pending="pendiente"
                            :penality="penas"
                            :currency="moneda_condominio"
                            :show-penality="true"
                        />
                    </div>

                    <div class="w-9/10 md:w-2/4 mt-10 md:mt-0 mx-auto space-y-4">
                        <div class="space-y-2">
                            <h2 class="text-xl font-bold text-primary dark:text-white">Pagos registrados</h2>
                            <TableRecords caption="Lista de pagos" :columns-head="columnsName">
                                <TableRow v-for="pago in pagos":for="pago.id">
                                    <TableCell>$ {{ formatCurrency(pago.amount) }} <span class="font-bold ">{{moneda_condominio.toUpperCase() }}</span></TableCell>
                                    <TableCell>$ {{ formatCurrency(pago.discount_condominium) }} <span class="font-bold ">{{ moneda_condominio.toUpperCase() }}</span></TableCell>
                                    <TableCell>{{ formatDateTime(pago.created_at) }}</TableCell>
                                </TableRow>
                            </TableRecords>
                        </div>
                    </div>

                </div>

            </div>

        </div>
    </AppLayout>
</template>