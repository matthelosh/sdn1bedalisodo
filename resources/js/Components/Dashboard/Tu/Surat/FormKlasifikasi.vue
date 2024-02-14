<script setup>
import { ref, computed, defineAsyncComponent} from 'vue';
import { useForm } from '@inertiajs/vue3';
import { Icon } from '@iconify/vue';
import { read, utils} from 'xlsx';
import {paginate} from '@/Plugins/misc';
import axios from 'axios';

const Loading = defineAsyncComponent(() => import('@/Components/General/Loading.vue'))
const loading = ref(false)

const props = defineProps({
    show: Boolean
})

const emit = defineEmits(['close'])

const form = useForm({
    kode: '',
    uraian: ''
})

const search = ref('')

const items = ref([])
const current = ref(1)
const pages = computed(() => {
    let query = new RegExp(search.value.toLowerCase(), "gi")
    let datas = search.value == "" 
                ? items.value
                : items.value.filter((item) => item.uraian.toLowerCase().match(query))
    // let datas = items.value.filter((item) => item.kode == '800')
    return paginate(datas, 10, current.value)
})

const fileName = ref(null)

const onFilePicked = async (e) => {
    let file = e.target.files[0]
    fileName.value = file.name
    let reader = new FileReader();

    reader.onload = async(e) => {
        let wb = await read(e.target.result)
        let datas = await utils.sheet_to_json(wb.Sheets[wb.SheetNames[0]])
        // let t = 'Kepegawaian'
        // let q = new RegExp(t.toLowerCase(), "gi")
        // console.log(datas.filter(data => data.uraian.toLowerCase().match(q)))
        let localItems = []
        await datas.forEach((item,index) => {
            item.no = index+1
            localItems.push(item)
        })
        items.value = localItems
    }
    reader.readAsArrayBuffer(file)
}

const impor = async() => {
    loading.value = true
    await axios.post(route('dashboard.tu.klasifikasi_surat.impor'), {datas: JSON.stringify(items.value)})
                .then(res => {
                    emit('close')
                    loading.value = false
                }).catch(err => console.log(err))
}
</script>

<template>

<div
    class="fixed top-0 right-0 bottom-0 left-0 bg-slate-800 bg-opacity-60 flex items-center justify-center backdrop-blur z-40"
    v-if="props.show"
    @click.self="emit('close')"
>
    <div class="flex items-start gap-2">
        <div class="dialog-box bg-white w-full shadow mx-auto">
            <div class="dialog-title bg-slate-400 p-2 text-white flex items-center justify-between">
                <h1>Form Klasifikasi</h1>
                <input type="file" ref="fileKlasifikasi" class="hidden" accept=".xls, .xlsx, .ods" @change="onFilePicked">
                {{ fileName }}
                <button class="text-white" @click="$refs.fileKlasifikasi.click()">
                    <Icon icon="mdi:file-excel" class="text-gray-50" />
                </button>
            </div>
            <div class="dialog-content p-3">
                <form ref="form" class="relative">
                    <label for="kode" class="w-full flex justify-between items-center">
                        Kode Klasifikasi
                        <input type="text" placeholder="Kode" class="rounded border-0 bg-gray-100 w-[60%]" />
                    </label>
                    <label for="uraian" class="w-full">
                        Uraian
                        <textarea placeholder="Uraian" class="rounded border-0 bg-gray-100 w-full"></textarea>
                    </label>
                    <button class="mx-auto py-1 px-3 rounded bg-gray-600 text-white">
                        Simpan
                    </button>
                </form>
            </div>
        </div>

        <div class="w-full bg-white pt-0 p-2 mx-auto">
            <div class="toolbar flex items-center justify-between h-12">
                <h1>Data Klasifikasi Surat</h1>
                <div class="cari relative">
                    <input type="text" placeholder="Cari" class="py-1 rounded border-0 bg-slate-200" v-model="search">
                    <Icon icon="mdi:magnify" class="absolute top-[50%] -translate-y-[50%] right-0" />
                </div>
            </div>
            
            <table class="border w-full">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode</th>
                        <th>Uraian</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(item,i) in pages.current" :key="i" class="odd:bg-slate-100">
                        <td>{{ item.no }}</td>
                        <td>{{ item.kode }}</td>
                        <td>{{ item.uraian }}</td>
                    </tr>
                </tbody>
            </table>
            <div class="w-full flex justify-end">
                <button class="bg-slate-600 py-1 px-2 rounded text-white" @click="impor">
                    Simpan
                </button>
            </div>
        </div>
    </div>
</div>
<Loading v-if="loading" />
</template>