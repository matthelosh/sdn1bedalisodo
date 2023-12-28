<template>
<div >
    <Head>
        <title>Hasil Pencarian {{searchQuery ? searchQuery : '' }}</title>
    </Head>
    <Navbar />
    <section class="min-h-[80vh] relative pb-8">
        <div class="breacrumbs">
            <ol class="flex items-center gap-0 breadcrumbs bg-slate-100 p-3 shadow">
                <li class="flex  items-center">
                    <Link href="/" class="text-sky-500 hover:text-sky-700 hover:undeline"><Icon icon="mdi:home" class="text-xl" /></Link>
                    <Icon icon="mdi:chevron-right" class="text-2xl" />
                </li>
                <li class="flex gap-1 items-center">
                    <Link :href="`/search/`" class="text-sky-500 hover:text-sky-700 hover:underline">Cari</Link>
                    <Icon icon="mdi:chevron-right" class="text-2xl" />
                </li>
                <li>{{ searchQuery }}</li>
            </ol>
        </div>
        <div class="content w-full sm:w-[80%] md:w-[70%] mx-auto py-8 bg-sky-50 px-4 my-8 shadow-lg">
            <h1 class="font-bold font-serif text-slate-700 text-2xl">
                Hasil Pencarian dengan Kata Kunci: 
                " <span :class="posts.length > 0 ? 'text-teal-600' : 'text-red-600'" class="underline">{{ capitalize(searchQuery) }}</span> " <span v-if="posts.length < 1">Tidak ditemukan</span></h1>
            <div class="posts-holder" v-if="posts.length > 0">
                <div class="card flex gap-2 my-4" v-for="(post, p) in posts" :key="post.slug">
                    <img :src="post.featured_image" alt="Cover" class=" h-20 sm:h-28 object-cover aspect-square" @error="defaultImage">
                    <article>
                        <h1 class="font-bold text-lg text-slate-600">{{ post.title }}</h1>
                        <p v-html="post.content.substr(0, 200)" class="mb-2"></p>
                        <Link :href="`/${post.category.label.toLowerCase()}/${post.slug}`" class="py-1 px-3 bg-sky-400 text-white mt-2 rounded shadow  font-semibold tracking-wide">Baca</Link>
                    </article>
                </div>
            </div>
        </div>
    </section>
    <Footer />
</div>
</template>

<script setup>
import { usePage, Head, Link } from '@inertiajs/vue3';
import { computed, defineAsyncComponent } from 'vue';
import { Icon } from '@iconify/vue';
import { capitalize, defaultImage } from '@/Plugins/misc';

const page = usePage();
const posts = computed(() => page.props.posts)

const searchQuery = computed(() => {
    let queryString = window.location.search;
    const params = new URLSearchParams(queryString);
    return params.get("q") ?? '';
})

const Navbar = defineAsyncComponent(() => import('./Navbar.vue'))
const Footer = defineAsyncComponent(() => import('./Footer.vue'))
</script>