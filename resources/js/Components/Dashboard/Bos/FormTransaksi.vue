<script setup>
import { ref, onBeforeMount, defineAsyncComponent } from  'vue';
import { Icon } from '@iconify/vue';
import axios from 'axios';

const props = defineProps({show: Boolean, transaksi: Object, title: String, antris: Array})
const emit = defineEmits(['close'])

onBeforeMount(() => {
    transaksi.value = props.transaksi;
    showForm.value = props.show
})


const transaksi = ref({})

const Loading = defineAsyncComponent(() => import('@/Components/General/Loading.vue'))
const loading = ref(false)

const setTransaksi = async(rka) => {
  transaksi.value = {
    rkas_id: rka.id,
    kode_kegiatan: rka.kode_kegiatan,
    kode_rekening: rka.kode_rekening,
    merchant: 'BOS Reguler',
    jenis: 'tunai',
    uraian: rka.uraian,
    nilai: rka.jumlah
  }

  const ul = document.querySelector(".rkas")
  selectedRkas.value = rka.uraian
  ul.classList.add("hidden")
}

const onBuktiPicked = (e) => {
    let file = e.target.files[0]
    // console.log(file)
    if (file.size > 2000000) {
        alert('Ukuran FIle terlalu Besar')
        return false
    }
    const imgNode = document.createElement("img")
    const pdfNode = document.createElement("object")
    const buktiHolder = document.querySelector(".bukti-holder")
    if(file.type.includes("image/")) {
        imgNode.setAttribute("src", URL.createObjectURL(file))
        files.value.push(file)
        
        buktiHolder.appendChild(imgNode)
    } else {
        files.value.push(file)
        pdfNode.setAttribute("type", "application/pdf")
        pdfNode.setAttribute("width", "100%")
        pdfNode.setAttribute("data", URL.createObjectURL(new Blob([file], {type: 'application/pdf'})))
        buktiHolder.appendChild(pdfNode)
    }
}

const onRkasFocus = (e) => {
  let ul = document.querySelector(".rkas")
  ul.classList.remove("hidden")
}

const files = ref([])

const selectedRkas = ref('')
const showForm = ref(false)

const simpan = async() => {
    loading.value = true
    let fd = new FormData()
    fd.append("transaksi", JSON.stringify(transaksi.value))
    for(let file of files.value) {
        fd.append("files[]", file)
    }
    await axios.post(route('dashboard.bos.transaksi.store'), fd, {headers: { 'Content-Type': 'multipart/form-data'}})
                .then(res => {
                    loading.value = false
                    emit('close')
                }).catch(err => {
                    loading.value = false
                    console.log(err)
                })
}
</script>

<template>
    <Loading v-if="loading" />
    <div class="fixed top-0 right-0 bottom-0 left-0 bg-white backdrop-blur-sm bg-opacity-60 flex items-center justify-center overflow-y-auto z-50" v-if="props.show">
        <div class="dialog bg-white w-11/12 md:max-w-full mx-auto md:min-w-[400px] overflow-y-auto shadow-lg border border-gray-200">
            <div class="toolbar p-3 bg-slate-100 flex items-center justify-between">
                <h1>{{props.title}}</h1>
                <div class="toolbar-items flex items-center gap-3 justify-end">
                    <button @click="emit('close')">
                        <Icon icon="mdi:close-box" class="text-red-400 hover:text-red-600 text-2xl" />
                    </button>
                </div>
            </div>
            <div class="dialog-content overflow-y-auto max-h-[80vh] grid grid-cols-2">
                <form ref="form-transaksi" class="form p-3" @submit.prevent="simpan">
                    <label for="kode_kegiatan" class="flex items-center justify-between gap-2 my-3">
                      <span class="font-bold">
                        Pilih RKAS
                      </span>
                      <span class="w-[60%] relative">
                        <input type="text" class="py-1 w-full bg-gray-100 border-0" v-model="selectedRkas" @focus="onRkasFocus" required />
                        <ul class="rkas absolute w-full bg-white shadow max-h-[300px] overflow-y-auto mt-1 z-20 cursor-pointer hidden">
                          <li
                            v-for="rka in props.antris" :key="rka.id"
                            class="hover:bg-slate-200 my-1 p-1"
                            @click="setTransaksi(rka)"
                          >
                            {{ rka.uraian }}
                          </li>
                        </ul>
                    </span>
                    </label>
                    <label for="kode_kegiatan" class="flex items-center justify-between gap-2 my-3">
                      <span class="font-bold">
                        Kode Kegiatan
                      </span>
                        <input type="text" class="py-1 w-[60%] bg-gray-100 border-0" v-model="transaksi.kode_kegiatan" required />
                    </label>
                    <label for="kode_rekening" class="flex items-center justify-between gap-2 my-3">
                      <span class="font-bold">
                        Kode Rekening
                      </span>
                        <input type="text" class="py-1 w-[60%] bg-gray-100 border-0" v-model="transaksi.kode_rekening" required />
                    </label>
                    <label for="no_bukti" class="flex items-center justify-between gap-2 my-3">
                      <span class="font-bold">
                        No Bukti
                      </span>
                        <input type="text" class="py-1 w-[40%] border-0 bg-gray-200" v-model="transaksi.no_bukti" required />
                    </label>
                    <label for="merchant" class="flex items-center justify-between gap-2 my-3">
                      <span class="font-bold">
                        Sumber | Penerima Dana
                      </span>
                        <input type="text" class="py-1 w-[60%] bg-gray-100 border-0" v-model="transaksi.merchant" required />
                    </label>
                    <label for="nilai" class="flex items-center justify-between gap-2 my-3">
                      <span class="font-bold">
                        Nilai Transaksi
                      </span>
                        <span class="py-1 w-[60%] relative">
                        <input type="number" class="py-1 pl-8 w-full bg-gray-100 border-0" v-model="transaksi.nilai" required />
                        <span class="absolute left-1 text-gray-800 font-bold top-[50%] -translate-y-[50%]">Rp.</span>
                        </span>
                    </label>
                    <label for="jenis" class="flex items-center justify-between gap-2 y-3">
                      <span class="font-bold">
                        Jenis Transaksi
                      </span>
                        <select name="jenis" id="jenis" v-model="transaksi.jenis" class="border-0 w-[30%] bg-gray-100 py-1">
                            <option value="tunai" selected>Tunai</option>
                            <option value="nontunai">Non Tunai</option>
                        </select>
                    </label>
                    <label for="tanggal" class="flex items-center justify-between gap-2 my-3">
                      <span class="font-bold">
                        Tanggal
                      </span>
                        <input type="date" v-model="transaksi.tanggal" class="py-1 bg-gray-100 border-0" required />
                    </label>
                    <label for="uraian" class="my-3">
                      <span class="font-bold">Uraian:</span>
                        <textarea class="w-full border-gray-200 bg-gray-100" v-model="transaksi.uraian"></textarea>
                    </label>
                    <div class="w-full relative flex items-center justify-center py-3">
                        <button class="mx-auto bg-sky-600 py-1 px-3 text-white rounded hover:bg-sky-400">Simpan</button>
                    </div>
                </form>
                <div class="buktis w-full min-h-32 bg-gray-50 transition mb-4">
                    <button class="w-full bg-sky-100 flex justify-center py-3 gap-2" @click="$refs.fileImage.click()">
                        <Icon icon="mdi:attachment" class="text-slate-800 font-bold hover:text-sky-600 text-2xl" />
                        <span class="text-gray-400">Klik untuk melampirkan bukti</span>
                    </button>
                      <div class="bukti-holder w-full  p-2 grid grid-cols-3 gap-2" v-if="transaksi.buktis">
                          <div
                              v-for="(bukti, bk) in transaksi.buktis" :key="bk"
                              v-if="transaksi.buktis.length > 0"
                          >
                              <img :src="bukti.url" alt="" v-if="bukti.tipe == 'foto'" />
                              <object :data="bukti.url" type="application/pdf" v-else></object>
                          </div>
                      </div>
                    <input type="file" ref="fileImage" class="hidden" @change="onBuktiPicked" accept=".jpg, .png, .JPG, .jpeg, .JPEG, .bmp, .PNG, .pdf">
                </div>

            </div>
        </div>
    </div>
</template>