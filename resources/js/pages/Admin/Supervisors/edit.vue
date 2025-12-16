<script setup lang="ts">
import { Head, useForm, usePage } from '@inertiajs/vue3';
import InputError from '@/components/InputError.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { AppPageProps, BreadcrumbItem, User } from '@/types';
import { RecordForm, RecordFormBody, RecordFormHeader, RecordFormSubmit } from '@/components/recordForm';
import { computed } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [
    {title:"Supervisores", href:"/supervisors"},
    {title:"Editar", href:"#"}
];

interface supervisorPagePropos extends AppPageProps{
    supervisor:User;
    edit:boolean;
}

const props = usePage<supervisorPagePropos>();
const supervisor = computed(()=>props.props.supervisor);
const edit = computed(()=>props.props.edit);

const form = useForm({
    name:supervisor.value.name,
    email:supervisor.value.email,
    password:'',
    password_confirmation:'',
});

function submit(){
  form.put(`/supervisors/${props.props.supervisor.id}`,{
    preserveScroll:true,
    onSuccess: () => form.reset()
  })
}

</script>

<template>
    <Head title="Editar supervisor"/>
    <AppLayout :breadcrumbs="breadcrumbs">
      <RecordForm>
        <RecordFormHeader v-if="edit" title-form="Editar supervisor" return-url="/supervisors"/>
        <RecordFormHeader v-else title-form="Ver supervisor" return-url="/supervisors"/>
        <RecordFormBody  :handle="submit">

            <div class="flex gap-6 flex-col md:flex-row">
                <div class="flex-1 grid gap-1">
                    <Label for="name">Nombre</Label>
                    <Input
                        id="name"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="form.name"
                        placeholder="Nombre del administrador"
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
                        placeholder="Correo de administrador"
                        disabled="true"
                    />
                    <InputError class="mt-1" :message="form.errors.email" />
                </div>
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

            <RecordFormSubmit/>
        </RecordFormBody>
      </RecordForm>
    </AppLayout>
</template>