<script setup lang="ts">

import { Select, SelectContent, SelectGroup, SelectItem, SelectLabel, SelectTrigger, SelectValue } from "@/components/ui/select";
import { AppPageProps, BreadcrumbItem, Client, Condominiums, Enum } from '@/types';
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { ArrowLeft, Save, DollarSign } from 'lucide-vue-next';
import { computed } from 'vue';

import InputError from '@/components/InputError.vue';
import AppLayout from '@/layouts/AppLayout.vue';

const breadcrumbs: BreadcrumbItem[] = [{title:'Condominios', href:'/condominiums'}, {title:'Editar condominio', href:'#'}];

interface CondominiumsPageProps extends AppPageProps{
    clients: Client[];
    currency:Enum[];
    towers:Enum[];
    condominium:Condominiums;
}

const {props} = usePage<CondominiumsPageProps>();
const currency = computed(() => props.currency);
const towers = computed(() => props.towers);
const clients = computed(() => props.clients);


const form = useForm({
    id_client:props.condominium.id_client,
    tower:props.condominium.tower,
    number:props.condominium.number,
    price:props.condominium.price,
    currency:props.condominium.currency
});

function submit(){
    form.put(`/condominiums/${props.condominium.id}`, {
        preserveScroll:true
    });
}

</script>

<template>
    <Head title="Crear condominios"/>

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="px-4 pt-6 flex justify-start items-center gap-3">
            <Button as-child class="bg-primary hover:bg-primary-hover text-white rounded-md gap-2 font-bold" size="lg"> 
                <Link href="/condominiums">
                    <ArrowLeft/> Regresar
                </Link>
            </Button>
        </div>

        <div class="border mx-auto w-9/10 md:w-7/10 lg:w-5/10 mt-4 p-4 rounded-md shadow-2xl">
            <h2 class="text-3xl text-primary font-bold">Nuevo condominio</h2>
            <hr class=" mb-5 mt-2">
            <form @submit.prevent="submit" class="space-y-5">
                
                <div class="grid gap-2 flex-1">
                    <Label for="tower">Torre</Label>
                    <Select v-model="form.tower" class="px-3 py-2 dark:text-white dark:bg-zinc-900" id="tower" disabled>
                        <SelectTrigger>
                            <SelectValue placeholder="Torre del condominio"/>
                        </SelectTrigger>

                        <SelectContent>
                        <SelectGroup>
                            <SelectLabel>Torres</SelectLabel>
                            <SelectItem v-for="tower in towers":key="tower.value" :value="tower.value">
                                {{ tower.label }}
                            </SelectItem>
                        </SelectGroup>
                        </SelectContent>

                    </Select>
                    <InputError class="mt-1" :message="form.errors.tower" />
                </div>

                <div class="grid gap-2 flex-1">
                    <Label for="number">No. de condominio</Label>
                    <Input
                        id="number"
                        type="text"
                        class="mt-1 block w-full"
                        required
                        disabled
                        placeholder="No. de condominio"
                        v-model="form.number"
                    />
                    <InputError class="mt-2" :message="form.errors.number"/>
                </div>

                <div class="grid gap-2 flex-1">
                    <Label for="price">Valor de piso</Label>
                    <div class="relative w-full items-center">
                        <Input required id="price" type="text" placeholder="precio de condominio" class="pl-10" v-model="form.price"/>
                        <span class="absolute start-0 inset-y-0 flex items-center justify-center px-2">
                        <DollarSign class="size-6 text-muted-foreground" />
                        </span>
                    </div>
                    <InputError class="mt-2" :message="form.errors.price"/>
                </div>
                
                <div class="grid gap-2 flex-1">
                    <Label for="currency">Moneda</Label>
                    <Select v-model="form.currency" class="px-3 py-2 dark:text-white dark:bg-zinc-900 w-auto" id="currency" disabled>
                        <SelectTrigger>
                            <SelectValue placeholder="Divisa del condominio"/>
                        </SelectTrigger>

                        <SelectContent>
                        <SelectGroup>
                            <SelectLabel>Monedas</SelectLabel>
                            <SelectItem v-for="currencyItem in currency":key="currencyItem.value" :value="currencyItem.value">
                                {{ currencyItem.label }}
                            </SelectItem>
                        </SelectGroup>
                        </SelectContent>

                    </Select>
                    <InputError class="mt-1" :message="form.errors.currency" />
                </div>

                <div class="grid gap-2 flex-1">
                    <Label for="id_client">Cliente</Label>
                    <Select v-model="form.id_client" class="px-3 py-2 dark:text-white dark:bg-zinc-900 w-auto" id="id_client" disabled>
                        <SelectTrigger>
                            <SelectValue placeholder="Cliente de condominio"/>
                        </SelectTrigger>

                        <SelectContent>
                        <SelectGroup>
                            <SelectLabel>Clientes</SelectLabel>
                            <SelectItem v-for="client in clients":key="client.id" :value="client.id">
                                {{`Cliente # ${client.id}` }}
                            </SelectItem>
                        </SelectGroup>
                        </SelectContent>

                    </Select>
                    <InputError class="mt-1" :message="form.errors.id_client" />
                </div>

                <div class="flex gap-4 justify-start">
                    <Button type="submit" size="lg">
                        <Save /> Guardar
                    </Button>
                </div>
            </form>
        </div>
    </AppLayout>
</template>