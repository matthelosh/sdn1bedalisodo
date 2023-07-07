<script setup>
import { computed, defineAsyncComponent } from 'vue';
import { Head, usePage} from '@inertiajs/vue3';

const page = usePage()
const Layout = page.props.layout;

const AuthorPage = defineAsyncComponent(() => import(`./../Layouts/Front/${Layout}/AuthorPage.vue`))
const meta = computed(() => {
    return {
        title:  `<span class="text-gray-600">${page.props.author.userable.nama}</span>`,
        description: `Laman deskripsi Penulis dan beberapa artikel yang telah ditulis oleh ${page.props.author.userable.nama}`
    }
})
</script>

<template>
<Head>
    <title>{{ meta.title.replace(/(<([^>]+)>)/gi, "") }}</title>
    <meta name="description" :content="meta.description" />
    
</Head>
<AuthorPage :author="page.props.author" />
</template>