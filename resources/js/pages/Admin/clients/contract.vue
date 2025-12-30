<script setup lang="ts">
import { RecordForm, RecordFormBody, RecordFormHeader, RecordFormSubmit } from '@/components/recordForm';
import { AppPageProps, BreadcrumbItem, Client } from '@/types';
import { Head, useForm, usePage } from '@inertiajs/vue3';
import { LoadingOverlay } from '@/components/overlay';
import InputError from '@/components/InputError.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Label } from '@/components/ui/label';
import { Input } from '@/components/ui/input';
import { computed } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [
    {title:"Clientes", href:"/clients"},
    {title:"Actualizar contrato", href:"#"}
];

interface contractPageProps extends AppPageProps{
    client:Client
}

const page = usePage<contractPageProps>();
const client = computed(() => page.props.client);

const form = useForm({
    contract: null
});

const handleFileChange = (event: { target: { files: null[]; }; }) => {
  form.contract = event.target.files[0];
  form.errors.contract = ''; // Limpiar mensaje anterior
};

function submit(){
    form.post(`/clients/new-contract/${client.value.id}`,{
        forceFormData:true,
        preserveScroll:true,
        onSuccess: () => form.reset()
    })
}

</script>

<template>
    <Head title="Subir contrato"/>
    <AppLayout :breadcrumbs="breadcrumbs" class="relative">
        <LoadingOverlay :show="form.processing" />

        <RecordForm>
            <RecordFormHeader title-form="Subir contrato" return-url="/clients"/>
            <RecordFormBody  :handle="submit">
                <div class="grid gap-1">
                    <Label for="contract">Contrato</Label>
                    <Input
                        id="contract"
                        type="file"
                        accept=".pdf"
                        @change="handleFileChange"
                    />
                    <InputError :message="form.errors.contract" />
                </div>
                <RecordFormSubmit/>
            </RecordFormBody>
        </RecordForm>
    </AppLayout>
</template>