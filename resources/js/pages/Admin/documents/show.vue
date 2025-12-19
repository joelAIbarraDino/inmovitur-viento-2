<script setup lang="ts">
import { AppPageProps, BreadcrumbItem, Document } from '@/types';
import { Button } from '@/components/ui/button';
import { Head, usePage } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { ArrowLeft } from 'lucide-vue-next';
import { computed } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [
    {title:"Clientes", href:"/clients"},
    {title:"Ver documento", href:"#"}
];

interface supervisorPagePropos extends AppPageProps{
    document:Document;
    streamURL:string;
}

const props = usePage<supervisorPagePropos>();

const document = computed(()=>props.props.document);
const streamURL = computed(()=>props.props.streamURL);

</script>

<template>
    <Head title="Ver documento"/>
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-1 flex-col gap-4 p-4 xl:w-1/2  md:w-3/4 w-11/12 mx-auto my-10 border rounded-md shadow-xl">
            <div class="flex justify-between items-center  gap-2 flex-row">
                <span class="text-primary font-bold text-xl md:text-4xl">{{ document.type_document }}</span>
                <Button as="a" :href="`/clients/${document.id_client}/edit`" class="bg-primary hover:bg-cyan-700 dark:text-white"> <ArrowLeft/> Regresar</Button>
            </div>
            <div>
                <p><span class="text-primary font-bold">Cliente: </span>{{ document.clients.users?.name??'Sin nombre' }}</p>
                <p><span class="text-primary font-bold">Contrato: </span>{{ document.clients.no_contract??'Sin contrato' }}</p>
                <Button class="bg-primary hover:bg-cyan-700 dark:text-white mt-4">Aceptar documento</Button>
            </div>
            <iframe
                :src="streamURL"
                class="w-full h-[80vh] border rounded"
            />
        </div>
    </AppLayout>
</template>