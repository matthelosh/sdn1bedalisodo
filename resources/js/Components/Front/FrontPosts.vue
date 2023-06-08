<script setup>
import { ref, defineAsyncComponent } from 'vue'
import { usePage } from '@inertiajs/vue3';
import SvgIcon from '@jamescoyle/vue-icon';
import { mdiNewspaper } from '@mdi/js';

const page = usePage()

</script>

<template>
    <div class="w-full">
        <div class="header flex justify-center items-end mt-4">
            <SvgIcon type="mdi" :path="mdiNewspaper" class="text-violet-400" size="38" />
            <h1 class="text-2xl">
                <span class="font-extrabold text-gray-800">Kabar</span>
                <span class=" font-extralight text-gray-600">Terbaru</span>
            </h1>
        </div>
        <div class="content p-3 grid grid-cols-1 gap-2">
            <article v-for="(post,p) in page.props.posts" :key="p" class="bg-white p-3 shadow">
                <h1>{{ post.title }}</h1>
            </article>
        </div>
    </div>
</template>