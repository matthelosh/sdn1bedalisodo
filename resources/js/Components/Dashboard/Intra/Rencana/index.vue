<script setup>
import { ref, defineAsyncComponent } from 'vue';
import { usePage } from '@inertiajs/vue3';

const page = usePage();
const props = defineProps({ rombel: Object, mapel: String });

const Cp = defineAsyncComponent(() => import('./Cp.vue'))
const Ki = defineAsyncComponent(() => import('./Ki.vue'))


</script>

<template>
    <div class="w-ful p-3 shadow grid grid-cols-1 md:grid-cols-4 bg-slate-100 gap-3">
        <Cp :mapel="props.mapel" />
        <Ki :mapel="props.mapel" />
    </div>
    
</template>