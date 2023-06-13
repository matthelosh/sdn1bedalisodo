<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { isClient } from '@vueuse/shared'
import { useDraggable, useClipboard } from '@vueuse/core'
import { UseDraggable as Draggable } from './component'
import  SvgIcon from '@jamescoyle/vue-icon';
import { mdiClose, mdiRefresh } from '@mdi/js';
import axios from 'axios';

const loading = ref(false)

const source = ref('')
const { text, copy, copied, isSupported } = useClipboard({ source })

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

const emit = defineEmits(['close', 'insertImg'])
const closeMe = () => {
    emit('close')
}

const picked = ref(null)
const pickedImages = ref([])

const onFilePicked = (e) => {
  let images = e.target.files
  let urls = []
  for(let i =0; i < images.length; i++) {
    urls.push(
      URL.createObjectURL(images.item(i))
    )
    // console.log(URL.createObjectURL(images.item(i)))
  }
  pickedImages.value = urls
  picked.value = e.target.files
  console.log(urls)
}

const salin = (e) => {
  
  emit('insertImg', e.target.src)
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
      shadow="~ hover:lg"
      class="fixed bg-white z-40 rounded-xl shadow-xl  overflow-hidden"
      :class="pickedImages.length > 0 ? 'w-auto' : 'w-[400px]'"
      :initial-value="{ x: innerWidth / 3.6, y: 240 }"
      :handle="handle"
    >
      <div ref="handle" class="cursor-move  p-2 flex items-center justify-between">
        <span>👋 Geser</span>
        <button @click="closeMe">
            <SvgIcon type="mdi" :path="mdiClose" class="text-red-400" />
        </button>
      </div>
      <div class="text-sm bg-gray-100 p-4 flex">
        <div>
            <h1>Data Gambar</h1>
            <div>
                <div class="w-full h-full flex items-center justify-center" v-if="loading">
                  <SvgIcon type="mdi" :path="mdiRefresh" size="64" class=" text-gray-600 animate-spin" />
              </div>
              <div class="w-full grid grid-cols-4 gap-1" v-else>
                <img v-for="(image, im) in images" :key="im" :src="`${image.replace('public', '')}`" class="w-full object-cover cursor-pointer" @click="salin"/>
              </div>
            </div>
        </div>
        <div v-if="picked" class="flex gap-2 p-4">
          <img v-for="(gambar,g) in pickedImages" :key="g" :src="gambar" class="w-[100px] aspect-square object-cover" />
        </div>
      </div>
      <div class="w-full bg-white flex p-2 items-center justify-between">
        <input type="file" id="images" name="images" multiple accept=".jpg,.png,.jpeg" @change="onFilePicked" />
        <button class="text-sky-600">Upload</button>
      </div>
    </Draggable>
  </div>
</template>