<x-mail::message>
# ¡Hola!

Orden de pago generado con exito

Entra a la plataforma para tener mas detalles de tu pago

<x-mail::button :url="$url">
Iniciar sesión
</x-mail::button>

Saludos,<br>
{{ config('app.name') }}
</x-mail::message>