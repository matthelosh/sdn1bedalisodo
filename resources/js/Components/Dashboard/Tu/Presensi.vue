<script setup>
import { ref, computed, onMounted, defineAsyncComponent } from 'vue';
import { usePage } from '@inertiajs/vue3'
import axios from 'axios'
import { Icon } from '@iconify/vue';
import dayjs from 'dayjs';
import localeData from 'dayjs/plugin/localeData'
import 'dayjs/locale/id'
dayjs.extend(localeData)
dayjs().localeData()
dayjs.locale("id");
import * as _ from 'lodash'

const page = usePage()
const emit = defineEmits(['close'])

// const Kop = defineAsyncComponent(() => import('@/Components/General/Kop.vue'))
const events = ref([])
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
// const pekan = ref(1)
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

// const pekans = ref([1,2,3,4,5])

const haris  =computed(() => dayjs.weekdays())

const getAgendas = async() => {
    await axios.post(route('dashboard.agenda.index', {_query: {bulan: (parseInt(bulan.value)+1), tahun: tahun.value}}))
                .then(res=> {
                    events.value = res.data.agendas
                    // console.log(events.value.map(e=>parseInt(e.start.substr(-2))))
                })
}

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
    let result = []
    let nulls = []
    let alldays = Array.from({length: days.value}, (value,index) => 1+index)
    for (let n=0;n<startDay.value;n++) {
        nulls.push(null)
    }

    nulls = [...nulls, ...alldays]

    for(let w=0;w<5;w++){
        result[w] = nulls.slice((w*7),((w*7)+7))
        
    }
    return result
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
                <title>Daftar Hadir Pegawai</title>
                <link rel="stylesheet" href="${cssUrl}" />
                <style>
                    table {
                        font-size: 0.7em!important;
                    }
                </style>
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

const isLibur = (tanggal) => {
    let tanggals = events.value.map(e => parseInt(e.start.substr(-2)))
    if (tanggals.includes(tanggal)) {
        return true
    } else {
        return false
    }
}

onMounted(() => {
    init()
    getAgendas()
})
</script>

<template>
<div class="wrapper  bg-slate-400 ">
    <div class="toolbar print:hidden flex items-center justify-between h-12 bg-slate-200 p-3 sticky top-0">
        <h3 class="font-bold text-slate-700">Blanko Daftar Hadir Pegawai</h3>
        <div class="toolbar-items flex gap-2">
            <select v-model="bulan" placeholder="Pilih Bulan" class="w-[200px]" @change="getAgendas()">
                <option v-for="(bln,b) in bulans" :key="b" :value="b">{{ bln }}</option>
            </select>
            <select v-model="tahun" placeholder="Pilih Tahun" class="w-[200px]">
                <option v-for="(thn,t) in tahuns" :key="t" :value="thn">{{ thn }}</option>
            </select>
            <el-button @click="cetak">
                <Icon icon="mdi:printer" />
            </el-button>
            <el-button class="ml-4" circle type="danger" @click="emit('close')">
                <Icon icon="mdi:close" />
            </el-button>
        </div>
    </div>
    <div class="cetak px-2" v-for="p of 5" :key="p">
        <div class="content p-4 bg-slate-50 sheet my-2 break-before-all break-inside-avoid-page">
            <!-- <Kop /> -->
            <div class="kop grid grid-cols-6 border-b border-b-double border-b-black border-b-6 pb-2">
                <div class="logo flex items-center justify-center">
                    <img src="/img/malangkab.png" alt="Logo Kab Malang" class="h-[80px]">
                </div>
                <div class="col-span-4">
                    <h3 class="text-center">PEMERINTAH KABUPATEN MALANG</h3>
                    <h3 class="text-center">KOORDINATOR WILAYAH DINAS PENDIDIKAN KECAMATAN WAGIR</h3>
                    <h3 class="text-center uppercase font-bold">{{ page.props.sekolah.nama }}</h3>
                    <p class="text-center">{{ page.props.sekolah.alamat }}, {{ page.props.sekolah.desa }}, Kec. {{ page.props.sekolah.kecamatan }}, Kab. {{ page.props.sekolah.kabupaten }}</p>
                </div>
                <div></div>
            </div>

            <!-- {{ bulan }} - {{ startDay }} - {{ days }} -{{ pekans }} - {{ cals }} -->
            <div class="meta mt-4">
                <h3 class="text-center">DAFTAR HADIR PEGAWAI</h3>
                <h3 class="text-center">BULAN {{ bulans[bulan]?.toUpperCase() }} TAHUN {{ tahun }}</h3>
                <h3 class="font-bold">Pekan Ke: {{ p }}</h3>
            </div>
            <table class="w-full text-[.6rem]">
                <thead>
                    <tr>
                        <th rowspan="2" class="border border-slate-500 ">No</th>
                        <th rowspan="2" class="border border-slate-500  w-[350px]">NAMA / NIP</th>
                        <th rowspan="2" class="border border-slate-500  w-[150px]">JABATAN</th>
                        <th class="border border-slate-500 " :class="(!cals[p-1][h] || isLibur(cals[p-1][h])) ?'bg-slate-300':'bg-white'" v-for="h in 6" :key="h" colspan="4">
                            {{ haris[h] }}, {{ cals[p-1][h] }}  <span v-if="cals[p-1][h]">{{ bulans[bulan] }} {{ tahun }}</span>
                        </th>

                    </tr>
                    <tr>
                        <template v-for="d in 6" :key="d">
                            <th :class="(!cals[p-1][d] || isLibur(cals[p-1][d])) ?'bg-slate-300':'bg-white'" class="border border-slate-500 w-[60px]" v-for="(l,i) in ['In', 'TTD','Out','TTD']" :key="i">{{ l }}</th>
                        </template>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(ptk,i) in ptks" :key="ptk.id">
                        <td class="border border-slate-500 text-center">{{ i+1 }}</td>
                        <td class="border border-slate-500 px-1">
                            <p class="leading-4">{{ ptk.nama + (ptk.gelar_belakang ? ", "+ptk.gelar_belakang : '') }}</p>
                            <p >NIP. {{ ptk.nip }}</p>
                        </td>
                        <td class="border border-slate-500 text-center">
                            {{ roles[ptk.role] }}
                        </td>
                        <template v-for="d in 6" :key="d">
                            <td :class="(!cals[p-1][d] || isLibur(cals[p-1][d])) ?'bg-slate-300':'bg-white'"  class="border border-slate-500 text-center" v-for="(l,i) in ['In', 'TTD','Out','TTD']" :key="i">
                                {{ isLibur(cals[p-1][d]) ? 'X' : '' }}
                            </td>
                        </template>
                    </tr>
                </tbody>
            </table>

            <div class="ttd grid grid-cols-3 mt-4">
                <div class="text-xs">
                    Keterangan:
                    <ul class="text-xs list-decimal pl-4">
                        <li v-for="ev in events" :key="ev.id">[{{ ev.start }}] {{ ev.name }}</li>
                    </ul>
                </div>
                <div></div>
                <div class="text-center text-xs">
                    <p>Wagir, ..... {{ bulans[bulan] }} {{ tahun }}</p>
                    <p>Kepala {{ page.props.sekolah.nama }}</p>

                    <p class="font-bold underline mt-12">{{ page.props.sekolah.ks.nama }}, {{ page.props.sekolah.ks.gelar_belakang }}</p>
                    <p>NIP. {{ page.props.sekolah.ks.nip }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

</template>