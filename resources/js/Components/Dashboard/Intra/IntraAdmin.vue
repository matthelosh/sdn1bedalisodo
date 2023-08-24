<script setup>
import { ref, onMounted, defineAsyncComponent } from 'vue';

import { Icon } from '@iconify/vue';
import axios from 'axios';

const loading = ref(false)
const Loading = defineAsyncComponent(() => import('@/Components/General/Loading.vue'))

const confirmDialog = ref(null)
const ConfirmDialog = defineAsyncComponent(() => import('@/Components/General/ConfirmDialog.vue'))

const mapel = ref({
    tingkat: 'all',
    kurikulum: 'all'
})
const mapels = ref([])
const formMapel = ref(false)

const showForm = () => {
    formMapel.value = true
}

const close = () => {
    formMapel.value = false
    mapel.value = {
        tingkat: 'all',
        kurikulum: 'all'
    }
}

const edit = (item) => {
    mapel.value = item
    formMapel.value = true
}

const simpan = async() => {
    loading.value= true
    await axios.post(route('dashboard.mapel.store'), {mapel: JSON.stringify(mapel.value)})
                .then(res => {
                    loading.value = false
                    listMapels()
                    close()
                }).catch(err => {
                    loading.value = false
                })
}

const listMapels = async() => {
    loading.value = true
    await axios.post(route('dashboard.mapel.index'))
                .then(res => {
                    mapels.value = res.data.mapels
                    loading.value = false
                })
}

const hapus = async(item) => {
    if( await confirmDialog.value.open("Yakin menghapus Mapel "+item.label+"?")) {
        loading.value = true
        await axios.delete(route('dashboard.mapel.destroy', {id: item.id}))
                    .then(res => {
                        loading.value = false
                        listMapels()
                    }).catch(err => {
                        loading.value = false
                        console.log(err)
                    })
    }
}

onMounted(() => {
    listMapels()
})
</script>

<template>

    <div class="wrapper w-full max-w-screen">
        <div class="toolbar bg-slate-200 sticky top-0 z-40 p-3 h-10 flex items-center justify-between">
            <h1>Intrakurikuler</h1>

            <div class="toolbar-items flex items-center gap-1">
                <button class="flex items-center text-sm hover:text-sky-600" @click="showForm">
                    <Icon icon="mdi:book" class="text-slate-600" />
                    Tambah Mapel
                </button>
            </div>
        </div>
        <div class="content w-full bg-white p-3">
            <div class="table w-full max-w-screen overflow-x-auto">
                <table class="w-full border">
                    <thead>
                        <tr>
                            <th class="border p-2">No</th>
                            <th class="border p-2">Kode</th>
                            <th class="border p-2">Label</th>
                            <th class="border p-2">Tingkat</th>
                            <th class="border p-2">Kurikulum</th>
                            <th class="border p-2">Kelompok</th>
                            <th class="border p-2">Kategori</th>
                            <th class="border p-2">Opsi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="(mapel, index) in mapels" :key="mapel.id"
                            class="odd:bg-slate-100 even:bg-white"
                        >
                            <td class="py-1 px-3 border text-center">{{ index+1 }}</td>
                            <td class="py-1 px-3 border text-center">{{ mapel.kode }}</td>
                            <td class="py-1 px-3 border text-left">
                                <button class="hover:text-teal-600 hover:underline text-teal-800" @click="edit(mapel)">
                                    {{ mapel.label }}
                                </button>
                            </td>
                            <td class="py-1 px-3 border text-center">{{ mapel.tingkat }}</td>
                            <td class="py-1 px-3 border text-center">{{ mapel.tingkat }}</td>
                            <td class="py-1 px-3 border text-center">{{ mapel.kurikulum }}</td>
                            <td class="py-1 px-3 border text-center">{{ mapel.kelompok }}</td>
                            <td class="py-1 px-3 border text-center">{{ mapel.kategori }}</td>
                            <td class="py-1 px-3 border text-center">
                                <div class="flex items-center gap-1 justify-center">
                                    <button @click="hapus(mapel)">
                                        <Icon icon="mdi:delete" class="text-red-600 hover:text-red-400 text-xl" />
                                    </button>
                                </div>
                            </td>

                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="w-full bg-slate-100 bg-opacity-80 backdrop-blur-sm fixed top-0 right-0 bottom-0 left-0 flex items-center justify-center z-40" @click.self="close" v-if="formMapel">
        <div class="dialog bg-white rounded shadow-lg w-[400px] max-w-[800px]">
            <div class="toolbar h-10 flex items-center justify-between w-full p-2 bg-slate-200">
                <h1>Formulir Mapel</h1>
                <div class="toolbar-items flex items-center gap-2 justify-end">
                    <button @click="close" class="flex items-center">
                        <Icon icon="mdi:close-box" class="text-2xl text-red-600 hover:text-red-400 active:text-red-800" />
                    </button>
                </div>
            </div>
            <div class="content p-2 bg-slate-50">
                <form ref="form" @submit.prevent="simpan">
                <fieldset :disabled="loading">
                    <label for="kode" class="w-full flex items-center justify-between my-2">
                        <span>Kode</span>
                        <input type="text" placeholder="Kode Mapel" class="border-none bg-slate-200 py-1" v-model="mapel.kode">
                    </label>
                    <label for="label" class="w-full flex items-center justify-between my-2">
                        <span>Label</span>
                        <input type="text" placeholder="Kode Mapel" class="border-none bg-slate-200 py-1" v-model="mapel.label">
                    </label>
                    <label for="tingkat" class="w-full flex items-center justify-between my-2">
                        <span>Tingkat/Kelas</span>
                        <select class="border-none bg-slate-200 py-1" v-model="mapel.tingkat">
                            <option 
                                v-for="i in ['all', 'besar']" :key="i"
                                :value="i">
                                {{ i == 'all' ? 'Semua kelas' : ('Kelas '+i) }}
                            </option>
                        </select>
                    </label>
                    <label for="kurikulum" class="w-full flex items-center justify-between my-2">
                        <span>Kurikulum</span>
                        <select class="border-none bg-slate-200 py-1" v-model="mapel.kurikulum">
                            <option 
                                v-for="k in ['all', 'merdeka', 'k13']" :key="k"
                                :value="k"> {{ k == 'all' ? 'Semua Kurikulum' : k }}</option>
                        </select>
                    </label>
                    <label for="kelompok" class="w-full flex items-center justify-between my-2">
                        <span>Kelompok</span>
                        <select class="border-none bg-slate-200 py-1" v-model="mapel.kelompok">
                            <option 
                                v-for="group in ['Kelompok A', 'Kelompok B']" :key="group"
                                :value="group"> {{ group }}</option>
                        </select>
                    </label>
                    <label for="kategori" class="w-full flex items-center justify-between my-2">
                        <span>Kategori</span>
                        <select class="border-none bg-slate-200 py-1" v-model="mapel.kategori">
                            <option 
                                v-for="kategori in ['Wajib', 'Mulok', 'Pilihan']" :key="group"
                                :value="kategori"> {{ kategori }}</option>
                        </select>
                    </label>
                    <label for="submit" class="w-full flex items-center justify-center mt-4 mb-1">
                        <button type="submit" class="py-1 px-3 bg-sky-400 hover:bg-sky-600 text-white flex items-center gap-1">
                            <Icon icon="mdi:loading" class="animate-spin" v-if="loading" />
                            Simpan
                        </button>
                    </label>
                </fieldset>
            </form>
            </div>
        </div>
    </div>
    <Loading v-if="loading" />
    <ConfirmDialog ref="confirmDialog" />
</template>