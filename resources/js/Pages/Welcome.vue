<script setup>
import { Head, Link, usePage } from '@inertiajs/vue3';
import { defineAsyncComponent, ref, onMounted } from 'vue';
import { Icon } from '@iconify/vue';
import FrontLayout from '@/Layouts/FrontLayout.vue'
const page = usePage()
const FrontPosts = defineAsyncComponent(() => import('@/Components/Front/FrontPosts.vue'))
const FrontPrestasi = defineAsyncComponent(() => import('@/Components/Front/FrontPrestasi.vue'))
const FrontGallery = defineAsyncComponent(() => import('@/Components/Front/FrontGallery.vue'))
const Campaign = defineAsyncComponent(() => import('@/Components/Front/Campaign.vue'))

const content = (html) => {
    let text = html.replace(/(<([^>]+)>)/gi, "")
    return text.split(".")
}

const showCampaign = ref(false)
onMounted(() => {
    setTimeout(() => {
        // showCampaign.value = true
    }, 500);
})

</script>

<template>
<Head>
    <title>Selamat Datang</title>
    <meta name="description" content="SD Negeri 1 Bedalisodo, Mandita (Mandiri, Disiplin, Bertakwa). Memiliki Visi menjadi sekolah dasar unggulan yang membawa peserta didik menjadi pribadi yang religius, berbudaya dan inovatif." />
</Head>
<Campaign v-if="showCampaign" @close="showCampaign = false" />
<FrontLayout>
    <FrontPrestasi />
    <FrontPosts />
    <FrontGallery />
</FrontLayout>
</template>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Nanum+Pen+Script&display=swap');
h1.hof {
    font-family: 'Nanum Pen Script', cursive;
}
</style>

