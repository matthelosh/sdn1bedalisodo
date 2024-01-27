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
    // let html = `<!doctype html>
    //                 <html>
    //                     <head>
    //                         <title>Cetak</title>
    //                         <link rel="stylesheet" type="text/css" href="${host}:5173/resources/css/app.css" />
    //                         <style>
    //                             .p-4{padding: 1rem;}
    //                             .mx-auto{margin-left:auto;margin-right:auto;}
    //                             .mt-2 {margin-top:0.5rem;}
    //                             .mt-20 {
    //                                 margin-top: 5rem/* 80px */;
    //                             }
    //                             .my-2 {
    //                                 margin-top: 0.5rem/* 8px */;
    //                                 margin-bottom: 0.5rem/* 8px */;
    //                             }
    //                             .leading-4 {line-height: 1rem;}
    //                             .w-full { width: 100%; }
    //                             .flex{
    //                                 display: flex;
    //                             }
    //                             .items-center { align-items: center; }
    //                             .justify-center { justify-contents: center; }
    //                             img {
    //                                 height: 75px;
    //                             }

    //                             .uppercase { text-transform: uppercase;}
    //                             .font-bold { font-weight: 700;}
    //                             .font-black {
    //                                 font-weight: 900;
    //                             }
    //                             .tracking-widest {
    //                                 letter-spacing: 0.1em;
    //                             }
    //                             .leading-3 {
    //                                 line-height: .75rem/* 12px */;
    //                             }
    //                             .underline { text-decoration: underline; }
    //                             .italic { font-style: italic;}
    //                             .text-center { text-align: center;}
    //                             h2 { font-size: 1em; margin: 0 }
    //                             h3 { font-size: 1em; margin: 0; font-weight: 400; }
    //                             .font-serif {
    //                                 font-family: ui-serif, Georgia, Cambria, "Times New Roman", Times, serif;
    //                             }
    //                             .border-double { border-bottom: 4px double black; padding-bottom: 10px; }
    //                             .border-b-dashed { boder-bottom: 4px dashed gray; }
    //                             .border-dashed {
    //                                 border-style: dashed;
    //                             }
    //                             .border-b-gray-400 {
    //                                 --tw-border-opacity: 1;
    //                                 border-bottom-color: rgb(156 163 175 / var(--tw-border-opacity));
    //                             }
    //                             .border-b-2 {
    //                                 border-bottom-width: 2px;
    //                             }
    //                             .border-t-0 {
    //                                 border-top-width: 0px;
    //                             }
    //                             .border-r-0 {
    //                                 border-right-width: 0px;
    //                             }
    //                             .border-l-0 {
    //                                 border-left-width: 0px;
    //                             }
    //                             .border-2 {
    //                                 border-width: 2px;
    //                             }
    //                             .border-black {
    //                                 --tw-border-opacity: 1;
    //                                 border-color: rgb(0 0 0 / var(--tw-border-opacity));
    //                             }
    //                             .grid {display: grid;}
    //                             .grid-cols-5 {grid-template-columns: repeat(5, minmax(0, 1fr));}
    //                             .col-span-2 { grid-column: span 2 / span 2; }
    //                             .grid-cols-3 {
    //                                 grid-template-columns: repeat(3, minmax(0, 1fr));
    //                             }
    //                             .py-1 {
    //                                 padding-top: 0.25rem/* 4px */;
    //                                 padding-bottom: 0.25rem/* 4px */;
    //                             }
    //                             .px-3 {
    //                                 padding-left: 0.75rem/* 12px */;
    //                                 padding-right: 0.75rem/* 12px */;
    //                             }
    //                             .text-sm {
    //                                 font-size: 0.875rem/* 14px */;
    //                                 line-height: 1.25rem/* 20px */;
    //                             }
    //                             .text-lg {
    //                                 font-size: 1.125rem/* 18px */;
    //                                 line-height: 1.75rem/* 28px */;
    //                             }
    //                             .text-2xl {
    //                                 font-size: 1.5rem/* 24px */;
    //                                 line-height: 2rem/* 32px */;
    //                             }
    //                             .text-white {color:white;}
    //                             .border {border: 1px solid gray;}
    //                             table.table-detail {border-collapse:collapse;}
    //                             .cetak {
    //                                 background: #efefef;
    //                                 box-shadow: 0 3px 5px rgba(0,0,0,0.4);
    //                             }
    //                             .font-mono {
    //                                 font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
    //                             }
    //                             p {margin:0;padding:0;}
    //                             .terbilang {
    //                                 border: 2px solid black!important;
    //                             }
    //                             lembar {border: 3px dashed black; height: 33cm;}
    //                             cetak {height:33cm;}
    //                             @media print {
    //                                 body{
    //                                     background: url('${host}/img/bg-stripes.jpg');
    //                                     height: 33cm;
    //                                 }
    //                                 .cetak {
    //                                     page-break-after: always;
    //                                     page-break-before: always;
    //                                     background: white;
    //                                     box-shadow: none;
    //                                     margin: 10px;
                                        
    //                                 }
    //                             }
    //                         </style>
    //                     </head>
    //                     <body>
    //                         ${container}
    //                     </body>
    //                 </html>
    //             `
    let protocol = window.location.protocol;
    let cssUrl = page.props.app_env == 'local' ? 'http://sdn1bedalisodo.test:5173/resources/css/app.css' : `${protocol}://${host}/build/assets/app.css`
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
    }, 500);
    


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
