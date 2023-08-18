<script setup>
import axios from 'axios';
import { ref, onMounted } from 'vue';
import { Icon } from '@iconify/vue';

const tapels = ref([])

const list = async() => {
    await axios.post(route('dashboard.tapel.index'))
                .then(res => {
                    tapels.value = res.data.tapels
                })
}

const toggleStatus = async(tapel) => {
    await axios.post(route('dashboard.tapel.toggle'), {tapel: tapel})
        .then(res => list())
        .catch(cerr => console.log(err))
}

const showForm = ref(false)
const newTapel = () => {
    showForm.value = !showForm.value
}

const tapel = ref({})

const simpan = async() => {
    await axios.post(route('dashboard.setting.tapel.store'), {tapel: JSON.stringify(tapel.value)})
                .then(res => {
                    showForm.value = false
                    tapel.value = {}
                    list()
                }).catch(err => console.log(err))
}

onMounted(() => {
    list()
})

</script>

<template>
<div class="wrapper w-full bg-white rounded shadow hover:shadow-lg">
    <div class="toolbar h-10 bg-slate-400 flex items-center justify-between p-3">
        <h1 class="text-white font-bold">Tahun Pelajaran</h1>
        <button class="shadow rounded-full" @click="newTapel">
            <Icon icon="mdi:plus-circle" class=" text-xl transition" :class="showForm ? 'rotate-45 text-red-100 hover:text-red-300' : 'rotate-0 text-white hover:text-sky-400'" />
        </button>
    </div>
    <div class="form my-2 bg-slate-300 p-2 transition" v-if="showForm">
        <form class="form-tapel" @submit.prevent="simpan">
            <label for="kode" class="my-2 w-full flex items-center justify-between">
                Kode
                <input type="text" placeholder="Kode Tapel" class="py-0 rounded-sm border-none bg-slate-100 shadow-inner" v-model="tapel.kode" required />
            </label>
            <label for="label" class="my-2 w-full flex items-center justify-between">
                Label
                <input type="text" placeholder="Label Tapel" class="py-0 rounded-sm border-none bg-slate-100 shadow-inner" v-model="tapel.label" required />
            </label>
            <label for="submit" class="my-2 w-full flex items-center justify-center">
                <button class="bg-sky-400 px-2 rounded-sm text-white shadow hover:bg-sky-500">
                    Simpan
                </button>
            </label>
        </form>
    </div>
    <table class="border w-full">
        <thead>
            <tr>
                <th class="border">Tapel</th>
                <th class="border">Status</th>
            </tr>
        </thead>
        <tbody>
            <tr 
                v-for="tapel in tapels"
                :key="tapel.id"
            >
                <td class="border text-center">{{ tapel.kode }}</td>
                <td class="border text-center">
                    <button class="py-0 px-2 text-sm rounded shadow-sm text-white" :class="tapel.status == '0' ? 'bg-red-400' : 'bg-sky-400'" @click="toggleStatus(tapel)">
                        {{ tapel.status == '1' ? 'Aktif' : 'Non Aktif' }}
                    </button>
                </td>
            </tr>
        </tbody>
    </table>
</div>
</template>