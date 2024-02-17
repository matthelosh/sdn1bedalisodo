<script setup>
import { ref, computed, defineAsyncComponent, onBeforeMount } from 'vue';
import { usePage } from '@inertiajs/vue3';
import { Icon } from '@iconify/vue';
import axios from 'axios';
import Compressor from 'compressorjs'
import QrcodeVue from 'qrcode.vue';
import { ElNotification} from 'element-plus';
// import {QrCode} from '@bitjson/qr-code'
// import { cetak } from '@/Plugins/misc';

const page = usePage()
const loading = ref(false)
const search = ref('')
const emit = defineEmits(['close', 'edit', 'reload'])
const props = defineProps({headers:Array, items: Array})
const mode = ref('list')
const toggle = ref(true)
const tujuan = computed(() => toggle.value ? 'keluar' : 'masuk')
const datas = computed(() => {
    let results = !search.value ? props.items : props.items.filter(surat => surat.perihal.toLowerCase().includes(search.value.toLowerCase()))
    return results.filter(s => s.tujuan === tujuan.value)
})
const fileArsip = ref(null)
const formArsip = ref(false)
const selectedSurat = ref(null)
const addArsip = (item) => {
    selectedSurat.value = item
    formArsip.value = true
}
const items = computed(() => {
    // return 
})

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

const cetak = () => {
    let ths = '';
    let trs = '';

    props.headers.forEach(th => {
        if(th.text !== 'Opsi') {
            ths += `<th class="border border-black">${th.text}</th>`
        }
    })
    let cssUrl = page.props.app_env == 'local' ? 'http://localhost:5173/resources/css/app.css' : `/build/assets/app.css`
    let el =document.querySelector(".agenda-surat")
    // <script src="https://unpkg.com/@bitjson/qr-code@1.0.2/dist/qr-code.js" />

    datas.value.forEach((data,i) => {
        trs += `<tr>
                <td class="border border-black p-2 text-center">${i+1}</td>
                <td class="border border-black p-2">${data.kode}</td>
                <td class="border border-black p-2">${data.penerima}</td>
                <td class="border border-black p-2">${data.tanggal}</td>
                <td class="border border-black p-2">${data.perihal}</td>
                <td class="border border-black p-2">${data.tembusan}</td>
                <td class="border border-black p-2">${data.arsip?.url}</td>
            </tr>`
    })

	let html = `<html>
                    <head>
                        <title>Agenda Surat ${tujuan.value[0].toUpperCase()+tujuan.value.substring(1)}</title> 
                        <link rel="stylesheet" href="${cssUrl}" />   
                    </head>
                    <body>
                        <h3 class="text-center text-xl font-bold">Agenda Surat ${tujuan.value[0].toUpperCase()+tujuan.value.substring(1)}</h3>
                        <table>
                            <thead>
                                ${ths}    
                            </thead> 
                            <tbody>
                                ${trs}
                                </tbody>   
                        </table>
                    </body>
    </html>
	`
	let win = window.open("","_blank","height=600,width=1024")
	win.document.write(html)
    setTimeout(() => {
        win.print();
        // win.close();
    }, 1500);
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
    loading.value = true
    let fd = new FormData()
    fd.append('surat_id', selectedSurat.value.kode)
    fd.append("file_arsip", fileArsip.value)
    axios.post(route('surat.arsip.add'), fd)
            .then(res => {
                init()
                loading.value = false
                ElNotification({title: "Info", message: "Arsip surat disimpan"})
            }).catch(err => {
                // ElNotification({title: "Error", message: err.response.data.message})
                console.log(err)
            })
}

const hapus = async (item) => {
    loading.value = true
    await axios.delete(route('surat.hapus', {id: item.id}))
                .then(res => {
                    ElNotification({title: 'Info', message: 'Surat dihapus'})
                    setTimeout(() => {
                        emit('reload')
                    }, 500)
                }).catch(err => {
                    console.log(err)
                })
}

const edit = (item) => {
    // console.log(item)
    emit('edit', item)
}
</script>

<template>
<div>
    <div class="content w-full p-3 bg-white agenda-surat">
        <div class="toolbar w-full flex items-center justify-between pb-2">
            <p class="title uppercase text-sm flex items-center gap-1">
                <Icon :icon="`mdi:${toggle ? 'envelope':'inbox'}`" class="text-sky-800" />    
                Agenda Surat {{ tujuan }}
            </p>
            <div class="toolbar-items print:hidden flex gap-2">
                <el-switch v-model="toggle" active-text="Keluar" inactive-text="Masuk" />
                <el-button class="ml-4" @click="cetak">
                    <Icon icon="mdi:printer" />
                </el-button>
                <el-input placeholder="search" v-model="search">
                    <template #prefix>
                        <Icon icon="mdi:magnify" />
                    </template>
                </el-input>
            </div>
        </div>
        <div class="table overflow-x-auto w-full h-[650px]" >
            <data-table :items="datas" :headers="props.headers" show-index >
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
                        <el-popconfirm placement="bottom-end" title="Yakin menghapus surat ini?" @confirm="hapus(item)">
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