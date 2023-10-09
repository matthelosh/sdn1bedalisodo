<script setup>
import { ref } from 'vue';
import { Swiper, SwiperSlide,  } from 'swiper/vue'
import { EffectCube, Pagination, Autoplay, EffectCards, EffectFlip, EffectCoverflow } from 'swiper'
import { Icon } from '@iconify/vue';

import 'swiper/css'
import 'swiper/css/effect-cube'
import 'swiper/css/pagination'
import 'swiper/css/autoplay'
import 'swiper/css/effect-cards'
import 'swiper/css/effect-coverflow'



const onSwiper = (swiper) => {
        // console.log(swiper)
      };
      const onSlideChange = () => {
        // console.log('slide change');
      };
</script>

<template>
<div class="w-full overflow-hidden bg-sky-950 p-8 md:pt-4 md:pb-8" >
  <div class="header flex justify-center items-end mt-4 md:mb-4">
      <Icon icon="mdi:camera" class="text-xl" />
      <h1>
          <span class="font-extrabold text-gray-50 text-2xl">Galeri</span>
          <span class=" font-extralight text-gray-200 text-xl">Terbaru</span>
      </h1>
  </div>
  <div class="sm-container md:hidden">
    <swiper
      @swiper="onSwiper"
      @slideChange="onSlideChange"
      :modules="[EffectCube, Pagination, Autoplay, EffectCards, EffectFlip, EffectCoverflow]" 
      effect="cube"
      :grab-cursor="true"
      :loop="true"
      :pagination="true"
      :autoplay="{delay:1500}"

      class="rounded-xl h-[400px] w-[300px]"
    >
      <swiper-slide
          v-for="i in 4" :key="i"
          class="rounded-xl"
      >
          <img :src="`/img/carousel/${i}.jpg`" alt="Foto">
      </swiper-slide>
    </swiper>
  </div>
  <div class="md-container hidden md:block w-full">
    <div class="gallery-container grid grid-cols-3 gap-3 w-3/4 mx-auto">
      <div 
        class="img-container h-64 w-full rounded-xl  hover:scale-110 hover:rounded-xl duration-300 hover:z-10 hover:shadow-lg hover:shadow-light-500 shadow-lime-500 aspect-square object-cover overflow-hidden group cursor-pointer relative" 
        v-for="im in 4" 
        :key="im">
        
        <img
          :src="`/img/carousel/${im}.jpg`" 
          alt="Foto"
          class="group-hover:scale-150 transition-all duration-500 saturate-50 group-hover:saturate-100 rounded-xl"
          >
        <div class="img-caption absolute bottom-0 h-12 bg-white w-full bg-opacity-25 backdrop-blur-md rounded-b-xl">
          <h1 class="img-title z-10 text-gray-800 box-border p-3 text-shadow-md top-0 text-lg">Gambar {{ im }}</h1>
        </div>
      </div>
    </div>
  </div>
</div>
</template>

<style scoped>
.swiper {
    /* width: 400px;
    height: 400px; */
    overflow: visible;
}

.swiper-slide img {
    height: 100%;
    width: 100%;
    object-fit: cover;
}

.swipe-pagination-bullet {
    width: 12px;
    height: 12px;
    background-color: white;
    opacity: 1;
}
.swipe-pagination-bullet-active {
    background-color: aqua;
}

</style>