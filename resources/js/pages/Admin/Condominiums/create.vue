<script setup lang="ts">
import { Head, useForm, usePage } from '@inertiajs/vue3';
import InputError from '@/components/InputError.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { AppPageProps, BreadcrumbItem, Client, enums } from '@/types';
import { RecordForm, RecordFormBody, RecordFormHeader, RecordFormSubmit } from '@/components/recordForm';
import { Select, SelectContent, SelectGroup, SelectItem, SelectLabel, SelectTrigger, SelectValue } from "@/components/ui/select";
import { LoadingOverlay } from '@/components/overlay';
import { computed } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [
    {title:"Condominios", href:"/condominiums"},
    {title:"Crear", href:"#"}
];

interface ClientPageProps extends AppPageProps{
    towers:enums[];
    currency:enums[];
    clients:Client[];
}

const props = usePage<ClientPageProps>();

const towerArray = computed(()=>props.props.towers);
const currencyArray = computed(()=>props.props.currency);
const clients = computed(()=>props.props.clients);

const form = useForm({
    id_client:undefined,
    tower:undefined,
    number:undefined,
    price:undefined as number | undefined,
    currency:undefined,

});

const priceFormatted = computed({
    get(): string {
        if (form.price === undefined || form.price === null) {
            return ''
        }

        return form.price.toLocaleString('en-US', {
            minimumFractionDigits: 0,
            maximumFractionDigits: 2,
        })
    },

    set(value: string) {
        const limpio = value
            .replace(/,/g, '')
            .replace(/[^0-9.]/g, '')

        const numero = parseFloat(limpio)

        form.price = isNaN(numero) ? undefined : numero
    },
})

function submit(){
  form.post('/condominiums',{
    preserveScroll:true,
    onSuccess: () => form.reset()
  })
}

</script>

<template>
    <Head title="Nuevo condominio"/>
    <AppLayout :breadcrumbs="breadcrumbs" class="relative">
        <LoadingOverlay :show="form.processing" />
        <RecordForm>
            <RecordFormHeader title-form="Nuevo condominio" return-url="/condominiums"/>
            <RecordFormBody  :handle="submit">

                <div class="grid gap-1">
                    <Label for="tower">Torre</Label>

                    <Select v-model="form.tower" class="px-3 py-2 dark:text-white dark:bg-zinc-900" id="tower">
                        <SelectTrigger>
                            <SelectValue placeholder="Torre"/>
                        </SelectTrigger>

                        <SelectContent>
                            <SelectGroup>
                                <SelectLabel>Torres</SelectLabel>
                                <SelectItem v-for="tower in towerArray":key="tower.value" :value="tower.value">
                                {{ tower.label }}
                                </SelectItem>
                            </SelectGroup>
                        </SelectContent>

                    </Select>

                    <InputError class="mt-1" :message="form.errors.tower" />
                </div>

                <div class="grid gap-1">
                    <Label for="number">No. de condominio</Label>
                    <Input
                        id="number"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="form.number"
                        placeholder="Numero de condominio"
                    />
                    <InputError class="mt-1" :message="form.errors.number" />
                </div>

                <div class="grid gap-1">
                    <Label for="price">Precio</Label>
                    <Input
                        id="price"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="priceFormatted"
                        placeholder="Precio de condominio"
                    />
                    <InputError class="mt-1" :message="form.errors.price" />
                </div>

                <div class="grid gap-1">
                    <Label for="currency">Divisa de condominio</Label>

                    <Select v-model="form.currency" class="px-3 py-2 dark:text-white dark:bg-zinc-900" id="currency">
                        <SelectTrigger>
                            <SelectValue placeholder="Divisa"/>
                        </SelectTrigger>

                        <SelectContent>
                            <SelectGroup>
                                <SelectLabel>Divisas</SelectLabel>
                                <SelectItem v-for="currency in currencyArray":key="currency.value" :value="currency.value">
                                {{ currency.label }}
                                </SelectItem>
                            </SelectGroup>
                        </SelectContent>

                    </Select>

                    <InputError class="mt-1" :message="form.errors.currency" />
                </div>

                <div class="grid gap-1">
                    <Label for="id_client">Cliente</Label>

                    <Select v-model="form.id_client" class="px-3 py-2 dark:text-white dark:bg-zinc-900" id="id_client">
                        <SelectTrigger>
                            <SelectValue placeholder="Cliente"/>
                        </SelectTrigger>

                        <SelectContent>
                            <SelectGroup>
                                <SelectLabel>Clientes</SelectLabel>
                                <SelectItem v-for="client in clients":key="client.id" :value="client.id">
                                {{ client.no_contract }} - {{ client.users?.name ?? "Sin nombre" }}
                                </SelectItem>
                            </SelectGroup>
                        </SelectContent>

                    </Select>

                    <InputError class="mt-1" :message="form.errors.tower" />
                </div>

                <RecordFormSubmit/>
            </RecordFormBody>
        </RecordForm>
    </AppLayout>
</template>