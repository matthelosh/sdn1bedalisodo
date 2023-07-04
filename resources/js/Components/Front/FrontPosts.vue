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
        <div class="post-holder grid grid-cols-1 md:grid-cols-3 gap-3 ">
            <Link
                as="article"
                v-for="(post,p) in page.props.posts"
                :key="p"
                :href="`/${post.category.label.toLowerCase()}/${post.slug}`"
                class="bg-white rounded p-2 shadow hover:shadow-lg hover:cursor-pointer"
                v-motion
                :initial="{ opacity: 0, x: 50 }"
                :enter="{ opacity: 1, x: 0, scale: 1 }"
                :delay="200 + (p * 100)"
            >
                <h1 class="text-gray-800 font-bold">{{ post.title }}</h1>
                <div class="grid grid-cols-4 gap-2">
                    <img :src="post.featured_image" alt="" class="w-full col-span-1 aspect-square object-cover">
                    <span class="col-span-3" v-html="post.content.substring(0,100)"></span>
                </div>
            </Link>
        </div>
    </div>
</template>