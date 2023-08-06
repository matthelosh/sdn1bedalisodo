<script setup>
import { ref, onMounted, computed } from 'vue';
import axios from 'axios';
import { Icon } from '@iconify/vue';

const siswas = ref([])
const pesertas = ref([])

const searchSiswa = ref('')
const searchPeserta = ref('')

const siswasFiltered = computed(() => {
    return siswas.value.filter(siswa => siswa.nama.toLowerCase().includes(searchSiswa.value.toLowerCase()))
})
const pesertaFiltered = computed(() => {
    return pesertas.value.filter(siswa => siswa.nama.toLowerCase().includes(searchPeserta.value.toLowerCase()))
})

const props = defineProps({
    ekskul: Object
})

const list = async() => {
    await axios.post(route('dashboard.ekskul.peserta', {
                id: props.ekskul.id
            }))
            .then(res => {
                siswas.value = res.data.siswas;
                // pesertas.value = res.data.pesertas;
            })
}

const startDragSiswa = (e, siswa) => {
    let dropzone = document.querySelector(".dropzone-peserta")
    dropzone.classList.remove("hidden")
    // dropzone.setAttribute("dropzone", true)
    dropzone.style.background = "#9a9a9a"
    e.dataTransfer.dropEffect = "move"
    e.dataTransfer.setData("siswa", JSON.stringify(siswa))
}

const onDragOverPeserta = (e) => {
    if(!e.target.classList.contains("table-peserta")) return;
    let dropzone = e.target
    dropzone.style.background = "pink"
}

const onDroppedOverPeserta = (e) => {
    alert('hi')
    let dropzone = document.querySelector(".table-peserta")
    dropzone.style.background = "transparent"
    let data = JSON.parse(e.dataTransfer.getData("siswa"))
    console.log(data)
    pesertas.value.push(data)
    let i = siswas.value.length
    while (i--) {
        if(siswas.value[i].id == data.id) siswas.value.splice(i,1)
    }
}

const masukkan = async(siswa, index) => {
    await axios.post(route('dashboard.ekskul.peserta.masukkan', {id_ekskul: props.ekskul.id, id_siswa: siswa.id}))
                .then(res => {
                    pesertas.value.push(siswa)
                    siswas.value.splice(index, 1)
                }).catch(err => {
                    console.log(err)
                })
}
const keluarkan = async(peserta, index) => {
    await axios.post(route('dashboard.ekskul.peserta.keluarkan', {id_ekskul: props.ekskul.id, id_siswa: peserta.id}))
                .then(res => {
                    siswas.value.unshift(peserta)
                    pesertas.value.splice(index, 1)
                }).catch(err => {
                    console.log(err)
                })
   
}

onMounted(() => {
    list()
    pesertas.value = props.ekskul.pesertas
})
</script>

<template>
    <div class="flex gap-3 px-3">
        <div class="table-peserta w-full dropzone bg-white" dropzone="move" @dragover="onDragOverPeserta" @drop.prevent="onDroppedOverPeserta">
            <input type="text" placeholder="Cari Peserta" v-model="searchPeserta" class="my-2 py-1 bg-slate-200 rounded border-none" />
            <div class="dropzone-peserta hidden w-auto rounded bg-gray-100 h-[200px] items-center justify-center m-2" dropzone="move">
                <h1 class="text-xl text-gray-600 font-bold animate-pulse">Taruh Disini</h1>
            </div>
            <table class="border w-full table-peserta">
                <thead>
                    <tr>
                        <th class="border">NISN</th>
                        <th class="border">Nama</th>
                        <th class="border">Kelas</th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                        v-for="(siswa, s) in pesertaFiltered" :key="siswa.id" class="odd:bg-slate-100">
                        <div class="flex gap-2">
                                <button @click="keluarkan(siswa, s)">
                                    <Icon icon="mdi:chevron-double-right" class="text-red-800 text-xl" />
                                </button>
                                {{ siswa.nisn }}
                            </div>
                        <td class="border px-2">{{ siswa.nama }}</td>
                        <td class="border px-2">{{ siswa.rombel }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="table-siswa w-full bg-white">
            <input type="text" placeholder="Cari Siswa" v-model="searchSiswa" class="my-2 py-1 bg-slate-200 rounded border-none" />
            <table class="border w-full table-siswa">
                <thead>
                    <tr>
                        <th class="border">NISN</th>
                        <th class="border">Nama</th>
                        <th class="border">Kelas</th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                        v-for="(siswa, s) in siswasFiltered" :key="siswa.id" 
                            class="odd:bg-slate-100 cursor-grab"
                            @dragstart="startDragSiswa($event, siswa)"
                            draggable="true"
                            
                        >
                        <td class="border px-2">
                            <div class="flex gap-2">
                                <button @click="masukkan(siswa, s)">
                                    <Icon icon="mdi:chevron-double-left" class="text-teal-800 text-xl" />
                                </button>
                                {{ siswa.nisn }}
                            </div>
                        </td>
                        <td class="border px-2">{{ siswa.nama }}</td>
                        <td class="border px-2">{{ siswa.rombel.length > 0 ? siswa.rombel[0].label : '-' }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>