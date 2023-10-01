<script setup>
import { ref, defineAsyncComponent } from 'vue';
import { usePage } from '@inertiajs/vue3';

const page = usePage();
const props = defineProps({ rombel: Object, mapel: String });

// const Cp = defineAsyncComponent(() => import('./Cp.vue'))
// const Ki = defineAsyncComponent(() => import('./Ki.vue'))
const Ahe = defineAsyncComponent(() => import('./Ahe.vue'))
const Jadwal = defineAsyncComponent(() => import('./Jadwal.vue'))
const Prota = defineAsyncComponent(() => import('./Prota.vue'))
const Materi = defineAsyncComponent(() => import('./Materi.vue'))
const Prosem = defineAsyncComponent(() => import('./Prosem.vue'))


</script>

<template>
    <div class="w-ful p-4 shadow grid grid-cols-1 md:grid-cols-4 bg-slate-600 gap-4 mb-8">
        <!-- <Cp :mapel="props.mapel" />
        <Ki :mapel="props.mapel" /> -->
        <Jadwal :mapel="props.mapel" class="col-span-2 shadow shadow-lime-400" />
        <Ahe class="col-span-2" :mapel="props.mapel" :rombel="props.rombel"  />
        <Materi class="col-span-2" :mapel="props.mapel" :rombel="props.rombel"  />
        <Prota class="col-span-2" :mapel="props.mapel" :rombel="props.rombel" />
        <Prosem class="col-span-4" :mapel="props.mapel" :rombel="props.rombel" />
    </div>
    
</template>