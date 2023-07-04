<script setup>
import { Icon } from '@iconify/vue';
</script>
<template>
    <header class="w-full  md:h-[70vh] relative" >
        <div class="md:w-8/12 mx-auto md:grid md:grid-cols-2 gap-4 h-full ">
            <div class="flex flex-col items-center justify-center h-[20vh] md:h-full ">
                <h1 class="text-6xl font-[950] tracking-widest md:text-[6rem]">
                    <span class="bg-sky-600 text-white px-1">
                        MANDITA
                    </span>
                </h1>
                <h2 class="text-2xl md:text-4xl flex justify-center items-center mt-6 border-b-4 border-dashed border-gray-600 pb-1">
                    Mandiri
                    <Icon icon="mdi:circle-medium" class="text-gray-600 animate-pulse" />
                    Disiplin
                    <Icon icon="mdi:circle-medium" class="text-gray-600 animate-pulse" />
                    Bertakwa
                </h2>
            </div>
            <figure class="relative h-[30vh] w-full md:h-auto ">
                <!-- <img src="/img/gunung.svg" alt="" class="absolute"> -->
                <Icon icon="mdi:cloud-outline" class="absolute text-[3rem] md:text-8xl text-gray-200 left-20 top-12 md:top-36" />
                <Icon icon="mdi:weather-sunny" class="absolute text-[3rem] md:text-[4rem] text-gray-200 left-48 top-12 md:top-20 animate-spin" />
                <Icon icon="mdi:cloud-outline" class="absolute text-[6rem] md:text-[8rem] text-gray-200  right-6 md:right-16  top-0 md:top-20" />
                <Icon icon="mdi:weather-windy" class="hidden md:block absolute text-[6rem] md:text-[4rem] text-gray-200  right-6 md:right-36  top-0 md:top-48 animate-pulse" />
                <Icon icon="mdi:home-city-outline" class="absolute text-slate-200 text-[10rem] left-[50%] md:-translate-x-[100%] top-[35%] md:top-[50%]" />
                <Icon icon="mdi:grass" class="absolute text-[3rem] text-green-600 right-4 md:left-20 bottom-4 md:bottom-20  " />
                <img src="/img/siswa.svg" alt="" class="absolute left-[50%] -translate-x-[70%] md:-translate-x-[50%] top-[15%] md:top-[50%] md:-translate-y-[25%]">
                <Icon icon="mdi:grass" class="absolute text-[2rem] md:text-[4rem] text-green-600 left-12 md:left-[60%] bottom-10 md:bottom-20  " />
                <span class="absolute text-[2rem] md:text-[4rem] text-gray-50 left-[58%] md:left-[63%] bottom-[50%] -translate-y-[50%] md:bottom-60 animate-pulse" >
                    <Icon icon="mdi:message"  class="text-[4rem] text-sky-400" />
                    <span class="absolute text-gray-100 font-bold text-sm top-[40%] -translate-y-[50%] left-[50%] -translate-x-[50%]">Halo</span>
                </span>
            </figure>
        </div>
    </header>
</template>