<script setup>
import {usePage} from '@inertiajs/vue3'

const page = usePage()
</script>


<template>
 <div class="wrapper w-screen h-screen bg-red-50 flex items-center justify-center">
    <div class="text-center">
        <h1 class="text-2xl font-bold text-gray-600 tracking-wide">Error 500</h1>
        <p class="text-gray-500">Ada sesuatu yang salah. Beri kami waktu mencari tahu.</p>
        <p>{{ page.props }}</p>
    </div>
 </div>
</template>