<script setup>
import { Icon } from '@iconify/vue';
import { ref, computed, onMounted } from 'vue';
import { router, usePage } from '@inertiajs/vue3';
import moment from 'moment';

moment().local('id');

const $page = usePage();

const props = defineProps({
    rombel: Object
})

const hari = ref('')
const bulans = ref(['JANUARI','FEBRUARI','MARET','APRIL','MEI','JUNI','JULI','AGUSTUS','SEPTEMBER','OKTOBER','NOVEMBER','DESEMBER']),
        haris = ref(['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu']),
        kaldiks = ref([])

const getAgendas = async() => {
    await axios.post(route('dashboard.agenda.index'))
                .then(res => {
                    let events = []
        let ranges = []
        res.data.agendas.forEach((event, i) => {
            let rentang = moment(event.end).date() - moment(event.start).date()
            ranges.push(rentang)
            if(rentang < 1) {
                events.push(event)
            } else {
                let datas = []
                for(let h=0;h<rentang;h++) {
                    events
                    .push({
                        name: event.name,
                        description: event.description,
                        start: moment(event.start).add(h,'d').format('YYYY-MM-DD'),
                        end: moment(event.start).add(h, 'd').format('YYYY-MM-DD'),
                        location: event.location,
                        color: event.color,
                        user_id: event.user_id,
                        is_libur: event.is_libur,
                        is_active: event.is_active
                    })
                }
            }
        })
        kaldiks.value = events
        })
}

const semester = ref('1')
const toggleSemester = (e) => {
    semester.value = e.target.value
}
const months = computed(() => semester.value == '2' ? bulans.value.slice(0,6) : bulans.value.slice(6))

const weeksPerMonth = (month) => {
    let monthNum = bulans.value.lastIndexOf(month)
    // const tapel = $page.props.tapel.split('/')
    let year = (semester.value == '1' ? '20'+($page.props.tapel.kode.substr(0,2)) : '20'+(($page.props.tapel.kode.substr(2,2))))
    let date = moment(new Date(year+'-'+(monthNum+1)))
    let weekday = haris.value.lastIndexOf(props.rombel.jadwals[0].hari)
    let dif = (7 + (weekday - date.weekday())) % 7 + 1;
    return Math.floor((date.daysInMonth() - dif) / 7) + 1;
}

const unefektif = (month, hari) => {
    let events = []
    kaldiks.value.forEach(kaldik => {
        let bulan = bulans.value[new Date(kaldik.start).getMonth()]
        let day = haris.value[moment(kaldik.start).get('day')]
            if(bulan == month && hari == day) {
                
                events.push(kaldik)
            }
        
    })
    return events
}

onMounted(() => {
    getAgendas()
})
</script>

<template>
    <div class="w-full">
        <div class="toolbar h-10 bg-slate-400 flex items-center justify-between p-3 text-white">
            <h1
                class="flex items-center gap-1"
            >
                <Icon icon="mdi:calendar-search" class="text-2xl" />
                Analisis Pekan Efektif {{ props.rombel.label }} Hari {{ props.rombel.jadwals[0].hari }}
            </h1>
            <div class="toolbar-items">
                <select name="semester" class="bg-slate-50 py-1 text-slate-800 border-none" v-model="semester" @change="toggleSemester">
                    <option value="1">Semester 1</option>
                    <option value="2">Semester 2</option>
                </select>
            </div>
        </div>
        <div class="content p-3 bg-white">
            <table class="border w-full">
                <thead>
                    <tr>
                        <th rowspan="2" class="p-2 border">No</th>
                        <th rowspan="2" class="p-2 border">BULAN</th>
                        <th colspan="3" class="p-2 border">JML MINGGU</th>
                        <th rowspan="2" class="p-2 border">KETERANGAN</th>
                    </tr>
                    <tr>
                        <th>JUMLAH</th>
                        <th>TDK EFEKTIF</th>
                        <th>EFEKTIF</th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                        v-for="(bulan,b) in months" :key="b"
                        >
                            <td class="border p-2 text-center">{{ b+1 }}</td>
                            <td class="border p-2">{{ bulan }}</td>
                            <td class="border p-2">{{ weeksPerMonth(bulan) }}</td>
                            <td class="border p-2">{{ unefektif(bulan, props.rombel.jadwals[0].hari).length }}</td>
                            <td class="border p-2">{{ weeksPerMonth(bulan) -  unefektif(bulan, props.rombel.jadwals[0].hari).length}}</td>
                            <td class="border p-2">
                                <ul>
                                    <li v-for="ev in unefektif(bulan, rombel.jadwals[0].hari)" :key="ev.id" :style="`color: ${ev.color}`">
                                        {{moment(ev.start).format('DD MMM YYYY')}}:  {{ ev.description }}
                                    </li>
                                </ul>
                            </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>