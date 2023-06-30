<script setup>
import {Link, Head, usePage} from '@inertiajs/vue3';
import { ref , defineAsyncComponent} from 'vue';
import { Icon } from '@iconify/vue'
import Navbar from './Default/Navbar.vue';
import Footer from './Default/Footer.vue';

const page = usePage()

const Prestasi = defineAsyncComponent(() => import('@/Components/Front/FrontPrestasi.vue'))
const Carousel = defineAsyncComponent(() => import('@/Components/Front/Carousel.vue'))
</script>

<template>
<div class="w-min-screen h-min-scren bg-sky-200 ">
    <header class="md:w-3/4 w-full mx-auto  md:bg-sky-950 md:block z-40 relative">
        <div class="w-11/12 mx-auto text-white h-10 justify-between items-center md:flex hidden">
            <div class="flex items-center gap-2">
                <Icon icon="mdi-domain" />
                <span class="text-xs">Jl. Raya Sengon No. 293, Dalisodo Kec. Wagir</span>
            </div>
            <div class="flex items-center gap-2">
                <span class="text-xs text-yellow-300">Menerima Calon Peserta Didik Baru Tahun 2023/2024</span>
            </div>

        </div>
        
    </header>
    <Navbar class="sticky top-0" />
    <main class="w-full md:w-3/4 mx-auto shadow-lg ">
        <div class="imagery relative  h-92 bg-gray-200 p-4 grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="carousel col-span-1 md:col-span-2 overflow-hidden">
                <Carousel />
            </div>
            <div class="sambutan bg-white w-full md:col-span-2 p-4">
                <h1>Sambutan Kepala Sekolah</h1>
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Officia minus expedita nulla ea, cum quisquam velit odit hic id excepturi doloribus cumque placeat ut consectetur aut alias sequi incidunt commodi? Lorem ipsum dolor sit amet consectetur, adipisicing elit.</p>
            </div>
        </div>

        <div class="lates-post w-full bg-gray-300 p-4">
            <h1 class="text-sky-800 font-bold text-lg mb-2 text-center">Pos Terbaru</h1>
            <!-- <hr class="mb-4 mt-2 border-sky-800 border-dashed border-1" /> -->
            <div class="post-holder grid grid-cols-1 md:grid-cols-3 gap-3 ">
                <Link
                    as="article"
                    v-for="(post,p) in page.props.posts"
                    :key="p"
                    :href="`/${post.category.label.toLowerCase()}/${post.slug}`"
                    class="bg-white rounded p-2 shadow hover:shadow-lg hover:cursor-pointer"
                    v-motion
                    :initial="{ opacity: 0, x: 50 }"
                    :enter="{ opacity: 1, x: 0, scale: 1 }"
                    :delay="200 + (p * 100)"
                >
                    <h1 class="text-gray-800 font-bold">{{ post.title }}</h1>
                    <div class="grid grid-cols-4 gap-2">
                        <img :src="post.featured_image" alt="" class="w-full col-span-1 aspect-square object-cover">
                        <span class="col-span-3" v-html="post.content.substring(0,100)"></span>
                    </div>
                </Link>
            </div>
        </div>
        <div class="guru w-full p-4 bg-gray-100">
            <div class="mx-auto flex overflow-x-auto md:grid md:grid-cols-4 gap-3 flex-grow-0 scroll-mx-0">
                <div
                    v-for="(guru, g) in page.props.gurus" :key="g"
                    v-motion
                    :visibleOnce="{opacity: 1.0, x: 0, scale: 1}"
                    :initial="{ opacity: 0, x: -50 }"
                    class="bg-white p-3 rounded h-80 shadow-lg flex items-center justify-center flex-grow-1 w-[400px] md:w-full"
                >
                    <div class="content">
                        <Icon icon="mdi:account-circle" class="text-8xl text-gray-600 mx-auto" />
                        <h1 class="text-center font-bold text-gray-800">{{ guru.nama }}</h1>
                        <h1 class="text-center">{{ guru.nip }}</h1>
                        <h1 class="text-center font-mono font-bold">{{ guru.role }}</h1>

                    </div>
                </div>
            </div>
        </div>
        <!-- <slot /> -->
    </main>
    <footer class="md:w-3/4 mx-auto">
        <Footer />
    </footer>
</div>
</template>