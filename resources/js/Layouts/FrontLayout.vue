<script setup>
import { ArrowLeftIcon, ArrowRightIcon, ChevronDoubleRightIcon, HeartIcon, UserCircleIcon } from '@heroicons/vue/24/outline'
import { ref, onMounted, defineAsyncComponent } from 'vue';
import { Link } from '@inertiajs/vue3';
import SvgIcon from '@jamescoyle/vue-icon';
import {mdiEmail, mdiHeart, mdiLaravel, mdiVuejs, mdiTailwind} from '@mdi/js';

const Navbar = defineAsyncComponent(() => import('@/Layouts/Navbar.vue'))
onMounted(() => {

    setTimeout(() => {
        pramuka.value = true
    }, 500)

    setInterval(() => {
        if (trending.value < (props.starredPosts.length -1)) {
            trending.value++
        } else {
            trending.value = 0
        }
    }, 2500)
})
const props = defineProps(
    {
        starredPosts: Array
    }
)

const trending = ref(0)

const pramuka = ref(false)

const prevTrending = () => {
    if (trending.value > 0) {
        trending.value--
    } else {
        return false
    }
}
const nextTrending = () => {
    if (trending.value < props.starredPosts.length - 1) {
        trending.value++
    } else {
        return false
    }
}
</script>

<template>
<div class="wrapper  text-dark w-full min-h-screen bg-gray-100">
    <header class="w-full md:block from-sky-100 to-sky-50 bg-gradient-to-br overflow-hidden relative">
        <div class="content h-[90vh]  pt-40 relative px-8 md:px-16">
            <transition name="fade">
                <span v-if="pramuka">
                    <img src="/img/gajah.png" alt="Siswa" class="absolute -left-56 md:left-0 md:-bottom-10 w-42 md:w-96" >
                    <img src="/img/cat.png" alt="Siswa" class="absolute w-32 md:w-96 right-0 top-0" >
                </span>
            </transition>
            <transition name="slide">
                <img src="/img/pramuka.png" alt="Siswa" class="absolute w-64 md:w-96 -right-28 -rotate-[25deg] md:right-12 bottom-0 z-20" v-show="pramuka">
            </transition>
            <h1 class="font-[monoton] text-4xl md:text-6xl mb-14 -translate-y-8 md:-translate-y-12 md:translate-x-64">MANDITA</h1>
            <transition name="fade">
                <article class="-translate-y-16 md:-translate-y-14 bg-white md:w-2/4 md:ml-64 p-3 md:p-10 shadow" v-if="pramuka">
                    <h1 class="flex justify-between">
                        <span>
                            <span class="font-mono text-xl md:text-2xl font-bold">Terbaru</span>
                            <span class="font-mono font-extrabold animate-ping h-full w-1 bg-gray-600">&nbsp;</span>
                        </span>
                        <ArrowLeftIcon class="h-6 cursor-pointer" :class="trending == 0 ? 'text-gray-400' : ''" @click="prevTrending" />
                        <ArrowRightIcon class="h-6 cursor-pointer" :class="trending == (starredPosts.length-1) ? 'text-gray-400' : ''"  @click="nextTrending" />
                    </h1>
                    <p class="font-mono mt-4">
                        <span class="title font-bold"><Link :href="`/${starredPosts[trending].category.label.toLowerCase()}/${starredPosts[trending].slug}`">{{ starredPosts[trending].title }}</Link> &nbsp;</span>
                        <span>{{ starredPosts[trending].content.substr(0,50) }}</span>
                    </p>
                    <p class="font-mono text-orange-600 italic flex gap-1 items-center">
                        <UserCircleIcon class="h-5" />
                        @{{ starredPosts[trending].author_id.toLowerCase().replace(" ","_") }}
                    </p>
                    <div class="flex justify-between mt-2">
                        <div class="hidden md:inline"></div>
                        <Link :href="`/${starredPosts[trending].category.label.toLowerCase()}/${starredPosts[trending].slug}`" class="bg-secondary text-white py-2 px-4 rounded-full hover:bg-secondary shadow hover:shadow-lg hover:-translate-y-1 duration-150">Baca</Link>
                    </div>
                </article>
            </transition>
        </div>
        
    </header>
    <Navbar />
    <main class="w-full min-h-[95vh] relative">
        <slot />
    </main>
    <footer class="w-full min-h-[200px] bg-primary">
        <div class="grid grid-cols-1 md:grid-cols-3 m-4">
            <div class="footer-identitas p-3 flex flex-col justify-center items-center md:flex-row gap-3 text-sm">
                <img src="/img/logo.png" alt="Logo" class="h-20 drop-shadow" />
                <div class="footer-identitas--alamat">
                    <h1 class="leading-4 md:leading-5 tracking-wide text-center md:text-left text-lg font-thin text-yellow-400">SD Negeri 1 Bedalisodo</h1>
                    <p  class="leading-4 md:leading-5 tracking-wide text-center md:text-left text-accent">Jl. Raya Sengon No. 293, Dalisodo</p>
                    <p  class="leading-4 md:leading-5 tracking-wide text-center md:text-left text-accent">Kec. Wagir, Kode Pos 65158</p>
                    <p  class="leading-4 md:leading-5 tracking-wide text-center md:text-left text-accent flex items-center underline underline-offset-3 gap-1">
                        <SvgIcon type="mdi" :path="mdiEmail" /> <a href="mailto:admin@sdn1-bedalisodo.sch.id">admin@sdn1-bedalisodo.sch.id</a>
                    </p>
                </div>
            </div>
            <div class="p-3 text-white">
                <h1 class="text-lg font-bold tracking-wide text-yellow-200">Tulisan terbaru:</h1>
                <ul>
                    <li v-for="(post,p) in $page.props.starredPosts" :key="p">
                        <Link class="flex items-center gap-1 text-justify text-accent hover:text-yellow-400" :href="route('Post.read', {kategori:post.category.label.toLowerCase(), slug: post.slug})"> <ChevronDoubleRightIcon class="h-3 " /> {{ post.title }}</Link>
                    </li>

                </ul>
            </div>
            <div class="p-3 text-white">
                <h1 class="text-lg font-bold tracking-wide text-yellow-200">Link Eksternal:</h1>
                <ul>
                    <li>
                        <a class="flex gap-1 items-center text-accent hover:text-yellow-400" href="https://kemdikbud.go.id" target="_blank"> <ChevronDoubleRightIcon class="h-3" /> Kemdikibudristek RI</a>
                    </li>
                    <li>
                        <a class="flex gap-1 items-center text-accent hover:text-yellow-400" href="https://guru.kemdikbud.go.id" target="_blank"> <ChevronDoubleRightIcon class="h-3" />Merdeka Mengajar</a>
                    </li>
                    <li>
                        <a class="flex gap-1 items-center text-accent hover:text-yellow-400" href="https://ditpsd.kemdikbud.go.id" target="_blank"> <ChevronDoubleRightIcon class="h-3" />Merdeka Belajar</a>
                    </li>
                    <li>
                        <a class="flex gap-1 items-center text-accent hover:text-yellow-400" href="https://dispendik.malangkab.go.id" target="_blank"> <ChevronDoubleRightIcon class="h-3" /> Dinas Pendidikan Kab. Malang</a>
                    </li>
                    <li>
                        <a class="flex gap-1 items-center text-accent hover:text-yellow-400" href="https://malang.kemenag.go.id" target="_blank"> <ChevronDoubleRightIcon class="h-3" /> Kemenag Kab. Malang</a>
                    </li>

                </ul>
            </div>
        </div>
        <div class="w-full h-12 flex flex-col md:flex-row justify-center md:justify-between items-center px-4 text-xs text-gray-100 bg-sky-800 bg-opacity-20">
            <div>
                Copyright &copy; {{ new Date().getFullYear() }} <span class="text-yellow-400">SD Negeri 1 Bedalisodo</span>
            </div>
            <div class="flex items-center">
                <p>
                    Dev. by <a href="https://wa.me/6285954944407">Mat Soleh</a> with 
                </p>
                <SvgIcon type="mdi" :path="mdiHeart" class="text-pink-100 animate-pulse" />
                <SvgIcon type="mdi" :path="mdiLaravel" class="text-red-100" />
                <SvgIcon type="mdi" :path="mdiVuejs" class="text-green-100" />
                <SvgIcon type="mdi" :path="mdiTailwind" class="text-sky-100" />
            </div>
        </div>
    </footer>
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