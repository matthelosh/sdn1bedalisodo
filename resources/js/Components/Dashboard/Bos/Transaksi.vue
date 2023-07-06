<script setup>
import { ref, onBeforeMount, computed } from 'vue';
import axios from 'axios';
import { Icon } from '@iconify/vue';
import {read, utils} from 'xlsx'

onBeforeMount(() => {
    list()
})

const loading = ref(false)
const list = async() => {
    loading.value = true
    await axios.post(route('dashboard.bos.transaksi.index'))
        .then(res => {
            transaksis.value = res.data.transaksis
            loading.value = false
        }).catch(err => console.log(err))
}

const transaksis = ref([])

const transaksi = ref({
    jenis: 'tunai'
})

const showForm = ref(false)

const simpan = async() => {
    loading.value = true
    let fd = new FormData()
    fd.append("transaksi", JSON.stringify(transaksi.value))
    // for (let i = 0; i < files.value.length; i++) {
    //     fd.append("files[]", files.value[i])
    // }
    for(let file of files.value) {
        // console.log(file)
        // file = file.type == "application/pdf" ? file : compressImg(file, 50);
        // console.log(file)
        // if(file.type == 'application/pdf') {
        //     fd.append("files[]", file)
        // } else {
        //     console.log(compressImg(file, 0.3, 0.3))
        // }
        fd.append("files[]", file)
    }
    await axios.post(route('dashboard.bos.transaksi.store'), fd, {headers: { 'Content-Type': 'multipart/form-data'}})
                .then(res => {
                    loading.value = false
                    list()
                }).catch(err => {
                    loading.value = false
                    console.log(err)
                })
}

const compressImg = async (imgToCompress,resizingFactor, quality) => {
    const canvas = document.createElement("canvas");
  const context = canvas.getContext("2d");
  
  const originalWidth = imgToCompress.width;
  const originalHeight = imgToCompress.height;
  
  const canvasWidth = originalWidth * resizingFactor;
  const canvasHeight = originalHeight * resizingFactor;
  
  canvas.width = canvasWidth;
  canvas.height = canvasHeight;
  
  context.drawImage(
    imgToCompress,
    0,
    0,
    originalWidth * resizingFactor,
    originalHeight * resizingFactor
  );
  
  // reducing the quality of the image
  canvas.toBlob(
    (blob) => {
      if (blob) {
        // showing the compressed image
        // resizedImage.src = URL.createObjectURL(resizedImageBlob);
        return blob
      }
    },
    "image/jpeg",
    quality
  );
    
}

const closeForm = () => {
    showForm.value = !showForm.value
}

const edit = async (trans) => {
    transaksi.value = trans
    showForm.value = true
}

const imageHolder = ref(false)

const addPictures = () => {
    imageHolder.value = !imageHolder.value
}

const files = ref([])

const onPicturesPicked = (e) => {
    let file = e.target.files[0]
    console.log(file)
    if (file.size > 1000000) {
        alert('Ukuran FIle terlalu Besar')
        return false
    }
    const imgNode = document.createElement("img")
    const pdfNode = document.createElement("object")
    const imageHolder = document.querySelector(".image-holder")
    if(file.type.includes("image/")) {
        imgNode.setAttribute("src", URL.createObjectURL(file))
        files.value.push(file)
        
        imageHolder.appendChild(imgNode)
    } else {
        files.value.push(file)
        pdfNode.setAttribute("type", "application/pdf")
        pdfNode.setAttribute("width", "100%")
        pdfNode.setAttribute("data", URL.createObjectURL(new Blob([file], {type: 'application/pdf'})))
        imageHolder.appendChild(pdfNode)
    }
    // alert(file.type)
    
    
}

const fileTransaksi = ref(null)
const onFileTransaksiPicked = async(ev) => {
    loading.value = true
    let file = ev.target.files[0]

    let reader = new FileReader();
    reader.onload = async(e) => {
        let wb = await read(e.target.result)
        let datas = await utils.sheet_to_json(wb.Sheets[wb.SheetNames[0]])
        await axios.post(route('dashboard.bos.transaksi.import'), {data: JSON.stringify(datas)})
                    .then(res => {
                        loading.value = true
                        list()
                    }).catch(err => {
                        loading.value = false
                        alert(err.response.data.msg)
                    })
    }
    reader.readAsArrayBuffer(file)
}
const search = ref('');
</script>

<template>
<div>
    <div class="bg-white p-3 w-full">
        <div class="toolbar w-full flex items-center justify-between sticky top-10 print:top-0 bg-white border-b py-1">
            <h1><span class="hidden">Data Transaksi</span></h1>
            <div class="toolbar-items flex gap-4 items-center justify-between print:hidden">
                <input type="file" ref="fileTransaksi" @change="onFileTransaksiPicked" class="hidden" accept=".xls,.xlsx,.ods,.csv" multiple>
                <button class="flex items-center gap-1 group text-gray-600 hover:font-bold hover:text-gray-800" @click="fileTransaksi.click()">
                    <Icon icon="mdi:cart-arrow-up" class="text-2xl" />
                    <span class="hidden md:block">Impor Transaksi</span>
                </button>
                <button class="flex items-center gap-1 justify-center group text-gray-600 hover:font-bold hover:text-gray-800" @click="showForm = true">
                    <Icon icon="mdi:cart-plus" class="text-2xl" />
                    <span class="hidden md:block">Tambah Transaksi</span>
                </button>
                <span class="relative">
                    <input type="text" placeholder="Cari Transaksi" v-model="search" class="py-1 rounded" />
                    <Icon icon="mdi:magnify" class="text-2xl text-gray-400 absolute top-[50%] -translate-y-[50%] right-2" />
                </span>
                
            </div>

        </div>
        <div class="content w-full overflow-x-auto my-4">
            <div class="table w-full overflow-x-auto">
                <table class="w-auto border border-slate-400">
                    <thead>
                        <tr class="bg-slate-200">
                            <th class="border py-1 border-slate-400">No</th>
                            <th class="border py-1 border-slate-400">Tanggal</th>
                            <th class="border py-1 border-slate-400 hidden md:table-cell">Kode Kegiatan</th>
                            <th class="border py-1 border-slate-400 hidden md:table-cell">Kode Rekening</th>
                            <th class="border py-1 border-slate-400">No Bukti</th>
                            <th class="border py-1 border-slate-400">Uraian</th>
                            <th class="border py-1 border-slate-400 hidden md:table-cell">Tipe</th>
                            <th class="border py-1 border-slate-400">Nilai</th>
                            <th class="border py-1 border-slate-400">Toko | Lembaga</th>
                            <th class="border py-1 border-slate-400">Bukti</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr 
                            v-for="(trans, tr) in transaksis" :key="tr"
                            class="even:bg-slate-50">
                            <td class="border border-slate-400 text-sm px-2 text-center">{{ tr+1 }}</td>
                            <td class="border border-slate-400 text-sm px-2 text-center">{{ trans.tanggal }}</td>
                            <td class="border border-slate-400 text-sm px-2 hidden md:table-cell text-center">{{ trans.kode_kegiatan }}</td>
                            <td class="border border-slate-400 text-sm px-2 hidden md:table-cell text-center">{{ trans.kode_rekening }}</td>
                            <td class="border border-slate-400 text-sm px-2 text-center">
                                <button class="p-2 bg-sky-400 rounded hover:bg-sky-600 text-white" @click="edit(trans)">
                                    {{ trans.no_bukti }}
                                </button>
                            </td>
                            <td class="border border-slate-400 text-sm px-2 ">{{ trans.uraian }}</td>
                            <td class="border border-slate-400 text-sm px-2 hidden md:table-cell text-center">{{ trans.tipe }}</td>
                            <td class="border border-slate-400 text-sm px-2 text-right">
                                <span class="flex justify-between w-full">
                                    <span>Rp. </span>
                                    {{ trans.nilai.toLocaleString("id-ID") }}
                                </span>
                            </td>
                            <td class="border border-slate-400 px-2 text-center">{{ trans.merchant }} </td>
                            <td class="border border-slate-400 px-2 text-center">
                                <a v-for="(bukti, bkt) in trans.buktis" :key="bkt" class="text-sm leading-3 text-teal-600 leading-3" :href="bukti.url" target="_blank">
                                    {{ bukti.label }}
                                </a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="fixed top-0 right-0 bottom-0 left-0 bg-slate-800 bg-opacity-60 flex items-center justify-center overflow-y-auto" v-if="showForm">
        <div class="dialog bg-white w-11/12 md:max-w-[600px] md:min-w-[400px] overflow-y-auto mt-16">
            <div class="toolbar p-3 bg-slate-100 flex items-center justify-between">
                <h1>Tambah Transaksi</h1>
                <div class="toolbar-items flex items-center gap-3 justify-end">
                    <button @click="addPictures">
                        <Icon icon="mdi:camera" class="text-sky-400 hove:text-sky-600 text-2xl" />
                    </button>
                    <button @click="closeForm">
                        <Icon icon="mdi:close-box" class="text-red-400 hover:text-red-600 text-2xl" />
                    </button>
                </div>
            </div>
            <div class="dialog-content overflow-y-auto max-h-[80vh]">
                <div class="pictures w-full min-h-32 bg-gray-800 transition mb-4" v-if="imageHolder">
                    <div class="image-holder w-full  p-2 grid grid-cols-3 gap-2">
                        
                    </div>
                    <input type="file" ref="fileImage" class="hidden" @change="onPicturesPicked" accept=".jpg, .png, .JPG, .jpeg, .JPEG, .bmp, .PNG, .pdf">
                    <button class="w-full bg-sky-100 flex justify-center py-3 gap-2" @click="$refs.fileImage.click()">
                        <Icon icon="mdi:camera" class="text-gray-400 hove:text-sky-600 text-2xl" />
                        <span class="text-gray-400">Klik untuk mengambil gambar</span>
                    </button>
                </div>
                <form ref="form-transaksi" class="form p-3" @submit.prevent="simpan">
                    <label for="kode_kegiatan" class="flex items-center justify-center gap-2 my-3">
                        Kode Kegiatan
                        <input type="text" class="py-1 flex-grow" v-model="transaksi.kode_kegiatan" required />
                    </label>
                    <label for="kode_rekening" class="flex items-center justify-center gap-2 my-3">
                        Kode Rekening
                        <input type="text" class="py-1 flex-grow" v-model="transaksi.kode_rekening" required />
                    </label>
                    <label for="no_bukti" class="flex items-center justify-center gap-2 my-3">
                        No Bukti
                        <input type="text" class="py-1 flex-grow" v-model="transaksi.no_bukti" required />
                    </label>
                    <label for="merchant" class="flex items-center justify-center gap-2 my-3">
                        Sumber | Penerima Dana
                        <input type="text" class="py-1 flex-grow" v-model="transaksi.merchant" required />
                    </label>
                    <label for="merchant" class="flex items-center justify-center gap-2 my-3">
                        Nilai Transaksi
                        <span class="py-1 flex-grow relative">
                        <input type="number" class="py-1 pl-8 w-full" v-model="transaksi.nilai" required />
                        <span class="absolute left-1 text-gray-800 font-bold top-[50%] -translate-y-[50%]">Rp.</span>
                        </span>
                    </label>
                    <label for="jenis" class="flex items-center justify-between gap-2 y-3">
                        Jenis Transaksi
                        <select name="jenis" id="jenis" v-model="transaksi.jenis" class="flex-grow py-1">
                            <option value="tunai" selected>Tunai</option>
                            <option value="nontunai">Non Tunai</option>
                        </select>
                    </label>
                    <label for="tanggal" class="flex items-center justify-between gap-2 my-3">
                        Tanggal
                        <input type="date" v-model="transaksi.tanggal" class="py-1" required />
                    </label>
                    <label for="uraian" class="my-3">
                        Uraian:
                        <textarea class="w-full" v-model="transaksi.uraian"></textarea>
                    </label>
                    <div class="w-full relative flex items-center justify-center py-3">
                        <button class="mx-auto bg-sky-600 py-1 px-3 text-white rounded hover:bg-sky-400">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="fixed top-0 right-0 bottom-0 left-0 bg-slate-800 bg-opacity-60 flex items-center justify-center" v-if="loading">
        <Icon icon="mdi:loading" class="animate-spin text-8xl text-white" />
    </div>
</div>
</template>