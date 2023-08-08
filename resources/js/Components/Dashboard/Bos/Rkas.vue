<script setup>
import {usePage} from '@inertiajs/vue3';
import { computed, ref, defineAsyncComponent, onMounted }  from 'vue';
import { read, utils } from 'xlsx';
import { Icon } from '@iconify/vue';
import axios from 'axios';

const FormRkas = defineAsyncComponent(() => import('./FormRkas.vue'))
const ImporRkas = defineAsyncComponent(() => import('./ImporRkas.vue'))
const Loading = defineAsyncComponent(() => import('@/Components/General/Loading.vue'))

const page = usePage();
const loading = ref(false);

const fileRkas = ref(null);

const search = ref(null);
const rkas = ref([]);

const total = computed(() => {
    return rkas.value.reduce((t, item) => t+item.jumlah, 0)
})

const selesai = computed(() => {
    return rkas.value.filter(item => item.status == 'selesai').reduce((s, item) =>  s+item.jumlah,0)
})
const saldo = computed(() => {
    return parseInt(total.value) - parseInt(selesai.value)
})

const datas = computed(() => {
    // return search.value == null ? rkas.value : rkas.value.filter(data => data.uraian.toLowerCase().includes(search.value.toLowerCase()))
    let datas = bulan.value == 'all' ? rkas.value : rkas.value.filter(rka => rka.bulan == bulan.value)
    return search.value == null ? datas : datas.filter(data => data.uraian.toLowerCase().includes(search.value.toLowerCase()))
});
const bulan = ref('all')
// const filter = (e) => {
//     if(e.target.value == 'all') {
//         rkas.value = rkas.value
//     } else {
//         let filtered = rkas.value.filter(item => item.bulan == e.target.value)
//         rkas.value =
//     }
// }

const listRkas = async() => {
    loading.value = true
    axios.post(route('dashboard.bos.rkas.index'))
            .then(res => {
                rkas.value = res.data.rkas
                loading.value = false
            }).catch(err => {
                loading.value = false
                console.log(err)
            })
}

const formImpor = ref(false)
const imported = ref([])
const bulanImpor = ref(null)
const onFileRkasPicked =  async (ev) => {
    const file = ev.target.files[0]
    const reader = new FileReader()

    reader.onload = async(e) => {
        let wb = await read(e.target.result)
        let items = await utils.sheet_to_json(wb.Sheets[wb.SheetNames[0]])
        if (items.length > 1) {
            formImpor.value = true
            imported.value = items
            bulanImpor.value = wb.SheetNames[0]
        } else {
            alert('Gagal membaca data dari excel')
            return false
        }
    }
    reader.readAsArrayBuffer(file);
}
const closeFormImpor = () => {
    formImpor.value = false
    listRkas()
}

const formRkas = ref(false)
const showForm = () => {
    formRkas.value = true
}

const updateStatus = async(e, item) => {
    // console.log(e.target.value, item)
    loading.value = true
    await axios.post(route('dashbaord.bos.rkas.status.change', {id: item.id}), {
        status: e.target.value,
        _method: 'put'
    }).then( res => {
        listRkas()
        loading.value = false
    })
}

onMounted(() => {
    listRkas()
})
</script>

<template>
<div class="w-full p-3">
    <div class="toolbar w-full flex flex-wrap items-center justify-between sticky top-10 print:top-0 bg-white border-b py-1 px-2">
        <div>
            <p class="text-sm leading-4">Total 1 Tahun Anggaran: [Rp. {{ total.toLocaleString("id-ID") }}]</p>
            <p class="text-sm leading-4">Selesai: [Rp. {{ selesai.toLocaleString("id-ID") }}] | Sisa: [Rp. {{ saldo.toLocaleString("id-ID") }}]</p>
        </div>
        <div class="toolbar-items flex flex-wrap gap-1 items-center justify-between print:hidden">
            <input type="file" ref="fileRkas" @change="onFileRkasPicked" class="hidden" accept=".xls,.xlsx,.ods,.csv" multiple>
            <button class="flex items-center gap-1 group text-gray-600 hover:font-bold hover:text-gray-800" @click="fileRkas.click()">
                <Icon icon="mdi:table-arrow-up" class="text-xl" />
                <span class="hidden md:block text-sm">Impor RKAS</span>
            </button>
            <button class="flex items-center gap-1 justify-center group text-gray-600 hover:font-bold hover:text-gray-800" @click="showForm">
                <Icon icon="mdi:table-plus" class="text-xl" />
                <span class="hidden md:block text-sm">Tambah RKAS</span>
            </button>
            <label for="filter" class="text-sm">
                Pilih Bulan:
                <select
                    class="rounded py-1 border-none bg-slate-200"
                    v-model="bulan"

                    >
                    <option 
                        v-for="b in ['all', 1,2,3,4,5,6,7,8,9,10,11,12]" :key="b"
                        :value="b">{{ b }}</option>
                </select>
            </label>
            <span class="relative w-full">
                <input type="text" placeholder="Cari Anggaran" v-model="search" class="py-1 rounded w-full border-none bg-slate-200" />
                <Icon icon="mdi:magnify" class="text-2xl text-gray-400 absolute top-[50%] -translate-y-[50%] right-2" />
            </span>
            
        </div>

    </div>
    <div class="content w-full overflow-x-auto my-4">
        <div class="table w-full overflow-x-auto bg-white">
            <table class="w-full border border-slate-400 text-sm">
                <thead>
                    <tr class="bg-slate-200">
                        <th class="border py-1 border-slate-300 text-slate-800">Bulan</th>
                        <th class="border py-1 border-slate-300 text-slate-800 hidden md:table-cell">Kode Kegiatan</th>
                        <th class="border py-1 border-slate-300 text-slate-800 hidden md:table-cell">Kode Rekening</th>
                        <th class="border py-1 border-slate-300 text-slate-800">Uraian</th>
                        <th class="border py-1 border-slate-300 text-slate-800 hidden md:table-cell">Volume</th>
                        <th class="border py-1 border-slate-300 text-slate-800 hidden md:table-cell">Satuan</th>
                        <th class="border py-1 border-slate-300 text-slate-800 hidden md:table-cell">Tarif</th>
                        <th class="border py-1 border-slate-300 text-slate-800 hidden md:table-cell">Jumlah</th>
                        <th class="border py-1 border-slate-300 text-slate-800">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                        v-for="(rka, i) in datas"
                        :key="rka.id"
                        :class="rka.status == 'selesai' ? 'bg-sky-100': (rka.status == 'gagal' ? 'bg-red-100':'')"
                    >
                        <td class="border pl-1 text-center">{{ rka.bulan }}/{{ rka.tahun }}</td>
                        <td class="border pl-1 hidden md:table-cell">{{ rka.kode_kegiatan }}</td>
                        <td class="border pl-1 hidden md:table-cell">{{ rka.kode_rekening }}</td>
                        <td class="border pl-1">{{ rka.uraian }}</td>
                        <td class="border pl-1 text-center hidden md:table-cell">{{ rka.volume }}</td>
                        <td class="border pl-1 hidden md:table-cell text-center">{{ rka.satuan }}</td>
                        <td class="border pl-1 hidden md:table-cell text-right">{{ rka.tarif.toLocaleString("id-ID") }}</td>
                        <td class="border pl-1 text-right hidden md:table-cell">{{ rka.jumlah.toLocaleString("id-ID") }}</td>
                        <td class="border pl-1" >
                            <select 
                                class="py-0 border-none " v-model="rka.status"
                                @change="updateStatus($event,rka)"
                                
                            >
                                <option
                                    v-for="status in ['antri','progres','selesai', 'gagal']"
                                    :key="status"
                                    :value="status">{{ status }}</option>
                            </select>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <FormRkas v-if="formRkas" @close="formRkas = !formRkas" />
    <ImporRkas v-if="formImpor" :items="imported" :bulan="bulanImpor" @close="closeFormImpor" />
    <Loading v-if="loading" />
</div>
</template>