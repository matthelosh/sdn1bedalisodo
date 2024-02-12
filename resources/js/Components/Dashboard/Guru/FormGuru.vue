<script setup>
import { ref, onMounted } from  'vue';
import { Icon } from '@iconify/vue';
import axios from 'axios';

const emit = defineEmits(['close'])

const props = defineProps({selectedGuru: Object})

const loading = ref(false)
const guru = ref({
    pangkat: '0',
    jk: '0',
    agama: '0'
})
const simpan = async() => {
    loading.value = true
    await axios.post(route('dashboard.guru.store'), { guru: JSON.stringify(guru.value)})
                .then(res => {
                    loading.value = false
                    emit('close')
                }).catch(err => {
                    console.log(err)
                    loading.value = false
                })
}

const pangkats = ref([
    { value: '0', text: 'Pilih Pangkat'},
    { value: 'iiia', text: 'Guru Pertama [Penata Muda] | III/a'},
    { value: 'iiib', text: 'Guru Pertama [Penata Muda Tk 1] | III/b'},
    { value: 'iiic', text: 'Guru Muda [Penata] | III/c'},
    { value: 'iiid', text: 'Guru Muda [Penata Tk 1] | III/d'},
    { value: 'iva', text: 'Guru Madya [Pembina] | IV/a'},
    { value: 'ivb', text: 'Guru Madya [Pembina Tk 1] | IV/b'},
    { value: 'ivc', text: 'Guru Madya [Pembina Utama Muda] | IV/c'},
    { value: 'ivd', text: 'Guru Utama [Pembina Utama Madya] | IV/d'},
    { value: 'ive', text: 'Guru Utama [Pembina Utama] | IV/e'},
])

onMounted(() => {
  if (props.selectedGuru !== null) {
      guru.value = props.selectedGuru
  }
})
</script>

<template>
    <div class="w-full bg-slate-100 bg-opacity-20 backdrop-blur-sm fixed top-0 right-0 bottom-0 left-0 flex items-center justify-center z-40" @click.self="emit('close')">
        <div class="dialog bg-white rounded shadow w-[400px] max-w-[800px]">
            <div class="toolbar h-10 flex items-center justify-between w-full p-2 bg-slate-200">
                <h1>Data guru</h1>
                <div class="toolbar-items flex items-center gap-2 justify-end">
                    <button @click="emit('close')">
                        <Icon icon="mdi:close" class="text-2xl text-red-600 hover:text-red-400 active:text-red-800" />
                    </button>
                </div>
            </div>
            <div class="content p-2">
                <form ref="formGuru" @submit.prevent="simpan">
                    <fieldset :disabled="loading">
                        <label for="nip" class="w-full flex items-center  justify-between">
                            NIP
                            <input type="text" class="bg-slate-200 border-0 rounded py-1 w-[60%]" placeholder="NIP" required v-model="guru.nip">
                        </label>
                        <label for="nickname" class="w-full flex items-center  justify-between">
                            Nama Panggilan
                            <input type="text" class="bg-slate-200 border-0 rounded py-1 w-[60%]" placeholder="Nama Panggilan" required v-model="guru.nickname">
                        </label>
                        <label for="nama" class="w-full flex items-center  justify-between">
                            Nama Lengkap
                            <input type="text" class="bg-slate-200 border-0 rounded py-1 w-[60%]" placeholder="Nama Lengkap" required v-model="guru.nama">
                        </label>
                        <label for="gelar" class="w-full flex items-center  justify-between gap-2">
                            Gelar
                            <div class="w-[60%] flex gap-1">
                                <input type="text" class="bg-slate-200 border-0 rounded py-1 w-full" placeholder="Depan"  v-model="guru.gelar_depan">
                                <input type="text" class="bg-slate-200 border-0 rounded py-1 w-full" placeholder="Belakang"  v-model="guru.gelar_belakang">
                            </div>
                        </label>
                        <label for="pangkat" class="w-full flex items-center  justify-between">
                            Pangkat
                            <select class="bg-slate-200 border-0 rounded py-1 w-[60%]"  v-model="guru.pangkat">
                                <option
                                    v-for="pangkat in pangkats" :key="pangkat.value"
                                    :value="pangkat.value">
                                        {{ pangkat.text }}
                                </option>
                            </select>
                        </label>
                        <label for="jk" class="w-full flex items-center  justify-between">
                            Jenis Kelamin
                            <select class="bg-slate-200 border-0 rounded py-1 w-[60%]" required v-model="guru.jk">
                                <option value="0">Pilih Jenis Kelamin</option>
                                <option value="Laki-laki">Laki-laki</option>
                                <option value="Perempuan">Perempuan</option>
                            </select>
                        </label>
                        <label for="tempat_lahir" class="w-full flex items-center  justify-between">
                            Tempat Lahir
                            <input type="text" class="bg-slate-200 border-0 rounded py-1 w-[60%]" placeholder="Tempat Lahir" required v-model="guru.tempat_lahir">
                        </label>
                        <label for="tanggal_lahir" class="w-full flex items-center  justify-between">
                            Tanggal Lahir
                            <input type="date" class="bg-slate-200 border-0 rounded py-1 w-[60%]" required v-model="guru.tanggal_lahir">
                        </label>
                        <label for="agama" class="w-full flex items-center  justify-between ">
                            Agama
                            <select class="bg-slate-200 border-0 rounded py-1 w-[60%]" required v-model="guru.agama">
                                <option value="0">Pilih Agama</option>
                                <option value="Islam">Islam</option>
                                <option value="Kristen">Kristen</option>
                                <option value="Katolik">Katolik</option>
                                <option value="Hindu">Hindu</option>
                                <option value="Budha">Budha</option>
                                <option value="Konghuchu">Konghuchu</option>
                            </select>
                        </label>
                        <label for="hp" class="w-full flex items-center  justify-between ">
                            No. HP
                            <input type="text" class="bg-slate-200 border-0 rounded py-1 w-[60%]" placeholder="No. HP" required v-model="guru.hp">
                        </label>
                        <label for="alamat" class="w-full ">
                            Alamat
                            <textarea type="text" class="bg-slate-200 border-0 rounded py-1 w-full" placeholder="Alamat" required v-model="guru.alamat"></textarea>
                        </label>
                        <div class="flex items-center justify-center py-2">
                            <button type="submit" class="py-1 px-3 rounded bg-slate-800 text-white hover:bg-slate-400 flex items-center" :disabled="loading">
                                <Icon icon="mdi:refresh" class="animate-spin" v-if="loading" />
                                Simpan
                            </button>
                        </div>
                    </fieldset>
                </form>
            </div>
        </div>
    </div>
</template>

<style scoped>
label {
    border-bottom: 1px solid #dbd9d9;
    padding: .3rem 0;
}
</style>
