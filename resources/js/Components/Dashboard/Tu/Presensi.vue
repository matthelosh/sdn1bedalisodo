<script setup>
import { ref, computed, onMounted, defineAsyncComponent } from 'vue';
import axios from 'axios'
import { Icon } from '@iconify/vue';
import dayjs from 'dayjs';
import localeData from 'dayjs/plugin/localeData'
import 'dayjs/locale/id'
dayjs.extend(localeData)
dayjs().localeData()
dayjs.locale("id");

const Kop = defineAsyncComponent(() => import('@/Components/General/Kop.vue'))

const ptks = ref([])
const roles = ref({
    ks: 'Kepala Sekolah',
    gkel: 'Guru Kelas',
    gpai: 'Guru PAI',
    gor: 'Guru PJOK',
    gen: 'Guru B. Inggris',
    pjg: 'Penjaga Sekolah'
})
const bulan = ref('0')
const tahun = ref('2024')
const pekan = ref('1')
const startDay = computed(() => {
    return dayjs(tahun.value+'-'+(bulan.value+1)+'-01').startOf("month").day()
})
const days = computed(() => {
    return dayjs(tahun.value+'-'+(bulan.value+1)+'-01').daysInMonth()
})

const bulans = computed(() => {
    return dayjs.months()
})
const tahuns = computed(() => {
    const d = new Date()
    let y = d.getFullYear()
    let thns = []
    for (let t = y-3; t < y+4; t++) {
        thns.push(t)
    }

    return thns
})

const pekans = ref([1,2,3,4,5])

const haris  =computed(() => dayjs.weekdays())

const init = async() => {
    await axios.post(route('dashboard.guru.index'))
                .then(res => ptks.value = res.data.gurus)
}

/***
 * cals = [
 *  1: [null,1,2,3,4,5,6],
 *  2: [7,8,...31]
 * ]
 */

const cals = computed(() => {
    let result = {1:[],2:[],3:[],4:[],5:[]}
    for(let n=0;n < startDay.value;n++){
        result[1].push(null)
    }
    for (let d=1;d<=(7-startDay.value);d++) {
        result[1].push(d)
    }

    return result
})

onMounted(() => {
    init()
})
</script>

<template>
<div class="wrapper  bg-slate-400">
    <div class="toolbar print:hidden flex items-center justify-between h-12 bg-slate-200 p-3">
        <h3 class="font-bold text-slate-700">Blanko Daftar Hadir Pegawai</h3>
        <div class="toolbar-items flex gap-2">
            <select v-model="pekan" placeholder="Pilih Bulan" class="w-[200px]">
                <option v-for="p of pekans" :key="p" :value="p">{{ p }}</option>
            </select>
            <select v-model="bulan" placeholder="Pilih Bulan" class="w-[200px]">
                <option v-for="(bln,b) in bulans" :key="b" :value="b">{{ bln }}</option>
            </select>
            <select v-model="tahun" placeholder="Pilih Tahun" class="w-[200px]">
                <option v-for="(thn,t) in tahuns" :key="t" :value="thn">{{ thn }}</option>
            </select>
            <el-button>
                <Icon icon="mdi:printer" />
            </el-button>
            <el-button class="ml-4" circle type="danger">
                <Icon icon="mdi:close" />
            </el-button>
        </div>
    </div>
    <div class="content p-4 bg-slate-50">
        <Kop />

        {{ bulan }} - {{ startDay }} - {{ days }} -{{ pekans }} - {{ cals }}
        <div class="meta">
            <h3 class="text-center">DAFTAR HADIR PEGAWAI</h3>
            <h3 class="text-center">BULAN {{ bulans[bulan]?.toUpperCase() }} TAHUN {{ tahun }}</h3>
            <h3 class="font-bold">Pekan Ke: {{ pekan }}</h3>
        </div>
        <table class="w-full">
            <thead>
                <tr>
                    <th rowspan="2" class="border border-slate-500 py-2">No</th>
                    <th rowspan="2" class="border border-slate-500 py-2">NAMA / NIP</th>
                    <th rowspan="2" class="border border-slate-500 py-2">JABATAN</th>
                    <th class="border border-slate-500 py-2" :class="cals[pekan][h]?'bg-white':'bg-slate-200'" v-for="h in 6" :key="h" colspan="4">
                        {{ haris[h] }}, {{ cals[pekan][h] }}  <span v-if="cals[pekan][h] !== null">{{ bulans[bulan] }} {{ tahun }}</span>
                    </th>

                </tr>
                <tr>
                    <template v-for="d in 6" :key="d">
                        <th :class="cals[pekan][d]?'bg-white':'bg-slate-200'" class="border border-slate-500 py-2 w-[70px]" v-for="(l,i) in ['In', 'TTD','Out','TTD']" :key="i">{{ l }}</th>
                    </template>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(ptk,i) in ptks" :key="ptk.id">
                    <td class="border border-slate-500 p-2 text-center">{{ i+1 }}</td>
                    <td class="border border-slate-500 p-2">
                        <p>{{ ptk.nama + (ptk.gelar_belakang ? ", "+ptk.gelar_belakang : '') }}</p>
                        <p>NIP. {{ ptk.nip }}</p>
                    </td>
                    <td class="border border-slate-500 p-2 text-center">
                        {{ roles[ptk.role] }}
                    </td>
                    <template v-for="d in 6" :key="d">
                        <td :class="cals[pekan][d]?'bg-white':'bg-slate-200'"  class="border border-slate-500 py-2" v-for="(l,i) in ['In', 'TTD','Out','TTD']" :key="i"></td>
                    </template>
                </tr>
            </tbody>
        </table>
    </div>
</div>

</template>