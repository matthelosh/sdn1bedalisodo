<script setup>
import { ref, onMounted, computed } from 'vue';
import { Icon } from '@iconify/vue';
import { Calendar, DatePicker } from 'v-calendar';
import 'v-calendar/style.css';
import axios from 'axios';

onMounted(() => {
    listAgendas()
})

const listAgendas = async() => {
    await axios.post(route('agenda.index')).then(res => {
        agendas.value = res.data.agendas.slice(-4)
    }).catch(err => console.log(err))
}

const agendas = ref([])

const attributes = computed(() => {
    let attrs = []
    agendas.value.forEach((item, index) => {
        attrs.push({
            key: item.id, 
            highlight: item.color, 
            dates: {start: new Date(item.start), end: new Date(item.end)},
            startDate: new Date(item.start),
            endDate: new Date(item.end),
            popover: {
                label: item.description,
                visibility: 'hover'
            },
            index: index,
            description: item.description
        })
    })

    return attrs
})

const date = ref(new Date())

const agenda = ref({
    id: '0'
})
const selectedAgendas = ref('Baru')

const formAgenda = ref(false)
const closeFormAgenda = () => {
    agenda.value = {id: '0'}
    selectedAgendas.value = 'Baru'
    formAgenda.value = false
}
const openFormAgenda = async (calendar, $event) => {
    // alert('hi')
    let items = []
    console.log(calendar.attributeCells)
    if(calendar.attributeCells.length > 0) {
        const cells = calendar.attributeCells
        await cells.forEach(cell => items.push({index: cell.data.index, label: cell.data.description}))
        selectedAgendas.value = items
    // console.log(items)
    }
    formAgenda.value = true
}

const selectAgenda = (e) => {
    agenda.value = agendas.value[e.target.value]
}
</script>

<template>
    <div class="w-full">
        <div class="container mx-auto bg-gray-400 p-3 rounded-lg shadow">
            <div class="header w-full">
                <h1 class="flex justify-center items-center gap-1 text-lg font-bold text-sky-800 tracking-wide">
                    <Icon icon="mdi:calendar-badge-outline" class="text-xl text-sky-800" />
                    Agenda Sekolah
                </h1>
            </div>
            <div class="content mx-auto flex justify-center p-2">
                <Calendar :attributes="attributes" @dayclick="openFormAgenda" timezone="Asia/Jakarta" />
            </div>
        </div>
        <div class="formAgenda fixed top-0 right-0 bottom-0 left-0 bg-gray-900 bg-opacity-80 flex items-center justify-center z-20 " @click.self="closeFormAgenda" v-if="formAgenda">
            <div class="dialog max-w-[400px] md:max-w-[600px] md:min-w-[400px] bg-white shadow-sky-100 shadow rounded">
                <h1 class="h-10 p-3 bg-slate-200 rounded-t">
                    <span v-if="selectedAgendas == 'Baru'">
                        Buat Agenda Baru
                    </span>
                    <span v-else class="h-full flex items-center justify-between gap-2">
                        Edit Agenda
                        <select v-model="agenda.id" @change="selectAgenda" class="h-8 rounded py-0">
                            <option value="0">Pilih Agenda</option>
                            <option
                                v-for="ag in selectedAgendas"
                                :key="ag.index"
                                :value="ag.index">{{ ag.label }}</option>
                        </select>
                    </span>
                </h1>
                <div class="p-3">
                    <!-- <div v-if="agenda.id !== '0'">
                        {{ agenda }}
                    </div> -->
                    <form @submit.prevent="simpan" class="formAgenda w-full">
                        <label for="name" class="w-full flex justify-between pb-1">
                            Nama
                            <input type="text" name="name" v-model="agenda.name" placeholder="Nama Kegiatan" class="py-1 rounded bg-gray-100 shadow" />
                        </label>
                        <label for="description" class="w-full flex justify-between py-1">
                            Deskripsi
                            <textarea type="text" name="name" v-model="agenda.description" placeholder="Deskripsi Kegiatan" class="py-1 rounded bg-gray-100 shadow" ></textarea>
                        </label>
                        <label for="mulai" class="w-full flex justify-between py-1">
                            Mulai
                            <input type="date" name="start" v-model="agenda.start"  class="py-1 rounded shadow bg-gray-100" />
                        </label>
                        <label for="selesai" class="w-full flex justify-between py-1">
                            Selesai
                            <input type="date" name="end" v-model="agenda.end"  class="py-1 rounded shadow bg-gray-100" />
                        </label>
                        <label for="location" class="w-full flex justify-between py-1">
                            Lokasi
                            <input type="text" name="location" v-model="agenda.location" placeholder="Lokasi" class="py-1 rounded bg-gray-100 shadow" />
                        </label>
                        <div class="flex items-center justify-between w-full mt-3">
                            <div></div>
                            <button class=" bg-sky-400 py-1 px-2 rounded text-white hover:bg-sky-600">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>