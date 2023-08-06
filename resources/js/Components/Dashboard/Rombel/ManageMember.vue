<script setup>
import { ref, computed, onMounted, defineAsyncComponent } from 'vue';
import { usePage } from '@inertiajs/vue3';
import { Icon } from '@iconify/vue';
import axios from 'axios';
import {paginate} from '@/Plugins/misc.js';
import { read, utils } from 'xlsx';

const Loading = defineAsyncComponent(() => import('@/Components/General/Loading.vue'))

const page = usePage();

const loading = ref(false)

onMounted(() => {
    members.value = props.rombel.siswas
    listNonmembers()
})

const memberPage = ref(0)
const props = defineProps({
    rombel: Object
})
const emit = defineEmits(['close'])
const nonmemberBox = ref(false)

const searchNonmember = ref('')

const chunk = ref('10')
const members = ref([])
const pagedMembers = computed(() =>  paginate(members.value, chunk.value, memberPage.value))
const nonmembers = ref([])

const selectedMembers = ref([])
const selectedNonmembers = ref([])

const toggleNonmember = async() => {
    nonmemberBox.value = !nonmemberBox.value
}

const listNonmembers = async() => {
    loading.value = true
    await axios.post(route('dashboard.rombel.nonmember', {_query: {rombel: props.rombel.kode}}))
                .then(res => {
                    nonmembers.value = res.data.nonmembers
                    // console.log(res.data)
                    loading.value = false
                }).catch(err => {
                    loading.value = false
                    console.log(err)
                })
}

const dragStarttoMember = (e, nonmem) => {
    // console.log(e.dataTransfer)
    let dropzone = document.querySelector(".table-member.dropzone")
    dropzone.style.background = "pink"
    e.dataTransfer.dropEffect = "move"
    e.dataTransfer.setData("item", JSON.stringify(nonmem))
    
}
const dragStarttoNonMember = (e, member) => {
    // console.log(e.dataTransfer)
    let dropzone = document.querySelector(".table-nonmember.dropzone")
    dropzone.style.background = "pink"
    e.dataTransfer.dropEffect = "move"
    e.dataTransfer.setData("item", JSON.stringify(member))
    
}


const droppedOnMember = (e) => {
    let dropzone = document.querySelector(".table-member.dropzone")
    dropzone.style.background = "transparent"
    let data = JSON.parse(e.dataTransfer.getData("item"))
    members.value.push(data)
    masukkan(data).then(res => console.log(res)).catch(err=>console.log(err))
    let i = nonmembers.value.length
    while(i--) {
        if(nonmembers.value[i].id === data.id) {
            nonmembers.value.splice(i,1)
        }
    }
}

const droppedOnNonmember = (e) => {
    let dropzone = document.querySelector(".table-nonmember.dropzone")
    dropzone.style.background = "transparent"
    let data = JSON.parse(e.dataTransfer.getData("item"))
    nonmembers.value.push(data)
    keluarkan(data).then(res => console.log(res)).catch(err=>console.log(err))
    let i = members.value.length
    while(i--) {
        if(members.value[i].id === data.id) {
            members.value.splice(i,1)
        }
    }
}

const keluarkan = async(siswa) => {
    await axios.post(route('dashboard.rombel.keluarkan', {
        id: props.rombel.id,
        siswa_id: siswa.id

    }), {siswa: siswa}).then(res => res).catch(err => err)
}

const masukkan = async(siswa) => {
    await axios.post(route('dashboard.rombel.masukkan', {
        id: props.rombel.id,
        masukkan: true

    }), {siswa: siswa}).then(res => res).catch(err => err)
}

const cetak = async() => {
    chunk.value = 'all'
    setTimeout(() => {
        window.print()
        chunk.value = 10
    }, 500)
}

// const fileMember = ref(null)
const calons = ref([])
const onFileMemberPicked = async(e) => {
    let file = e.target.files[0]
    let reader = new FileReader();

    reader.onload = async(ev) => {
        let wb = await read(ev.target.result)
        calons.value = await utils.sheet_to_json(wb.Sheets[wb.SheetNames[0]])
        // calons.value = datas
    }
    reader.readAsArrayBuffer(file)
}
const imporMember = async() => {
    loading.value = true
    await axios.post(route('dashboard.rombel.member.impor', {id: props.rombel.id}), {siswas: JSON.stringify(calons.value)})
                .then(res => {
                    members.value = [...calons.value]
                    loading.value = false
                    calons.value = []
                })
                .catch(err => {
                    console.log(err)
                    loading.value = false
                })
}


</script>

<template>
    <Loading v-if="loading" />
    <div class="w-full bg-white shadow print:shadow-none">
        <div class="toolbar min-h-12 flex items-center justify-between p-3 bg-slate-50 print:hidden flex-wrap">
            <h1>
                Manajemen Peserta Didik Kelas {{ props.rombel.label }} 
                <span class="hidden md:inline">| Wali Kelas {{ props.rombel.guru.nama }}</span></h1>
            <div class="toolbar-items flex items-center justify-end gap-2 relative">
                <input type="file" ref="fileMember" class="hidden" accept=".xls,.xlsx,.ods" @change="onFileMemberPicked" />
                <button class="flex items-center p-1" @click="$refs.fileMember.click()">
                    Impor
                    <Icon icon="mdi:upload" class="text-2xl text-sky-400 hover:text-sky-600" />
                </button>
                <button class="flex items-center p-1" @click="toggleNonmember">
                    Lihat Siswa
                    <Icon icon="mdi:account-school" class="text-2xl text-sky-400 hover:text-sky-600" />
                </button>
                <input type="text" v-model="searchNonmember" placeholder="Cari" class="rounded-lg h-8" v-if="nonmemberBox" />
                <button @click="cetak">
                    <Icon icon="mdi:printer" class="text-2xl text-gray-600 hover:text-gray-400" />
                </button>
                <button @click="emit('close')" class="absolute -top-8 -right-24 md:relative md:top-0 md:right-0">
                    <Icon icon="mdi:close-box" class="text-red-400 hover:text-red-600 text-4xl" />
                </button>
            </div>
        </div>
        <div class="content p-3 w-full overflow-x-hidden" :class="nonmemberBox ? 'grid grid-cols-6 gap-3': ''">
            <div class="table w-full overflow-scroll print:col-span-6" :class="nonmemberBox ? 'col-span-4': ''">
                <caption class="text-center text-xl hidden print:block font-bold mb-4">Data Peserta Didik Kelas {{ props.rombel.label }}</caption>
                <table class="w-full border border-collapse dropzone table-member" @dragover.prevent="dragOverMember" @drop.prevent="droppedOnMember" dropzone>
                    
                    <thead>
                        <tr class="bg-slate-200">
                            <th class="border p-2 border-slate-300">No</th>
                            <th class="border p-2 border-slate-300">NISN</th>
                            <th class="border p-2 border-slate-300">Nama</th>
                            <th class="border p-2 border-slate-300">JK</th>
                            <th class="border p-2 border-slate-300 print:hidden">Opsi</th>
                        </tr>
                    </thead>
                    <tbody >
                        <tr v-if="members.length < 1" class="border p-3 w-full">
                            <td colspan="5" class="text-center p-8">
                                <h1 class="text-2xl font-bold text-sky-800">Belum Ada data Peserta di Kelas {{ props.rombel.label }}</h1>
                            </td>
                        </tr>
                        <tr v-for="(siswa,s) in pagedMembers.current" :key="s" class="odd:bg-gray-50" draggable="true" @dragstart="dragStarttoNonMember($event,siswa)">
                            <td class="py-1 px-2  border text-center">{{ siswa.no }}</td>
                            <td class="py-1 px-2  border text-center">{{ siswa.nisn }}</td>
                            <td class="py-1 px-2  border ">{{ siswa.nama }}</td>
                            <td class="py-1 px-2  border text-center">{{ siswa.jk }}</td>
                            <td class="py-1 px-2  border text-center print:hidden"></td>
                        </tr>
                    </tbody>
                </table>
                <div class="pagination w-full flex justify-between my-2 print:hidden">
                    Jumlah: {{pagedMembers.total}}
                    <div class="page-nav flex flex-wrap">
                        <button class="flex items-center justify-center p-2 border" :disabled="memberPage == 0" @click="memberPage--">
                            <Icon icon="mdi:chevron-double-left" />
                        </button>
                        
                        <button v-for="b in pagedMembers.page_length" :key="b" class="flex items-center justify-center p-2 border flex-grow" :class="b == (memberPage+1) ? 'bg-sky-400 text-white': ''" @click="memberPage = (b-1)">
                            <span>{{ b }}</span>
                        </button>

                        <button class="flex items-center justify-center p-2 border" :disabled="memberPage == (members.page_length-1)" @click="memberPage++">
                            <Icon icon="mdi:chevron-double-right" />
                        </button>
                    </div>
                </div>
            </div>
            <div class="non-member col-span-2 h-screen overflow-y-scroll bg-gray-100 p-2 print:hidden" v-if="nonmemberBox">
                <table class="w-full border border-collapse table-nonmember bg-white dropzone" dropzone @dragover.prevent="dragOverNonmember" @drop.prevent="droppedOnNonmember" >
                    <thead class="bg-white">
                        <tr>
                            <th class="border">NISN</th>
                            <th class="border">Nama</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white">
                        <tr class="cursor-grab" v-for="(nonmem, nm) in nonmembers.filter(item => item.nama.toLowerCase().includes(searchNonmember.toLowerCase()))" :key="nm" draggable="true" @dragstart="dragStarttoMember($event, nonmem)">
                            <td class="border">{{ nonmem.nisn }}</td>
                            <td class="border">{{ nonmem.nama }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="dialog-overlay impor fixed top-0 right-0 bottom-0 left-0 bg-white bg-opacity-60 backdrop-blur flex p-2 md:p-10 z-40" v-if="calons.length > 0">
        <div class="dialog bg-white w-full shadow-lg">
            <div class="toolbar h-10 w-full bg-slate-300 flex items-center justify-between p-3">
                <h1 class="font-bold text-slate-800">Data Calon Peserta Rombel {{ props.rombel.label }}</h1>
                <div class="toolbar-items flex items-center gap-2">
                    <button class="py-1 px-3 bg-sky-400 hover:bg-sky-600 text-white rounded shadow" @click="imporMember">
                        Impor
                    </button>
                    <button @click="calons = []">
                        <Icon icon="mdi:close-circle" class="text-2xl hover:text-red-600" />
                    </button>
                </div>
            </div>
            <div class="content overflow-y-scroll max-h-[90vh] md:max-h-[95%]">
                <table class="w-full border  ">
                    <thead>
                        <tr class="bg-slate-200">
                            <th class="py-2 ">No</th>
                            <th class="py-2 border-s border-slate-300">NISN</th>
                            <th class="py-2 border-s border-slate-300">Nama Siswa</th>
                        </tr>
                    </thead>
                    <tbody class="h-max-[300px] overflow-y-auto">
                        <tr
                            class="odd:bg-slate-100"
                            v-for="(calon,c) in calons" :key="calon.id"
                        >
                            <td class="py-1 px-2 text-center">{{ c+1 }}</td>
                            <td class="py-1 px-2 text-center border-s">{{ calon.nisn }}</td>
                            <td class="py-1 px-2 border-s">{{ calon.nama }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>
