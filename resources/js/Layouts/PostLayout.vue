<script setup>
import { Bars3CenterLeftIcon } from '@heroicons/vue/24/outline';
import { ref, defineAsyncComponent } from 'vue';

const Navbar = defineAsyncComponent(() => import('./Navbar.vue'))
const navShow = ref(false)
const toggleNav = () => {
    // document.querySelector('.header-items--nav').classList.toggle('hidden')
    navShow.value = !navShow.value
}
</script>

<template>
    <Navbar />
    <main class="w-full min-h-[95vh] relative">
        <slot />
    </main>
</template>