<script setup>
import { ref, onMounted, computed } from 'vue';
import axios from 'axios';
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

onMounted(() => {
    list()
    pesertas.value = props.ekskul.pesertas
})
</script>

<template>
    <div class="flex gap-3 px-3">
        <div class="table-peserta w-full">
            <input type="text" placeholder="Cari Peserta" v-model="searchPeserta" class="my-2 py-1 bg-slate-200 rounded border-none" />
            <table class="border w-full">
                <thead>
                    <tr>
                        <th class="border">NISN</th>
                        <th class="border">Nama</th>
                        <th class="border">Kelas</th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                        v-for="siswa in pesertaFiltered" :key="siswa.id" class="odd:bg-slate-100">
                        <td class="border px-2">{{ siswa.nisn }}</td>
                        <td class="border px-2">{{ siswa.nama }}</td>
                        <td class="border px-2">{{ siswa.rombel_id }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="table-siswa w-full bg-white">
            <input type="text" placeholder="Cari Siswa" v-model="searchSiswa" class="my-2 py-1 bg-slate-200 rounded border-none" />
            <table class="border w-full">
                <thead>
                    <tr>
                        <th class="border">NISN</th>
                        <th class="border">Nama</th>
                        <th class="border">Kelas</th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                        v-for="siswa in siswasFiltered" :key="siswa.id" class="odd:bg-slate-100">
                        <td class="border px-2">{{ siswa.nisn }}</td>
                        <td class="border px-2">{{ siswa.nama }}</td>
                        <td class="border px-2">{{ siswa.rombel }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>