<script setup>
import { ref, defineAsyncComponent } from 'vue';
import { Head } from '@inertiajs/vue3';
import { Icon } from '@iconify/vue';

const emit = defineEmits(['close'])
const mode = ref('list')

const FormKlasifikasi = defineAsyncComponent(() => import ('./Forms/FormKlasifikasi.vue'))
const TulisSurat = defineAsyncComponent(() => import('./Forms/WriteSurat.vue'))

const search = ref('');
const formKlasifikasi = ref(false)

const showFormKlasifikasi = () => {
    formKlasifikasi.value = true
}
const closeMe = () => {
    emit('close')
}

const compose = async() => {
    mode.value = 'tulis-surat'
}
</script>

<template>
<Head title="Surat Keluar"  />
<FormKlasifikasi :show="formKlasifikasi" @close="formKlasifikasi = false" />
<div v-if="mode=='list'">
    <div class="toolbar h-10 bg-gray-100 flex items-center justify-between sticky top-0 z-20 p-3">
        <h1 class="text-gray-800 flex gap-1 items-center">
            <Icon icon="mdi:mail" class="text-xl" />
            Surat Keluar
        </h1>
        <div class="toolbar-items flex items center justify-end gap-2">
            <button @click="showFormKlasifikasi" class="flex items-center hover:text-gray-600">
                <Icon icon="mdi:list-box-outline" class="text-xl" />
                <span>Klasifikasi Surat</span>
            </button>
            <button @click="compose" class="flex items-center hover:text-gray-600">
                <Icon icon="mdi:typewriter" class="text-xl" />
                <span>Tulis Surat</span>
            </button>
            <div class="search-box relative">
                <input type="text" placeholder="Cari Surat" class="rounded py-1" v-model="search">
                <Icon icon="mdi:magnify" class="text-xl absolute top-[50%] -translate-y-[50%] right-2 text-gray-400" />
            </div>
            <button @click="closeMe" class=" text-red-600 hover:text-red-400">
                <Icon icon="mdi:close-box" class="text-2xl" />
            </button>
        </div>
    </div>
    <div class="content w-full p-3 bg-white">
        <div class="table overflow-x-auto w-full" >
            <table class="border w-full">
                <thead>
                    <tr>
                        <th class="border">No</th>
                        <th class="border">No. Surat</th>
                        <th class="border">Penerima</th>
                        <th class="border">Perihal</th>
                        <th class="border">Tanggal</th>
                        <th class="border">Tembusan</th>
                        <th class="border">Status</th>
                        <th class="border">Arsip</th>
                        <th class="border">Opsi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>

                    </tr>
                </tbody>
            </table>
        </div>
        
    </div>
    
</div>
<TulisSurat class="w-full" v-if="mode == 'tulis-surat'" @close="mode = 'list'" />
</template>