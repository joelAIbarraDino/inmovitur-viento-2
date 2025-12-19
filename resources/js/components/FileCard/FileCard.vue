<script setup lang="ts">
import { FileText } from 'lucide-vue-next';

const props = defineProps<{
    status:string,
    fileUrl:string,
    fileType:string
}>();

let conteinerClass = "text-white py-2 px-4 rounded-sm flex items-center justify-between "

switch(props.status){
    case 'aceptado':
        conteinerClass +="bg-primary";
    break;

    case 'revision':
        conteinerClass +="bg-orange-500";
    break;

    case 'rechazado':
        conteinerClass +="bg-red-800";
    break;

    case 'pendiente':
        conteinerClass +="bg-amber-500";
    break;

    default:
        conteinerClass +="bg-primary";
    break;
}


</script>

<template>
    <div :class="conteinerClass">
        <div>
            <p class="text-sm">{{ fileType }}</p>
            <a class="text-xl font-bold underline hover:no-underline transition" :href="fileUrl">Ver archivo</a>
        </div>

        <FileText />
    </div>
</template>