<script setup>
import { Icon } from '@iconify/vue';
import { ref, computed, onMounted } from 'vue';
import { router, usePage } from '@inertiajs/vue3';
import moment from 'moment';

moment().local('id');

const $page = usePage();

const props = defineProps({
    rombel: Object,
    mapel: String
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

const protaItems = ref([]);
const protas = computed(() => {
    return protaItems.value.filter(item => item.semester == semester.value)
});
const listProta = async() => {
    await axios.post(route('dashboard.prota.index', 
                    {
                        _query: {
                            rombel_id: props.rombel.kode,
                            mapel_id: props.mapel
                        }
                    }
                ))
                .then( res => {
                    protaItems.value = res.data.protas
                })
                .catch( err => {

                })
}

const prosems = ref([])
const listPromes = async() => {
    await axios.post(route('dashboard.prosem.index',
            {
                _query: {
                    rombel_id: props.rombel.kode,
                    mapel_id: props.mapel
                }
            }
        ))
        .then(res => {
            prosems.value = res.data.prosems
        })
}

const tes = (item, minggu) => {
    console.log(item, minggu)
}

onMounted(() => {
    getAgendas();
    listProta();
})
</script>

<template>
<div class="w-full">
    <div class="toolbar p-3 h-10 bg-slate-400 text-white flex items-center justify-between">
        <h1 class="flex gap-1 items-center">
            <Icon icon="mdi:calendar-clock" class="text-2xl " />
            Program Semester
        </h1>
        <div class="toolbar-items flex items-center gap-2">
            <button title="Cetak Program Semester">
                <Icon icon="mdi:printer" class="text-2xl" />
            </button>
            <select name="semester" class="bg-slate-50 py-1 text-slate-800 border-none" v-model="semester" @change="toggleSemester">
                <option value="1">Semester 1</option>
                <option value="2">Semester 2</option>
            </select>
        </div>
    </div>
    <div class="content p-3 bg-white">
        <table class="border w-full text-sm">
            <thead>
                <tr>
                    <th class="border" rowspan="2">No</th>
                    <th class="border" rowspan="2">No. ATP</th>
                    <th class="border" rowspan="2">ATP / Materi</th>
                    <th class="border" rowspan="2">JML</th>
                    <th class="border" rowspan="2">SMT</th>
                    <th class="border"
                        
                        v-for="(bulan,b) in months" :key="b"
                        :colspan="weeksPerMonth(bulan)"
                    >
                        {{ bulan }} 
                    </th>
                </tr>
                <tr>
                    <th
                        v-for="m in weeksPerMonth(months[0])"
                        class="border"
                    >
                        {{ m }}
                    </th>
                    <th
                        v-for="m in weeksPerMonth(months[1])"
                        class="border"
                    >
                        {{ m }}
                    </th>
                    <th
                        v-for="m in weeksPerMonth(months[2])"
                        class="border"
                    >
                        {{ m }}
                    </th>
                    <th
                        v-for="m in weeksPerMonth(months[3])"
                        class="border"
                    >
                        {{ m }}
                    </th>
                    <th
                        v-for="m in weeksPerMonth(months[4])"
                        class="border"
                    >
                        {{ m }}
                    </th>
                    <th
                        v-for="m in weeksPerMonth(months[5])"
                        class="border"
                    >
                        {{ m }}
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr
                    v-for="(prota, index) in protas"
                    :key="prota.id"
                >
                    <td class="border text-center">{{ index+1 }}</td>
                    <td class="border text-center">{{rombel.tingkat}}.{{ prota.alur }}</td>
                    <td class="border w-[20%] p-2">{{ prota.materi.pokok }}</td>
                    <td class="border text-center">{{ prota.jp }}</td>
                    <td class="border text-center">{{ prota.semester }}</td>
                    <td
                        v-for="m in weeksPerMonth(months[0])"
                        class="border w-[40px] hover:cursor-pointer"
                        @click="tes(prota, m)"
                    >
                        
                    </td>
                    <td
                        v-for="m in weeksPerMonth(months[1])"
                        class="border w-[40px]"
                    >
                       
                    </td>
                    <td
                        v-for="m in weeksPerMonth(months[2])"
                        class="border w-[40px]"
                    >
                        
                    </td>
                    <td
                        v-for="m in weeksPerMonth(months[3])"
                        class="border w-[40px]"
                    >
                        
                    </td>
                    <td
                        v-for="m in weeksPerMonth(months[4])"
                        class="border w-[40px]"
                    >
                        
                    </td>
                    <td
                        v-for="m in weeksPerMonth(months[5])"
                        class="border w-[40px]"
                    >
                        
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
</template>