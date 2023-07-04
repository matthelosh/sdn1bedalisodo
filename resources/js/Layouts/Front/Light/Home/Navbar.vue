<script setup>
import { Icon } from '@iconify/vue';
import { Link, router } from '@inertiajs/vue3';
import { ref, defineAsyncComponent, onMounted } from 'vue';

onMounted(() => {
    const nav = document.querySelector("nav")
    const observHelper = document.querySelector(".sticky-observer")

    const observer = new IntersectionObserver(function(entries, observer) {
        entries.forEach(entry => {
            // console.log(entry.intersectionRatio);
            nav.classList.toggle("bg-transparent", entry.isIntersecting)
            nav.classList.toggle("shadow", !entry.isIntersecting)
            nav.classList.toggle("bg-gray-50", !entry.isIntersecting)
            nav.classList.toggle("text-slate-800", !entry.isIntersecting)
        })
    }, {
        root: null,
        threshold: 0,
        // rootMargin: "-60px"
    });

    observer.observe(document.querySelector(".sticky-observer"))
})

const search = ref('')

const toggleSearch = () => {
    document.querySelector(".search-box").classList.toggle("w-0")
    document.querySelector(".search-box").classList.toggle("opacity-0")
}

const doSearch = (e) => {
    router.visit(route('Search', {_query: {q: search.value}}))
}

const toggleNav = () => {
    const navLink = document.querySelector(".nav-link")
    navLink.classList.toggle("hidden")
}
</script>

<template>
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
                <ul class="nav-link bg-slate-900 p-3 md:p-0 md:bg-transparent fixed top-0 right-0 bottom-0 left-0 md:relative hidden md:flex gap-6 transition">
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
            <div class="right h-full flex justify-end items-center gap-2">
                <span class="h-full relative flex items-center">
                    <input type="text" placeholder="Cari Tulisan" v-model="search" class="search-box w-0 opacity-0 rounded-md bg-sky-50 border-slate-300" @keypress.enter="doSearch" />
                    <Icon icon="mdi:magnify" class="text-2xl absolute top-[50%]  -translate-y-[45%] right-1 cursor-pointer" @click="toggleSearch" />
                </span>
                <button @click="toggleNav" class="md:hidden">
                    <Icon icon="mdi:menu" class="text-2xl text-slate-600" />
                </button>
            </div>
        </div>
    </nav>
</template>