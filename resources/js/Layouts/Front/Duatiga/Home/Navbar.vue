<script setup>
import { usePage, Link } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import { Icon } from '@iconify/vue';

const page = usePage()

const routes = computed(() => {
   return page.props.categories
})

const toggleNav = () => {
    const navItems = document.querySelector("ul.nav-items")
    navItems.classList.toggle("hidden")
    navItems.classList.toggle("shadow-xl")
}
</script>

<template>
    <div class="row bg-sky-600 w-full">
        <div class="cols flex items-center justify-between w-full sm:w-10/12 md:w-9/12  mx-auto h-8 px-4 sm:px-0 text-xs">
           <div class="contacts flex items-center text-white gap-0 sm:gap-1">
                <div class="telp hidden sm:flex items-center gap-1 text-white text-xs">
                    <Icon icon="mdi:phone" class="hidden sm:inline-block" />
                    <a :href="`tel:${page.props.sekolah.telp}`" >{{ page.props.sekolah.telp }}</a>
                </div>
                
                <div class="email hidden sm:flex  items-center gap-1 text-white text-xs">
                    <Icon icon="mdi:email" class=" sm:inline-block" />
                    <a :href="`mailto:${page.props.sekolah.email}`" >{{ page.props.sekolah.email }}</a>
                </div>
                
                <div class="alamat flex items-center gap-1 text-white text-xs">
                    <Icon icon="mdi:map-marker" class=" sm:inline-block" />
                    <p>{{ page.props.sekolah.alamat }}<span class="inline sm:hidden">, {{ page.props.sekolah.desa }}, {{ page.props.sekolah.kecamatan }}</span></p>
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
        <div class="cols flex items-center justify-between w-full sm:w-10/12 md:w-9/12  px-2 sm:px-0 mx-auto h-full text-sm">
            <Link href="/" class="logo flex gap-1 items-center">
                <img src="/img/logo.png" alt="Logo" class="h-10 sm:h-12">
                <span class="hidden sm:block">
                    <h1 class="leading-3 font-extrabold uppercase text-teal-800">{{ page.props.sekolah.nama }}</h1>
                    <p class="leading-3 text-xs font-light text-slate-600">{{ page.props.sekolah.alamat }}</p>
                </span>
            </Link>
            <div class="motto block sm:hidden">
                <h1 class="font-bold text-lg tracking-wide text-sky-700">MANDITA</h1>
            </div>
            <div class="nav-menu block sm:flex items-center gap-3  ">
                <ul class="nav-items hidden transition-all bg-white sm:bg-transparent fixed sm:relative top-0 right-[40%] bottom-0 left-0 sm:flex gap-2 justify-center text-sm">
                    <li class="block sm:hidden pb-2 pt-4 shadow-md bg-sky-400 text-white">
                        <h1 class="text-center ">Menu</h1>
                    </li>
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
                    <li class="sm:hidden px-3">
                        <Link :href="page.props.auth.user ? '/dashboard' : '/login'" class="bg-sky-500 text-white py-1 shadow-lg hover:shadow-sm px-4 w-[40%] flex items-center gap-1">
                        {{ page.props.auth.user ? 'Dashboard' : 'Login' }}
                    </Link>
                    </li>
                </ul>
                <Link :href="page.props.auth.user ? '/dashboard' : '/login'" class="bg-sky-500 text-white py-1 shadow-lg hover:shadow-sm px-4 rounded sm:flex items-center gap-1 hidden">
                    {{ page.props.auth.user ? 'Dashboard' : 'Login' }}
                </Link>
            </div>
            <button class="block sm:hidden " @click="toggleNav">
                <Icon icon="mdi:menu" class="text-2xl text-slate-600" />
            </button>
        </div>
        
    </div>
</template>