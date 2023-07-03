<script setup>
import { ref, computed, onMounted } from 'vue';
import { usePage, Link, router } from '@inertiajs/vue3';
import SvgIcon from '@jamescoyle/vue-icon';
import { mdiExitToApp, mdiClose, mdiMenu, mdiViewDashboardOutline } from '@mdi/js';
import SidebarItem from '@/Layouts/Admin/SidebarItem.vue'

onMounted(() => {
    const aside = document.querySelector("aside")
    const asideStyle = window.getComputedStyle(aside)
    // console.log(asideStyle)
    asideWidth.value = parseInt(asideStyle.width)+'px'
    // console.log(window.getComputedStyle(aside))
})

const asideWidth = ref('')

const props = defineProps({title: String})
const page = usePage()
const showSide = ref(false)

// const side = computed(() => {
//     const aside = document.querySelector("aside")
//     return window.getComputedStyle(aside)
//     // const asideElement = window.getComputedStyle(aside)
//     // return aside.style
// })

const logout = async() => {
    router.post(route('logout'))
}
</script>
<template>
<div class="wrapper grid grid-cols-12 h-screen w-auto bg-gray-400">
    <Transition name="slide">
        <aside :class="!showSide ? 'hidden md:block' : 'md:block'" class=" absolute md:relative md:top-0 overflow-x-hidden md:w-full z-10 w-[60vw]  md:col-span-2 bg-sky-200 h-screen transition-transform duration-150 print:hidden col-span-4">
            <div class="avatar p-4 bg-sky-800">
                <img src="/img/logo.png" alt="Avatar" class="w-[100px] aspect-square object-contain mx-auto">
                <h1 class="text-center text-white mt-3">
                    {{ page.props.auth.user.name }}
                </h1>
                <small></small>
            </div>
            <div class="sidenav">
                <SidebarItem />
                <ul>
                    <li >
                        <a href="#" @click.prevent="logout" class="block hover:bg-white p-2">Keluar</a>
                    </li>
                </ul>
            </div>
        </aside>
    </Transition>
    <main class="col-span-12  md:col-span-10 bg-sky-50 print:bg-white w-full max-h-screen overflow-y-auto">
        <header class="w-full h-10 bg-sky-100 shadow sticky top-0 print:hidden">
            <nav class="w-full h-full flex items-center justify-between px-3">
                <h1>{{ props.title ? props.title : 'Dashboard' }}</h1>
                <div class="nav-items flex items-center justify-end gap-2">
                    <button class="group md:hidden">
                        <SvgIcon type="mdi" :path="showSide ? mdiClose : mdiMenu" class="text-gray-800" :class="showSide ? 'text-red-400' : ''" @click="showSide = !showSide" />
                    </button>
                    <button class="group hidden" @click="logout">
                        <SvgIcon type="mdi" :path="mdiExitToApp" class="text-red-400 group-hover:text-red-600" />
                    </button>
                </div>
            </nav>
        </header>
        <section>
            <slot />
        </section>
    </main>
</div>
</template>
<style>

    .slide-enter-active {
        transition: all 0.5s cubic-bezier(1, 0.5, 0.8, 1);
    }
    .slide-leave-active {
        /* transition: all 0.3s ease-out; */
        transition: all 0.5s cubic-bezier(1, 0.5, 0.8, 1);

    }

    .slide-enter-from,
    .slide-leave-to {
        transform: translateX(-20px);
        opacity: 0;
    }

</style>