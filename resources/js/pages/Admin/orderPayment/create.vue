<script setup lang="ts">
import { Head, useForm, usePage } from '@inertiajs/vue3';
import InputError from '@/components/InputError.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { AppPageProps, BreadcrumbItem, Condominium } from '@/types';
import { RecordForm, RecordFormBody, RecordFormHeader, RecordFormSubmit } from '@/components/recordForm';
import { Select, SelectContent, SelectGroup, SelectItem, SelectLabel, SelectTrigger, SelectValue } from "@/components/ui/select";
import { LoadingOverlay } from '@/components/overlay';
import { computed } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [
    {title:"Condominios", href:"/condominiums"},
    {title:"Crear", href:"#"}
];

interface ClientPageProps extends AppPageProps{
    condominiums:Condominium[]
}

const props = usePage<ClientPageProps>();

const condominiums = computed(()=>props.props.condominiums);

const form = useForm({
    id_condominium:undefined,
    amount:undefined as number | undefined,
    discount_condominium:0 as number | undefined,
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

const discount_condominiumFormatted = computed({
    get(): string {
        if (form.discount_condominium === undefined || form.discount_condominium === null) {
            return ''
        }

        return form.discount_condominium.toLocaleString('en-US', {
            minimumFractionDigits: 0,
            maximumFractionDigits: 2,
        })
    },

    set(value: string) {
        const limpio = value
            .replace(/,/g, '')
            .replace(/[^0-9.]/g, '')

        const numero = parseFloat(limpio)

        form.discount_condominium = isNaN(numero) ? undefined : numero
    },
})

function submit(){
  form.post('/order-payments',{
    preserveScroll:true,
    onSuccess: () => form.reset()
  })
}

</script>

<template>
    <Head title="Nueva orden de pago"/>
    <AppLayout :breadcrumbs="breadcrumbs" class="relative">
            <LoadingOverlay :show="form.processing" />

            <RecordForm>
                <RecordFormHeader title-form="Nueva orden de pago" return-url="/order-payments"/>
                <RecordFormBody  :handle="submit">

                    <div class="grid gap-1">
                        <Label for="id_condominium">Condominio</Label>

                        <Select v-model="form.id_condominium" class="px-3 py-2 dark:text-white dark:bg-zinc-900" id="id_condominium">
                            <SelectTrigger>
                                <SelectValue placeholder="Condominios"/>
                            </SelectTrigger>

                            <SelectContent>
                                <SelectGroup>
                                    <SelectLabel>Condominios</SelectLabel>
                                    <SelectItem v-for="condominium in condominiums":key="condominium.id" :value="condominium.id">
                                    {{ condominium.number }}
                                    </SelectItem>
                                </SelectGroup>
                            </SelectContent>

                        </Select>

                        <InputError class="mt-1" :message="form.errors.id_condominium" />
                    </div>

                    <div class="grid gap-1">
                        <Label for="price">Pago</Label>
                        <Input
                            id="price"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="amountFormatted"
                            placeholder="Precio de condominio"
                        />
                        <InputError class="mt-1" :message="form.errors.amount" />
                    </div>

                    <div class="grid gap-1">
                        <Label for="discount_condominium">Pena</Label>
                        <Input
                            id="discount_condominium"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="discount_condominiumFormatted"
                            placeholder="Precio de condominio"
                        />
                        <InputError class="mt-1" :message="form.errors.discount_condominium" />
                    </div>

                    <RecordFormSubmit/>
                </RecordFormBody>
            </RecordForm>
    </AppLayout>
</template>