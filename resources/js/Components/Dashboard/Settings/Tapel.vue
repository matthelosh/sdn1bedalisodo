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
                <td>{{ tapel.kode }}</td>
                <td>{{ tapel.status }}</td>
            </tr>
        </tbody>
    </table>
</div>
</template>