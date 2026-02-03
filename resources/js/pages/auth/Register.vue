<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import AuthBase from '@/layouts/AuthLayout.vue';
import TextLink from '@/components/TextLink.vue';
import { login } from '@/routes';
</script>

<template>
    <AuthBase
        title="Registro deshabilitado"
        description="La creación de cuentas nuevas no está permitida en este momento."
    >
        <Head title="Registro" />

        <div class="flex flex-col gap-6 text-center">
            <p class="text-sm text-muted-foreground">
                Por favor, contacta al administrador del sistema si necesitas una cuenta de acceso.
            </p>

            <div class="text-sm">
                <TextLink
                    :href="login()"
                    class="underline underline-offset-4"
                >
                    Volver al inicio de sesión
                </TextLink>
            </div>
        </div>
    </AuthBase>
</template>