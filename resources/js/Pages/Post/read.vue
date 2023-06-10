<script setup>
import { usePage, Head } from '@inertiajs/vue3';
import SvgIcon from '@jamescoyle/vue-icon';
import { mdiAccountTie, mdiCalendarBadgeOutline, mdiStar } from '@mdi/js';
import { defineAsyncComponent, ref, computed } from 'vue';

const page = usePage();

const PostLayout = defineAsyncComponent(() => import('@/Layouts/PostLayout.vue'))

const description = computed(() => {
    let text = page.props.post.content.match(/<p(.)>.*?<\/p\1>/g) ? page.props.post.content.match(/<p(.)>.*?<\/p\1>/g) : page.props.post.title
    return text
})

const post = computed(() => page.props.post)

</script>

<template>
<Head>
    <title>{{page.props.post.title}}</title>
    <meta name="description" :content="description" />
</Head>
<PostLayout>
    <div class="container bg-white p-4 rounded-xl shadow">
        <h1 class="text-xl font-bold text-gray-600">{{ page.props.post.title }}</h1>
        <p class="meta flex items-center mb-4">
            <SvgIcon type="mdi" :path="mdiAccountTie" class="text-teal-600" /> 
            <span class="text-teal-800">
                {{ post.author_id }}
            </span>
            &nbsp;
            <SvgIcon type="mdi" :path="mdiCalendarBadgeOutline" class="text-orange-600" />
            <span class="text-orange-800">
                {{ new Date(post.created_at).toDateString() }}
            </span>
            &nbsp;
            <SvgIcon type="mdi" :path="mdiStar" class="text-yellow-400" v-if="post.starred" />
        </p>
        <article v-html="page.props.post.content" class="text-gray-600">
        </article>
    </div>
</PostLayout>
</template>