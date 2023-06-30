<script setup>
import {ref, onMounted,} from 'vue'

onMounted(() => {
    setInterval(() => nextSlide(), 500)
})

const show = ref(0)
const current = ref(0)
const nextSlide = () => {
    if (current.value < 4) {
        current.value++
    } else {
        current.value = 0
    }
}
const colors = ref(['#2B2D42', '#8D99AE', '#EDF2F4', '#EF233C', '#D90429'])

</script>

<template>
    <div class="carousel overflow-hidden bg-pink relative w-screen md:w-full rounded-md">
        <div class="carousel-inner relative bg-gray-100 transition-all ease-in-out flex flex-row w-[2500px] scroll-smooth" :style="`transform: translateX(-${500 * current}px)`">
            <div class="carousel-item w-screen  md:w-[500px] h-[300px] rounded " v-for="i in 5" :key="i" :style="`background: ${colors[i-0]}`">
                <div class="w-full h-full flex items-center justify-center">
                    <h1>Halo {{ i }}</h1>
                </div>
            </div>
        </div>
    </div>
</template>