<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import AuthBase from '@/layouts/AuthLayout.vue';
import { Head } from '@inertiajs/vue3';
import { useForm } from '@inertiajs/vue3';



const form = useForm({
    email:'',
    password:''
});

function submit(){
    form.post('/new-client', {
        forceFormData:true,
        preserveScroll:true,
        onSuccess: () => form.reset()
    });
}
</script>

<template>
    <AuthBase
        title="Nuevo cliente"
        description="Ingrese su correo y contraseña temporal"
        title-layout="Bienvenido a viento"
        msg-layout="¡Con Viento App, toma el control de los pagos de tu condominio de manera fácil y rápida!"
    >
        <Head title="Registrar cliente" />

        <form
            class="flex flex-col gap-6"
            @submit.prevent="submit"
        >
            <div class="grid gap-6">
                <div class="grid gap-2">
                    <Label for="email">Correo</Label>
                    <Input
                        id="email"
                        type="email"
                        name="email"
                        autofocus
                        :tabindex="1"
                        v-model="form.email"
                        placeholder="email@example.com"
                    />
                    <InputError :message="form.errors.email" />
                </div>

                <div class="grid gap-2">
                    <div class="flex items-center justify-between">
                        <Label for="password">Contraseña</Label>
                    </div>
                    <Input
                        id="password"
                        type="password"
                        name="password"
                        required
                        :tabindex="2"
                        v-model="form.password"
                        placeholder="Contraseña"
                    />
                    <InputError :message="form.errors.password" />
                </div>

                <Button
                    type="submit"
                    class="mt-4 w-full"
                    :disabled="form.processing"
                >
                    <Spinner v-if="form.processing" />
                    Validar
                </Button>
            </div>

            <div class="text-center text-sm text-muted-foreground">
                ¿Ya tienes una cuenta?
                <TextLink href="/login">Inicia sesión</TextLink>
            </div>
        </form>
    </AuthBase>
</template>
