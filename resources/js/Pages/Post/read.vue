<script setup>
import { usePage, Head } from '@inertiajs/vue3';
import { defineAsyncComponent, ref, computed } from 'vue';

const page = usePage();

const PostLayout = defineAsyncComponent(() => import('@/Layouts/PostLayout.vue'))

const description = computed(() => {
    let text = page.props.post.content.match(/<p(.)>.*?<\/p\1>/g)
    return text
})

</script>

<template>
<Head>
    <title>{{page.props.post.title}}</title>
    <meta name="description" :content="description" />
</Head>
<PostLayout>
    <h1>{{ page.props.post.title }}</h1>
    <article v-html="page.props.post.content">
    </article>
    <p>{{ description }}</p>
</PostLayout>
</template>