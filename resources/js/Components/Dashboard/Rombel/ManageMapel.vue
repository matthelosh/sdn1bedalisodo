<script setup>
import { ref, onMounted } from 'vue';
import { Icon } from '@iconify/vue';
import axios from 'axios';

const emit = defineEmits(['close']);
const props = defineProps({rombel: Object})

const loading = ref(false)

const mapels = ref([])

const excludedMapels = ref([])
const listExcluded = async() => {
    loading.value = true
    await axios.post(route('dashboard.mapel.excluded'), {rombel_id: props.rombel.id, kurikulum: props.rombel.kurikulum})
                .then(res => {
                    excludedMapels.value = res.data.mapels
                    loading.value = false
                }).catch(err => {
                    loading.value = false
                    console.log(err)
                })
}

// Drag Excluded to Included

const startDragExcluded = ($event, item) => {
    $event.dataTransfer.setData("mapel", JSON.stringify(item))
    console.log($event)
}

const onDragoverMapel = (e) => {
    e.preventDefault()
    if(!e.target.hasAttribute('dropzone')) return;
    e.target.style.background = "pink!important"
}

const onExcludedDropped = (e) => {
    e.preventDefault()
    let data = JSON.parse(e.dataTransfer.getData("mapel"))
    mapels.value.push(data)
    masukkan(data)
    
}

const masukkan = async(item) => {
    await axios.post(route('dashboard.rombel.mapel.masukkan', {id: props.rombel.id}), {mapel_id: item.id})
                .then(res => {
                    let i = excludedMapels.value.length
                    while (i--) {
                        if(excludedMapels.value[i].id == item.id) {
                            excludedMapels.value.splice(i,1)
                        }
                    }
                }).catch(err => {
                    console.log(err)
                })
}

// Drag Included to Excluded
const startDragMapel = (e, item) => {
    e.dataTransfer.setData("mapel", JSON.stringify(item))
    let dropzone = document.querySelector(".right")
    dropzone.setAttribute("dropzone", "move")
    dropzone.style.background = 'black!important'
}


const onDragoverExcluded = (e) => {
    e.preventDefault()
    if(!e.target.hasAttribute('dropzone')) return;
    e.target.style.background = "pink!important"
}

const onMapelDropped = (e) => {
    e.preventDefault()
    let data = JSON.parse(e.dataTransfer.getData("mapel"))
    excludedMapels.value.push(data)
    keluarkan(data)
}

const keluarkan = async(item) => {
    await axios.post(route('dashboard.rombel.mapel.keluarkan', {id: props.rombel.id}), {mapel_id: item.id})
                .then(res => {
                    let i = mapels.value.length
                    while (i--) {
                        if(mapels.value[i].id == item.id) {
                            mapels.value.splice(i,1)
                        }
                    }
                }).catch(err => {
                    console.log(err)
                })
}


onMounted(() => {
    mapels.value = props.rombel.mapels
    listExcluded()
})
</script>

<template>
    <div class="wrapper w-full">
        <div class="toolbar h-10 bg-slate-50 shadow flex items-center justify-between p-3 sticky top-0 z-40">
            <h1>Atur Mata Pelajaran | Kelas {{ props.rombel.label }} | Kurikulum {{ props.rombel.kurikulum }}</h1>

            <div class="toolbar-items flex items-center gap-1 justify-end">
                <button class="bg-red-500 hover:bg-red-600 active:bg-red-400 p-1 rounded text-white" @click="emit('close')">
                    <Icon icon="mdi:close" />
                </button>
            </div>
        </div>
        <div class="content w-full p-3 bg-white grid grid-cols-2 gap-3">
            <div class="left bg-slate-100 p-3" dropzone="move" @dragover="onDragoverMapel" @drop="onExcludedDropped">
                <h1>Data Mata Pelajaran Kelas {{ props.rombel.label }}</h1>
                <table class="w-full border">
                    <thead>
                        <tr class="bg-slate-200">
                            <th class="border border-white py-2">No</th>
                            <th class="border border-white py-2">Label</th>
                            <th class="border border-white py-2">Kurikulum</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="(mapel, index) in mapels" :key="mapel.id"
                            class="odd:bg-white even:bg-slate-50 cursor-grab"
                            draggable="true"
                            @dragstart="startDragMapel($event, mapel)"
                        >
                            <td class="py-1 px-2 border  text-center">{{ index+1 }}</td>
                            <td class="py-1 px-2 border  text-left">{{ mapel.label }}</td>
                            <td class="py-1 px-2 border  text-center">{{ mapel.kurikulum }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="right bg-slate-100 p-3" @dragover="onDragoverExcluded" @drop="onMapelDropped">
                <h1 class="font-bold mb-2">Mapel Yang Belum Masuk Kelas {{  props.rombel.label }}</h1>
                <table class="w-full border">
                    <thead>
                        <tr class="bg-slate-200">
                            <th class="border border-white py-2">No</th>
                            <th class="border border-white py-2">Label</th>
                            <th class="border border-white py-2">Kurikulum</th>
                            <th class="border border-white py-2">Tingkat</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="(mapel, index) in excludedMapels" :key="mapel.id"
                            class="odd:bg-white even:bg-slate-50 cursor-grab"
                            draggable="true"
                            @dragstart="startDragExcluded($event, mapel)"
                        >
                            <td class="py-1 px-2 border  text-center">{{ index+1 }}</td>
                            <td class="py-1 px-2 border  text-left">{{ mapel.label }}</td>
                            <td class="py-1 px-2 border  text-center">{{ mapel.kurikulum }}</td>
                            <td class="py-1 px-2 border  text-center">{{ mapel.tingkat }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>