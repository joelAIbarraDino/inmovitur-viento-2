<script setup lang="ts">
import { AppPageProps, BreadcrumbItem, Document, ProfPayment } from '@/types';
import { Head, router, usePage } from '@inertiajs/vue3';
import { ArrowLeft } from 'lucide-vue-next';
import { computed, ref } from 'vue';
    
import LoadingOverlay from '@/components/overlay/LoadingOverlay.vue';
import Button from '@/components/ui/button/Button.vue';
import AppLayout from '@/layouts/AppLayout.vue';

const breadcrumbs: BreadcrumbItem[] = [
    {title:"Clientes", href:"/clients"},
    {title:"Ver documento", href:"#"}
];

interface supervisorPagePropos extends AppPageProps{
    profPayment:ProfPayment;
    streamURL:string;
}

const props = usePage<supervisorPagePropos>();

const isProcessing = ref(false);
const profPayment = computed(()=>props.props.profPayment);
const streamURL = computed(()=>props.props.streamURL);
let disabled = ref(profPayment.value.status=='aceptado'?true:false);

function updateStatus(status:string){
    router.patch(
        `/profDocument/${profPayment.value.id}/status`,
        {status},
        {
            preserveScroll:true,
            onStart: () => {
                isProcessing.value = true;
            },
            onFinish: () => {
                isProcessing.value = false;
                disabled.value = true;
            }

        }
    );
}

</script>

<template>
    <Head title="Ver documento"/>
    <AppLayout :breadcrumbs="breadcrumbs" class="relative">
        <LoadingOverlay :show="isProcessing" />
        <div class="flex flex-1 flex-col gap-4 p-4 xl:w-1/2  md:w-3/4 w-11/12 mx-auto my-10 border rounded-md shadow-xl">
            <div class="flex justify-between items-center  gap-2 flex-row">
                <span class="text-primary font-bold text-xl md:text-4xl">Comprobante de pago</span>
                <Button as="a" href="/prof-payments" class="bg-primary hover:bg-cyan-700 dark:text-white"> <ArrowLeft/> Regresar</Button>
            </div>
            <div>
                <Button class="mt-2 bg-amber-500 hover:bg-amber-600" @click="updateStatus('aceptado')" :disabled="disabled">Aceptar Pago</Button>
            </div>
            <img 
                :src="streamURL" 
                class="rounded w-full my-4"
            />

        </div>
    </AppLayout>
</template>