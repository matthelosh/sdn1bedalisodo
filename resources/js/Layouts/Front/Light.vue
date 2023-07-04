<script setup lang="ts">
import { Icon } from '@iconify/vue';
import { Link } from '@inertiajs/vue3';
import { defineAsyncComponent, onMounted } from 'vue';
const VisiMisi = defineAsyncComponent(() => import('./Light/VisiMisi.vue'));
const DewanGuru = defineAsyncComponent(() => import('./Light/Guru.vue'));
const Post = defineAsyncComponent(() => import('./Light/Post.vue'));
const Agenda = defineAsyncComponent(() => import('./Light/Agenda.vue'));
const Footer = defineAsyncComponent(() => import('./Light/Footer.vue'));

onMounted(() => {
    const nav = document.querySelector("nav")
    const observHelper = document.querySelector(".sticky-observer")

    const observer = new IntersectionObserver(function(entries, observer) {
        entries.forEach(entry => {
            console.log(entry.intersectionRatio);
            nav.classList.toggle("bg-transparent", entry.isIntersecting)
            nav.classList.toggle("shadow", !entry.isIntersecting)
            nav.classList.toggle("bg-gray-50", !entry.isIntersecting)
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

const toggleNav = () => {
    const navLink = document.querySelector(".nav-link")
    navLink.classList.toggle("hidden")
}
</script>

<template>

<div class="wrapper bg-white relative">   
    <div class="sticky-observer h-2 md:h-4 bg-pink">&nbsp;</div>

    <nav class="w-full h-16  sticky top-0 bg-gray-50 z-20 transition-all">
        <div class="md:w-8/12 h-full flex items-center justify-between mx-auto px-3 md:px-0">
            <div class="left flex items-center gap-8">
                <Link :href="route('Welcome')" class="flex items-center gap-2">
                    <img src="/img/logo.png" alt="Logo" class="w-8 mx-auto drop-shadow" />
            
                    <h1 class="flex flex-col leading-3 justify-center pt-1">
                            <span class="font-bold text-teal-800">SDN1</span>
                            <small class=" tracking-tighter text-red-800">Bedalisodo</small>
                    </h1>
                </Link>
                <ul class="nav-link bg-slate-900 p-3 md:p-0 md:bg-transparent fixed top-0 right-0 bottom-0 left-0 md:relative hidden md:flex gap-6">
                    <li class="md:hidden text-slate-50 font-bold tracking-wider text-xl flex justify-between border-b-slate-300 border-b-2 py-4">
                        <span>
                            Menu
                        </span>
                        <button @click="toggleNav">
                            <Icon icon="mdi:close" class="text-2xl text-red-400" />
                        </button>
                    </li>
                    <li class="my-1 md:my-0">
                        <Link class="text-slate-100 md:text-slate-800 block py-3 md:py-0" :href="route('About')">Profil</Link>
                    </li>
                    <li class="my-1 md:my-0">
                        <Link class="text-slate-100 md:text-slate-800 block py-3 md:py-0" href="#">Agenda</Link>
                    </li>
                </ul>
            </div>
            <div class="right flex-justify-end items-center gap-4">
                <button>
                    <Icon icon="mdi:magnify" class="text-2xl" />
                </button>
                <button @click="toggleNav" class="md:hidden">
                    <Icon icon="mdi:menu" class="text-2xl text-slate-600" />
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
                <Icon icon="mdi:cloud-outline" class="absolute text-[3rem] md:text-8xl text-gray-200 left-20 top-12 md:top-36" />
                <Icon icon="mdi:weather-sunny" class="absolute text-[3rem] md:text-[4rem] text-gray-200 left-48 top-12 md:top-20 animate-spin" />
                <Icon icon="mdi:cloud-outline" class="absolute text-[6rem] md:text-[8rem] text-gray-200  right-6 md:right-16  top-0 md:top-20" />
                <Icon icon="mdi:weather-windy" class="hidden md:block absolute text-[6rem] md:text-[4rem] text-gray-200  right-6 md:right-36  top-0 md:top-48 animate-pulse" />
                <Icon icon="mdi:home-city-outline" class="absolute text-slate-200 text-[10rem] left-[50%] md:-translate-x-[100%] top-[35%] md:top-[50%]" />
                <Icon icon="mdi:grass" class="absolute text-[3rem] text-green-600 right-4 md:left-20 bottom-4 md:bottom-20  " />
                <img src="/img/siswa.svg" alt="" class="absolute left-[50%] -translate-x-[70%] md:-translate-x-[50%] top-[15%] md:top-[50%] md:-translate-y-[25%]">
                <Icon icon="mdi:grass" class="absolute text-[2rem] md:text-[4rem] text-green-600 left-12 md:left-[60%] bottom-10 md:bottom-20  " />
                <span class="absolute text-[2rem] md:text-[4rem] text-gray-50 left-[58%] md:left-[63%] bottom-[50%] -translate-y-[50%] md:bottom-60 animate-pulse" >
                    <Icon icon="mdi:message"  class="text-[4rem] text-sky-400" />
                    <span class="absolute text-gray-100 font-bold text-sm top-[40%] -translate-y-[50%] left-[50%] -translate-x-[50%]">Halo</span>
                </span>
            </figure>
        </div>
    </header>
    <main class=" h-auto">
        <VisiMisi />
        <Post />
        <DewanGuru />
        <Agenda />
    </main>
    <footer>
        <Footer />
    </footer>
</div>
</template>

<style scoped>
</style>