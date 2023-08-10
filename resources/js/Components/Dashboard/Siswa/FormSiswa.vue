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
    await axios.post(route('dashboard.siswa.store'), {siswa: JSON.stringify(siswa.value)})
                .then(res => {
                    loading.value = false
                    emit('close')
                }).catch(err=> {
                    loading.value = false
                    console.log(err)
                })
}

onMounted(() => {
    if (props.selectedSiswa.mode !== 'create') siswa.value = props.selectedSiswa

    console.log(props.selectedSiswa)
})

</script>

<template>
<div class="w-full bg-slate-100 bg-opacity-80 backdrop-blur-sm fixed top-0 right-0 bottom-0 left-0 flex items-center justify-center z-40">
    <div class="dialog bg-white rounded shadow-lg w-[600px] max-w-[800px]">
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
        <div class="content w-full bg-slate-100 p-3">
            <form ref="formSiswa" @submit.prevent="simpan">
                <fieldset :disabled="loading">
                    <label for="nisn" class="w-full flex items-start justify-between my-2">
                        <span>NISN/NIS</span>
                        <span class="w-[60%] flex gap-2">
                            <input type="text" placeholder="NISN" required v-model="siswa.nisn" class="border-none bg-slate-200 py-1 px-2 w-[50%]" />
                            <input type="text" placeholder="NIS" v-model="siswa.nis" class="border-none bg-slate-200 py-1 px-2 w-[50%]" />
                            </span>
                    </label>
                    <label for="nik" class="w-full flex items-start justify-between my-2">
                        <span>NIK</span>
                        <input type="text" placeholder="N.I.K." v-model="siswa.nik" class="border-none bg-slate-200 py-1 px-2 min-w-[60%]" />
                    </label>
                    <label for="nama" class="w-full flex items-start justify-between my-2">
                        <span>Nama</span>
                        <input type="text" placeholder="Nama Lengkap" required v-model="siswa.nama" class="border-none bg-slate-200 py-1 px-2 min-w-[60%]" />
                    </label>
                    <label for="jk" class="w-full flex items-start justify-between my-2">
                        <span>Jenis Kelamin</span>
                        <select required v-model="siswa.jk" class="border-none bg-slate-200 py-1 px-2 min-w-[60%]" >
                            <option
                                v-for="jk in ['Laki-laki', 'Perempuan']"
                                :key="jk"
                                :value="jk"
                            >
                                {{ jk }}
                            </option>
                        </select>
                    </label>
                    <label for="agama" class="w-full flex items-start justify-between my-2">
                        <span>Agama</span>
                        <select required v-model="siswa.agama" class="border-none bg-slate-200 py-1 px-2 min-w-[60%]" >
                            <option
                                v-for="agama in ['Islam', 'Kristen', 'Katolik','Hindu','Budha', 'Konghuchu']"
                                :key="agama"
                                :value="agama"
                            >
                                {{ agama }}
                            </option>
                        </select>
                    </label>
                    <label for="ttl" class="w-full flex items-start justify-between my-2">
                        <span>Tempat/tanggal Lahir</span>
                        <span class="w-[60%] flex gap-2">
                            <input type="text" placeholder="Tmpt Lahir" required v-model="siswa.tempat_lahir" class="border-none bg-slate-200 py-1 px-2 w-[50%]" />
                            <input type="date" required v-model="siswa.tanggal_lahir" class="border-none bg-slate-200 py-1 px-2 w-[50%]" />
                            </span>
                    </label>
                    <label for="alamat" class="w-full flex items-start justify-between my-2">
                        <span>Alamat</span>
                        <input type="text" placeholder="Alamat" required v-model="siswa.alamat" class="border-none bg-slate-200 py-1 px-2 min-w-[60%]" />
                    </label>
                    <label for="rt_rw" class="w-full flex items-start justify-between my-2">
                        <span>RT/RW</span>
                        <span class="w-[60%] flex gap-2">
                            <input type="text" placeholder="RT" v-model="siswa.rt" class="border-none bg-slate-200 py-1 px-2 w-[50%]" />
                            <input type="text" placeholder="RW" v-model="siswa.rw" class="border-none bg-slate-200 py-1 px-2 w-[50%]" />
                            </span>
                    </label>
                    <label for="desa" class="w-full flex items-start justify-between my-2">
                        <span>Desa</span>
                        <input type="text" placeholder="Desa" required v-model="siswa.desa" class="border-none bg-slate-200 py-1 px-2 min-w-[60%]" />
                    </label>
                    <label for="kecamatan" class="w-full flex items-start justify-between my-2">
                        <span>Kecamatan</span>
                        <span class="w-[60%] flex gap-2">
                            <input type="text" placeholder="Kecamatan" required v-model="siswa.kecamatan" class="border-none bg-slate-200 py-1 px-2 w-[75%]" />
                            <input type="text" placeholder="Kd Pos" required v-model="siswa.kode_pos" class="border-none bg-slate-200 py-1 px-2 w-[25%]" />
                            </span>
                    </label>
                    <label for="kabupaten" class="w-full flex items-start justify-between my-2">
                        <span>Kabupaten</span>
                        <input type="text" placeholder="Kabupaten" required v-model="siswa.kabupaten" class="border-none bg-slate-200 py-1 px-2 min-w-[60%]" />
                    </label>
                    <label for="angkatan" class="w-full flex items-start justify-between my-2">
                        <span>Tahun Masuk</span>
                        <input type="text" placeholder="Tahun Masuk" v-model="siswa.angkatan" class="border-none bg-slate-200 py-1 px-2 min-w-[60%]" />
                    </label>
                    <label for="hp" class="w-full flex items-start justify-between my-2">
                        <span>No. HP</span>
                        <input type="text" placeholder="HP" v-model="siswa.hp" class="border-none bg-slate-200 py-1 px-2 min-w-[60%]" />
                    </label>
                    <label for="email" class="w-full flex items-start justify-between my-2">
                        <span>Email</span>
                        <input type="text" placeholder="Email" v-model="siswa.email" class="border-none bg-slate-200 py-1 px-2 min-w-[60%]" />
                    </label>
                    <label for="submit" class="w-full flex items-start justify-center mt-4 mb-2">
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