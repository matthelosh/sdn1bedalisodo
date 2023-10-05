<script setup>
import { ref, computed } from 'vue';
import { Link } from '@inertiajs/vue3';
import { Splide, SplideSlide } from '@splidejs/vue-splide';
import '@splidejs/vue-splide/css';

const props = defineProps({items: Array})

const setDefaultImage = (e) => {
    e.target.src = '/img/kegiatan/8.jpg'
}
</script>

<template>
    <Splide :options="{ rewind: true, height: 500,  autoplay:true }" aria-label="My Favorite Images">
    <SplideSlide
        v-for="item in props.items"
        class="group"
        style="background-color: #e12525;"
    >
      <img :src="item.featured_image" :alt="item.title" class="w-full filter grayscale opacity-50 hover:opacity-100 transition-all hover:grayscale-0" @error="setDefaultImage">
      <Link :href="`/${item.category.label.toLowerCase()}/${item.slug}`" class="absolute bottom-10 left-[50%] -translate-x-[50%]  group-hover:bg-slate-800 bg-slate-200 group-hover:text-white p-4 text-xl transition-all  hover:shadow-lg rounded-lg">{{ item.title }}</Link>
    </SplideSlide>
  </Splide>
</template>

<style scoped>

</style>