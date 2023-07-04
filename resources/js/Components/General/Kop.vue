<script setup>
import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';
const page = usePage();

const sekolah = computed(() => {
    return page.props.sekolah
})
</script>

<template>
    <div class="w-full bg-white flex columns-6 border-b-4 border-b-black border-double">
        <div class="logo-kab  col-span-1  flex items-center justify-center">
            
            <img src="/img/malangkab.png" alt="" class="w-24">
        </div>
        <div class="identitas col-span-4 w-full py-3">
            <h2 class="text-center leading-4 font-bold">PEMERINTAH KABUPATEN <span class="uppercase">{{ sekolah.kabupaten }}</span></h2>
            <h2 class="text-center leading-4 font-bold">DINAS PENDIDIKAN</h2>
            <h2 class="text-center leading-4 font-bold">KOORDINATOR WILAYAH KECAMATAN WAGIR</h2>
            <h2 class="text-center  font-black text-xl"><span class="uppercase">{{ sekolah.nama }}</span></h2>
            <h2 class="text-center leading-4 font-bold"><span class="uppercase">NPSN: {{ sekolah.npsn }} NSS:{{ sekolah.nss }}</span></h2>
            <h3 class="text-center leading-4"><small>{{ sekolah.alamat }}, {{sekolah.desa}}, {{ sekolah.kecamatan }}, Kode Pos {{ sekolah.kode_pos }}</small></h3>
            <h3 class="text-center leading-4"><small>Telp.: {{ sekolah.telp }}, Email: {{sekolah.email}}, Website:{{ sekolah.website }}</small></h3>
        </div>
        <div class="logo-sd col-span-1 flex items-center justify-center">
            <img src="/img/logo.png" class="aspect-square w-24" >
        </div>
    </div>
</template>