<script setup>
import { ref } from 'vue';
import SvgIcon from '@jamescoyle/vue-icon';
import { mdiClose } from '@mdi/js';

const campaign =ref(
    {
        id: '1',
        status: '1',
        title: 'Pembangunan Mushola',
        description: `
            Mari berpartisipasi dalam pembangunan mushola SD Negeri 1 Bedalisodo. Semoga bantuan yang diberikan menjadi shodaqoh jariyah dan diberikan keberkahan oleh Allah SWT.
        `,
        photo: '/img/mushola2.jpg',
        background: '/img/mushola1.jpg'
    }
)

const emit = defineEmits(['close'])

const close = () => emit('close')

</script>

<template>
<div class="w-screen h-screen fixed z-50 bg-gray-800 bg-opacity-90 flex items-center justify-center" :style="`background-image: url('${campaign.background}');`" style="background-position: bottom; background-blend-mode: multiply;">
    <button class="absolute right-4 top-4" @click="close">
        <SvgIcon type="mdi" :path="mdiClose" size="32" class="text-white z-50" />
    </button>
    <div class="w-3/4">
        <h1 class="text-4xl text-white text-center">{{campaign.title}}</h1>
        <p class="text-white">{{ campaign.description }}</p>
        <img :src="campaign.photo" alt="Foto" class="w-full md:w-2/4">
    </div>
</div>
</template>