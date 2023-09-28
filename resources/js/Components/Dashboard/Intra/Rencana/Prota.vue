<script setup>
import { ref, computed, onMounted, defineAsyncComponent} from 'vue';
import { Icon } from '@iconify/vue';
import axios from 'axios';
import EasyDataTable from 'vue3-easy-data-table';

const props = defineProps({
    rombel: Object, mapel: String
})

const FormProta = defineAsyncComponent(() => import('./Forms/FormProta.vue'))

const protas = ref([]);
const prota = ref(null)
const form = ref(false);
const closeForm = () => {
    form.value = false
    prota.value = null
    listProta()
}
const listProta = async() => {
    await axios.post(route('dashboard.prota.index', 
                    {
                        _query: {
                            rombel_id: props.rombel.kode,
                            mapel_id: props.mapel
                        }
                    }
                ))
                .then( res => {
                    protas.value = res.data.protas
                })
                .catch( err => {

                })
}

const headers = [
    {
        text: 'Alur', value: 'alur'
    },
    {
        text: 'Atp', value: 'atp'
    },
    {
        text: 'Bab', value: 'bab'
    },
    {
        text: 'Materi Pokok', value: 'pokok'
    },
    {
        text: 'JP', value: 'jp'
    },
    {
        text: 'Semester', value: 'semester'
    },
]

onMounted(() => {
    listProta()
})

</script>

<template>
    <div class="w-full">
        <div class="toolbar h-10 p-3 bg-slate-400 text-white flex items-center justify-between">
            <h1 class="flex items-center gap-1">
                <Icon icon="mdi:calendar-check" class="text-2xl" />
                Program Tahunan
            </h1>
            <div class="toolbar-items flex items-center gap-2">
                <button @click="form = true">
                    <Icon icon="mdi:plus-circle" class="text-2xl text-slate-100" />
                </button>
            </div>
        </div>
        <div class="content bg-white w-full p-3">
            <EasyDataTable :headers="headers" :items="protas">
                <template #item-atp="item">
                    {{item.atp.teks}}
                </template>
                <template #item-bab="item">
                    {{item.materi.bab}}
                </template>
                <template #item-pokok="item">
                    {{item.materi.pokok}}
                </template>
            </EasyDataTable>
        </div>
        <FormProta v-if="form" @close="closeForm" :rombel="rombel" :mapel="mapel" />
    </div>
    
</template>