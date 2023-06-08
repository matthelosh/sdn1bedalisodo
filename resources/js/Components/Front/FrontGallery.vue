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
  <div class="w-full overflow-hidden bg-gray-800" >
  <div class="header flex justify-center items-end mt-4">
      <SvgIcon type="mdi" :path="mdiCamera" class="text-violet-400" size="38" />
      <h1>
          <span class="font-extrabold text-gray-50 text-2xl">Galeri</span>
          <span class=" font-extralight text-gray-200 tetx-xl">Terbaru</span>
      </h1>
  </div>
  
    <swiper
    @swiper="onSwiper"
    @slideChange="onSlideChange"
    :modules="[EffectCube, Pagination, Autoplay, EffectCards, EffectFlip, EffectCoverflow]" 
    effect="cube"
    :grab-cursor="true"
    :loop="true"
    :pagination="true"
    :autoplay="{delay:1500}"

    class="eounded-xl"
  >
    <swiper-slide
        v-for="i in 4" :key="i"
        class="rounded-xl"
    >
        <img :src="`/img/carousel/${i}.jpg`" alt="Foto">
    </swiper-slide>
  </swiper>
</div>
</template>

<style scoped>
.swiper {
    width: 400px;
    height: 400px;
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