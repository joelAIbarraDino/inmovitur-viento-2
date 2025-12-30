<script setup lang="ts">
import { AppPageProps, BreadcrumbItem, Document } from '@/types';
import { Head, router, usePage } from '@inertiajs/vue3';
import { ArrowLeft } from 'lucide-vue-next';
import { computed, ref } from 'vue';
    
import LoadingOverlay from '@/components/overlay/LoadingOverlay.vue';
import Button from '@/components/ui/button/Button.vue';
import AppLayout from '@/layouts/AppLayout.vue';

const breadcrumbs: BreadcrumbItem[] = [
    {title:"Mis documentos", href:"/my-files"},
    {title:"Ver documento", href:"#"}
];

interface supervisorPagePropos extends AppPageProps{
    document:Document;
    streamURL:string;
}

const props = usePage<supervisorPagePropos>();

const isProcessing = ref(false);
const document = computed(()=>props.props.document);
const streamURL = computed(()=>props.props.streamURL);

function updateStatus(status:string){
    router.patch(
        `/documents/${document.value.id}/status`,
        {status},
        {
            preserveScroll:true,
            onStart: () => {
                isProcessing.value = true;
            },
            onFinish: () => {
                isProcessing.value = false;
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
                <span class="text-primary font-bold text-xl md:text-4xl">{{ document.type_document }}</span>
                <Button as="a" href="/my-files" class="bg-primary hover:bg-cyan-700 dark:text-white"> <ArrowLeft/> Regresar</Button>
            </div>
            <iframe
                :src="streamURL"
                class="w-full h-[80vh] border rounded"
            />
        </div>
    </AppLayout>
</template>