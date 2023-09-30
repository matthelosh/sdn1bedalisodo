<script setup>
import { ref, defineAsyncComponent, onMounted, computed } from 'vue';
import { Icon } from '@iconify/vue';
import axios from 'axios';
import EasyDataTable from 'vue3-easy-data-table';

const FormMateri = defineAsyncComponent(() => import('./Forms/FormMateri.vue'));
const props = defineProps({
    rombel: Object, mapel: String
});

const form = ref(false);
const showForm = () => {
    form.value = true
}

const closeForm = () => {
    form.value = false
    listMateri()
}

const materis = ref([])
const items = computed(() => {
    return materis.value.filter((item, index) => {
        item.no = index+1
        return item
    })
})
const listMateri = async() => {
    await axios.post(route('dashboard.materi.index', {
        _query: {
            tingkat: props.rombel.tingkat,
            mapel_id: props.mapel
        }
    }))
    .then(res => {
        materis.value = res.data.materis
    })
}

const headers = ref([
    { text: 'No.', value: 'no' },
    { text: 'Tingkat', value: 'tingkat' },
    { text: 'Bab', value: 'bab' },
    { text: 'Materi Pokok', value: 'pokok' },
    { text: 'Ringkasan', value: 'ringkasan' },
])

const selectedMateri = ref(null)
const edit = (item) => {
    selectedMateri.value = item
    showForm()
}

onMounted(() => {
    listMateri()
})
</script>

<template>
    <div class="wrapper w-full bg-white">
        <div class="toolbar h-10 flex items-center justify-between text-white bg-slate-400 p-3">
            <h1 class="flex items-center gap-1">
                <Icon icon="mdi:bookshelf" class="text-2xl" />
                Materi Pembelajaran {{ props.mapel.toUpperCase() }} Kelas {{ props.rombel.label }}
            </h1>
            <div class="toolbar-items flex gap-2">
                <button class="hover:text-slate-300 active:text-slate-100" @click="showForm">
                    <Icon icon="mdi:plus-circle" class="text-2xl " />
                </button>
            </div>
        </div>
        <div class="content p-3">
            <EasyDataTable :headers="headers" :items="items" :rows-per-page="10" >
                <template #item-no="item">
                    <div class="text-center w-full ">{{ item.no }}</div>
                </template>
                <template #item-tingkat="item">
                    <div class="text-center">{{ item.tingkat }}</div>
                </template>
                <template #item-bab="item">
                    <div class="text-teal-600 hover:underline hover:cursor-pointer" @click="edit(item)">{{ item.bab }}</div>
                </template>
            </EasyDataTable>
        </div>
        <FormMateri :rombel="props.rombel" :mapel="props.mapel" :selectedMateri="selectedMateri" @close="closeForm" v-if="form" />
    </div>
    
</template>