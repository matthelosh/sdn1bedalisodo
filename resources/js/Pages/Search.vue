<script setup>
import { computed, ref } from 'vue';
import { Head, Link, usePage} from '@inertiajs/vue3';
import PostLayout from '@/Layouts/PostLayout.vue';

const page = usePage()

const meta = computed(() => {
    return {
        title:  `<span class="text-gray-600">Hasil Pencarian:</span> <span>'${route().params.q}'</span>`,
        description: `Hasil Pencarian dengan kata kunci: ${route().params.q} di SD Negeri 1 Bedalisodo`
    }
})
</script>

<template>
<Head>
    <title>{{ meta.title.replace(/(<([^>]+)>)/gi, "") }}</title>
    <meta name="description" :content="meta.description" />
    
</Head>
<PostLayout>
    <div class="w-full bg-white p-4 rounded-xl">
        <h1 class="text-2xl font-bold my-2" v-html="meta.title" />
        <div class="grid grid-cols-1 gap-3">
            <div v-for="(post,p) in page.props.posts" :key="p" class="bg-lime-200 py-2 px-4 rounded-xl hover:bg-lime-400 hover:shadow">
                <Link :href="`/${post.category.label.toLowerCase()}/${post.slug}`">
                    <h1 class="text-2xl">{{ post.title }}</h1>
                </Link>
            </div>
        </div>
    </div>
</PostLayout>
</template>