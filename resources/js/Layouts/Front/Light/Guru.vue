<script setup>
import {ref, computed} from 'vue';
import { usePage } from '@inertiajs/vue3';
import { Icon } from '@iconify/vue';

const page = usePage();

const showAll = ref(false)
const gurus = computed(() => {
    return showAll.value  ? page.props.gurus : page.props.gurus.filter(guru => guru.role == 'ks')
})
const jabatan = (role) => {
    const items = {
        "ks": "Kepala Sekolah",
        "gkel": "Guru Kelas",
        "gor": "Guru PJOK",
        "gpai": "Guru PAI",
        "gen": "Guru Bahasa Inggris",
        "pjg": "Penjaga"
    }

    return items[role]
}

const defaultAvatar = ($event, guru) => {
    let image = guru.jk == 'Laki-laki' ? 'guru-l' : (guru.agama == 'Islam' ? 'guru-p-jilbab' : 'guru-p')
    $event.target.src = `/img/${image}.png`
    // return `/img/${image}.png`
}
</script>
<template>
    <div class="w-full bg-white py-8">
        <div class="container w-10/12 md:w-8/12 min-h-[400px] mx-auto relative text-center">
            <div class="header w-full">
                <h1 class="flex justify-center items-top gap-1 font-bold text-sky-950">
                    <Icon icon="mdi:account-tie" class="text-2xl text-sky-800" />
                    <span class="text-xl font-bold">
                        Dewan Guru
                    </span>
                </h1>
            </div>
            <p class="text-center font-mono text-gray-800 md:w-2/4 mx-auto text-lg mt-4">
                Berikut adalah guru-guru kami yang berdidasi tinggi dalam mengemban misi pendidikan di SD Negeri 1 Bedalisodo
            </p>
            <div class="staffs-container flex gap-2 md:gap-6 overflow-x-auto flex-wrap justify-center mt-4">
                <figure
                    v-for="(guru, g) in gurus"
                    :class="g === 0 ? 'w-full mb-6' : 'w-[40%] md:w-[20%] mb-4'"
                    :key="g" >
                    <img
                    
                        :src="`/storage/images/guru/${guru.nip}.jpg`"
                        @error="defaultAvatar($event, guru)"
                        class="rounded-full border-4 border-gray-600 w-[200px] md:w-[300px] hover:border-sky-600 mx-auto shadow" 
                        
                        alt="Guru">
                    <figcaption class="md:mt-4 leading-3">
                        <h1 class="text-center text-lg font-bold font-mono">
                            <span class="hidden md:block">{{ guru.nama }}</span>
                            <span class="md:hidden">{{guru.jk == 'Laki-laki' ? 'Bapak ' : 'Ibu '}} {{ guru.nickname }}</span>
                        </h1>
                        <h1 class="text-center">{{ jabatan(guru.role) }}</h1>
                    </figcaption>
                </figure>
                
            </div>
            <button class="mx-auto py-1 px-2 my-3 rounded-xl bg-sky-400 text-white font-bold" @click="showAll = !showAll">{{showAll ? 'Sembunyikan':'Lihat Semua'}}</button>
        </div>
    </div>
</template>