<script setup>
import { Head, Link, usePage } from '@inertiajs/vue3';
import { Icon } from '@iconify/vue';
import { ref, computed, defineAsyncComponent } from 'vue';

const page = usePage();

const Header = defineAsyncComponent(() => import('./Duatiga/Home/Navbar.vue'))
const Main = defineAsyncComponent(() => import('./Duatiga/Home/Main.vue'))
const Footer = defineAsyncComponent(() => import('./Duatiga/Home/Footer.vue'))

const Carousel = defineAsyncComponent(() => import('@/Layouts/Front/Duatiga/Home/Carousel.vue'))

</script>

<template>
    <Header />
    <div class="row hero w-full">
        <div class="hero-content w-full sm:w-10/12 md:w-full bg-slate-300 mx-auto">
            <Carousel :items="page.props.beritas" />
        </div>
    </div>
    
    <Main :posts="page.props.posts" />
    <div class="row main w-full py-20 bg-slate-100">
        <div class="main-content w-full sm:w-10/12 md:w-9/12  mx-auto grid gap-4 grid-cols-12">

        </div>
    </div>
    <Footer />
</template>