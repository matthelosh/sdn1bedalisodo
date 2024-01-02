<script setup>
import { ref, onBeforeMount, computed, defineAsyncComponent } from 'vue';
import { usePage, Head } from '@inertiajs/vue3';
import axios from 'axios';
import { Icon } from '@iconify/vue';
import {read, utils} from 'xlsx'

const page = usePage();
const anggarans = page.props.anggarans;

const anggaran = ref({})
const changeAnggaran = (e) => {
    anggaran.value = anggarans[e.target.value]
    list()
}

const Loading = defineAsyncComponent(() => import('@/Components/General/Loading.vue'))

onBeforeMount(() => {
    anggaran.value = anggarans[0]
    list()
    
})

const FormTransaksi = defineAsyncComponent(() => import('./FormTransaksi.vue'))

const loading = ref(false)


const list = async() => {
    loading.value = true
    // alert(anggaran.value.kode)
    await axios.post(route('dashboard.bos.transaksi.index', {
        anggaran_id: anggaran.value.kode
    }))
        .then(res => {
            rawItems.value = res.data.transaksis;
            silpa.value = res.data.silpa;
            antris.value = res.data.antris;
            loading.value = false;
        }).catch(err => console.log(err))
}

const antris = ref([])
const silpa = ref(0)
const terpakai = computed(() => {
    return rawItems.value.reduce((t,item) => t+item.nilai, 0)
})
const rawItems = ref([])
const transaksis = computed(() => {
    let word = search.value.replace(" ", "|")
    let query = new RegExp(word, "gi")
    return search.value !== '' 
            ? rawItems.value.filter(item => item.uraian.toLowerCase().match(query)) 
            : rawItems.value
})

const transaksi = ref({
    jenis: 'tunai'
})

const showForm = ref(false)

const closeForm = () => {
    showForm.value = !showForm.value
    list()
}

const edit = async (trans) => {
    formTitle.value = `Edit Transaksi ${trans.no_bukti}`
    transaksi.value = trans
    // console.log(transaksi.value)
    // imageHolder.value = true
    showForm.value = true
}

const formTitle = ref('Tambah Transaksi')
const fileTransaksi = ref(null)
const formImpor =ref(false)
const imported = ref([])
const closeImpor = () => {
    formImpor.value = false
    imported.value = []
}
const onFileTransaksiPicked = async(ev) => {
    // loading.value = true
    let file = ev.target.files[0]

    let reader = new FileReader();
    
    reader.onload = async(e) => {
        let wb = await read(e.target.result)
        let datas = await utils.sheet_to_json(wb.Sheets[wb.SheetNames[0]])
       
        imported.value = datas
        formImpor.value = true
    }
    reader.readAsArrayBuffer(file)
}
const search = ref('');

const newTransaksi = () => {
    showForm.value = true
}

const ImporBku = defineAsyncComponent(() => import('./ImporBku.vue'))
const CopyBku = defineAsyncComponent(() => import('./CopyBku.vue'))
const showCopy = ref(false)
const closeCopy = () => {
    showCopy.value = !showCopy.value
}

</script>

<template>

<div>
    <Head>
        <title>Data Transaksi {{ anggaran.uraian }}</title>
    </Head>
    <Loading v-if="loading" />
    <ImporBku v-if="formImpor" :items="imported" @close="closeImpor" :anggaran="anggaran" />
    <CopyBku v-if="showCopy" :items="imported" @close="closeCopy" :anggaran="anggaran" />
    <div class="bg-white p-3 w-full">
        <div class="toolbar w-full flex flex-wrap items-center justify-between sticky top-10 print:top-0 bg-white border-b py-1">
            <div class="text-sm flex items-center gap-2 ">
                <select @change="changeAnggaran" >
                    <option v-for="(anggaran, index) in anggarans" :key="anggaran.kode" :value="index">
                        {{ anggaran.uraian }}
                    </option>
                </select>
                <div class="border-s-4 h-full border-slate-800 pl-1 font-bold text-slate-700">
                    <p class="leading-4">Dana Tahap {{ anggaran.tahap }}: Rp. {{anggaran.nilai.toLocaleString("id-ID")}} Silpa: Rp. {{ silpa !== null ? silpa.silpa?.toLocaleString("id-ID") : 0 }} Total: Rp. {{ (parseInt(anggaran.nilai)+parseInt(silpa !== null ? silpa.silpa : 0)).toLocaleString("id-ID") }}</p>
                    <p class="leading-4">Terpakai: Rp. {{ terpakai.toLocaleString("id-ID") }} Saldo: Rp. {{ (parseInt(anggaran.nilai)+parseInt(silpa !==null ? silpa.silpa : 0 ) - terpakai).toLocaleString("id-ID") }}</p>
                </div>
            </div>
            <div class="toolbar-items flex gap-4 items-center justify-between print:hidden">
                <input type="file" ref="fileTransaksi" @change="onFileTransaksiPicked" class="hidden" accept=".xls,.xlsx,.ods,.csv" multiple>
                <button class="flex items-center gap-1 group text-gray-600 hover:font-bold hover:text-gray-800" @click="showCopy = !showCopy">
                    <Icon icon="mdi:clipboard" class="text" />
                    <span class="hidden md:block text-sm">Copy Transaksi</span>
                </button>
                <button class="flex items-center gap-1 group text-gray-600 hover:font-bold hover:text-gray-800" @click="fileTransaksi.click()">
                    <Icon icon="mdi:cart-arrow-up" class="text" />
                    <span class="hidden md:block text-sm">Impor Transaksi</span>
                </button>
                <button class="flex items-center gap-1 justify-center group text-gray-600 hover:font-bold hover:text-gray-800" @click="newTransaksi">
                    <Icon icon="mdi:cart-plus" class="text" />
                    <span class="hidden md:block text-sm">Tambah Transaksi</span>
                </button>
                <span class="relative">
                    <input type="text" placeholder="Cari Transaksi" v-model="search" class="py-1 rounded border-none bg-slate-200 focus:bg-slate-100 focus:shadow" />
                    <Icon icon="mdi:magnify" class="text-2xl text-gray-400 absolute top-[50%] -translate-y-[50%] right-2" />
                </span>
                
            </div>

        </div>
        <div class="content w-full overflow-x-auto my-4">
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
                            v-for="(trans, tr) in transaksis" :key="tr"
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
    <FormTransaksi :transaksi="transaksi" :antris="antris" @close="closeForm" :show="showForm" :title="formTitle" v-if="showForm" />
</div>
</template>
