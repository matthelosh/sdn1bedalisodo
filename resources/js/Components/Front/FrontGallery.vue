<script setup>
import { ref } from 'vue';
import { Swiper, SwiperSlide,  } from 'swiper/vue'
import { EffectCube, Pagination, Autoplay, EffectCards, EffectFlip, EffectCoverflow } from 'swiper'
import SvgIcon from '@jamescoyle/vue-icon'
import { mdiCamera } from '@mdi/js';

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
<div class="w-full overflow-hidden bg-gray-800 p-8" >
  <div class="header flex justify-center items-end mt-4">
      <SvgIcon type="mdi" :path="mdiCamera" class="text-violet-400" size="38" />
      <h1>
          <span class="font-extrabold text-gray-50 text-2xl">Galeri</span>
          <span class=" font-extralight text-gray-200 tetx-xl">Terbaru</span>
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
    <div class="gallery-container grid grid-cols-3 gap-2 w-3/4 mx-auto">
      <div 
        class="img-container h-64 w-full rounded-xl hover:rounded-none  hover:scale-110 transition-all duration-300 hover:z-10 hover:shadow-lg hover:shadow-light-500 shadow-lime-500 aspect-square object-cover overflow-hidden group cursor-pointer relative" 
        v-for="im in 4" 
        :key="im">
        
        <img
          :src="`/img/carousel/${im}.jpg`" 
          alt="Foto"
          class="group-hover:scale-150 transition-all duration-500 saturate-50 group-hover:saturate-100 absolute"
          >
        <h1 class="img-title">Gambar {{ im }}</h1>
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