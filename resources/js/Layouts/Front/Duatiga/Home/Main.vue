<script setup>
import { Icon } from '@iconify/vue';
import { ref, computed } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({ posts: Array});
const defaultImg = (e) => {
    e.target.src =  '/img/cover-artikel.jpg'
}

const search = ref('')

const filteredPost = computed(() => {
    return props.posts.filter(post => post.title.toLowerCase().includes(search.value.toLowerCase()))
})
</script>

<template>
    <div class="row main w-full py-12 bg-slate-100">
        <div class="main-content w-full sm:w-10/12 md:w-9/12  mx-auto my-4 flex justify-between">
            <h1 class="text-2xl font-bold text-sky-900 flex gap-1 items-center font-serif border-b border-sky-800 border-dashed">
                <Icon icon="mdi:post" />
                Tulisan terbaru
            </h1>
            <div class="cari relative">
                <input type="text" placeholder="Cari Tulisan" class="rounded-lg shadow-lg border-none" v-model="search">
                <Icon icon="mdi:magnify" class="text-xl text-slate-600 absolute right-2 top-[50%] -translate-y-[50%]" />
            </div>
        </div>
        <div class="main-content w-full sm:w-10/12 md:w-9/12  mx-auto grid gap-4 grid-cols-12">
            <Link 
                class="card col-span-3 rounded-md bg-white shadow-lg hover:shadow hover:cursor-pointer"
                :href="`/${post.category.label.toLowerCase()}/${post.slug}`"
                v-for="(post,i) in filteredPost"
                :class="i == 0 || i == (posts.length-2)  ? 'col-span-6 row-span-2':'col-span-3 row-span-1'"
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