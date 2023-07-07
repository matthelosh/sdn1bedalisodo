<script setup>
import { Head } from '@inertiajs/vue3';
import { ref, defineAsyncComponent, computed } from 'vue';
import { Icon } from '@iconify/vue';
import Dash from '@/Layouts/AdminLayout.vue'

const Kuitansi = defineAsyncComponent(() => import('@/Components/Dashboard/Bos/Kuitansi.vue'));
const Transaksi = defineAsyncComponent(() => import('@/Components/Dashboard/Bos/Transaksi.vue'));
const Bku = defineAsyncComponent(() => import('@/Components/Dashboard/Bos/Bku.vue'));
const Rkas = defineAsyncComponent(() => import('@/Components/Dashboard/Bos/Rkas.vue'));

const mode = ref('transaksi')
const title = computed(() => {
    return mode.value[0].toUpperCase() + mode.value.slice(1)
})
const cetak = async() => {
    await window.print()
}
</script>

<template>
<Head :title="title" />
<Dash :title="title">
    <div class="sticky top-0 toolbar w-full h-10 p-3 bg-sky-200 flex items-center justify-between print:hidden z-10">
            <h1><span class="hidden md:block">Menu Pengelolaan BOS</span></h1>
            <div class="toolbar-items flex items-center gap-2 justify-end">
                <button
                    @click="mode = 'Rencana Anggaran'" 
                    class="flex items-center gap-1 bg-white py-1 px-2 rounded hover:bg-gray-100 active:bg-gray-50">
                    RKAS
                    <Icon icon="mdi:list-box-outline" />
                </button>
                <button
                    @click="mode = 'transaksi'" 
                    class="flex items-center gap-1 bg-white py-1 px-2 rounded hover:bg-gray-100 active:bg-gray-50">
                    Transaksi
                    <Icon icon="mdi:credit-card-edit-outline" />
                </button>
                <button 
                    @click="mode = 'kuitansi'"
                    class="flex items-center gap-1 bg-white py-1 px-2 rounded hover:bg-gray-100 active:bg-gray-50">
                    Kuitansi
                    <Icon icon="mdi:checkbook" />
                </button>
                <button 
                    @click="cetak"
                    class="flex items-center gap-1 bg-white py-1 px-2 rounded hover:bg-gray-100 active:bg-gray-50">
                    Cetak
                    <Icon icon="mdi:printer" />
                </button>
            </div>
        </div>
    <div class="w-full container p-3">
        
        <div class="content relative">
            <Transition>
                <Transaksi v-if="mode == 'transaksi'" />
                <Kuitansi v-else-if="mode == 'kuitansi'" />
                <Rkas v-else-if="mode == 'Rencana Anggaran'" />
            </Transition>
        </div>
    </div>
</Dash>
</template>