<script setup>
import { ref, computed, defineAsyncComponent, onBeforeMount } from 'vue';
import { Head } from '@inertiajs/vue3';
import { Icon } from '@iconify/vue';
import axios from 'axios';
import Compressor from 'compressorjs'
import QrcodeVue from 'qrcode.vue';
import { UploadFilled } from '@element-plus/icons-vue'

const emit = defineEmits(['close'])
const mode = ref('list')
const headers = ref([
    { text: 'Perihal', value: 'perihal', fixed: true, width:200 },
    { text: 'No Surat', value: 'kode', fixed:true, width: 300 },
    { text: 'Penerima/Pengirim', value: 'penerima' },
    { text: 'Tanggal', value: 'tanggal' },
    { text: 'Status', value: 'status' },
    { text: 'Tembusan', value: 'tembusan' },
    { text: 'Arsip', value: 'arsip' },
    { text: 'Opsi', value: 'opsi' },
])

const Agenda = defineAsyncComponent(() => import('@/Components/Dashboard/Tu/Surat/Agenda.vue'))
const FormKlasifikasi = defineAsyncComponent(() => import ('./Surat/FormKlasifikasi.vue'))
const TulisSurat = defineAsyncComponent(() => import('./Surat/WriteSurat.vue'))

const tujuan = ref('keluar')
const loading = ref(false)
const search = ref(null);
const formKlasifikasi = ref(false)
const formArsip = ref(false)
const surats = ref([])
const formInput = ref(false)


const klasifikasis = ref([])

const fileArsip = ref(null)

const surat = ref({})


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

const getKlasifikasi = async() => {
    await axios.post(route('dashboard.tu.klasifikasi_surat.index'))
                .then(res => {
                    let result = []
                    res.data.klasifikasis.forEach(item => {
                        result.push({label: item.uraian, value:item.kode})
                    })
                    klasifikasis.value = result
                })
}




const edit = (item) => {
    
    // console.log(item)
    selectedSurat.value = item
    mode.value = 'tulis-surat'
}

const closeCompose = () => {
    mode.value = 'list'
    selectedSurat.value = null
    init()
}

const categories = ref([
    {value: 'SkTugas', label: 'SK Pembagian Tugas'},
    {value: 'SkGtt', label: 'SK GTT dan PTT'},
    {value: 'Sppd', label: 'Perjalanan Dinas'},
    {value: 'SkKhusus', label: 'SK Khusus'},
    {value: 'Undangan', label: 'Surat Undangan'},
    {value: 'Lainnya', label: 'Lainnya'},
])

// Form Input data surat
const showFormInput = () => {
    formInput.value = true
}

const onInputClosed = () => {
    formInput.value = false
    surat.value = {}
}

const simpanSurat = async() => {
    loading.value = true
    let data = surat.value
    data.klasifikasi_id = data.kode.split("/")[0]
    data.no_surat = data.kode.split("/")[1]
    let fd = new FormData()
    fd.append("data", JSON.stringify(data))
    // console.log(fd)
    if ( fileArsip.value ) {
        fd.append("file_arsip", fileArsip.value)
    }

    await axios.post(route('surat.store'), fd)
                .then(res => {
                    loading.value = false
                    ElNotification({title: "Info", message: "Data Surat disimpan"})
                    formInput.value = false
                    init()
                }).catch(err => {
                    console.log(err)
                    loading.value = false
                })
}


onBeforeMount(() => {
    init()
    getKlasifikasi()
    
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
            <div class="toolbar-items flex items center justify-end gap-4">
                <button @click="showFormKlasifikasi" class="flex items-center hover:text-gray-600">
                    <Icon icon="mdi:list-box-outline" class="text-xl" />
                    <span>Klasifikasi Surat</span>
                </button>
                <button @click="showFormInput" class="flex items-center hover:text-gray-600">
                    <Icon icon="mdi:pencil-box" class="text-xl" />
                    <span>Input Data Surat</span>
                </button>
                <button @click="compose" class="flex items-center hover:text-gray-600">
                    <Icon icon="mdi:typewriter" class="text-xl" />
                    <span>Tulis Surat</span>
                </button>
                
                <button @click="closeMe" class=" text-red-600 hover:text-red-400">
                    <Icon icon="mdi:close-box" class="text-2xl" />
                </button>
            </div>
        </div>
        <div class="content w-full overflow-x-auto p-3 bg-white ">

            <Agenda :headers="headers" :items="surats" @edit="edit($event, item)" @reload="init()" />
        </div>
        
    </div>
    <TulisSurat class="w-full" v-if="mode == 'tulis-surat'" @close="closeCompose" :selectedSurat="selectedSurat"  />
    

    <el-dialog v-model="formInput" draggable @close="onInputClosed">
        <template #header>
            <div class="w-full flex justify-between items-center">
                <h3>Masukkan Data Surat</h3>
                <el-button type="primary" size="small" class="mr-4" :loading="loading" @click="simpanSurat">Simpan</el-button>
            </div>
        </template>
        <div class="dialog-body">
            <el-form v-model="surat" label-position="top">
                <el-row :gutter="20">
                    <el-col :span="8">
                        <el-form-item label="Jenis Surat">
                            <el-select v-model="surat.tujuan" placeholder="Jenis Surat">
                                <el-option value="keluar">Keluar</el-option>
                                <el-option value="masuk">Masuk</el-option>
                            </el-select>
                        </el-form-item>
                    </el-col>
                    <el-col :span="16">
                        <el-form-item label="No Surat">
                            <el-input placeholder="No Surat" v-model="surat.kode"></el-input>
                        </el-form-item>
                    </el-col>
                </el-row>
                <el-row :gutter="20">
                    <el-col :span="12">
                        <el-form-item label="Penerima / Pengirim">
                            <el-input placeholder="Penerima / Pengirim" v-model="surat.penerima"></el-input>
                        </el-form-item>
                    </el-col>
                    <el-col :span="12">
                        <el-form-item label="Kategori">
                            <el-select placeholder="Pilih Kategori Surat" v-model="surat.kategori">
                                <el-option v-for="category in categories" :key="category.value" :value="category.value">{{ category.label }}</el-option>
                            </el-select>
                        </el-form-item>
                    </el-col>
                </el-row>
                <el-row  :gutter="20">
                    <el-col :span="16">
                        <el-form-item label="Perihal">
                            <el-input placeholder="Perihal" v-model="surat.perihal"></el-input>
                        </el-form-item>
                    </el-col>
                    <el-col :span="8">
                        <el-form-item label="Tanggal surat">
                            <el-date-picker v-model="surat.tanggal" format="DD MMM YYYY" value-format="YYYY-MM-DD" type="date" placeholder="Pilih tanggal surat" />
                        </el-form-item>
                    </el-col>
                </el-row>
                <el-row class="w-full" :gutter="20">
                    <el-col :span="12">
                        <el-form-item label="Tembusan">
                            <el-select placeholder="Tembusan" v-model="surat.tembusan" multiple collapse-tags collapse-tags-tooltip>
                                <el-option v-for="tembusan in [
                                    'Dinas Pendidikan Kab. Malang',
                                    'Korwil Dinas Pendidikan Kec. Wagir',
                                    'Kemenag Kab. Malang',
                                    'PGRI Kab. Malang',
                                    'Yang bersangkutan',
                                    'Arsip'
                                ]" :key="tembusan" :value="tembusan">{{ tembusan }}</el-option>
                            </el-select>
                        </el-form-item>
                    </el-col>
                    <el-col :span="12">
                        <el-form-item label="&nbsp;">
                            <form id="formFileArsip">
                                <input type="file" ref="inputArsip" @change="onfileArsipPicked" accept=".pdf, .jpg, .png, .jpeg, .JPG, .JPEG, .PNG, .PDF" />
                            </form>
                        </el-form-item>
                    </el-col>
                </el-row>
                <el-row>
                    <el-col :span="24">
                        <div class="arsip-holder"></div>
                    </el-col>
                </el-row>
            </el-form>
        </div>
    </el-dialog>
</div>
</template>