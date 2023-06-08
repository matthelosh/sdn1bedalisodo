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
  <div class="w-full h-full relative">
    <img src="/img/tropi-1.png" alt="Piala" class="absolute -top-14 -left-4 w-36 z-20" />
    <h1 class="text-2xl ml-28 mt-6 py-2 pl-4 bg-purple-400 text-white font-bold tracking-wider rounded-s-full">Prestasi Siswa</h1>
    <div class="carousel w-full  h-[500px] mt-4">
      <Swiper class="w-full h-full"
        :modules="[Pagination, Autoplay, EffectCoverflow]"
        effect="coverflow"
        :autoplay="{delay: 500}"
        :loop="true"
        :pagination="true"
      >
        <SwiperSlide v-for="(prestasi,p) in prestasis" :key="p" class="relative">
          <img :src="prestasi.foto" alt="Foto" class="w-full h-full object-cover object-left saturate-[100%] hue-rotate-0 grayscale-[10%] contrast-[104%] brightness-[105%]">
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