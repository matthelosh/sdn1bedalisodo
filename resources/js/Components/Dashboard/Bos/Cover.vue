<template>
    <div class="bg-slate-100 print:bg-white">
        <div class="w-full h-10 px-2 bg-white flex items-center justify-between mb-4 print:mb-0 sticky top-10 print:hidden z-10">
            <div class="flex gap-2 items-center h-full">
                <select @change="changeAnggaran" class="h-8 text-sm py-1" >
                    <option v-for="(anggaran, index) in anggarans" :key="anggaran.kode" :value="index">
                        {{ anggaran.uraian }}
                    </option>
                </select>
            </div>
        </div>
        <div class="sheet flex flex-col justify-between min-h-[100vh] bg-white rounded shadow-lg w-[80%] print:w-full mx-auto p-10">
            <div>
                <h1 class="text-2xl font-bold text-center uppercase">LAPORAN</h1>
                <h1 class="text-2xl font-bold text-center uppercase">PERTANGGUNGJAWABAN</h1>
                <h1 class="text-4xl font-bold text-center uppercase">{{ anggaran.uraian }}</h1>
                <h1 class="text-2xl font-bold text-center uppercase">TA {{ anggaran.tahun_anggaran }}</h1>
            </div>
            <div class="mx-auto">
                <div class="relative flex justify-between w-20 mx-auto items-end mb-10">
                    <div class="rect relative w-4 h-10 bg-slate-950"></div>
                    <div class="rect relative w-4 h-20 bg-slate-950"></div>
                    <div class="rect relative w-4 h-10 bg-slate-950"></div>
                </div>
                <img src="/img/logo.png" alt="Logo SD" class="mx-auto w-40">
                <div class="relative flex justify-between w-20 mx-auto items-start mt-10">
                    <div class="rect relative w-4 h-10 bg-slate-950"></div>
                    <div class="rect relative w-4 h-20 bg-slate-950"></div>
                    <div class="rect relative w-4 h-10 bg-slate-950"></div>
                </div>
            </div>
            <div class="mb-4">
                <h1 class="text-2xl font-bold text-center uppercase">{{ sekolah.nama }}</h1>
                <p class="text-center">{{ sekolah.alamat }}, {{ sekolah.desa }}</p>
                <p class="text-center">Kecamatan {{ sekolah.kecamatan }}, Kabupaten {{ sekolah.kabupaten }}, Kode Pos {{ sekolah.kode_pos }}</p>
                <p class="text-center">Telp.: {{ sekolah.telp }}, Email: {{ sekolah.email }}</p>
                <p class="text-center">Website: {{ sekolah.website }}</p>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onBeforeMount } from 'vue';
import { usePage } from '@inertiajs/vue3';
const page = usePage();
const sekolah = computed(() => page.props.sekolah)
const anggarans = page.props.anggarans;

const anggaran = ref({})
const changeAnggaran = (e) => {
    anggaran.value = anggarans[e.target.value]
}

onBeforeMount(() => {
    anggaran.value = anggarans[0]
})
</script>