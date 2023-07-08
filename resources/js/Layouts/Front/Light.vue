<script setup lang="ts">
import { defineAsyncComponent } from 'vue';
const VisiMisi = defineAsyncComponent(() => import('./Light/Home/VisiMisi.vue'));
const DewanGuru = defineAsyncComponent(() => import('./Light/Home/Guru.vue'));
const Post = defineAsyncComponent(() => import('./Light/Home/Post.vue'));
const Agenda = defineAsyncComponent(() => import('./Light/Home/Agenda.vue'));
const Footer = defineAsyncComponent(() => import('./Light/Home/Footer.vue'));
const Navbar = defineAsyncComponent(() => import('./Light/Home/Navbar.vue'));
const Header = defineAsyncComponent(() => import('./Light/Home/Header.vue'));
const Galeri = defineAsyncComponent(() => import('./Light/Home/Galeri.vue'));

</script>

<template>

<div class="wrapper bg-white relative">   
    <Navbar />
    <Header />
    <main class=" h-auto">
        <VisiMisi />
        <Post />
        <DewanGuru />
        <Agenda />
        <Galeri />
    </main>
    <footer>
        <Footer />
    </footer>
</div>
</template>

<style scoped>
</style>