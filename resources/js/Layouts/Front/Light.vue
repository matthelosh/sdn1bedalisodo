<script setup lang="ts">
import { Icon } from '@iconify/vue';
import { Link } from '@inertiajs/vue3';
import { defineAsyncComponent, onMounted } from 'vue';
const VisiMisi = defineAsyncComponent(() => import('./Light/VisiMisi.vue'));
const DewanGuru = defineAsyncComponent(() => import('./Light/Guru.vue'));
const Post = defineAsyncComponent(() => import('./Light/Post.vue'));

onMounted(() => {
    const nav = document.querySelector("nav")
    const observHelper = document.querySelector(".sticky-observer")

    const observer = new IntersectionObserver(function(entries, observer) {
        entries.forEach(entry => {
            console.log(entry.intersectionRatio);
            nav.classList.toggle("bg-transparent", entry.isIntersecting)
            nav.classList.toggle("shadow", !entry.isIntersecting)
            nav.classList.toggle("bg-slate-100", !entry.isIntersecting)
            nav.classList.toggle("text-slate-800", !entry.isIntersecting)
            // nav.classList.toggle("instersected", !entry.isIntersecting)
        })
    }, {
        root: null,
        threshold: 0,
        // rootMargin: "-60px"
    });

    observer.observe(document.querySelector(".sticky-observer"))
})
</script>

<template>

<div class="wrapper bg-sky-100 relative">   
    <div class="sticky-observer h-2 md:h-4 bg-pink">&nbsp;</div>

    <nav class="w-full h-16  sticky top-0 bg-gray-50 z-20 transition-all">
        <div class="md:w-8/12 h-full flex items-center justify-between mx-auto px-3 md:px-0">
            <div class="left flex items-center gap-8">
                <Link class="logo w-32" :href="route('Welcome')">
                    <h1 class="text-2xl font-bols tracking-wide">LOGO</h1>
                </Link>
                <ul class="nav-link flex gap-6">
                    <li>
                        <Link :href="route('About')">Profil</Link>
                    </li>
                    <li>
                        <Link href="#">Agenda</Link>
                    </li>
                </ul>
            </div>
            <div class="right flex-justify-end items-center gap-3">
                <button>
                    <Icon icon="mdi:magnify" class="text-2xl" />
                </button>
            </div>
        </div>
    </nav>
    <!-- <img src="/img/blob1.svg" alt="Blob 1" class="absolute w-[60rem] -translate-y-[32rem] -translate-x-64 z-0 opacity-30" /> -->
    <header class="w-full  md:h-[70vh] relative" >
        <div class="md:w-8/12 mx-auto md:grid md:grid-cols-2 gap-4 h-full ">
            <div class="flex flex-col items-center justify-center h-[20vh] md:h-full ">
                <h1 class="text-6xl font-[950] tracking-widest md:text-[6rem]">
                    <span class="bg-sky-600 text-white px-1">
                        MANDITA
                    </span>
                </h1>
                <h2 class="text-2xl md:text-4xl flex justify-center items-center mt-6 border-b-4 border-dashed border-gray-600 pb-1">
                    Mandiri
                    <Icon icon="mdi:circle-medium" class="text-gray-600 animate-pulse" />
                    Disiplin
                    <Icon icon="mdi:circle-medium" class="text-gray-600 animate-pulse" />
                    Bertakwa
                </h2>
            </div>
            <figure class="relative h-[30vh] w-full md:h-auto ">
                <!-- <img src="/img/gunung.svg" alt="" class="absolute"> -->
                <Icon icon="mdi:cloud-outline" class="absolute text-[3rem] md:text-8xl text-white left-20 top-8 md:top-20" />
                <Icon icon="mdi:cloud-outline" class="absolute text-[6rem] md:text-[8rem] text-white  right-6 md:right-16  top-0 md:top-20" />
                <Icon icon="mdi:home-city-outline" class="absolute text-white text-[10rem] left-[50%] md:-translate-x-[100%] top-[35%] md:top-[50%]" />
                <Icon icon="mdi:grass" class="absolute text-[3rem] text-green-600 right-4 md:left-20 bottom-4 md:bottom-14  " />
                <img src="/img/siswa.svg" alt="" class="absolute left-[50%] -translate-x-[70%] md:-translate-x-[50%] top-[15%] md:top-[50%] md:-translate-y-[25%]">
                <Icon icon="mdi:grass" class="absolute text-[2rem] md:text-[4rem] text-green-600 left-12 md:right-36 bottom-10  " />
            </figure>
        </div>
    </header>
    <main class=" h-auto">
        <VisiMisi />
        <Post />
        <DewanGuru />
        
    </main>
</div>
</template>

<style scoped>
</style>