<script setup>
import { Head } from '@inertiajs/vue3';
import { ref, defineAsyncComponent } from 'vue';
import { Icon } from '@iconify/vue';
import Dash from '@/Layouts/AdminLayout.vue'

const Kuitansi = defineAsyncComponent(() => import('@/Components/Dashboard/Bos/Kuitansi.vue'));
const Transaksi = defineAsyncComponent(() => import('@/Components/Dashboard/Bos/Transaksi.vue'));
const Bku = defineAsyncComponent(() => import('@/Components/Dashboard/Bos/Bku.vue'));

const mode = ref('transaksi')

const cetak = async() => {
    await window.print()
}
</script>

<template>
<Head title="BOS" />
<Dash title="BOS">
    <div class="w-full container p-3">
        <div class="toolbar w-full h-10 p-3 bg-sky-200 flex items-center justify-between print:hidden">
            <h1>Menu Pengelolaan BOS</h1>
            <div class="toolbar-items flex items-center gap-2 justify-end">
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
        <div class="content relative">
            <Transition>
                <Transaksi v-if="mode == 'transaksi'" />
                <Kuitansi v-else-if="mode == 'kuitansi'" />
            </Transition>
        </div>
    </div>
</Dash>
</template>