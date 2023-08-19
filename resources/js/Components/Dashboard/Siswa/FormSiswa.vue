<script setup>
import { Icon } from '@iconify/vue';
import { ref, onMounted, defineAsyncComponent } from 'vue';
import axios from 'axios'

const props = defineProps({ selectedSiswa: Object })
const emit = defineEmits(['close'])

const loading = ref(false)
const Loading = defineAsyncComponent(() => import('@/Components/General/Loading.vue'))

const siswa = ref({
    kode_pos: '65158',
    desa: 'Dalisodo',
    alamat: 'Sengon',
    kecamatan: 'Wagir',
    kabupaten: 'Malang',
    jk: 'Laki-laki',
    agama: 'Islam',
    angkatan: '2023',
    tempat_lahir: 'Malang'
})

const close = () => {
    emit('close')
}

const simpan = async() => {
    loading.value = true
    let fd = new FormData()
    fd.append("foto", fotoSiswa.value)
    fd.append("siswa", JSON.stringify(siswa.value))
    await axios.post(route('dashboard.siswa.store'), fd)
                .then(res => {
                    loading.value = false
                    emit('close')
                }).catch(err=> {
                    loading.value = false
                    console.log(err)
                })
}

const fotoSiswa = ref(null)
const onFotoPicked = async(e) => {
    let foto = e.target.files[0]
    fotoSiswa.value = foto
    siswa.value.foto_url = URL.createObjectURL(foto)
}

onMounted(() => {
    if (props.selectedSiswa.mode !== 'create') siswa.value = props.selectedSiswa

    // console.log(props.selectedSiswa)
})

</script>

<template>
<div class="w-full bg-slate-100 bg-opacity-80 backdrop-blur-sm fixed top-0 right-0 bottom-0 left-0 flex items-center justify-center z-40">
    <div class="dialog bg-white rounded shadow-lg w-[95vw] border border-slate-200 md:max-w-[800px]">
        <div class="toolbar h-10 flex items-center justify-between w-full p-2 bg-slate-200">
            <h1 class="font-bold text-lg text-slate-800">
                Formulir Siswa
            </h1>
            <div class="toolbar-items flex items-center gap-2 justify-end">
                <button @click="close" class="flex items-center">
                    <Icon icon="mdi:close-box" class="text-2xl text-red-600 hover:text-red-400 active:text-red-800" />
                </button>
            </div>
        </div>
        <div class="content w-full bg-slate-100 p-3 h-full md:max-h-[75vh] overflow-y-auto overflow-auto max-h-[90vh]">
            <form ref="formSiswa" @submit.prevent="simpan" >
                <fieldset :disabled="loading" class="grid grid-cols-1 md:grid-cols-3 gap-3 overflow-auto">
                    <div class="foto-holder col-span-3 md:col-span-1 bg-slate-300">
                        <input type="file" ref="foto" @change="onFotoPicked" class="hidden" />
                        <img :src="siswa.foto_url ? siswa.foto_url : (siswa.jk && siswa.jk == 'Laki-laki') ? '/img/siswa.png' : '/img/siswi.png'" alt="Siswa" class="rounded-full aspect-square bg-slate-400 border border-slate-400 hover:bg-slate-100 shadow hover:shadow-lg w-3/4 mx-auto mt-4 cursor-pointer object-cover" title="Klik untuk mengganti Foto" @click="$refs.foto.click()">
                        <h1 class="text-center">{{ fotoSiswa ? fotoSiswa.name : 'Klik gambar untuk mengganti foto' }}</h1>
                    </div>
                    <div class="w-full col-span-3 md:col-span-2">
                        <label for="nisn" class="my-2 w-full shadow bg-slate-50 pl-2 flex items-center justify-between">
                            <span>NISN/NIS</span>
                            <span class="w-[65%] flex gap-2">
                                <input type="text" placeholder="NISN" required v-model="siswa.nisn" class="border-none bg-slate-200 py-1 px-2 w-[50%]" />
                                <input type="text" placeholder="NIS" v-model="siswa.nis" class="border-none bg-slate-200 py-1 px-2 w-[50%]" />
                                </span>
                        </label>
                        <label for="nik" class="my-2 w-full shadow bg-slate-50 pl-2 flex items-center justify-between">
                            <span>NIK</span>
                            <input type="text" placeholder="N.I.K." v-model="siswa.nik" class="border-none bg-slate-200 py-1 px-2 min-w-[65%]" />
                        </label>
                        <label for="nama" class="my-2 w-full shadow bg-slate-50 pl-2 flex items-center justify-between">
                            <span>Nama</span>
                            <input type="text" placeholder="Nama Lengkap" required v-model="siswa.nama" class="border-none bg-slate-200 py-1 px-2 min-w-[65%]" />
                        </label>
                        <label for="jk" class="my-2 w-full shadow bg-slate-50 pl-2 flex items-center justify-between">
                            <span>Jenis Kelamin</span>
                            <select required v-model="siswa.jk" class="border-none bg-slate-200 py-1 px-2 min-w-[65%]" >
                                <option
                                    v-for="jk in ['Laki-laki', 'Perempuan']"
                                    :key="jk"
                                    :value="jk"
                                >
                                    {{ jk }}
                                </option>
                            </select>
                        </label>
                        <label for="agama" class="my-2 w-full shadow bg-slate-50 pl-2 flex items-center justify-between">
                            <span>Agama</span>
                            <select required v-model="siswa.agama" class="border-none bg-slate-200 py-1 px-2 min-w-[65%]" >
                                <option
                                    v-for="agama in ['Islam', 'Kristen', 'Katolik','Hindu','Budha', 'Konghuchu']"
                                    :key="agama"
                                    :value="agama"
                                >
                                    {{ agama }}
                                </option>
                            </select>
                        </label>
                        <label for="ttl" class="my-2 w-full shadow bg-slate-50 pl-2 flex items-center justify-between">
                            <span>Tempat/tanggal Lahir</span>
                            <span class="w-[65%] flex gap-2">
                                <input type="text" placeholder="Tmpt Lahir" required v-model="siswa.tempat_lahir" class="border-none bg-slate-200 py-1 px-2 w-[50%]" />
                                <input type="date" required v-model="siswa.tanggal_lahir" class="border-none bg-slate-200 py-1 px-2 w-[50%]" />
                                </span>
                        </label>
                        <label for="alamat" class="my-2 w-full shadow bg-slate-50 pl-2 flex items-center justify-between">
                            <span>Alamat</span>
                            <input type="text" placeholder="Alamat" required v-model="siswa.alamat" class="border-none bg-slate-200 py-1 px-2 min-w-[65%]" />
                        </label>
                        <label for="rt_rw" class="my-2 w-full shadow bg-slate-50 pl-2 flex items-center justify-between">
                            <span>RT/RW</span>
                            <span class="w-[65%] flex gap-2">
                                <input type="text" placeholder="RT" v-model="siswa.rt" class="border-none bg-slate-200 py-1 px-2 w-[50%]" />
                                <input type="text" placeholder="RW" v-model="siswa.rw" class="border-none bg-slate-200 py-1 px-2 w-[50%]" />
                                </span>
                        </label>
                        <label for="desa" class="my-2 w-full shadow bg-slate-50 pl-2 flex items-center justify-between">
                            <span>Desa</span>
                            <input type="text" placeholder="Desa" required v-model="siswa.desa" class="border-none bg-slate-200 py-1 px-2 min-w-[65%]" />
                        </label>
                        <label for="kecamatan" class="my-2 w-full shadow bg-slate-50 pl-2 flex items-center justify-between">
                            <span>Kecamatan</span>
                            <span class="w-[65%] flex gap-2">
                                <input type="text" placeholder="Kecamatan" required v-model="siswa.kecamatan" class="border-none bg-slate-200 py-1 px-2 w-[70%]" />
                                <input type="text" placeholder="Kd Pos" required v-model="siswa.kode_pos" class="border-none bg-slate-200 py-1 px-2 w-[30%]" />
                                </span>
                        </label>
                        <label for="kabupaten" class="my-2 w-full shadow bg-slate-50 pl-2 flex items-center justify-between">
                            <span>Kabupaten</span>
                            <input type="text" placeholder="Kabupaten" required v-model="siswa.kabupaten" class="border-none bg-slate-200 py-1 px-2 min-w-[65%]" />
                        </label>
                        <label for="angkatan" class="my-2 w-full shadow bg-slate-50 pl-2 flex items-center justify-between">
                            <span>Tahun Masuk</span>
                            <input type="text" placeholder="Tahun Masuk" v-model="siswa.angkatan" class="border-none bg-slate-200 py-1 px-2 min-w-[65%]" />
                        </label>
                        <label for="hp" class="my-2 w-full shadow bg-slate-50 pl-2 flex items-center justify-between">
                            <span>No. HP</span>
                            <input type="text" placeholder="HP" v-model="siswa.hp" class="border-none bg-slate-200 py-1 px-2 min-w-[65%]" />
                        </label>
                        <label for="email" class="my-2 w-full shadow bg-slate-50 pl-2 flex items-center justify-between">
                            <span>Email</span>
                            <input type="text" placeholder="Email" v-model="siswa.email" class="border-none bg-slate-200 py-1 px-2 min-w-[65%]" />
                        </label>
                    </div>
                    <label for="submit" class="w-full flex items-start justify-center mt-4 mb-2 col-span-3">
                        <button class="flex gap-1 items-center bg-sky-600 text-white py-1 px-3 hover:bg-sky-800 active:bg-sky-400">
                            <Icon icon="mdi:loading" class="animate-spin" v-if="loading" />
                            Simpan
                        </button>
                    </label>
                </fieldset>
            </form>
        </div>
    </div>
</div>
<Loading v-if="loading" />

</template>