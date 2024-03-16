<script setup>
import axios from 'axios';
import { ref, computed, onBeforeMount } from 'vue';
const loading = ref(false)

const emit = defineEmits(['close'])

const props = defineProps({
    items: Array,
    bulan: String,
    anggaran: String,
})

const bulans = [
    { value: "0", text: "Pilih"},
    { value: "1", text: "Januari"},
    { value: "2", text: "Februari"},
    { value: "3", text: "Maret"},
    { value: "4", text: "April"},
    { value: "5", text: "Mei"},
    { value: "6", text: "Juni"},
    { value: "7", text: "Juli"},
    { value: "8", text: "Agustus"},
    { value: "9", text: "September"},
    { value: "10", text: "Oktober"},
    { value: "11", text: "November"},
    { value: "12", text: "Desember"},
];

const tahuns = computed(() => {
    let tahuns = [];
    let year = new Date().getFullYear();
    for (let i = (year - 1); i < (year+3); i++) {
        tahuns.push(i)
    }

    return tahuns;
});

const bulan = ref('0')
const tahun = ref('0')

const simpan = async() => {
    if(bulan.value == '0' || tahun.value == '0') {
        alert('Pilih Bulan dan Tahun RKAS')
        return false
    }
    loading.value = true
    await axios.post(route('dashboard.bos.rkas.impor', {_query: {anggaran_id: props.anggaran}}), {
        bulan: bulan.value,
        tahun: tahun.value,
        datas: JSON.stringify(props.items)
    }).then(res => {
        loading.value = false
        emit('close')
    }).catch(err => {
        alert('Impor Gagal')
    })
}

onBeforeMount(() => {
    // let cek = bulans.filter(bulan => bulan.value.includes(props.bulan))
    // if(cek.length > 0 ) {
    //     bulan.value == props.bulan
    // } else {
        alert('Jangn lupa pilih Bulan dan Tahun ya..')
    // }
    // console.log(cek)
    bulan.value = props.bulan
})

</script>

<template>
    <div class="fixed top-0 right-0 bottom-0 left-0 bg-white backdrop-blur-sm bg-opacity-60 flex items-center justify-center overflow-y-auto z-50" @click.self="emit('close')">
        <div class="dialog bg-white md:w-8/12 md:max-w-full mx-auto md:min-w-[400px] overflow-y-auto shadow-lg border border-gray-200">
            <div class="toolbar w-full h-10 bg-slate-200 flex items-center justify-between p-3">
                <h1>Impor RKAS</h1>
                <div class="toolbar-items flex items-center gap-4">
                    <label for="bulan">
                        Bulan
                        <select name="bulan" v-model="bulan" class="bg-slate-300 py-1 border-none ">
                            <option 
                                v-for="bulan in bulans"
                                :key="bulan"
                                :value="bulan.value">
                                {{ bulan.text }}
                            </option>
                        </select>
                    </label>
                    <label for="tahun">
                        Tahun
                        <select name="tahun" v-model="tahun" class="bg-slate-300 py-1 border-none ">
                            <option 
                                v-for="tahun in tahuns"
                                :key="tahun"
                                :value="tahun">
                                {{ tahun }}
                            </option>
                        </select>
                    </label>
                    <button
                        class="bg-sky-400 tetx-white py-1 px-2 rounded text-white text-sm"
                        @click="simpan"
                        :disabled="loading"
                    >
                        Simpan
                    </button>
                </div>
            </div>
            <div class="content w-full bg-slate-50 p-4 max-h-[500px] overflow-y-auto">
                <div class="table w-full overflow-x-auto bg-white">
                    <table class="w-full border border-slate-400">
                        <thead>
                            <tr class="bg-slate-200">
                                <th class="border py-1 border-slate-300 text-slate-800">No</th>
                                <th class="border py-1 border-slate-300 text-slate-800 hidden md:table-cell">Kode Kegiatan</th>
                                <th class="border py-1 border-slate-300 text-slate-800 hidden md:table-cell">Kode Rekening</th>
                                <th class="border py-1 border-slate-300 text-slate-800">Uraian</th>
                                <th class="border py-1 border-slate-300 text-slate-800">Volume</th>
                                <th class="border py-1 border-slate-300 text-slate-800">Satuan</th>
                                <th class="border py-1 border-slate-300 text-slate-800">Tarif</th>
                                <th class="border py-1 border-slate-300 text-slate-800">Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                v-for="(rka) in props.items"
                                :key="rka.id"
                            >
                                <td class="border pl-1 text-center">{{ rka.no }}</td>
                                <td class="border pl-1">{{ rka.kode_kegiatan }}</td>
                                <td class="border pl-1">{{ rka.kode_rekening }}</td>
                                <td class="border pl-1">{{ rka.uraian }}</td>
                                <td class="border pl-1 text-center">{{ rka.volume }}</td>
                                <td class="border pl-1 text-center">{{ rka.satuan }}</td>
                                <td class="border pl-1 text-right">{{ rka.tarif.toLocaleString("id-ID") }}</td>
                                <td class="border pl-1 text-right">{{ rka.jumlah.toLocaleString("id-ID") }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    </template>