<template>
<div class="overlay fixed top-0 right-0 bottom-0 left-0 bg-slate-800 bg-opacity-60 backdrop-blur flex items-center justify-center z-40 ">
    <div class="content w-full sm:w-[80%] bg-white rounded overflow-hidden">
        <div class="toolbar w-full h-12 flex items-center justify-between bg-slate-200 p-2">
            <h1 class="text-slate-800 text-xl font-bold ">
                {{ anggaran.uraian }} |
                <small class="animate-pulse">
                    Pastikan Data Sudah Benar
                </small>
            </h1>
            <div class="toolbar-items flex items-center justify-end gap-2">
                <label class="flex items-center gap-1 transition-all">
                    <input type="checkbox" v-model="yakin">
                    Data Sudah Benar
                </label>
                <button class="bg-sky-400  rounded shadow text-white py-1 px-3 disabled:bg-slate-400" :disabled="!yakin" @click="impor">
                    Impor
                </button>
                <button @click="emit('close')" class="text-red-500 hover:text-white rounded-full p-2 hover:bg-red-500 transition-colors">
                    <Icon icon="mdi:close" class="text-xl" />
                </button>
            </div>
        </div>
        <div class="dialog h-[90vh] overflow-auto">
            <div class="content w-full overflow-x-auto my-4 px-4">
            <div class="table w-full overflow-x-auto">
                <table class="w-full border border-slate-400 text-sm">
                    <thead>
                        <tr class="bg-slate-200">
                            <th class="border py-1 border-slate-300 text-slate-800">No</th>
                            <th class="border py-1 border-slate-300 text-slate-800">Tanggal</th>
                            <th class="border py-1 border-slate-300 text-slate-800 hidden md:table-cell">Kode Kegiatan</th>
                            <th class="border py-1 border-slate-300 text-slate-800 hidden md:table-cell">Kode Rekening</th>
                            <th class="border py-1 border-slate-300 text-slate-800">No Bukti</th>
                            <th class="border py-1 border-slate-300 text-slate-800">Uraian</th>
                            <th class="border py-1 border-slate-300 text-slate-800 hidden md:table-cell">Tipe</th>
                            <th class="border py-1 border-slate-300 text-slate-800">Nilai</th>
                            <th class="border py-1 border-slate-300 text-slate-800">Toko | Lembaga</th>
                            <th class="border py-1 border-slate-300 text-slate-800">Bukti</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr 
                            v-for="(trans, tr) in items" :key="tr"
                            class="even:bg-slate-50">
                            <td class="border text-sm px-2 text-center">{{ tr+1 }}</td>
                            <td class="border text-sm px-2 text-center">{{ trans.tanggal }}</td>
                            <td class="border text-sm px-2 hidden md:table-cell text-center">{{ trans.kode_kegiatan }}</td>
                            <td class="border text-sm px-2 hidden md:table-cell text-center">{{ trans.kode_rekening }}</td>
                            <td class="border text-sm px-2 text-center">
                                <button class="p-2 bg-sky-400 rounded hover:bg-sky-600 text-white" @click="edit(trans)">
                                    {{ trans.no_bukti }}
                                </button>
                            </td>
                            <td class="border text-sm px-2 ">{{ trans.uraian }}</td>
                            <td class="border text-sm px-2 hidden md:table-cell text-center">{{ trans.tipe }}</td>
                            <td class="border text-sm px-2 text-right">
                                <span class="flex justify-between w-full">
                                    <span>Rp. </span>
                                    {{ trans.nilai.toLocaleString("id-ID") }}
                                </span>
                            </td>
                            <td class="border px-2 text-center">{{ trans.merchant }} </td>
                            <td class="border px-2 ">
                                <ul>
                                    <li v-for="(bukti, bkt) in trans.buktis" :key="bkt" class="py-1">
                                        <a  class="text-sm leading-3 text-teal-600 hover:underline" :href="bukti.url" target="_blank">
                                            {{bkt+1}}. {{ bukti.label }}
                                        </a>
                                    </li>
                                </ul>
                                
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        </div>
    </div>
    <Loading v-if="loading" />
</div>
</template>

<script setup>
import axios from 'axios';
import { Icon } from '@iconify/vue'
import { ref, computed, onBeforeMount, defineAsyncComponent } from 'vue';
const loading = ref(false)

const emit = defineEmits(['close'])

const props = defineProps({
    items: Array,
    anggaran: Object,
})

const Loading = defineAsyncComponent(() => import('@/Components/General/Loading.vue'))

const yakin = ref(false)

const impor = async() => {
     await axios.post(route('dashboard.bos.transaksi.import', {anggaran_id: anggaran.value.kode}
        ), {data: JSON.stringify(props.items)})
                    .then(res => {
                        loading.value = true
                        emit('close')
                    }).catch(err => {
                        loading.value = false
                        alert(err.response.data.msg)
                    })
}
</script>