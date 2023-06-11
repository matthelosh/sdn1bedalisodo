<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { isClient } from '@vueuse/shared'
import { useDraggable } from '@vueuse/core'
import { UseDraggable as Draggable } from './component'
import  SvgIcon from '@jamescoyle/vue-icon';
import { mdiClose, mdiRefresh } from '@mdi/js';
import axios from 'axios';

const loading = ref(false)

const el = ref<HTMLElement | null>(null)
const handle = ref<HTMLElement | null>(null)

const innerWidth = isClient ? window.innerWidth : 200

const { x, y, style } = useDraggable(el, {
  initialValue: { x: innerWidth / 4.2, y: 80 },
  preventDefault: true,
})

const images = ref([]);

const listImages = async () => {
    loading.value = true
    await axios.post(route('images.list'))
                .then(res => {
                    images.value = res.data.images
                    loading.value = false
                })
} 

const emit = defineEmits(['close'])
const closeMe = () => {
    emit('close')
}
onMounted(() => {
    listImages()
})
</script>

<template>
  <div>
    <Draggable
      v-slot="{ x, y }"
      p="x-4 y-2"
      border="~ gray-400/30 rounded"
      shadow="~ hover:lg"
      class="fixed bg-white z-40 rounded-xl shadow-xl w-[400px] overflow-hidden"
      :initial-value="{ x: innerWidth / 3.6, y: 240 }"
      :handle="handle"
    >
      <div ref="handle" class="cursor-move  p-2 flex items-center justify-between">
        <span>👋 Geser</span>
        <button @click="closeMe">
            <SvgIcon type="mdi" :path="mdiClose" class="text-red-400" />
        </button>
      </div>
      <div class="text-sm bg-gray-100 p-4">
            <h1>Data Gambar</h1>
            <div class="w-full h-full flex items-center justify-center" v-if="loading">
                <SvgIcon type="mdi" :path="mdiRefresh" size="64" class=" text-gray-600 animate-spin" />
           </div>
           <div class="w-full grid grid-cols-4 gap-1" v-else>
            <img v-for="(image, im) in images" :key="im" :src="`${image.replace('public', '')}`" class="w-full h-20 object-cover cursor-grab" />
           </div>
           
      </div>
    </Draggable>
  </div>
</template>