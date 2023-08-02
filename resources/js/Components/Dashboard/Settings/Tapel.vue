<script setup>
import axios from 'axios';
import { ref, onMounted } from 'vue';

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

onMounted(() => {
    list()
})

</script>

<template>
<div class="wrapper w-full bg-white p-2 rounded shadow">
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