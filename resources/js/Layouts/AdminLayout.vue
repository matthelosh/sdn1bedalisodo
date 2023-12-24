<script setup>
import { ref, defineAsyncComponent, onMounted, onBeforeMount } from 'vue';
import { usePage, Link, router } from '@inertiajs/vue3';
import { Icon } from '@iconify/vue';
import SidebarItem from '@/Layouts/Admin/SidebarItem.vue'

const ConfirmDialog = defineAsyncComponent(() => import('@/Components/General/ConfirmDialog.vue'))
const confirmDialog = ref(null)

onMounted(() => {
    const aside = document.querySelector("aside")
    const asideStyle = window.getComputedStyle(aside)
    asideWidth.value = parseInt(asideStyle.width)+'px'
})

onBeforeMount(() => {
    if(!page.props.auth.user) router.get('/login')
})

const asideWidth = ref('')

const props = defineProps({title: String})
const page = usePage()
const showSide = ref(false)


const logout = async() => {
    if ( await confirmDialog.value.open("Mau Logout?", "orange")) {
        router.post(route('logout'))
    }
    
}
</script>
<template>
<div class="wrapper grid grid-cols-12 h-screen w-auto bg-[url('/img/bg-stripes.jpg')] p-4 gap-0 print:bg-white">
    <Transition name="slide">
        <aside :class="!showSide ? 'hidden md:block' : 'md:block'" class=" absolute md:relative md:top-0 overflow-x-hidden md:w-full z-10 w-[60vw]  md:col-span-2 bg-[#fefefe] shadow-xl hover:shadow-none h-[97vh] transition-all duration-150 print:hidden col-span-4 rounded-s-lg ">
            <div class="avatar p-4 bg-teal-400 w-full relative">
                <img src="/img/logo.png" alt="Avatar" class="w-[100px] aspect-square object-contain mx-auto">
                <Link as="h1" class="text-center text-lime-200 mt-3 mx-auto cursor-pointer" :href="`/guru/${page.props.auth.user.name}`">
                    @{{ page.props.auth.user.name }}
                </Link>
                <small></small>
            </div>
            <div class="sidenav">
                <SidebarItem />
                <ul>
                    <li >
                        <a href="#" @click.prevent="logout" class="flex items-center gap-2 hover:bg-red-400 hover:text-white transition p-2">
                            <Icon icon="mdi:exit-to-app" />
                            Keluar
                        </a>
                    </li>
                </ul>
            </div>
        </aside>
    </Transition>
    <main class="col-span-12  md:col-span-10 bg-[#fefefe] print:bg-white w-full max-h-[100vh] overflow-hidden shadow-lg hover:shadow-none transition-all rounded-e-lg pb-20">
        <header class="w-full h-14 top-0 print:hidden z-10 ">
            <nav class="w-full h-full flex items-center justify-between px-4">
                <h1 class="font-bold text-slate-600 tracking-wide flex items-center text-lg gap-2">
                    <Icon icon="mdi-folder-open" class="text-2xl" />
                    {{ props.title ? props.title : 'Dashboard' }}
                </h1>
                <div class="nav-items flex items-center justify-end gap-2">
                    <button class="group md:hidden">
                        <Icon :icon="`mdi:${showSide ? 'close' : 'menu'}`" @click="showSide = !showSide" />
                    </button>
                    <button class="group hidden" @click="logout">
                        <!-- <SvgIcon type="mdi" :path="mdiExitToApp" class="text-red-400 group-hover:text-red-600" /> -->
                        <Icon icon="mdi:exit-to-app" class="text-lg text-red-400 group-hover:text-red-600" />
                    </button>
                </div>
            </nav>
        </header>
        <section class="max-h-[95vh] bg-white print:h-full print:overflow-y-visible overflow-y-auto">
            <slot />
        </section>
    </main>
    
</div>
<ConfirmDialog ref="confirmDialog" />
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