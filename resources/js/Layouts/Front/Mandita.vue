<script setup>

import { ref, onMounted, defineAsyncComponent, computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';

const page = usePage();
const Header = defineAsyncComponent(() => import('@/Layouts/Front/Mandita/Header.vue'))
const Navbar = defineAsyncComponent(() => import('@/Layouts/Front/Mandita/Navbar.vue'))
const Footer = defineAsyncComponent(() => import('@/Layouts/Front/Mandita/Footer.vue'))





</script>

<template>
<div class="wrapper  text-dark w-full min-h-screen bg-gray-100">
    <Header />
    <Navbar />
    <main class="w-full min-h-[95vh] relative">
        <slot />
    </main>
    <Footer />
</div>

</template>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Monoton&display=swap');


    .slide-enter-active,
    .slide-leave-active {
        transition: all .35s ease-in-out;
    }

    .slide-enter-from,
    .slide-leave-to {
        transform: translateX(100%);
    }
    .fade-enter-active,
    .fade-leave-active {
        transition: all .35s ease-in-out;
    }

    .fade-enter-from,
    .fade-leave-to {
        opacity: 0;
    }


</style>