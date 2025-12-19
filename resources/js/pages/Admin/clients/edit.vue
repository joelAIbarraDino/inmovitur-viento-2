<script setup lang="ts">
import { Head, useForm, usePage } from '@inertiajs/vue3';
import InputError from '@/components/InputError.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { AppPageProps, BreadcrumbItem, Client, enums } from '@/types';
import { RecordForm, RecordFormBody, RecordFormHeader, RecordFormSubmit } from '@/components/recordForm';
import { Select, SelectContent, SelectGroup, SelectItem, SelectLabel, SelectTrigger, SelectValue } from "@/components/ui/select";
import { computed } from 'vue';
import { LoadingOverlay } from '@/components/overlay';

const breadcrumbs: BreadcrumbItem[] = [
    {title:"Clientes", href:"/clients"},
    {title:"Editar", href:"#"}
];

interface ClientPageProps extends AppPageProps{
    LegalPersonality:enums[];
    MaritalPartnership:enums[];
    client:Client;
    Nacionality:enums[];
    edit:boolean;
}

const props = usePage<ClientPageProps>();
const MaritalPartnershipArray = computed(() => props.props.MaritalPartnership);
const LegalPersonalityArray = computed(() => props.props.LegalPersonality);
const NacionalityArray = computed(() => props.props.Nacionality);
const client = computed(()=>props.props.client);
const edit = computed(()=>props.props.edit);

const form = useForm({
    no_contract:client.value.no_contract, 
    name:client.value.users?.name,
    email:client.value.users?.email,
    phone:client.value.phone,
    legal_personality:client.value.legal_personality,
    marital_partnership:client.value.marital_partnership,
    nacionality:client.value.nacionality,
    password:'',
    password_confirmation:'',
});

function submit(){
  form.put(`/clients/${props.props.client.id}`,{
    preserveScroll:true,
    onSuccess: () => form.reset()
  })
}

</script>

<template>
    <Head title="Editar cliente"/>
    <AppLayout :breadcrumbs="breadcrumbs" class="relative">
        <LoadingOverlay :show="form.processing" />
        <RecordForm>
            <RecordFormHeader v-if="edit" title-form="Editar cliente" return-url="/clients"/>
            <RecordFormHeader v-else title-form="Ver cliente" return-url="/clients"/>
            <RecordFormBody  :handle="submit">

                <div class="flex gap-6 flex-col md:flex-row">
                    <div class="flex-1 grid gap-1">
                        <Label for="no_contract">No de contrato</Label>
                        <Input
                            id="no_contract"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="form.no_contract"
                            placeholder="Numero de contrato"
                            disabled="true"
                        />
                        <InputError class="mt-1" :message="form.errors.no_contract" />
                    </div>

                    <div class="flex-1 grid gap-1">
                        <Label for="name">Nombre</Label>
                        <Input
                            id="name"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="form.name"
                            placeholder="Nombre de cliente"
                            :disabled="!edit"
                        />
                        <InputError class="mt-1" :message="form.errors.name" />
                    </div>
                </div>

                <div class="flex gap-6 flex-col md:flex-row">
                    <div class="flex-1 grid gap-1">
                        <Label for="email">Correo</Label>
                        <Input
                            id="email"
                            type="email"
                            class="mt-1 block w-full"
                            v-model="form.email"
                            placeholder="Correo de cliente"
                            disabled="true"
                        />
                        <InputError class="mt-1" :message="form.errors.email" />
                    </div>

                    <div class="flex-1 grid gap-1">
                        <Label for="phone">Teléfono</Label>
                        <Input
                            id="phone"
                            type="tel"
                            class="mt-1 block w-full"
                            v-model="form.phone"
                            placeholder="Teléfono del cliente"
                            :disabled="!edit"
                        />
                        <InputError class="mt-1" :message="form.errors.phone" />
                    </div>
                </div>

                <div class="flex gap-6 flex-col md:flex-row">
                    <div class="flex-1 grid gap-1">
                        <Label>Personalidad jurídica</Label>

                        <Select v-model="form.legal_personality" class="px-3 py-2 dark:text-white dark:bg-zinc-900" id="legal_personality" :disabled="!edit">
                            <SelectTrigger>
                                <SelectValue placeholder="Personalidad juridica"/>
                            </SelectTrigger>

                            <SelectContent>
                                <SelectGroup>
                                    <SelectLabel>Personalidad jurídica</SelectLabel>
                                    <SelectItem v-for="LegalPersonality in LegalPersonalityArray":key="LegalPersonality.value" :value="LegalPersonality.value">
                                    {{ LegalPersonality.label }}
                                    </SelectItem>
                                </SelectGroup>
                            </SelectContent>

                        </Select>

                        <InputError class="mt-1" :message="form.errors.legal_personality" />
                    </div>

                    <div class="flex-1 grid gap-1">
                        <Label>Sociedad conyugal</Label>

                        <Select v-model="form.marital_partnership" class="px-3 py-2 dark:text-white dark:bg-zinc-900" id="marital_partnership" :disabled="!edit">
                            <SelectTrigger>
                                <SelectValue placeholder="Estado civil"/>
                            </SelectTrigger>

                            <SelectContent>
                                <SelectGroup>
                                    <SelectLabel>Sociedad conyugal</SelectLabel>
                                    <SelectItem v-for="MaritalPartnership in MaritalPartnershipArray":key="MaritalPartnership.value" :value="MaritalPartnership.value">
                                    {{ MaritalPartnership.label }}
                                    </SelectItem>
                                </SelectGroup>
                            </SelectContent>

                        </Select>

                        <InputError class="mt-1" :message="form.errors.marital_partnership" />
                    </div>
                </div>

                <div class="grid gap-1">
                    <Label for="legal_personality">Nacionalidad</Label>

                    <Select v-model="form.nacionality" class="px-3 py-2 dark:text-white dark:bg-zinc-900" id="nacionality" :disabled="!edit">
                        <SelectTrigger>
                            <SelectValue placeholder="Nacionalidad"/>
                        </SelectTrigger>

                        <SelectContent>
                            <SelectGroup>
                                <SelectLabel>Nacionalidad</SelectLabel>
                                <SelectItem v-for="Nacionality in NacionalityArray":key="Nacionality.value" :value="Nacionality.value">
                                {{ Nacionality.label }}
                                </SelectItem>
                            </SelectGroup>
                        </SelectContent>

                    </Select>

                    <InputError class="mt-1" :message="form.errors.nacionality" />
                </div>

                <div class="grid gap-1" v-if="edit">
                    <Label for="password">Contraseña</Label>
                    <Input
                        id="password"
                        type="password"
                        class="mt-1 block w-full"
                        v-model="form.password"
                        placeholder="Correo de usuario"
                        :disabled="!edit"
                    />
                    <InputError class="mt-1" :message="form.errors.password" />
                </div>

                <div class="grid gap-1" v-if="edit">
                    <Label for="password_confirmation">Confirmar contraseña</Label>
                    <Input
                        id="password_confirmation"
                        type="password"
                        class="mt-1 block w-full"
                        v-model="form.password_confirmation"
                        placeholder="Correo de usuario"
                        :disabled="!edit"
                    />
                    <InputError class="mt-1" :message="form.errors.password_confirmation" />
                </div>

                <RecordFormSubmit v-if="edit"/>
            </RecordFormBody>
        </RecordForm>
    </AppLayout>
</template>