<script setup>
import { ref, defineAsyncComponent, computed } from 'vue'
import { usePage, Link } from '@inertiajs/vue3';
import SvgIcon from '@jamescoyle/vue-icon';
import { mdiNewspaper } from '@mdi/js';

const page = usePage()

const stripHTML = (html) => {
    html.replace(/(<([^>]+)>)/gi, "")
    return html.split(".")
}

</script>

<template>
    <div class="w-full md:w-3/4 mx-auto md:px-16 mb-6">

        <div class="header flex justify-center items-end mt-4">
            <SvgIcon type="mdi" :path="mdiNewspaper" class="text-violet-400" size="38" />
            <h1 class="text-2xl">
                <span class="font-extrabold text-gray-800">Kabar</span>
                <span class=" font-extralight text-gray-600">Terbaru</span>
            </h1>
        </div>
        <div class="p-3 columns-1 md:columns-3 gap-3">
            <Link as="article" v-for="(post,p) in page.props.posts" :key="p" :href="`/${post.category.label.toLowerCase()}/${post.slug}`" class="bg-white shadow hover:shadow-xl rounded-xl break-inside-avoid mb-4 hover:cursor-pointer overflow-hidden" :class="p==0? 'md:row-span-2' : ''">
                <img :src="post.featured_image ? post.featured_image:'/img/no-image.jpg'" alt="Feature Image" class="w-full h-64 object-cover rounded-t-xl bg-sky-300 hover:scale-110 transition-all duration-300 saturate-50 hover:saturate-100" >
                <div class="content p-3">
                    <Link :href="`/${post.category.label.toLowerCase()}/${post.slug}`">
                        <h1 class="text-xl font-bold text-gray-800 mb-2">{{ post.title }}</h1>
                    </Link>
                    <p class="hidden md:block" v-html="stripHTML(post.content)[0]"></p>
                </div>
            </Link>
        </div>
    </div>
</template>