<script setup>
import { computed, ref } from 'vue';
import { Head } from '@inertiajs/vue3';
import DashLayout from '@/Layouts/AdminLayout.vue'

import {terbilang} from '@/Plugins/terbilang'

const number = ref(null)

const counted = computed(() => {
    return terbilang(number.value)
})
</script>

<template>
<Head title="Pengaturan" />
<DashLayout title="Pengaturan">
    <div class="w-full h-[90vh] bg-white flex items-center justify-center">
        <div class="text-center">
            <h1 class="text-2xl text-slate-800 font-bold">{{ counted }}</h1>
            <input type="number" v-model="number">
        </div>
    </div>
</DashLayout>
</template>