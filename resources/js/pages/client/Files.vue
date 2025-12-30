<script setup lang="ts">
import FileCard from '@/components/FileCard/FileCard.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { AppPageProps, BreadcrumbItem, Document } from '@/types';
import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [
    {title:"Mis documentos", href:"#"},
];

interface FilePageProps extends AppPageProps{
    documents: Document[];
    pendingDocuments: [];
}

const page = usePage<FilePageProps>();
const documents = computed(() => page.props.documents);
const pendingDocuments = computed(()=>Object.values(page.props.pendingDocuments));

</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-4 rounded-xl w-9/10 md:w-9/10 lg:w-8/10 xl:/10 mx-auto my-10 border shadow-xl">
            <h2 class="text-xl md:text-4xl font-black text-left">Documentos cargados</h2>
            <hr class="w-full mb-4"/>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-2">
                <FileCard v-if="documents.length > 0" v-for="document in documents":key="document.id"
                    :file-type="document.type_document"
                    :status="document.status"
                    :file-url=" document.status !='rechazado'?`/file-view/${document.id}`:`/file-view/${document.id}/edit`"
                    :text-button="document.status !='rechazado'?'Ver archivo':'Actualizar archivo'"
                />
                <div v-else>
                    <span class="text-lg font-black text-center text-red-800">No hay documentos cargados</span>
                </div>
            </div>
        </div>

        <div class="p-4 rounded-xl w-9/10 md:w-9/10 lg:w-8/10 xl:/10 mx-auto my-10 border shadow-xl">
            <h2 class="text-xl md:text-4xl font-black text-left">Documentos pendientes</h2>
            <hr class="w-full mb-4"/>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-2">
                <FileCard v-if="pendingDocuments.length > 0 " v-for="pendingDocument in pendingDocuments":key="pendingDocument"
                    :file-type="pendingDocument"
                    status="pendiente"
                    :file-url="`/file-upload/${pendingDocument}`"
                    text-button="Cargar archivo"
                />
                <div v-else>
                    <span class="text-lg font-black text-center text-red-800">No hay documentos pendientes</span>
                </div>
            </div>
        </div>
    </AppLayout>
</template>