<script setup>
import { Link } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';
import { Icon } from '@iconify/vue';
import axios from 'axios';

onMounted(() => {
    navShow.value = window.innerWidth > 768 ? true : false
    window.addEventListener("resize", function(e) {
        navShow.value = e.target.innerWidth > 768 ? true : false
    })
})

const navShow = ref(false)
const toggleNav = () => {
    navShow.value = !navShow.value
}

const searchQuery = ref(null)
const search = (e) => {
    if(searchQuery.value) {
        window.location.href = `/search?q=${searchQuery.value}`
    }
}

const items = ref([
    {
        label: 'Beranda',
        url: 'Welcome'
    },
    {
        label: 'Profil',
        url: 'About'
    }
])
</script>

<template>
    <nav class="w-full h-10 md:h-8  rounded-b-2xl md:rounded-none shadow bg-white text-dark flex items-center justify-between p-3 md:p-8 fixed md:sticky top-0 z-40">
        <div class="header-logo ">
            <Link href="/" class="flex items-center gap-2">
            <img src="/img/logo.png" alt="Logo" class="w-8 mx-auto drop-shadow" />
           
                <h1 class="flex flex-col leading-3 justify-center pt-1">
                        <span class="font-bold text-teal-800">SDN1</span>
                        <small class=" tracking-tighter text-red-800">Bedalisodo</small>
                </h1>
            </Link>
        </div>
        <div class="header-items flex items-center">
            <button class="md:hidden" @click="toggleNav">
                <Icon icon="mdi:close" class="h-8 text-red-400" v-if="navShow" />
                <Icon icon="mdi:menu" class="h-8 " v-else />
            </button>
            <transition name="expand">
                <ul class="header-items--nav flex items-center flex-col justify-start md:flex-row md:gap-3 w-screen absolute md:relative md:w-auto md:shadow-none left-0 bg-white rounded-b-xl shadow-lg top-12 md:top-0" v-show="navShow">
                    <li v-for="(item, it) in items" :key="it">
                        <Link :href="route(item.url)" class="block text-sky-800 tracking-wide p-3 hover:border-b-4 hover:bg-sky-50 hover:border-sky-800 box-border " :class="route().current() == item.url ? 'border-b-4 border-sky-800 bg-sky-50': ''">{{ item.label }}</Link>
                    </li>
                    <li>
                        <form @submit.prevent="search">
                            <label for="search" class="flex items-center px-2 mb-4 md:mb-0 h-full">
                                <input type="text" id="search" placeholder="Cari" class="rounded-xl w-full" v-model="searchQuery">
                                <Icon icon="mdi:magnify" class="h-6 absolute right-4 text-teal-800" />
                            </label>
                        </form>
                    </li>
                </ul>
            </transition>
        </div>
    </nav>
</template>

<style scoped>
.expand-enter-active,
    .expand-leave-active {
        transition: all .35s ease;
    }

    .expand-enter-to,
    .expand-leave-from {
        opacity: 1;
        /* transform: translateY(100%); */
        overflow: 0;
    }
    .expand-enter-from,
    .expand-leave-to {
        opacity: 0;
        height: 0!important;
    }

</style>