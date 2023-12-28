<template>
    <div>
        <Head>
            <title>{{post.title}}</title>
            <meta name="description" :content="description" />
        </Head>
        <Navbar />
        <article class="w-full sm:w-[80%] md:w-[70%] mx-auto bg-sky-50 p-3 shadow-lg mt-4 mb-10 pb-10">
            <ol class="flex items-center gap-0 breadcrumbs bg-sky-100 p-3 shadow">
                <li class="flex  items-center">
                    <Link href="/" class="text-sky-500 hover:text-sky-700 hover:undeline"><Icon icon="mdi:home" class="text-xl" /></Link>
                    <Icon icon="mdi:chevron-right" class="text-2xl" />
                </li>
                <li class="flex gap-1 items-center">
                    <Link :href="`/kategori/${post.category.label.toLowerCase()}`" class="text-sky-500 hover:text-sky-700 hover:underline">{{ post.category.label.toLowerCase() }}</Link>
                    <Icon icon="mdi:chevron-right" class="text-2xl" />
                </li>
                <li>{{ post.slug }}</li>
            </ol>
            
            <figure class="bg-sky-200 flex justify-center py-6">
                <img :src="post.featured_image" :alt="post.title" 
                @error="defaultImage" class="shadow-lg rounded hover:shadow-none cursor-pointer">
                
            </figure>
            <h1 class="font-bold text-[3em] text-sky-700 font-serif mt-8 text-justify sm:px-8 italic leading-12">{{ post.title }}</h1>
            <div class="meta py-4 sm:px-8 bg-slate-200">
                <p class="flex items-center gap-1">
                    <Icon icon="mdi:account-tie" class="text-lg text-green-600" />
                    {{ capitalize(post.author.userable.nama) }}
                    |
                    <Icon icon="mdi:calendar" class="text-lg text-orange-500" />
                    {{ moment(post.updated_at).format("LLLL") }}
                    |
                    <Icon icon="mdi:tag" class="text-lg text-sky-400" />
                    {{ post.category.label }}
                    |
                    <Icon icon="mdi:glasses" class="text-lg text-teal-500" />
                    {{ post.views.length }}
                </p>
            </div>
            <div class="post-content font-serif text-lg text-justify leading-8 sm:px-8" v-html="post.content"></div>
        </article>
        <Footer />
    </div>
</template>

<script setup>
import { usePage, Head, Link } from '@inertiajs/vue3';
import { Icon } from '@iconify/vue';
import { defineAsyncComponent, ref, computed } from 'vue';
import moment from 'moment/min/moment-with-locales';
moment.locale("id");
import { capitalize } from '@/Plugins/misc';

const Navbar = defineAsyncComponent(() => import('./Navbar.vue'))
const Footer = defineAsyncComponent(() => import('./Footer.vue'))

const page = usePage();
const props = defineProps({post: Object})

// const PostLayout = defineAsyncComponent(() => import('@/Layouts/PostLayout.vue'))

const description = computed(() => {
    let text = props.post.content.match(/<p(.)>.*?<\/p\1>/g) ? props.post.content.match(/<p(.)>.*?<\/p\1>/g) : props.post.title
    return text
})

const post = computed(() => props.post)

const defaultImage = (e) => {
    e.target.src = '/img/no-image.jpg'
}

</script>

<style>
.post-content p {
    margin: 1em 0;
}
.post-content img {
    margin: 1em auto;
}
</style>