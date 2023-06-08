<script setup>
import { ref } from 'vue';
import { Swiper, SwiperSlide } from 'swiper/vue';
import { Pagination, Autoplay, EffectCoverflow } from 'swiper';

import 'swiper/css'
import 'swiper/css/pagination'
import 'swiper/css/autoplay'
import 'swiper/css/effect-coverflow'

const prestasis = ref([
  {
      siswa_id : 1,
      kegiatan: 'Seleksi KSN Kec. Wagir',
      tingkat: 'Kecamatan',
      peringkat: 'Juara 1',
      foto : '/img/prestasi/ksn-kec.jpg',
      deskripsi: 'Juara seleksi KSN Kecamatan Wagir',
      tanggal: '20 Maret 2023'
  },
  {
      siswa_id : 2,
      kegiatan: 'Pentas PAI  Kec. Wagir',
      tingkat: 'Kecamatan',
      peringkat: 'Juara 1 Cerdas Cermat',
      foto : '/img/prestasi/lcc-pai.jpg',
      deskripsi: 'Juara 1 Cerdas Cermat Pentas PAI Kecamatan Wagir 2023',
      tanggal: '16 Mei 2023'
  },
])



</script>

<template>
<div class="w-full bg-gray-200 ">
  <img src="/img/tropi-1.png" alt="Piala" class="absolute -top-14 md:top-0 -left-4 w-36 z-20" />
      
  <div class="w-full md:w-3/4 mx-auto grid grid-cols-1 md:grid-cols-2 h-full md:py-8 relative ">
    <div class="w-full hidden md:block">
      <h1 class=" text-9xl leading-none text-gray-600 text-right">
        <span>Prestasi</span> <br>
        <small class="font-extralight">Siswa</small>
      </h1>
    </div>
    <div class="w-full">
      
      <div class="carousel w-full  h-[500px] mt-4">
        <Swiper class="w-full h-full"
          :modules="[Pagination, Autoplay, EffectCoverflow]"
          effect="coverflow"
          :autoplay="{delay: 5000}"
          :loop="true"
          :pagination="true"
        >
          <SwiperSlide v-for="(prestasi,p) in prestasis" :key="p" class="relative">
            <img :src="prestasi.foto" alt="Foto" class="w-full h-full object-cover object-left-top saturate-[100%] hue-rotate-0 grayscale-[10%] contrast-[104%] brightness-[105%]">
            <div class="caption absolute bottom-4 p-3 box-border bg-white bg-opacity-25 backdrop-blur-sm">
              <div>
                <h1 class="text-2xl font-bold text-white drop-shadow-md">{{ prestasi.peringkat }} {{ prestasi.kegiatan }}</h1>
                <p class="text-white">{{ prestasi.deskripsi }}</p>
              </div>
            </div>
          </SwiperSlide>
        </Swiper>
      </div>
    </div>
  </div>
</div>
</template>

<style scoped>
.swiper {
  width: 90%;
  overflow: hidden;
}

/* .swiper img {
  -webkit-filter:  brightness(105%) contrast(104%) grayscale(10%) hue-rotate(0deg) invert(0%) opacity(100%) saturate(100%) sepia(50%);
          filter:  brightness(105%) contrast(104%) grayscale(10%) hue-rotate(0deg) invert(0%) opacity(100%) saturate(100%) sepia(50%);
  mix-blend-mode: none;
} */

</style>