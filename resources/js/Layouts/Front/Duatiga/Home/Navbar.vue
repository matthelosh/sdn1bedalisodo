<script setup>
import { usePage, Link } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import { Icon } from '@iconify/vue';

const page = usePage()

const routes = computed(() => {
   return page.props.categories
})
</script>

<template>
    <div class="row bg-sky-600 w-full">
        <div class="cols flex items-center justify-between w-full sm:w-10/12 md:w-9/12  mx-auto h-8  text-sm">
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
        <div class="cols flex items-center justify-between w-full sm:w-10/12 md:w-9/12 bg-slate-100 mx-auto h-full text-sm">
            <Link href="/" class="logo flex gap-1 items-center">
                <img src="/img/logo.png" alt="Logo" class="h-12">
                <span>
                    <h1 class="leading-3 font-extrabold uppercase text-teal-800">{{ page.props.sekolah.nama }}</h1>
                    <p class="leading-3 text-xs font-light text-slate-600">{{ page.props.sekolah.alamat }}</p>
                </span>
            </Link>
            <div class="nav-menu flex items-center gap-3">
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
                <Link :href="page.props.auth.user ? '/dashboard' : '/login'" class="bg-sky-500 text-white py-1 shadow-lg hover:shadow-sm px-4 rounded flex items-center gap-1">
                    {{ page.props.auth.user ? 'Dashboard' : 'Login' }}
                </Link>
            </div>
        </div>
        
    </div>
</template>