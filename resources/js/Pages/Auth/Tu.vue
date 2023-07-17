<script setup>
import { ref, defineAsyncComponent, computed} from 'vue';
import { Head, usePage, router } from '@inertiajs/vue3';
import { Icon } from '@iconify/vue';

const AdminLayout = defineAsyncComponent(() => import('@/Layouts/AdminLayout.vue'));
const Loading = defineAsyncComponent(() => import('@/Components/General/Loading.vue'));
const loading = ref(false);

const SuratKeluar = defineAsyncComponent(() => import('@/Components/Dashboard/Tu/SuratKeluar.vue'));

const title = ref('Tata Usaha');

const mode = ref('list');
const close = () => {
    mode.value = 'list'
}

</script>

<template>
<Head :title="title" />
<Loading v-if="loading" />
<AdminLayout :title="title">
    <div class="container w-full grid grid-cols-1 md:grid-cols-4 p-2 md:py-4" v-if="mode == 'list'">
        <div class="surat-keluar bg-white p-3 rounded shadow flex justify-between items-center">
            <h1 class="flex gap-1 items-center text-gray-800">
                <Icon icon="mdi:mail" class="text-xl " />
                Surat Keluar
            </h1>

            <button @click="mode = 'surat-keluar'">
                <Icon icon="mdi:open-in-new" class="text-xl text-sky-600 hover:text-sky-400" />
            </button>
        </div>
        <div class="surat-masuk"></div>
    </div>
    <SuratKeluar v-if="mode == 'surat-keluar'" @close="close" />
</AdminLayout>
</template>