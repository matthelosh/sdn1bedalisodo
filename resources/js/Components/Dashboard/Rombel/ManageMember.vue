<script setup>
import { ref, computed, onMounted } from 'vue';
import { usePage } from '@inertiajs/vue3';
import { Icon } from '@iconify/vue';
import axios from 'axios';
import {paginate} from '@/Plugins/misc.js'

const page = usePage();

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
const members = ref([])
const pagedMembers = computed(() =>  paginate(members.value, 10, memberPage.value))
const nonmembers = ref([])

const selectedMembers = ref([])
const selectedNonmembers = ref([])

const toggleNonmember = async() => {
    nonmemberBox.value = !nonmemberBox.value
}

const listNonmembers = async() => {
    await axios.post(route('dashboard.rombel.nonmember', {_query: {rombel: props.rombel.kode}}))
                .then(res => {
                    nonmembers.value = res.data.nonmembers
                    // console.log(res.data)
                }).catch(err => console.log(err))
}

const dragStarttoMember = (e, nonmem) => {
    // console.log(e.dataTransfer)
    e.dataTransfer.dropEffect = "move"
    e.dataTransfer.setData("item", JSON.stringify(nonmem))
    
}
const dragStarttoNonMember = (e, member) => {
    // console.log(e.dataTransfer)
    e.dataTransfer.dropEffect = "move"
    e.dataTransfer.setData("item", JSON.stringify(member))
    
}


const dragOverMember = (e) => {
    // e.dataTrasnfer.dropEffect = "move"
    let dropzone = document.querySelector(".table-member.dropzone")
    dropzone.style.background = "pink"
}

const dragOverNonmember = (e) => {
    // e.dataTrasnfer.dropEffect = "move"
    let dropzone = document.querySelector(".table-nonmember.dropzone")
    dropzone.style.background = "pink"
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
    await console.log(siswa)
}

const masukkan = async(siswa) => {
    await axios.post(route('dashboard.rombel.masukkan', {
        id: props.rombel.id,
        masukkan: true

    }), {siswa: siswa}).then(res => res).catch(err => err)
}
</script>

<template>
    
        <div class="w-full bg-white shadow">
            <div class="toolbar h-12 flex items-center justify-between p-3 bg-slate-50">
                <h1>Manajemen Peserta Didik Kelas {{ props.rombel.label }} | Wali Kelas {{ props.rombel.guru.nama }}</h1>
                <div class="toolbar-items flex items-center justify-end gap-2">
                    <button class="flex items-center hover:border border-sky-400 hover:border-sky-600 rounded-full p-1" @click="toggleNonmember">
                        <Icon icon="mdi:account-school" class="text-2xl text-sky-400 hover:text-sky-600" />
                    </button>
                    <input type="text" v-model="searchNonmember" placeholder="Cari" class="rounded-lg h-8" v-if="nonmemberBox" />
                    <button @click="emit('close')">
                        <Icon icon="mdi:close-box" class="text-red-400 hover:text-red-600 text-4xl" />
                    </button>
                </div>
            </div>
            <div class="content p-3 w-full overflow-x-hidden" :class="nonmemberBox ? 'grid grid-cols-4 gap-3': ''">
                <div class="table w-full overflow-scroll" :class="nonmemberBox ? 'col-span-2': ''">
                    <table class="w-full border border-collapse dropzone table-member" @dragover.prevent="dragOverMember" @drop.prevent="droppedOnMember" dropzone>
                        <thead>
                            <tr class="bg-slate-200">
                                <th class="border p-2 border-slate-300">No</th>
                                <th class="border p-2 border-slate-300">NISN</th>
                                <th class="border p-2 border-slate-300">Nama</th>
                                <th class="border p-2 border-slate-300">JK</th>
                                <th class="border p-2 border-slate-300">Opsi</th>
                            </tr>
                        </thead>
                        <tbody >
                            <tr v-if="members.length < 1" class="border p-3 w-full">
                                <td colspan="5" class="text-center p-8">
                                    <h1 class="text-2xl font-bold text-sky-800">Belum Ada data Peserta di Kelas {{ props.rombel.label }}</h1>
                                </td>
                            </tr>
                            <tr v-for="(siswa,s) in pagedMembers.current" :key="s" class="odd:bg-sky-100" draggable="true" @dragstart="dragStarttoNonMember($event,siswa)">
                                <td class="py-1 px-2  border text-center">{{ s+1 }}</td>
                                <td class="py-1 px-2  border text-center">{{ siswa.nisn }}</td>
                                <td class="py-1 px-2  border ">{{ siswa.nama }}</td>
                                <td class="py-1 px-2  border text-center">{{ siswa.jk }}</td>
                                <td class="py-1 px-2  border text-center"></td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="pagination w-full flex justify-between my-2">
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
                <div class="non-member col-span-2 h-screen overflow-y-scroll" v-if="nonmemberBox">
                    <table class="w-full border border-collapse table-nonmember dropzone" dropzone @dragover.prevent="dragOverNonmember" @drop.prevent="droppedOnNonmember" >
                        <thead>
                            <tr>
                                <th class="border">NISN</th>
                                <th class="border">Nama</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(nonmem, nm) in nonmembers.filter(item => item.nama.toLowerCase().includes(searchNonmember.toLowerCase()))" :key="nm" draggable="true" @dragstart="dragStarttoMember($event, nonmem)">
                                <td class="border">{{ nonmem.nisn }}</td>
                                <td class="border">{{ nonmem.nama }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
</template>