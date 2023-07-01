<script setup>
import {ref, computed} from 'vue';
import { usePage } from '@inertiajs/vue3';

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
        "gen": "Guru Bahasa Inggris"
    }

    return items[role]
}
</script>
<template>
    <div class="w-full bg-white py-8">
            <div class="container w-10/12 md:w-8/12 h-[400px] mx-auto py-8 relative text-center">
                <h1 class="font-mono text-2xl text-center tracking-wider font-bold text-gray-800">DEWAN GURU</h1>
                <p class="text-center font-mono text-gray-800 md:w-2/4 mx-auto text-lg">
                    Berikut adalah guru-guru kami yang berdidasi tinggi dalam mengemban misi pendidikan di SD Negeri 1 Bedalisodo
                </p>
                <div class="staffs-container flex gap-2 md:gap-6 overflow-x-auto flex-wrap justify-center mt-8">
                    <figure
                        v-for="(guru, g) in gurus"
                        :class="g === 0 ? 'w-full mb-6' : 'w-[40%] md:w-[20%] mb-4'"
                        :key="g" >
                        <img
                        
                            src="/img/guru.png"
                            class="rounded-full border-4 border-gray-600 w-[200px] md:w-[300px] hover:border-sky-600 mx-auto shadow" 
                            
                            alt="Guru">
                        <figcaption class="mb:mt-2 leading-3">
                            <h1 class="text-center text-lg font-bold font-mono">{{ guru.nickname }}</h1>
                            <h1 class="text-center">{{ jabatan(guru.role) }}</h1>
                        </figcaption>
                    </figure>
                    
                </div>
                <button class="mx-auto py-1 px-2 rounded-xl bg-sky-400 text-white font-bold" @click="showAll = !showAll">{{showAll ? 'Sembunyikan':'Lihat Semua'}}</button>
            </div>
        </div>
</template>