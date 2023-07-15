<script setup>
import {Icon} from '@iconify/vue'
</script>

<template>
<div class="loading fixed top-0 right-0 bottom-0 left-0 bg-white bg-opacity-10 backdrop-blur-sm z-50 flex items-center justify-center">
    <div class="w-1/4">
        <Icon icon="mdi:progress-helper" class="text-teal-600 text-8xl animate-spin mx-auto" />
        <h1 class="text-center text-teal-600 text-xl font-bold">Mohon Tunggu</h1>
    </div>
</div>
</template>