<script setup>
import { ref, computed, defineAsyncComponent, onBeforeMount } from 'vue';
import { Head } from '@inertiajs/vue3';
import { Icon } from '@iconify/vue';
import axios from 'axios';
import Compressor from 'compressorjs'
import QrcodeVue from 'qrcode.vue';

const emit = defineEmits(['close'])
const mode = ref('list')
const headers = ref([
    { text: 'No Surat', value: 'kode' },
    { text: 'Penerima', value: 'penerima' },
    { text: 'Tanggal', value: 'tanggal' },
    { text: 'Perihal', value: 'perihal' },
    { text: 'Status', value: 'status' },
    { text: 'Tembusan', value: 'tembusan' },
    { text: 'Arsip', value: 'arsip' },
    { text: 'Opsi', value: 'opsi' },
])

const FormKlasifikasi = defineAsyncComponent(() => import ('./Surat/FormKlasifikasi.vue'))
const TulisSurat = defineAsyncComponent(() => import('./Surat/WriteSurat.vue'))

const search = ref(null);
const formKlasifikasi = ref(false)
const formArsip = ref(false)
const surats = ref([])
const items = computed(() => {
    return !search.value ? surats.value : surats.value.filter(surat => surat.perihal.toLowerCase().includes(search.value.toLowerCase()))
})

const fileArsip = ref(null)

const onfileArsipPicked = (e) => {
    let file = e.target.files[0]
    // console.log(file)
    let arsipHolder = document.querySelector(".arsip-holder")
    if (file.type.includes("image/") ) {
        let imgNode = document.createElement("img")
        if ( file.size > 2000000 ) {
            new Compressor(file, {
              quality: 0.2,
              convertTypes: 'image/webp',
              success(result) {
                imgNode.setAttribute("src", URL.createObjectURL(result))
                fileArsip.value = new File([result], result.name)
                
                
              }
            })
        } else {
            fileArsip.value = file
            imgNode.setAttribute("src", URL.createObjectURL(file))
        }
        arsipHolder.appendChild(imgNode)
    }else {
            fileArsip.value = file
            let pdfNode = document.createElement("object")
            pdfNode.setAttribute("type", "application/pdf")
            pdfNode.setAttribute("width", "100%")
            pdfNode.setAttribute("data", URL.createObjectURL(new Blob([file], {type: 'application/pdf'})))
            arsipHolder.appendChild(pdfNode)
        }
}

const selectedSurat = ref(null)

const showFormKlasifikasi = () => {
    formKlasifikasi.value = true
}
const closeMe = () => {
    emit('close')
}

const compose = async() => {
    mode.value = 'tulis-surat'
}

const init = async() => {
    await axios.post(route('surat.index'))
                .then(res => {
                    surats.value = res.data.surats
                }).catch(err => console.log(err))
}


const addArsip = (item) => {
    selectedSurat.value = item
    formArsip.value = true
}
const onFormArsipClose = () => {
    let arsipHolder = document.querySelector(".arsip-holder")
    let formFileArsip = document.querySelector("#formFileArsip")
    selectedSurat.value = null
    fileArsip.value = null
    arsipHolder.innerHTML = ""
    formFileArsip.reset()
}

const saveArsip = async() => {
    let fd = new FormData()
    fd.append('surat_id', selectedSurat.value.kode)
    fd.append("file_arsip", fileArsip.value)
    axios.post(route('surat.arsip.add'), fd)
            .then(res => {
                init()
            }).catch(err => console.log(err))
}

const edit = (item) => {
    selectedSurat.value = item
    mode.value = 'tulis-surat'
}

const closeCompose = () => {
    mode.value = 'list'
    selectedSurat.value = null
    init()
}

onBeforeMount(() => {
    init()
})
</script>

<template>
<div>
    <Head title="Surat Keluar"  />
    <FormKlasifikasi :show="formKlasifikasi" @close="formKlasifikasi = false" />
    <div v-if="mode=='list'">
        <div class="toolbar h-10 bg-gray-100 flex items-center justify-between sticky top-0 z-20 p-3">
            <h1 class="text-gray-800 flex gap-1 items-center">
                <Icon icon="mdi:mail" class="text-xl" />
                Surat
            </h1>
            <div class="toolbar-items flex items center justify-end gap-2">
                <button @click="showFormKlasifikasi" class="flex items-center hover:text-gray-600">
                    <Icon icon="mdi:list-box-outline" class="text-xl" />
                    <span>Klasifikasi Surat</span>
                </button>
                <button @click="compose" class="flex items-center hover:text-gray-600">
                    <Icon icon="mdi:typewriter" class="text-xl" />
                    <span>Tulis Surat</span>
                </button>
                <div class="search-box relative">
                    <input type="text" placeholder="Cari Surat" class="rounded py-1" v-model="search">
                    <Icon icon="mdi:magnify" class="text-xl absolute top-[50%] -translate-y-[50%] right-2 text-gray-400" />
                </div>
                <button @click="closeMe" class=" text-red-600 hover:text-red-400">
                    <Icon icon="mdi:close-box" class="text-2xl" />
                </button>
            </div>
        </div>
        <div class="content w-full p-3 bg-white ">
            <div class="table overflow-x-auto w-full h-[650px]" >
                <data-table :items="items" :headers="headers" show-index >
                    <template #item-kode="item">
                        <el-button text type="primary" class="text-sm" @click="edit(item)">{{item.kode}}</el-button>
                    </template>
                    <template #item-arsip="{arsip}">
                        <el-link :href="arsip.url" target="_blank" :underline="false" class="my-4" v-if="arsip">
                            <qrcode-vue :value="arsip.url" :size="50" level="L" :foreground="'#363636'" />
                        </el-link>
                    </template>
                    <template #item-opsi="item">
                        <el-button-group rounded>
                            <el-button size="small" type="primary" @click="addArsip(item)">
                                <Icon icon="mdi:archive" />
                            </el-button>
                            <el-popconfirm placement="bottom-end" title="Yakin menghapus surat ini?">
                                <template #reference>
                                    <el-button size="small"  type="danger">
                                        <Icon icon="mdi:delete" />
                                    </el-button>
                                </template>
                            </el-popconfirm>
                        </el-button-group>
                    </template>
                </data-table>
            </div>
            
        </div>
        
    </div>
    <TulisSurat class="w-full" v-if="mode == 'tulis-surat'" @close="closeCompose" :selectedSurat="selectedSurat" />
    <el-dialog v-model="formArsip" draggable width="900px" @close="onFormArsipClose">
        <template #header>
            <h3>Formulir Arsip Surat {{selectedSurat?.kode}}</h3>
        </template>
        <div class="dialog-body">
            <div class="flex justify-between mb-4">
                <form id="formFileArsip">
                    <input type="file" ref="inputArsip" @change="onfileArsipPicked" accept=".pdf, .jpg, .png, .jpeg, .JPG, .JPEG, .PNG, .PDF" />
                </form>
                <el-button type="primary" @click="saveArsip">Simpan</el-button>
            </div>
            <div class="arsip-holder"></div>
        </div>
    </el-dialog>
</div>
</template>