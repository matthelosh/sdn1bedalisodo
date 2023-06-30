<script setup lang="ts">
import { Icon } from '@iconify/vue';
import { Link } from '@inertiajs/vue3';
import { onMounted } from 'vue';

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
<div class="wrapper">   
    <div class="sticky-observer h-6 md:h-8 bg-pink">&nbsp;</div>
    
    <nav class="w-full h-16 flex items-center sticky justify-between px-3 top-0 bg-gray-50 z-10 transition-all">
            <div class="left flex items-center gap-8">
                <Link class="logo w-32" :href="route('Welcome')">
                    <h1 class="text-2xl font-bols tracking-wide">LOGO</h1>
                </Link>
                <ul class="nav-link flex gap-3">
                    <li>
                        <Link :href="route('Welcome')">Home</Link>
                    </li>
                    <li>
                        <Link :href="route('About')">Profil</Link>
                    </li>
                    <li>
                        <Link href="#">Lain</Link>
                    </li>
                </ul>
            </div>
            <div class="right flex-justify-end items-center gap-3">
                <button>
                    <Icon icon="mdi:magnify" class="text-2xl" />
                </button>
            </div>
    </nav>
    <!-- <img src="/img/blob1.svg" alt="Blob 1" class="absolute w-[60rem] -translate-y-[32rem] -translate-x-64 z-0 opacity-30" /> -->
    <header class="w-full  md:h-[60vh] relative" >
        <div class="w-10/12 mx-auto md:grid md:grid-cols-2 gap-4">
            <div class="flex flex-col items-center justify-center">
                <h1 class="text-4xl font-bold md:text-8xl">MANDITA</h1>
                <h2 class="text-xl md:text-4xl">Mandiri - Disiplin - Bertakwa</h2>
            </div>
            <img src="/img/hero_image.png" alt="Hero Image" class=" md:w-80 mx-auto" />
        </div>
    </header>
    <main class=" h-[200vh] p-3">
        <!-- <slot /> -->
    </main>
</div>
</template>

<style scoped>
/* .wrapper {
    background-position: -100px -150px;
    background-size: 300px;
}
@media screen and(min-width: 1024px) {
    .wrapper {
        background-position: -500px -600px;
        background-size: 100%!important;
        background-repeat: no-repeat; 
    } 
} */
</style>