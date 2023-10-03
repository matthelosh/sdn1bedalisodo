<script setup>
import { read, utils } from 'xlsx';
import EasyDataTable from 'vue3-easy-data-table';
import { Icon } from '@iconify/vue';
import { ref, computed, defineAsyncComponent, onMounted } from 'vue';
import axios from 'axios';

const props = defineProps({rombel: Object, mapel: String});
const emit = defineEmits(['close'])

const AlertBox = defineAsyncComponent(() => import('@/Components/General/Alert.vue'))
const ConfirmDialog = defineAsyncComponent(() => import('@/Components/General/ConfirmDialog.vue'))
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
const cp = ref({})

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

const tambah = () => {
    formCp.value = true
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

const headers = ref([
    { text: 'Kode', value: 'kode' },
    { text: 'Fase', value: 'fase' },
    { text: 'Tingkat', value: 'tingkat' },
    { text: 'Elemen', value: 'elemen' },
    { text: 'Teks', value: 'teks', width: 600 },
    { text: 'Opsi', value: 'opsi' },
]);

const formCp = ref(false)

const listCp = async() => {
    await axios.post(route('dashboard.intra.cp.index', {
        _query: {
            tingkat: props.rombel.tingkat,
            mapel_id: props.mapel
        }
        }))
        .then(res => {
            let items = []
            res.data.cps.forEach((item,index) => {
                item.no = index+1
                items.push(item)
            })

            cps.value = items
        })
}

const edit = (item) => {
    cp.value = item
    formCp.value = true
}

const closeForm = () => {
    formCp.value = false
    cp.value = {}
}

const simpan = async() => {
    let data = cp.value
    data.mapel_id = props.mapel
    await axios.post(route('dashboard.intra.cp.store'), data)
            .then(res => {
                listCp()
            }).catch(err => {
                console.log(err)
            })
}

const confirmDialog = ref(null)
const hapus = async(item) => {
    if (await(confirmDialog.value.open(`Yakin ingin menghapus CP ${item.kode}`))) {
        axios.delete(route('dashboard.intra.cp.destroy', {
            id: item.id
        }))
            .then(res => {
                listCp()
            })
    }
}

onMounted(() => {
    listCp()
})
</script>

<template>
    <div class="w-full bg-slate-100 bg-opacity-80 backdrop-blur-sm fixed top-0 right-0 bottom-0 left-0 flex items-center justify-center z-40">
    <div class="dialog bg-white rounded shadow-lg w-[95vw] border border-slate-200 relative">
        <div class="toolbar h-10 flex items-center justify-between w-full p-4 bg-slate-500 shadow-lg">
            <h1 class="font-bold text-lg text-white">
                Formulir Capaian Pembelajaran {{ mapel?.toUpperCase() }} {{ rombel?.label }} / Tingkat {{ rombel?.tingkat }}
            </h1>
            <div class="toolbar-items flex items-center gap-1 justify-end">
                <button class="mr-4 bg-sky-50 shadow px-2 py-1 rounded text-teal-900 hover:text-teal-800 active:text-teal-600 flex items-center hover:bg-sky-100 active:bg-sky-200" @click="tambah">
                    <Icon icon="mdi:plus-circle" />
                    Tambah
                </button>
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
                    <Icon icon="mdi:close-box" class="text-2xl text-red-100 hover:text-red-400 active:text-red-200" />
                </button>
            </div>
        </div>
        <div class="content w-full bg-slate-100 p-3 h-full md:h-[90vh] overflow-y-auto overflow-auto max-h-[90vh]">
            <div class="items relative h-[87vh]">
                <EasyDataTable :headers="headers" :items="cps">
                    <template #item-kode="item">
                        <button class="text-teal-600 font-bold tracking-wide hover:underline" @click="edit(item)">{{ item.kode }}</button>
                    </template>
                    <template #item-opsi="item">
                        <button class="text-red-400 hover:text-red-600 active:text-red-500" @click="hapus(item)">
                            <Icon icon="mdi:delete" class="text-2xl hover:drop-shadow-lg hover:-translate-y-1 transition-all" />
                        </button>
                    </template>
                </EasyDataTable>
                <form ref="formCp" @submit.prevent="simpan" class="absolute top[50%] left-[50%] -translate-x-[50%] -translate-y-[50%] z-10 bg-slate-50 rounded p-4 shadow-xl w-[500px] transition-all" v-if="formCp">
                    <fieldset class="flex gap-2  flex-wrap">
                        <div class="col flex flex-col w-[68.5%]">
                            <label for="kode">Kode</label>
                            <input type="text" placeholder="Kode CP" v-model="cp.kode" >
                        </div>
                        <div class="col flex flex-col">
                            <label for="fase">Fase</label>
                            <select value="cp.fase" v-model="cp.fase">
                                <option v-for="fase in ['A','B','C']" :value="fase">{{ fase }}</option>
                            </select>
                        </div>
                        <div class="col flex flex-col">
                            <label for="tingkat">Tingkat</label>
                            <select v-model="cp.tingkat">
                                <option v-for="tingkat in 6" :value="tingkat">{{ tingkat }}</option>
                            </select>
                        </div>
                        <div class="col flex flex-col w-full">
                            <label for="elemen">Elemen</label>
                            <input type="text" placeholder="Elemen" v-model="cp.elemen">
                        </div>
                        <div class="col flex flex-col w-full">
                            <label for="teks">Teks</label>
                            <textarea placeholder="Teks Capaian Pembelajaran" rows="4" v-model="cp.teks" ></textarea>
                        </div>
                        <div class="col flex  justify-between items-center w-full ">
                            <button class="bg-orange-400 hover:bg-orange-600 active:bg-orange-500 text-white py-2 px-4 rounded"
                                @click="closeForm"
                            >
                                Batal
                            </button>
                            <button class="bg-sky-400 hover:bg-sky-600 active:bg-sky-500 text-white p-2 rounded">Simpan</button>
                        </div>
                    </fieldset>
                </form>
            </div>
        </div>
    </div>
</div>

<ConfirmDialog ref="confirmDialog" />
<AlertBox ref="alertBox" />
</template>