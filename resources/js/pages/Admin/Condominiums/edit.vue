<script setup lang="ts">
import { Head, useForm, usePage } from '@inertiajs/vue3';
import InputError from '@/components/InputError.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { AppPageProps, BreadcrumbItem, Client, Condominium, enums } from '@/types';
import { RecordForm, RecordFormBody, RecordFormHeader, RecordFormSubmit } from '@/components/recordForm';
import { Select, SelectContent, SelectGroup, SelectItem, SelectLabel, SelectTrigger, SelectValue } from "@/components/ui/select";
import { computed } from 'vue';
import { Checkbox } from '@/components/ui/checkbox';
import { LoadingOverlay } from '@/components/overlay';

const breadcrumbs: BreadcrumbItem[] = [
    {title:"Condominios", href:"/condominiums"},
    {title:"Editar", href:"#"}
];

interface ClientPageProps extends AppPageProps{
    towers:enums[];
    currency:enums[];
    clients:Client[];
    condominium:Condominium;
    edit:boolean;
}

const props = usePage<ClientPageProps>();

const towerArray = computed(()=>props.props.towers);
const currencyArray = computed(()=>props.props.currency);
const clients = computed(()=>props.props.clients);
const condominium = computed(()=>props.props.condominium);
const edit = computed(()=>props.props.edit);

const form = useForm({
    id_client:condominium.value.id_client,
    tower:condominium.value.tower,
    number:condominium.value.number,
    price:condominium.value.price as number | undefined,
    monthly_payment:condominium.value.monthly_payment as number | undefined,
    currency:condominium.value.currency,
    show_penality:Boolean(condominium.value.show_penality),

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

const priceFormatted2 = computed({
    get(): string {
        if (form.monthly_payment === undefined || form.monthly_payment === null) {
            return ''
        }

        return form.monthly_payment.toLocaleString('en-US', {
            minimumFractionDigits: 0,
            maximumFractionDigits: 2,
        })
    },

    set(value: string) {
        const limpio = value
            .replace(/,/g, '')
            .replace(/[^0-9.]/g, '')

        const numero = parseFloat(limpio)

        form.monthly_payment = isNaN(numero) ? undefined : numero
    },
})

function submit(){
  form.put(`/condominiums/${condominium.value.id}`,{
    preserveScroll:true,
    onSuccess: () => form.reset()
  })
}

</script>

<template>
    <Head title="Editar condominio"/>
    <AppLayout :breadcrumbs="breadcrumbs" class="relative">
        <LoadingOverlay :show="form.processing" />
        <RecordForm>
            <RecordFormHeader title-form="Editar condominio" return-url="/condominiums"/>
            <RecordFormBody  :handle="submit">

                <div class="grid gap-1">
                    <Label for="tower">Torre</Label>

                    <Select v-model="form.tower" class="px-3 py-2 dark:text-white dark:bg-zinc-900" id="tower" :disabled="true">
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
                        disabled="true"
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
                        placeholder="precio de condominio"
                        :disabled="!edit"
                    />
                    <InputError class="mt-1" :message="form.errors.price" />
                </div>

                <div class="grid gap-1">
                    <Label for="monthly_payment">Mensualidad</Label>
                    <Input
                        id="monthly_payment"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="priceFormatted2"
                        placeholder="Mensualidad de condominio"
                    />
                    <InputError class="mt-1" :message="form.errors.monthly_payment" />
                </div>

                <div class="grid gap-1">
                    <Label for="currency">Divisa de condominio</Label>

                    <Select v-model="form.currency" class="px-3 py-2 dark:text-white dark:bg-zinc-900" id="currency" :disabled="true">
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

                    <Select v-model="form.id_client" class="px-3 py-2 dark:text-white dark:bg-zinc-900" id="id_client" :disabled="!edit">
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

                    <InputError class="mt-1" :message="form.errors.id_client" />
                </div>

                <Label class="hover:bg-accent/50 flex items-start gap-3 rounded-lg border p-3 has-[[aria-checked=true]]:border-orange-600 has-[[aria-checked=true]]:bg-orange-50 dark:has-[[aria-checked=true]]:border-orange-900 dark:has-[[aria-checked=true]]:bg-orange-950">
                    <Checkbox
                        id="show_penality"
                        v-model="form.show_penality"
                        @update:checked="(value:boolean) => form.show_penality = value"
                        class="data-[state=checked]:border-orange-600 data-[state=checked]:bg-orange-600 data-[state=checked]:text-white dark:data-[state=checked]:border-orange-700 dark:data-[state=checked]:bg-orange-700"
                    />
                    <div class="grid gap-1.5 font-normal">
                        <p class="text-sm leading-none font-medium">Mostrar penas al cliente</p>
                        <p class="text-muted-foreground text-sm">Puedes editar o deshabilitar esta opcion en cualquier momento</p>
                    </div>
                </Label>
                
                <RecordFormSubmit v-if="edit"/>
            </RecordFormBody>
        </RecordForm>
    </AppLayout>
</template>