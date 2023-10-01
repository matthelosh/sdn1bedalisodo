<script setup>
import { read, utils } from 'xlsx';

import { Icon } from '@iconify/vue';
import { ref, computed, defineAsyncComponent } from 'vue';
import axios from 'axios';

const props = defineProps({rombel: Object, mapel: String});
const emit = defineEmits(['close'])

const AlertBox = defineAsyncComponent(() => import('@/Components/General/Alert.vue'))
const alertBox = ref(null);

const loading = ref(false)

const close = ()=>{
    emit('close')
}

const items = computed(() => {
    return [...cps.value, ...newCps.value]
});
const cps = ref([]);
const newCps = ref([]);

const onFilePicked = async(e) => {
    let file = e.target.files[0];

    let reader = new FileReader();

    reader.onload = async(ev) => {
        let wb = await read(ev.target.result);
        let datas = await utils.sheet_to_json(wb.Sheets[wb.SheetNames[0]])
        newCps.value = datas
                        .filter(data => data.tingkat == props.rombel.tingkat)
        // console.log(datas)
    }

    reader.readAsArrayBuffer(file);
}

const kirim = async() => {
    loading.value = true
    await axios.post(route('dashboard.intra.cp.impor'), {cps: JSON.stringify(newCps.value)})
                .then(res => {
                    loading.value = false
                    newCps.value = []
                    alertBox.value.open(res.data.msg, 'lime')
                    // emit('close')
                }).catch(err => {
                    loading.value = false
                    alertBox.value.open(err.response.data.msg, 'red')
                    console.log(err)
                })
}
</script>

<template>
    <div class="w-full bg-slate-100 bg-opacity-80 backdrop-blur-sm fixed top-0 right-0 bottom-0 left-0 flex items-center justify-center z-40">
    <div class="dialog bg-white rounded shadow-lg w-[95vw] border border-slate-200">
        <div class="toolbar h-10 flex items-center justify-between w-full p-2 bg-slate-300 shadow-lg">
            <h1 class="font-bold text-lg text-slate-800">
                Formulir Capaian Pembelajaran {{ mapel?.toUpperCase() }} {{ rombel?.label }} {{ rombel.tingkat }}
            </h1>
            <div class="toolbar-items flex items-center gap-2 justify-end">
                <input type="file" ref="fileCp" class="hidden" @change="onFilePicked" accept=".xls, .xlsx, .ods" />
                <button v-if="newCps.length < 1" class="mr-4 bg-sky-50 shadow px-2 py-1 rounded text-teal-900 hover:text-teal-800 active:text-teal-600 flex items-center" @click="$refs.fileCp.click()">
                    <Icon icon="mdi:file-excel" />
                    Impor
                </button>
                <button v-if="newCps.length > 0" class="mr-4 bg-sky-50 shadow px-2 py-1 rounded text-sky-900 hover:text-sky-800 active:text-sky-600 flex items-center" @click="kirim">
                    <Icon icon="mdi:loading" v-if="loading" class="animate-spin" />
                    <Icon icon="mdi:send" v-else />
                    Kirim
                </button>
                <button @click="close" class="flex items-center">
                    <Icon icon="mdi:close-box" class="text-2xl text-red-600 hover:text-red-400 active:text-red-800" />
                </button>
            </div>
        </div>
        <div class="content w-full bg-slate-100 p-3 h-full md:h-[90vh] overflow-y-auto overflow-auto max-h-[90vh]">
            <div class="items relative h-[100vh]">
                <table class="w-full bg-white">
                    <thead>
                        <tr>
                            <th class="py-2 px-3 border">No</th>
                            <th class="py-2 px-3 border">Kode</th>
                            <th class="py-2 px-3 border">Fase</th>
                            <th class="py-2 px-3 border">Tingkat</th>
                            <th class="py-2 px-3 border">Mapel</th>
                            <th class="py-2 px-3 border">Elemen</th>
                            <th class="py-2 px-3 border">Teks</th>
                            <th class="py-2 px-3 border">Opsi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            class="odd:bg-slate-100"
                            v-for="(item, i) in items"
                            :key="i"
                        >
                            <td class="border px-1 text-center">{{ i+1 }}</td>
                            <td class="border px-1 text-center">{{ item.kode }}</td>
                            <td class="border px-1 text-center">{{ item.fase }}</td>
                            <td class="border px-1 text-center">{{ item.tingkat }}</td>
                            <td class="border px-1 text-center">{{ item.mapel_id }}</td>
                            <td class="border px-1 text-center">{{ item.elemen }}</td>
                            <td class="border px-1">{{ item.teks }}</td>
                            <td class="border px-1 text-center">Opsi</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<AlertBox ref="alertBox" />
</template>