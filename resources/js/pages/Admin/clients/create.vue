<script setup lang="ts">
import { Head, useForm, usePage } from '@inertiajs/vue3';
import InputError from '@/components/InputError.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { AppPageProps, BreadcrumbItem, enums } from '@/types';
import { RecordForm, RecordFormBody, RecordFormHeader, RecordFormSubmit } from '@/components/recordForm';
import { Select, SelectContent, SelectGroup, SelectItem, SelectLabel, SelectTrigger, SelectValue } from "@/components/ui/select";
import { LoadingOverlay } from '@/components/overlay';
import { computed } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [
    {title:"Clientes", href:"/clients"},
    {title:"Crear", href:"#"}
];

interface ClientPageProps extends AppPageProps{
    LegalPersonality:enums[];
    MaritalPartnership:enums[];
    Nacionality:enums[];
}

const props = usePage<ClientPageProps>();
const LegalPersonalityArray = computed(() => props.props.LegalPersonality);
const MaritalPartnershipArray = computed(() => props.props.MaritalPartnership);
const NacionalityArray = computed(() => props.props.Nacionality);

const form = useForm({
    no_contract:'', 
    name:'',
    email:'',
    phone:'',
    legal_personality:undefined,
    marital_partnership:undefined,
    nacionality:undefined,
    password:'',
    password_confirmation:'',
    contract: null
});

const handleFileChange = (event: { target: { files: null[]; }; }) => {
  form.contract = event.target.files[0];
  form.errors.contract = ''; // Limpiar mensaje anterior
};

function submit(){
    form.post('/clients',{
        forceFormData:true,
        preserveScroll:true,
        onSuccess: () => form.reset()
    })
}

</script>

<template>
    <Head title="Nuevo cliente"/>
    <AppLayout :breadcrumbs="breadcrumbs" class="relative">
        <LoadingOverlay :show="form.processing" />

        <RecordForm>
            <RecordFormHeader title-form="Nuevo cliente" return-url="/clients"/>
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
                        />
                        <InputError class="mt-1" :message="form.errors.phone" />
                    </div>
                </div>

                <div class="flex gap-6 flex-col md:flex-row">
                    <div class="flex-1 grid gap-1">
                        <Label for="legal_personality">Personalidad jurídica</Label>

                        <Select v-model="form.legal_personality" class="px-3 py-2 dark:text-white dark:bg-zinc-900" id="type">
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
                        <Label for="marital_partnership">Sociedad conyugal</Label>

                        <Select v-model="form.marital_partnership" class="px-3 py-2 dark:text-white dark:bg-zinc-900" id="type">
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

                    <Select v-model="form.nacionality" class="px-3 py-2 dark:text-white dark:bg-zinc-900" id="nacionality">
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

                <div class="grid gap-1">
                <Label for="password">Contraseña</Label>
                <Input
                    id="password"
                    type="password"
                    class="mt-1 block w-full"
                    v-model="form.password"
                    placeholder="Correo de usuario"
                />
                <InputError class="mt-1" :message="form.errors.password" />
                </div>

                <div class="grid gap-1">
                <Label for="password_confirmation">Confirmar contraseña</Label>
                <Input
                    id="password_confirmation"
                    type="password"
                    class="mt-1 block w-full"
                    v-model="form.password_confirmation"
                    placeholder="Correo de usuario"
                />
                <InputError class="mt-1" :message="form.errors.password_confirmation" />
                </div>

                <RecordFormSubmit/>
            </RecordFormBody>
        </RecordForm>
    </AppLayout>
</template>