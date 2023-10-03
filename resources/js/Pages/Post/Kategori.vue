<script setup>
import { usePage, Head, Link } from '@inertiajs/vue3';
import { defineAsyncComponent, ref, computed } from 'vue';
import { Icon } from '@iconify/vue';

const page = usePage();
const props = defineProps({ posts: Array, kategori: String });

const PostLayout = defineAsyncComponent(() => import('@/Layouts/PostLayout.vue'));
const Breadcrumbs = defineAsyncComponent(() => import('@/Components/General/Breadcrumbs.vue'));


</script>

<template>
    <Head>
        <title>Kategori {{ kategori }}</title>
        <meta name="description" :content="`List tulisan SD Negeri 1 Bedalisodo dalam kategori ${kategori}`" />
        <meta name="keywords" :content="`Mandita, SD Negeri 1 Bedalisodo, ${kategori}`" />
    </Head>
    <PostLayout>
        <div class="hero w-full bg-[url('/img/cover-artikel.jpg')] relative h-[400px] bg-bottom bg-fixed bg-contain">
            <div class="hero-content absolute top-[50%] left-[50%] -translate-x-[50%] -translate-y-[50%] p-4 bg-slate-800 shadow-xl">
                <h1 class="text-4xl font-bold text-white">Tulisan dalam kategori: {{ kategori }} </h1>
            </div>
        </div>
        <Breadcrumbs />
        <div class="w-full p-4 bg-teal-100">
            <div class="w-full sm:w-3/4  mx-auto grid grid-cols-1 sm:grid-cols-3 gap-8">
                <div 
                    class="card bg-white shadow p-3 rounded-md hover:shadow-lg transition-all hover:-translate-y-1"
                    v-for="post in props.posts"
                    :ket="post.id"
                >
                    <figure class="w-full mb-4 shadow-md">
                        <img :src="post.featured_image" alt="Sampul" class="w-full h-[200px] object-cover object-center">
                    </figure>
                    <Link 
                        :href="`/${post.category.label.toLowerCase()}/${post.slug}`" 
                        class="text-slate-600 font-bold text-xl leading-5 my-4">
                        <h1>{{ post.title }}</h1>
                    </Link>
                    <small class="text-teal-600">{{ post.author.userable.nama }}</small>
                    <p v-html="post.content.substr(0,100)+'...'"></p>
                    <Link 
                        as="button"
                        :href="`/${post.category.label.toLowerCase()}/${post.slug}`" 
                        class="mt-4 px-4 py-2 text-white font-bold text-lg leading-5 bg-slate-600"
                        >
                        Baca
                    </Link>
                </div>
            </div>
        </div>
    </PostLayout>
</template>