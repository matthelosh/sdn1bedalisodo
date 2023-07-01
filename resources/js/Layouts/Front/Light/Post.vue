<script setup>
import { ref, computed } from 'vue';
import { usePage } from '@inertiajs/vue3';

const page = usePage();
</script>
<template>
<div class="w-full bg-white py-8">
    <div class="container w-10/12 md:w-8/12 mx-auto grid grid-cols-2 md:grid-cols-3 gap-4">
        <div class="card bg-gray-50 hover:cursor-pointer shadow" v-for="(post,p) in page.props.posts" :key="p">
            <div class="relative card-header h-[150px] w-full overflow-hidden bg-cover after:absolute after:bg-gray-400 after:top-0 after:right-0 after:bottom-0 after:left-0 after:bg-opacity-80 after:backdrop-blur after:hover:bg-transparent after:hover:backdrop-blur-0 hover:bg-[length_250px_300px] after:transition p-3 group" :style="`background-image: url('${post.featured_image}');`">
                <!-- <img :src="post.featured_image" alt="" class="object-fill"> -->
                <h1 class="absolute z-10 text-white text-xl group-hover:shadow group-hover:bg-gray-600 p-3">{{ post.title }}</h1>
            </div>
            <div class="card-content p-3">
                <!-- <h1 class="text-xl font-bold text-gray-900 leading-5">{{post.title}}</h1> -->
                <p v-html="post.content.substr(0, 100)"></p>
            </div>
        </div>
    </div>
</div>
</template>
<style>
    .tes {
        background-color: #4a81e18a;
    }
</style>