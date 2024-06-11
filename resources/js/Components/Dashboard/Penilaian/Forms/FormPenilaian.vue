<script setup>
import { ref, computed } from 'vue';
import { Icon } from '@iconify/vue';
import axios from 'axios';

const props = defineProps({rombel: Object, mapel: String, periode: String})

const emit = defineEmits(['close'])

const penilaian = ref({
    semester: '',
    tanggal: ''
});

const simpan = async() => {
    let data = penilaian.value;
    data.rombel_id = props.rombel.kode;
    data.mapel_id = props.mapel;
    data.periode = props.periode;
    data.kode = (penilaian.value.tanggal+props.mapel+props.rombel.kode).replace("-", "")
    console.log(data)
    await axios.post(route('dashboard.intra.penilaian.store'), data)
                .then(res => {
                    listPenilaian()
                })
}

const penilaians = ref([])

const listPenilaian = async() => {
    await axios.post(route('dashboard.intra.penilaian.index'))
                .then(res => {
                    penilaians.value = res.data.penilaians
                })
}
</script>

<template>
    <div class="wrapper fixed top-0 right-0 bottom-0 left-0 flex items-center justify-center z-50 bg-slate-800 bg-opacity-40 backdrop-blur">
        <div class="content w-[400px] bg-slate-100 rounded-md shadow-lg">
            <div class="toolbar p-2 flex items-center justify-between">
                <h1>Formulir Penilaian</h1>
                <button @click="emit('close')">
                    <Icon icon="mdi:close-circle" class="text-red-500 text-xl hover:text-red-600 active:text-red-400" />
                </button>
            </div>
            <div class="content bg-white p-3">
                <form @submit.prevent="simpan">
                    <fieldset>
                        <div class="row w-full my-2">
                            <label for="semester" class="w-full">Semester</label>
                            <select v-model="penilaian.semester" class="w-full rounded bg-slate-100 border-none shadow-lg px-1">
                                <option 
                                    v-for="semester in 2"
                                    :value="semester">Semester {{ semester }}</option>
                            </select>
                        </div>
                        <div class="row w-full my-2">
                            <label for="tanggal" class="w-full">Tanggal</label>
                            <input type="date" v-model="penilaian.tanggal" class="w-full rounded bg-slate-100 border-none shadow-lg px-1" />
                        </div>
                        <div class="row w-full mt-6 flex justify-center">
                            <button 
                                class="bg-sky-400 text-white py-1 px-4 rounded shadow-lg hover:bg-sky-500 active:bg-sky-300 hover:shadow transition" 
                                @click="simpan"
                                >
                                Simpan
                            </button>
                        </div>
                    </fieldset>
                </form>
            </div>
        </div>
    </div>
</template>