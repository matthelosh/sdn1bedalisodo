<script setup>
import { ArrowLeftIcon, ArrowRightIcon, UserCircleIcon } from '@heroicons/vue/24/outline'
import { ref, onMounted, defineAsyncComponent, computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';

const page = usePage();
const Navbar = defineAsyncComponent(() => import('./Front/Navbar.vue'))
const Footer = defineAsyncComponent(() => import('./Front/Footer.vue'))
const infos  = ref([])
onMounted(() => {
    infos.value = page.props.infos
    setTimeout(() => {
        pramuka.value = true
    }, 500)

    setInterval(() => {
        if (trending.value < (infos.value.length -1)) {
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
    if (trending.value < infos.value.length - 1) {
        trending.value++
    } else {
        return false
    }
}
</script>

<template>
<div class="wrapper  text-dark w-full min-h-screen bg-gray-100">
    <header class="w-full md:block after:to-[#aaaaff99] after:from-[#78976744] overflow-hidden relative bg-[url('/img/muharram-2022.jpg')] bg-no-repeat bg-cover bg-center after:content='' after:absolute after:top-0 after:right-0 after:bottom-0 after:left-0 after:bg-gradient-to-br after:backdrop-blur-sm after:bg-opacity-20">
        <div class="content h-[90vh]  pt-40 relative px-8 md:px-16 z-10">
            <transition name="fade">
                <span v-if="pramuka">
                    <!-- <img src="/img/gajah.png" alt="Siswa" class="absolute -left-56 md:left-0 md:-bottom-10 w-42 md:w-96" > -->
                    <!-- <img src="/img/cat.png" alt="Siswa" class="absolute w-32 md:w-96 right-0 top-0" > -->
                </span>
            </transition>
            <transition name="slide">
                <img src="/img/pramuka.png" alt="Siswa" class="absolute w-64 md:w-96 -right-28 -rotate-[25deg] md:right-12 bottom-0 z-20" v-show="pramuka">
            </transition>
            <h1 class="font-[monoton] text-4xl md:text-6xl mb-14 -translate-y-8 md:-translate-y-12 md:translate-x-64">MANDITA</h1>
            <transition name="fade">
                <article class="-translate-y-16 md:-translate-y-14 bg-white md:w-2/4 md:ml-64 p-3 md:p-10 shadow-xl rounded-xl" v-if="pramuka">
                    <h1 class="flex justify-between">
                        <span>
                            <span class="font-mono text-xl md:text-2xl font-bold">Pengumuman</span>
                            <span class="font-mono font-extrabold animate-ping h-full w-1 bg-gray-600">&nbsp;</span>
                        </span>
                        <ArrowLeftIcon class="h-6 cursor-pointer" :class="trending == 0 ? 'text-gray-400' : ''" @click="prevTrending" />
                        <ArrowRightIcon class="h-6 cursor-pointer" :class="trending == (infos.length-1) ? 'text-gray-400' : ''"  @click="nextTrending" />
                    </h1>
                    <p class="font-mono mt-4">
                        <span class="title font-bold text-xl mb-2"><Link :href="`/${infos[trending].category.label.toLowerCase()}/${infos[trending].slug}`">{{ infos[trending].title }}</Link></span>
                        <span>{{ infos[trending].content.substr(0,50) }}</span>
                        <!-- {{ infos[trending].category }} -->
                    </p>
                    <p class="font-mono text-orange-600 italic flex gap-1 items-center">
                        <UserCircleIcon class="h-5" />
                        @{{ infos[trending].author_id.toLowerCase().replace(" ","_") }}
                    </p>
                    <div class="flex justify-between mt-2">
                        <div class="hidden md:inline"></div>
                        <Link :href="`/${infos[trending].category.label.toLowerCase()}/${infos[trending].slug}`" class="bg-secondary text-white py-2 px-4 rounded-full hover:bg-secondary shadow hover:shadow-lg hover:-translate-y-1 duration-150">Baca</Link>
                    </div>
                </article>
            </transition>
        </div>
        
    </header>
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