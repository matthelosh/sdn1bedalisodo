<script setup>
import { ref, defineAsyncComponent, computed} from 'vue';
import { Head, usePage, router } from '@inertiajs/vue3';
import { Icon } from '@iconify/vue';

const AdminLayout = defineAsyncComponent(() => import('@/Layouts/AdminLayout.vue'));
const Loading = defineAsyncComponent(() => import('@/Components/General/Loading.vue'));
const loading = ref(false);

const Surat = defineAsyncComponent(() => import('@/Components/Dashboard/Tu/Surat.vue'));
const Presensi = defineAsyncComponent(() => import('@/Components/Dashboard/Tu/Presensi.vue'))

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
    <div class="w-full h-[93vh] overflow-y-auto px-6 py-4 bg-slate-300 grid grid-cols-1 sm:grid-cols-4" v-if="mode=='list'">
        <el-button size="large" @click="mode = 'presensi'">
            Form Daftar Hadir Pegawai
            <Icon icon="mdi:account-multiple-check" class="ml-2 text-2xl" />
        </el-button>
        <el-button size="large" @click="mode = 'surat-keluar'">
            Persuratan
            <Icon icon="mdi:mailbox" class="ml-2 text-2xl" />
        </el-button>
        
    </div>
    <Surat v-if="mode == 'surat-keluar'" @close="close" />
    <Presensi v-if="mode=='presensi'" @close="mode='list'" />
</AdminLayout>
</template>