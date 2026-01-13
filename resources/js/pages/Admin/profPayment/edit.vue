<script setup lang="ts">
import { RecordForm, RecordFormBody, RecordFormHeader, RecordFormSubmit } from '@/components/recordForm';
import { AppPageProps, BreadcrumbItem, ProfPayment } from '@/types';
import { Head, useForm, usePage } from '@inertiajs/vue3';
import {LoadingOverlay} from '@/components/overlay';
import AppLayout from '@/layouts/AppLayout.vue';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { HandCoins } from 'lucide-vue-next';

import { computed } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [
    {title:"Clientes", href:"/clients"},
    {title:"Ver documento", href:"#"}
];

interface supervisorPagePropos extends AppPageProps{
    profPayment:ProfPayment;
    streamURL:string;
}

const props = usePage<supervisorPagePropos>();
const profPayment = computed(()=>props.props.profPayment);
const streamURL = computed(()=>props.props.streamURL);

const form = useForm({
    amount: profPayment.value.amount as number | undefined
});

const amountFormatted = computed({
    get(): string {
        if (form.amount === undefined || form.amount === null) {
            return ''
        }

        return form.amount.toLocaleString('en-US', {
            minimumFractionDigits: 0,
            maximumFractionDigits: 2,
        })
    },

    set(value: string) {
        const limpio = value
            .replace(/,/g, '')
            .replace(/[^0-9.]/g, '')

        const numero = parseFloat(limpio)

        form.amount = isNaN(numero) ? undefined : numero
    },
})

function submit(){
    form.put(`/prof-payments/${profPayment.value.id}`, {
        preserveScroll:true,
        onSuccess: () => form.reset()
    });
}

</script>

<template>
    <Head title="Ver documento"/>
    <AppLayout :breadcrumbs="breadcrumbs" class="relative">
        <LoadingOverlay :show="form.processing" />
        <RecordForm>
            <RecordFormHeader title-form="Revisar pago" return-url="/prof-payments"/>
            <RecordFormBody  :handle="submit">
            
                <div class="flex gap-6 flex-col md:flex-row">
                    <div class="flex-1 grid gap-1">
                        <Label for="name">Monto</Label>
                        <Input
                            id="name"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="amountFormatted"
                            placeholder="Monto de pago"
                        />
                        <InputError class="mt-1" :message="form.errors.amount" />
                    </div>
                </div>

                <img 
                    :src="streamURL" 
                    class="rounded w-full my-4"
                />

                <RecordFormSubmit label="Aceptar pago" :icon="HandCoins"/>
            </RecordFormBody>
        </RecordForm>
        
    </AppLayout>
</template>