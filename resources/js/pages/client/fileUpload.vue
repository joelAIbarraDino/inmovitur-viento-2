<script setup lang="ts">

import { RecordForm, RecordFormBody, RecordFormHeader, RecordFormSubmit } from '@/components/recordForm';
import { AppPageProps, BreadcrumbItem } from '@/types';
import { LoadingOverlay } from '@/components/overlay';
import { useForm, usePage } from '@inertiajs/vue3';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { computed } from 'vue';

import InputError from '@/components/InputError.vue';
import AppLayout from '@/layouts/AppLayout.vue';

const breadcrumbs: BreadcrumbItem[] = [
    {title:"Mis documentos", href:"/my-files"},
    {title:"Cargar documento", href:"#"},
];

interface FilePageProps extends AppPageProps{
    typeFile: string;
}

const page = usePage<FilePageProps>();
const typeFile = computed(() => page.props.typeFile);

const form = useForm({
    file:null,
    type_document:typeFile.value
});



const handleFileChange = (event: { target: { files: null[]; }; }) => {
  form.file = event.target.files[0];
  form.errors.file = '';
};

function submit(){
    form.post('/file-upload',{
        forceFormData:true,
        preserveScroll:true,
        onSuccess: () => form.reset()
    })
}

</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs"  class="relative">
        <LoadingOverlay :show="form.processing" />

        <RecordForm>
            <RecordFormHeader :title-form="`Cargar ${typeFile}`" return-url="/my-files"/>
            <RecordFormBody  :handle="submit">

                <div class="grid gap-1">
                    <Label for="file">{{typeFile}}</Label>
                    <Input
                        id="file"
                        type="file"
                        accept=".pdf"
                        @change="handleFileChange"
                    />
                    <InputError :message="form.errors.file" />
                </div>

                <RecordFormSubmit/>
            </RecordFormBody>
        </RecordForm>
    </AppLayout>
</template>