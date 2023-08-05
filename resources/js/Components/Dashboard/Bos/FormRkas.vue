<script setup>
import axios from 'axios';
import { ref, computed, onMounted } from 'vue';
const loading = ref(false)

const emit = defineEmits(['close'])

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

const kegiatans = ref([])
const queryProgram = ref('')
const programs = computed(() => {
    return kegiatans.value.filter(item => item.uraian.toLowerCase().includes(queryProgram.value.toLowerCase()))
});
const listKodeProgram = async() => {
    await axios.post(route('dashboard.bos.kegiatan.index'))
                .then(res => kegiatans.value = res.data.kegiatans)
                .catch(err => console.log(err))
}

const pickProgram = (program) => {
    rkas.value.kode_program = program.kode
    queryProgram.value = program.uraian
}

const rkas = ref({
    bulan: '0'
});

onMounted(() => {
    listKodeProgram()
})

</script>

<template>
<div class="fixed top-0 right-0 bottom-0 left-0 bg-white backdrop-blur-sm bg-opacity-60 flex items-center justify-center overflow-y-auto z-50" @click.self="emit('close')">
    <div class="dialog bg-white md:w-6/12 md:max-w-full mx-auto md:min-w-[400px] overflow-y-auto shadow-lg border border-gray-200">
        <div class="toolbar w-full h-10 bg-slate-200 flex items-center justify-between p-3">
            <h1>Buat Rencana Anggaran Baru</h1>
            <div class="toolbar-items flex items-center gap-4">
                <label for="bulan">
                    Bulan
                    <select name="bulan" v-model="rkas.bulan" class="bg-slate-300 py-1 border-none ">
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
                    <select name="tahun" v-model="rkas.tahun" class="bg-slate-300 py-1 border-none ">
                        <option 
                            v-for="tahun in tahuns"
                            :key="tahun"
                            :value="tahun">
                            {{ tahun }}
                        </option>
                </select>
                </label>
            </div>
        </div>
        <div class="content w-full bg-slate-50 p-4">
            <form class="form-rkas w-full">
                <label for="kode_program" class="w-full">
                    Kode Program
                    <div class="w-full">
                        <input type="text" placeholder="Pilih Program" class="border-none bg-slate-200 w-full border-transparent focus:border-transparent focus:ring-0 focus:bg-sky-100 shadow-inner" v-model="queryProgram">
                        <ul class="w-full h-max-[200px] max-h-[200px] overflow-auto bg-white shadow">
                            <li
                                v-for="program in programs" :key="program.id"
                                class="hover:bg-slate-300 cursor-pointer"
                                @click="pickProgram(program)"
                            >
                                {{program.kode}} - {{ program.uraian }}
                            </li>
                        </ul>
                    </div>
                </label>
            </form>
        </div>
    </div>
</div>

</template>