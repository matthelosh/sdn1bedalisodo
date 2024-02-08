<script setup>
import { Head, usePage } from '@inertiajs/vue3';
import { ref, defineAsyncComponent, computed } from 'vue';
import { Icon } from '@iconify/vue';
import Dash from '@/Layouts/AdminLayout.vue'

const Kuitansi = defineAsyncComponent(() => import('@/Components/Dashboard/Bos/Kuitansi.vue'));
const Transaksi = defineAsyncComponent(() => import('@/Components/Dashboard/Bos/Transaksi.vue'));
const Rkas = defineAsyncComponent(() => import('@/Components/Dashboard/Bos/Rkas.vue'));
const Anggaran = defineAsyncComponent(() => import('@/Components/Dashboard/Bos/Anggaran.vue'));
const Cover = defineAsyncComponent(() => import('@/Components/Dashboard/Bos/Cover.vue'));

const page = usePage();
const mode = ref('Mata Anggaran')
const title = computed(() => {
    return mode.value[0].toUpperCase() + mode.value.slice(1)
})
const cetak = async() => {
    // await window.print()
    let host = window.location.host
    let cetaks = document.querySelectorAll(".cetak");
    let container = "";
    cetaks.forEach(sheet => {
        container += sheet.outerHTML
    })
    let win = window.open(host+'/print',"_blank","height=600,width=1024")
    let cssUrl = page.props.app_env == 'local' ? 'http://localhost:5173/resources/css/app.css' : `/build/assets/app.css`
    let html = `
        <!doctype html>
        <html>
            <head>
                <title>Cetak</title>
                <link rel="stylesheet" href="${cssUrl}" />
                
            </head>
            <body>
                ${container}
            </body>
        </html>
    `
    await win.document.write(html)
    setTimeout(() => {
        win.print();
        // win.close();
    }, 1500);
    


}
</script>

<template>
<div>
    <Head :title="title" />
    <Dash :title="title">
        <div class="sticky top-0 toolbar w-full h-10 p-3 bg-sky-200 flex items-center justify-between print:hidden z-10">
            <h1><span class="hidden md:block">Menu Pengelolaan BOS</span></h1>
            <div class="toolbar-items flex items-center gap-2 justify-end">
                <button
                    @click="mode = 'Mata Anggaran'" 
                    class="flex items-center gap-1 py-1 px-2 rounded hover:bg-gray-100 hover:text-gray-600 active:bg-gray-50" :class="mode == 'Mata Anggaran' ? 'bg-sky-400 text-white': 'bg-white'">
                    Anggaran
                    <Icon icon="mdi:wallet" />
                </button>
                <button
                    @click="mode = 'Rencana Anggaran'" 
                    class="flex items-center gap-1  py-1 px-2 rounded hover:bg-gray-100 active:bg-gray-50 hover:text-gray-600" :class="mode == 'Rencana Anggaran' ? 'bg-sky-400 text-white': 'bg-white'">
                    RKAS
                    <Icon icon="mdi:list-box-outline" />
                </button>
                <button
                    @click="mode = 'transaksi'" 
                    class="flex items-center gap-1  py-1 px-2 rounded hover:bg-gray-100 active:bg-gray-50 hover:text-gray-600" :class="mode == 'transaksi' ? 'bg-sky-400 text-white': 'bg-white'">
                    Transaksi
                    <Icon icon="mdi:credit-card-edit-outline" />
                </button>
                <button 
                    @click="mode = 'kuitansi'"
                    class="hidden md:flex items-center gap-1  py-1 px-2 rounded hover:bg-gray-100 active:bg-gray-50 hover:text-gray-600" :class="mode == 'kuitansi' ? 'bg-sky-400 text-white': 'bg-white'">
                    Kuitansi
                    <Icon icon="mdi:checkbook" />
                </button>
                <button 
                    @click="mode = 'cover'"
                    class="hidden md:flex items-center gap-1  py-1 px-2 rounded hover:bg-gray-100 active:bg-gray-50 hover:text-gray-600" :class="mode == 'cover' ? 'bg-sky-400 text-white': 'bg-white'">
                    Cover
                    <Icon icon="mdi:document" />
                </button>
                <button 
                    @click="cetak"
                    class="items-center gap-1 bg-white py-1 px-2 rounded hover:bg-gray-100 active:bg-gray-50 hidden md:flex">
                    Cetak
                    <Icon icon="mdi:printer" />
                </button>
            </div>
        </div>
        <div class="w-full p-3">
            <div class="content relative w-full">
                <Transition>
                    <Transaksi v-if="mode == 'transaksi'" />
                    <Kuitansi v-else-if="mode == 'kuitansi'" />
                    <Rkas v-else-if="mode == 'Rencana Anggaran'" />
                    <Anggaran v-else-if="mode == 'Mata Anggaran'" />
                    <Cover v-else-if="mode == 'cover'" />
                </Transition>
            </div>
        </div>
    </Dash>
</div>
</template>
