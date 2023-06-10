<script setup>
import { Link } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';
import { Bars3CenterLeftIcon, MagnifyingGlassIcon } from '@heroicons/vue/24/outline';

onMounted(() => {
    navShow.value = window.innerWidth > 768 ? true : false
    window.addEventListener("resize", function(e) {
        navShow.value = e.target.innerWidth > 768 ? true : false
    })
})

const navShow = ref(false)
const toggleNav = () => {
    // document.querySelector('.header-items--nav').classList.toggle('hidden')
    navShow.value = !navShow.value
}

const search = (e) => {
    
}
</script>

<template>
    <nav class="w-full h-14 md:h-16  rounded-b-2xl md:rounded-none shadow-lg bg-white text-dark flex items-center justify-between p-3 md:p-8 fixed md:sticky top-0 z-40">
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
                <Bars3CenterLeftIcon class="h-8" />
            </button>
            <transition name="expand">
                <ul class="header-items--nav flex flex-col justify-start md:flex-row md:gap-3 w-screen absolute md:relative md:w-auto md:shadow-none left-0 bg-white rounded-b-xl shadow-lg top-12 md:top-0" v-show="navShow">
                    <li>
                        <a href="#" class="block tracking-wide transition-all p-3 text-secondary hover:font-semibold">
                            Home
                        </a>
                    </li>
                    <li><Link :href="route('about')" class="block tracking-wide transition-all p-3 text-secondary hover:font-semibold">About</Link></li>
                    <li><a href="#" class="block tracking-wide transition-all p-3 text-secondary hover:font-semibold">Students</a></li>
                    <li>
                        <form @submit.prevent="search">
                            <label for="search" class="flex items-center px-2 mb-4 md:mb-0 h-full">
                                <input type="text" id="search" placeholder="Cari" class="rounded-xl w-full">
                                <MagnifyingGlassIcon class="h-6 absolute right-4 text-teal-800" />
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