<script setup>
import { Head, Link, usePage } from '@inertiajs/vue3';
import { Icon } from '@iconify/vue';
import { ref, computed, defineAsyncComponent } from 'vue';

const page = usePage();

const Header = defineAsyncComponent(() => import('./Duatiga/Home/Navbar.vue'))

const Carousel = defineAsyncComponent(() => import('@/Layouts/Front/Duatiga/Home/Carousel.vue'))
const defaultImg = (e) => {
    e.target.src =  '/img/cover-artikel.jpg'
}
</script>

<template>
    <!-- <div class="row bg-sky-600 w-full">
        <div class="cols flex items-center justify-between w-full sm:w-10/12 md:w-9/12  mx-auto h-8 px-3 text-sm">
           <div class="contacts flex items-center text-white gap-1">
                <div class="telp flex items-center gap-1 text-white text-xs">
                    <Icon icon="mdi:phone" />
                    <a :href="`tel:${page.props.sekolah.telp}`" >{{ page.props.sekolah.telp }}</a>
                </div>
                |
                <div class="email flex items-center gap-1 text-white text-xs">
                    <Icon icon="mdi:email" />
                    <a :href="`mailto:${page.props.sekolah.email}`" >{{ page.props.sekolah.email }}</a>
                </div>
                |
                <div class="alamat flex items-center gap-1 text-white text-xs">
                    <Icon icon="mdi:map-marker" />
                    <p>{{ page.props.sekolah.alamat }}</p>
                </div>
           </div>
           <div class="sosmed flex items-center gap-1">
                <a href="https://facebook.com" target="_blank">
                    <Icon icon="mdi:facebook" class="text-lg text-slate-100 hover:text-white active:text-slate-100" />
                </a>
                <a href="https://youtube.com" target="_blank">
                    <Icon icon="mdi:youtube" class="text-lg text-slate-100 hover:text-white active:text-slate-100" />
                </a>
                <a href="https://instagram.com" target="_blank">
                    <Icon icon="mdi:instagram" class="text-lg text-slate-100 hover:text-white active:text-slate-100" />
                </a>
           </div>
        </div>
    </div>
    <div class="row navbar h-16 bg-slate-100 w-full sticky top-0 z-30 shadow-xl">
        <div class="cols flex items-center justify-between w-full sm:w-10/12 md:w-9/12 bg-slate-100 mx-auto h-full px-3 text-sm">
            <Link href="/" class="logo flex gap-1 items-center">
                <img src="/img/logo.png" alt="Logo" class="h-12">
                <span>
                    <h1 class="leading-3 font-extrabold uppercase text-teal-800">{{ page.props.sekolah.nama }}</h1>
                    <p class="leading-3 text-xs font-light text-slate-600">{{ page.props.sekolah.alamat }}</p>
                </span>
            </Link>
            <div class="nav-menu">
                <ul class="flex gap-2 justify-center text-sm">
                <li>
                    <Link href="/"
                        class="text-slate-600 block p-3 hover:bg-slate-50">
                        Beranda
                    </Link>
                </li>
                <li
                    v-for="menu in routes"
                >
                    <Link
                        :href="`/kategori/${menu.label.toLowerCase()}`"
                        class="text-slate-600 block p-3 hover:bg-slate-50"
                    >
                        {{ menu.label }}
                    </Link>
                </li>
            </ul>
            </div>
        </div>
        
    </div> -->
    <Header />
    <div class="row hero w-full">
        <div class="hero-content w-full sm:w-10/12 md:w-full bg-slate-300 mx-auto">
            <Carousel :items="page.props.beritas" />
        </div>
    </div>
    <div class="row main w-full py-20 bg-slate-100">
        <div class="main-content w-full sm:w-10/12 md:w-9/12  mx-auto grid gap-4 grid-cols-12">
            <Link 
                class="card col-span-3 rounded-md bg-white shadow-lg hover:shadow hover:cursor-pointer"
                :href="`/${post.category.label.toLowerCase()}/${post.slug}`"
                v-for="(post,i) in page.props.posts"
                :class="i == 0 || i == (page.props.posts.length-2)  ? 'col-span-6 row-span-2':'col-span-3 row-span-1'"
            >
                <figure class="relative h-[100px]">
                    <div class="overlay absolute top-0 right-0 bottom-0 left-0 bg-pink-200 p-4 bg-opacity-50 mix-blend-multiply">
                        <!-- <h1>{{ post.category.label }}</h1> -->
                    </div>
                    <img :src="post.featured_image" :alt="post.title" class="w-full object-cover h-full" @error="defaultImg">
                </figure>
                <div class="card-content">
                    <h1 class=" p-2 text-lg font-bold text-slate-800 leading-5 w-full  font-serif">{{ post.title }}</h1>
                    <p v-if="i == 0" v-html="post.content.substr(0,100)" class="p-2 text-sm t text-slate-600"></p>
                </div>
            </Link>
        </div>
    </div>
    <div class="row main w-full py-20 bg-slate-100">
        <div class="main-content w-full sm:w-10/12 md:w-9/12  mx-auto grid gap-4 grid-cols-12">

        </div>
    </div>
</template>