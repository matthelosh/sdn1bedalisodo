<script setup>
import { ref, onBeforeMount, computed } from 'vue';
import axios from 'axios';
import { Icon } from '@iconify/vue';
import {read, utils} from 'xlsx'

onBeforeMount(() => {
    list()
})

const loading = ref(false)
const list = async() => {
    loading.value = true
    await axios.post(route('dashboard.bos.transaksi.index'))
        .then(res => {
            transaksis.value = res.data.transaksis
            loading.value = false
        }).catch(err => console.log(err))
}

const transaksis = ref([])

const transaksi = ref({
    jenis: 'tunai'
})

const showForm = ref(false)

const simpan = async() => {
    loading.value = true
    await axios.post(route('dashboard.bos.transaksi.store'), {transaksi: JSON.stringify(transaksi.value)})
                .then(res => {
                    loading.value = false
                    list()
                })
}

const closeForm = () => {
    showForm.value = !showForm.value
}

const fileTransaksi = ref(null)
const onFileTransaksiPicked = async(ev) => {
    loading.value = true
    let file = ev.target.files[0]

    let reader = new FileReader();
    reader.onload = async(e) => {
        let wb = await read(e.target.result)
        let datas = await utils.sheet_to_json(wb.Sheets[wb.SheetNames[0]])
        await axios.post(route('dashboard.bos.transaksi.import'), {data: JSON.stringify(datas)})
                    .then(res => {
                        loading.value = true
                        list()
                    }).catch(err => {
                        loading.value = false
                        alert(err.response.data.msg)
                    })
    }
    reader.readAsArrayBuffer(file)
}
const search = ref('');
</script>

<template>
<div>
    <div class="bg-white p-3 w-full">
        <div class="toolbar w-full flex items-center justify-between sticky top-10 print:top-0 bg-white border-b py-1">
            <h1>Data Transaksi</h1>
            <div class="toolbar-items flex gap-2 items-center justify-center print:hidden">
                <input type="file" ref="fileTransaksi" @change="onFileTransaksiPicked" class="hidden" accept=".xls,.xlsx,.ods,.csv">
                <button class="flex items-center gap-1 group text-gray-600 hover:font-bold hover:text-gray-800" @click="fileTransaksi.click()">
                    <Icon icon="mdi:cart-arrow-up"  />
                    Impor Transaksi
                </button>
                <button class="flex items-center gap-1 group text-gray-600 hover:font-bold hover:text-gray-800" @click="showForm = true">
                    <Icon icon="mdi:cart-plus"  />
                    Tambah Transaksi
                </button>
                <span class="relative">
                    <input type="text" placeholder="Cari Transaksi" v-model="search" class="py-1 rounded" />
                    <Icon icon="mdi:magnify" class="text-2xl text-gray-400 absolute top-[50%] -translate-y-[50%] right-2" />
                </span>
                
            </div>

        </div>
        <div class="content w-full overflow-x-hidden my-4">
            <div class="table w-full">
                <table class="w-full border border-slate-400">
                    <thead>
                        <tr class="bg-slate-200">
                            <th class="border py-1 border-slate-400">No</th>
                            <th class="border py-1 border-slate-400">Tanggal</th>
                            <th class="border py-1 border-slate-400">Kode Kegiatan</th>
                            <th class="border py-1 border-slate-400">Kode Rekening</th>
                            <th class="border py-1 border-slate-400">No Bukti</th>
                            <th class="border py-1 border-slate-400">Uraian</th>
                            <th class="border py-1 border-slate-400">Tipe</th>
                            <th class="border py-1 border-slate-400">Nilai</th>
                            <th class="border py-1 border-slate-400">Toko | Lembaga</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr 
                            v-for="(trans, tr) in transaksis" :key="tr"
                            class="even:bg-slate-50">
                            <td class="border border-slate-400 text-sm px-2 text-center">{{ tr+1 }}</td>
                            <td class="border border-slate-400 text-sm px-2 text-center">{{ trans.tanggal }}</td>
                            <td class="border border-slate-400 text-sm px-2 text-center">{{ trans.kode_kegiatan }}</td>
                            <td class="border border-slate-400 text-sm px-2 text-center">{{ trans.kode_rekening }}</td>
                            <td class="border border-slate-400 text-sm px-2 text-center">{{ trans.no_bukti }}</td>
                            <td class="border border-slate-400 text-sm px-2 ">{{ trans.uraian }}</td>
                            <td class="border border-slate-400 text-sm px-2 text-center">{{ trans.tipe }}</td>
                            <td class="border border-slate-400 text-sm px-2 text-right">
                                <span class="flex justify-between w-full">
                                    <span>Rp. </span>
                                    {{ trans.nilai.toLocaleString("id-ID") }}
                                </span>
                            </td>
                            <td class="border border-slate-400 px-2 text-center">{{ trans.merchant }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="fixed top-0 right-0 bottom-0 left-0 bg-slate-800 bg-opacity-60 flex items-center justify-center" v-if="showForm">
        <div class="dialog bg-white max-w-[600px] min-w-[400px]">
            <div class="dialog-title p-3 bg-slate-100 flex items-center justify-between">
                <h1>Tambah Transaksi</h1>
                <button @click="closeForm">
                    <Icon icon="mdi:close-box" class="text-red-400 hover:text-red-600 text-2xl" />
                </button>
            </div>
            <div class="dialog-content">
                <form ref="form-transaksi" class="form p-3" @submit.prevent="simpan">
                    <label for="kode_kegiatan" class="flex items-center justify-center gap-2 my-3">
                        Kode Kegiatan
                        <input type="text" class="py-1 flex-grow" v-model="transaksi.kode_kegiatan" required />
                    </label>
                    <label for="kode_rekening" class="flex items-center justify-center gap-2 my-3">
                        Kode Rekening
                        <input type="text" class="py-1 flex-grow" v-model="transaksi.kode_rekening" required />
                    </label>
                    <label for="no_bukti" class="flex items-center justify-center gap-2 my-3">
                        No Bukti
                        <input type="text" class="py-1 flex-grow" v-model="transaksi.no_bukti" required />
                    </label>
                    <label for="merchant" class="flex items-center justify-center gap-2 my-3">
                        Sumber | Penerima Dana
                        <input type="text" class="py-1 flex-grow" v-model="transaksi.merchant" required />
                    </label>
                    <label for="merchant" class="flex items-center justify-center gap-2 my-3">
                        Nilai Transaksi
                        <span class="py-1 flex-grow relative">
                        <input type="number" class="py-1 pl-8 w-full" v-model="transaksi.nilai" required />
                        <span class="absolute left-1 text-gray-800 font-bold top-[50%] -translate-y-[50%]">Rp.</span>
                        </span>
                    </label>
                    <label for="jenis" class="flex items-center justify-between gap-2 y-3">
                        Jenis Transaksi
                        <select name="jenis" id="jenis" v-model="transaksi.jenis" class="flex-grow py-1">
                            <option value="tunai" selected>Tunai</option>
                            <option value="nontunai">Non Tunai</option>
                        </select>
                    </label>
                    <label for="tanggal" class="flex items-center justify-between gap-2 my-3">
                        Tanggal
                        <input type="date" v-model="transaksi.tanggal" class="py-1" required />
                    </label>
                    <label for="uraian" class="my-3">
                        Uraian:
                        <textarea class="w-full" v-model="transaksi.uraian"></textarea>
                    </label>
                    <div class="w-full relative flex items-center justify-center py-3">
                        <button class="mx-auto bg-sky-600 py-1 px-3 text-white rounded hover:bg-sky-400">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="fixed top-0 right-0 bottom-0 left-0 bg-slate-800 bg-opacity-60 flex items-center justify-center" v-if="loading">
        <Icon icon="mdi:loading" class="animate-spin text-8xl text-white" />
    </div>
</div>
</template>