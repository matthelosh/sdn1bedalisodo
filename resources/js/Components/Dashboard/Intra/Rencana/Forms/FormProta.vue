<script setup>
import { Icon } from '@iconify/vue';
import { ref, onMounted, computed} from 'vue';
import axios from 'axios';

const emit = defineEmits(['close'])

const props = defineProps({
    rombel: Object,
    mapel: String,
})

const atps = ref([])
const materis = ref([])

const prota = ref({})

const listAtp = async() => {
    await axios.post(route('dashboard.atp.index', {
        _query: {
                tingkat: props.rombel.tingkat,
                mapel_id: props.mapel
            }
    }))
            .then(res => {
                atps.value = res.data.atps
            }).catch( err => {
                console.log(err)
            })
}
const listMateri = async() => {
    await axios.post(route('dashboard.materi.index', {
        _query: {
            tingkat: props.rombel.tingkat,
            mapel_id: props.mapel
        }
    }))
            .then(res => {
                materis.value = res.data.materis
            }).catch( err => {
                console.log(err)
            })
}

const atpList = ref(false)
const atpText = ref('')
const showAtpList = () => {
    atpList.value = true
}

const pickAtp = (item) => {
    prota.value.atp_id = item.id
    atpText.value = item.teks
    atpList.value = false
}

const simpan = async() => {
    let data = prota.value
    data.rombel_id = props.rombel.kode;
    data.mapel_id = props.mapel
    data.tingkat = props.rombel.tingkat
    await axios.post(route('dashboard.prota.store'), data)
            .then(res => {
                emit('close')
            })
}

onMounted(() => {
    listAtp()
    listMateri()
})

</script>

<template>
    <div class="w-full bg-slate-100 bg-opacity-20 backdrop-blur-sm fixed top-0 right-0 bottom-0 left-0 flex items-center justify-center z-40" @click.self="close">
        <div class="dialog bg-white rounded shadow-lg w-full md:w-1/3">
            <div class="toolbar h-10 flex items-center justify-between w-full p-2 bg-slate-200">
                <h1>Formulir Program Tahunan</h1>
                <div class="toolbar-items flex items-center gap-2 justify-end">
                    <button @click="emit('close')">
                        <Icon icon="mdi:close-box" class="text-2xl text-red-600 hover:text-red-400 active:text-red-800" />
                    </button>
                </div>
            </div>
            <div class="content p-2">
                <form ref="formProta" @submit.prevent="simpan">
                    <fieldset>
                        <div class="row w-full relative">
                            <label for="atp">Alur Tujuan Pembelajaran</label>
                            <!-- <select v-model="prota.atp_id" class="py-1 px-2 border-none bg-slate-100 w-[100%] overflow-x-hidden whitespace-pre-wrap break-words">
                                <option 
                                    class="overflow-x-clipped w-[100px] whitespace-break-spaces break-words"
                                    v-for="atp in atps"
                                    :key="atp.id"
                                    :value="atp.id">
                                    <span class="w-full overflow-x-hidden bg-pink-200">
                                        {{ atp.teks }}
                                    </span>
                                </option>
                            </select> -->
                            <textarea type="text" class="py-1 px-2 border-none bg-slate-100 w-[100%]" placeholder="ATP" @focus="showAtpList" v-model="atpText"></textarea>
                            <ul class="max-h-[500px] overflow-y-auto absolute bg-slate-100 mt-1 transition-all" v-if="atpList">
                                <li
                                    v-for="atp in atps"
                                    :key="atp.id"
                                    class="my-1 hover:bg-slate-50 hover:cursor-pointer "
                                    @click="pickAtp(atp)"
                                >
                                    {{ atp.teks }}
                                </li>
                            </ul>
                        </div>
                        <div class="row w-full">
                            <label for="atp">Urutan</label>
                            <select v-model="prota.alur" class="py-1 px-2 border-none bg-slate-100 w-[100%] overflow-x-hidden whitespace-pre-wrap break-words">
                                <option 
                                    class="overflow-x-clipped w-[100px] whitespace-break-spaces break-words"
                                    v-for="u in atps.length"
                                    :key="u"
                                    :value="u">{{u}}</option>
                            </select>
                        </div>
                        <div class="row w-full">
                            <label for="materi">Materi</label>
                            <select v-model="prota.materi_id" class="py-1 px-2 border-none bg-slate-100 w-[100%] overflow-x-hidden whitespace-pre-wrap break-words">
                                <option 
                                    class="overflow-x-clipped w-[100px] whitespace-break-spaces break-words"
                                    v-for="materi in materis"
                                    :key="materi.id"
                                    :value="materi.id">{{materi.bab}}</option>
                            </select>
                        </div>
                        <div class="row w-full">
                            <label for="jp">Jam Pelajaran</label>
                            <input type="number" v-model="prota.jp" class="py-1 px-2 border-none bg-slate-100 w-[100%] overflow-x-hidden whitespace-pre-wrap break-words" placeholder="Jumlah JP" />
                        </div>
                        <div class="row w-full">
                            <label for="semester">Semester</label>
                            <select v-model="prota.semester" class="py-1 px-2 border-none bg-slate-100 w-[100%] overflow-x-hidden whitespace-pre-wrap break-words">
                                <option 
                                    class="overflow-x-clipped w-[100px] whitespace-break-spaces break-words"
                                    v-for="semester in 2"
                                    :key="semester"
                                    :value="semester">Semester{{semester}}</option>
                            </select>
                        </div>
                        <div class="row w-full flex justify-center mt-6 ">
                            <button class="bg-sky-400 hover:bg-sky-600 active:bg-sky-500 text-white py-2 px-4 rounded">
                                Simpan
                            </button>
                        </div>
                    </fieldset>
                </form>
            </div>
        </div>
    </div>
</template>