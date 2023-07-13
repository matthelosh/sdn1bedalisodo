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
            popover: {
                label: item.description,
                visibility: 'hover'
            }
        })
    })

    return attrs
})

const date = ref(new Date())
</script>

<template>
    <div class="w-full bg-slate-950 py-8">
        <div class="container w-10/12 md:w-8/12 mx-auto">
            <div class="header w-full my-4">
                <h1 class="flex justify-center items-center gap-1 text-xl font-bold text-sky-100 tracking-wide">
                    <Icon icon="mdi:calendar-badge-outline" class="text-2xl text-sky-100" />
                    Agenda Sekolah
                </h1>
            </div>
            <div class="content grid grid-cols-4 md:flex justify-center  gap-2 md:gap-4 mt-6 mx-auto">
                <Calendar class="cols-span-4" :attributes="attributes" />
                <!-- <DatePicker v-model="date" /> -->
                <ol class="relative border-l border-gray-200 dark:border-gray-700 col-span-4 ">                  
                    <li class="ml-4 mb-8" v-for="(agenda, a) in agendas" :key="a">
                        <div 
                            class="absolute w-3 h-3 rounded-full mt-1.5 -left-1.5 border border-white dark:border-gray-900 dark:bg-gray-700"
                            :style="`background: ${agenda.color}`"
                            ></div>
                        <time class="mb-1 text-sm font-normal leading-none text-gray-400 dark:text-gray-500">{{ agenda.start }}</time>
                        <h3 class="text-lg font-semibold text-gray-100 dark:text-white">{{ agenda.description }}</h3>
                        <!-- <p class="text-base font-normal text-gray-500 dark:text-gray-400">{{agenda.description}}</p> -->
                    </li>
                </ol>
            </div>
            
            

        </div>
    </div>
</template>