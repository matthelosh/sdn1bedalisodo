<script setup>
import { ref, computed } from 'vue';
import { usePage, Head, router } from '@inertiajs/vue3';
import Compressor from 'compressorjs'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import QrcodeVue from 'qrcode.vue';
import { Icon } from '@iconify/vue';
import { ElNotification } from 'element-plus';

const tipes = ref([
    { kode: 'skpeg', label: 'SK Pegawai'},
    { kode: 'skpbm', label: 'SK PBM'},
    { kode: 'ijazah', label: 'Ijazah'},
    { kode: 'berkala', label: 'Gaji Berkala'},
    { kode: 'pangkat', label: 'SK Pangkat'},
    { kode: 'kk', label: 'Kartu Keluarga'},
    { kode: 'ktp', label: 'KTP'},
    { kode: 'karpeg', label: 'Kartu Pegawai'},
    { kode: 'karis', label: 'Karis / Karsu'},
    { kode: 'akta', label: 'Akta Kelahiran'},
    { kode: 'bpjs', label: 'Kartu BPJS'},
    { kode: 'npwp', label: 'Kartu NPWP'},
    { kode: 'rekening', label: 'Buku Rekening'},
    { kode: 'taspen', label: 'Kartu Taspen'},
    { kode: 'serdik', label: 'Sertifikat Pendidik'},
    { kode: 'lain', label: 'Lainnya'},
])

const loading = ref(false)
const selectedTipe = ref({})
const page = usePage()
const guru = page.props.guru
const dialog = ref(false)
const arsip = ref({})
const dokumen = ref(null)
const params = computed(() => {
    return route().params
})
const arsips = computed(() => {
    return Object.groupBy(guru.arsips, arsip => arsip.tipe)
})

const onFormClosed = ()  => {
    arsip.value = {}
    dokumen.value = null
}

const showForm = (item) => {
    arsip.value.tipe = item.kode
    arsip.value.label = item.label
    dialog.value = true
}
const onFilePicked = async(e) => {
    let file = e.target.files[0]

    const imgNode = document.createElement("img")
    const pdfNode = document.createElement("object")
    const fileHolder = document.querySelector(".file-holder")
    fileHolder.innerHTML = ''
    // console.log(file)
    if (file.type.includes("image/")) {
        // alert('Ukuran FIle terlalu Besar')
        // return false
        if(file.size > 2000000 ) {
        new Compressor(file, {
          quality: 0.2,
          convertTypes: 'image/webp',
          success(result) {
            imgNode.setAttribute("src", URL.createObjectURL(result))
            dokumen.value = new File([result], result.name)
            
            fileHolder.appendChild(imgNode)
          }
        })
      } else {
        imgNode.setAttribute("src", URL.createObjectURL(file))
        dokumen.value = file
        
        fileHolder.appendChild(imgNode)
      }
    } else {
        dokumen.value = file
        pdfNode.setAttribute("type", "application/pdf")
        pdfNode.setAttribute("width", "100%")
        pdfNode.setAttribute("data", URL.createObjectURL(new Blob([file], {type: 'application/pdf'})))
        fileHolder.appendChild(pdfNode)
    }
}

const simpan = async() => {
    let fd = new FormData()
    fd.append("data", JSON.stringify(arsip.value))
    fd.append("dokumen", dokumen.value)

    loading.value = true
    router.post(route('arsip.store', {_query: {guru_id: params.value.nip}}), fd, {
        onError: (err) => {
            console.log(err)
            ElNotification({title: 'Error', message: err})
            loading.value = false
        }, onSuccess: (page) => {
            ElNotification({title: 'Info', message: 'Arsip Disimpan'})
            router.reload({only: ['guru']})
            loading.value = false
        }
    })
}

const hapus = async(item) => {
    await router.delete(route('arsip.destroy', {id: item.id}))
}

</script>

<template>
<AdminLayout >
    <Head title="Arsip Guru" />
    <div class="w-full p-8">
        <el-row>
            <el-col :span="24">
                <el-card>
                    <template #header>
                        <div class="w-full flex justify-between items-center">
                            <h3 class="font-bold text-gray-800 text-lg uppercase">Arsip Dokumen {{ guru.nama }}</h3>
                        </div>
                        <div class="items flex items-center gap-2">
                            
                        </div>
                    </template>
                    <div class="card-body">
                        <el-collapse accordion>
                            <el-collapse-item v-for="(tipe,t) in tipes" :name="tipe.kode" :key="t">
                                <template #title>
                                    <h3 class="font-bold">
                                        {{ (t+1) +'. '+ tipe.label }}
                                        <span :class="arsips[tipe.kode] ? 'text-blue-600' : 'text-red-600'">[{{ arsips[tipe.kode] ? arsips[tipe.kode].length : '0' }} berkas]</span>
                                    </h3>
                                </template>
                                <div class="content px-3">
                                    <div class="w-full">
                                        <el-button type="primary" size="small" class="mb-2" @click="showForm(tipe)">Tambah</el-button>
                                        <table class="w-full">
                                            <thead>
                                                <tr>
                                                    <th class="border border-black">No</th>
                                                    <th class="border border-black">Label</th>
                                                    <th class="border border-black">Deskripsi</th>
                                                    <th class="border border-black">Keterangan</th>
                                                    <th class="border border-black">Lokasi File</th>
                                                    <th class="border border-black">Opsi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="(arsip, ar) in arsips[tipe.kode]">
                                                    <td class="border border-black p-2 text-center">{{ ar+1 }}</td>
                                                    <td class="border border-black p-2">{{ arsip.label }}</td>
                                                    <td class="border border-black p-2">{{ arsip.deskripsi }}</td>
                                                    <td class="border border-black p-2">{{ arsip.keterangan }}</td>
                                                    <td class="border border-black p-2 ">
                                                        <a :href="arsip.url" target="_blank">
                                                            <qrcode-vue :value="arsip.url" :size="70" level="L" :foreground="'#363636'" />
                                                        </a>
                                                        <p>{{ arsip.url }}</p>
                                                    </td>
                                                    <td class="border border-black text-center">
                                                        <div class="flex gap-2 justify-center">
                                                            <el-popconfirm title="Hapus arsip?" @confirm="hapus(arsip)">
                                                                <template #reference>
                                                                    <el-button type="danger" circle text>
                                                                        <Icon icon="mdi:delete" />
                                                                    </el-button>
                                                                </template>
                                                            </el-popconfirm>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </el-collapse-item>
                        </el-collapse>
                    </div>
                </el-card>
            </el-col>
        </el-row>
    </div>
    <el-dialog v-model="dialog" @close="onFormClosed">
        <template #header>
            <div class="w-full flex justify-between items-center">
                <h3>Form Arsip Guru</h3>
                <el-button :loading="loading" type="primary" size="small" class="mr-6" @click="simpan">Simpan</el-button>
            </div>
        </template>
        <div class="dialog-body">
            <el-form v-model="arsip" label-position="top">
                <el-form-item label="Tipe">
                    <el-input v-model="arsip.tipe" placeholder="Tipe Dokumen"></el-input>
                </el-form-item>
                <el-form-item label="Label">
                    <el-input v-model="arsip.label" placeholder="Tipe Dokumen"></el-input>
                </el-form-item>
                <el-form-item label="Deskripsi">
                    <el-input type="textarea" v-model="arsip.deskripsi" placeholder="Tipe Dokumen"></el-input>
                </el-form-item>
                <el-form-item label="Keterangan">
                    <el-input type="textarea" v-model="arsip.keterangan" placeholder="Tipe Dokumen"></el-input>
                </el-form-item>
                <el-form-item label="File">
                    <input type="file" ref="dokumen" class="hidden" accept=".jpg, .pdf, .png" @change="onFilePicked" />
                    <el-button type="success" @click="$refs.dokumen.click()" size="small">Pilih Dokumen</el-button>
                </el-form-item>
            </el-form>
            <div class="file-holder">

            </div>
        </div>
    </el-dialog>
</AdminLayout>
</template>