<script setup>
import { computed, defineAsyncComponent, ref } from 'vue';
import { Head } from '@inertiajs/vue3';
import DashLayout from '@/Layouts/AdminLayout.vue'

const Agenda = defineAsyncComponent(() => import('@/Components/Dashboard/Settings/Agenda.vue'))
const Tapel = defineAsyncComponent(() => import('@/Components/Dashboard/Settings/Tapel.vue'))
</script>

<template>
<Head title="Pengaturan" />
<DashLayout title="Pengaturan">
    <div class="wrapper w-full p-3 grid grid-cols-1 md:grid-cols-4 gap-3">
        <Agenda />
        <Tapel />
    </div>
</DashLayout>
</template>