<script setup>
import { computed, defineAsyncComponent } from 'vue';
import { Head, usePage} from '@inertiajs/vue3';

const page = usePage()
const Layout = page.props.layout;

const ReadPost = defineAsyncComponent(() => import(`./../Layouts/Front/${Layout}/ReadPost.vue`))

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
<ReadPost :post="page.props.post" />
</template>