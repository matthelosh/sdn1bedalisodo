<script setup>
import { Icon } from '@iconify/vue';
import axios from 'axios';
import {onMounted, ref } from 'vue';
const props = defineProps({ selectedSiswa: Object })
const emit = defineEmits(['close'])

const close = () => {
    emit('close')
}

const newFoto = ref(null)

const loading = ref(false)
const simpan = async() => {
    let fd = new FormData()
    fd.append("foto", props.selectedSiswa.fileFoto)
    fd.append("nisn", props.selectedSiswa.nisn)
    fd.append("_method", 'put')
    loading.value = true
    await axios.post(route('dashboard.siswa.foto.update'), fd)
                .then(res => {
                    loading.value = false
                    emit('close')
                }).catch(err => {
                    loading.value = false
                    console.log(err)
                })
}

onMounted(() => {
    // console.log(props)
    newFoto.value = URL.createObjectURL(props.selectedSiswa.fileFoto)
})
</script>

<template>
    <div class="w-full bg-slate-100 bg-opacity-80 backdrop-blur-sm fixed top-0 right-0 bottom-0 left-0 flex items-center justify-center z-40" @click.self="close">
        <div class="dialog w-[95vw] md:w-[400px] bg-slate-50 shadow p-2">
            <h1 class="text-xl mt-2 text-slate-600 text-center">Ganti Foto</h1>
            <h1 class="text-xl mt-2 text-slate-600 text-center leading-3"><small>{{ selectedSiswa.nama }}</small></h1>
            <div class="content w-full grid grid-cols-2 px-2 gap-2">
                <div class="old-foto">
                    <img :src="props.selectedSiswa.foto_url ? props.selectedSiswa.foto_url : (props.selectedSiswa.jk && props.selectedSiswa.jk == 'Laki-laki') ? '/img/siswa.png' : '/img/siswi.png'" alt="Siswa" class="aspect-square bg-slate-400 border border-slate-400 hover:bg-slate-100 shadow hover:shadow-lg mx-auto  mt-4 cursor-pointer object-cover w-48" title="Klik untuk membuka/mengganti foto" />
                    <h2 class="text-center">Foto Lama</h2> 
                </div>
                <div class="new-foto">
                    <img :src="newFoto" alt="Siswa" class="aspect-square bg-slate-400 border border-slate-400 hover:bg-slate-100 shadow hover:shadow-lg mx-auto  mt-4 cursor-pointer object-cover w-48" /> 
                    <h2 class="text-center">Foto Baru</h2> 
                </div>
                <div class="col-span-2 flex justify-center">
                    <button class=" bg-sky-600 hover:bg-sky-400 active:bg-sky-300 text-white py-2 w-full flex items-center gap-2 justify-center" @click="simpan">
                        <Icon icon="mdi:loading" class="animate-spin" v-if="loading" />
                        Simpan
                    </button>
                </div>
            </div>
            
            <!-- {{props.selectedSiswa.jk}} -->
        </div>
    </div>
</template>