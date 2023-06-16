<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { isClient } from '@vueuse/shared'
import { useDraggable, useClipboard } from '@vueuse/core'
import { UseDraggable as Draggable } from './component'
import  SvgIcon from '@jamescoyle/vue-icon';
import { mdiClose, mdiRefresh } from '@mdi/js';
import axios from 'axios';
import { Icon } from '@iconify/vue';

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

const disk = ref('local');
const toggleDisk = async (storage) => {
   disk.value = storage
   listImages()
} 

const listImages = async () => {
    loading.value = true
    await axios.post(route('images.list', {_query:{disk:disk.value}}))
                .then(res => {
                    images.value = res.data.images
                    loading.value = false
                    imagesInput.value.value = ''
                })
} 

const emit = defineEmits(['close', 'insertImg'])
const closeMe = () => {
    emit('close')
}

const picked = ref(null)
const pickedImages = ref([])
const imagesInput = ref(null)

const onFilePicked = (e) => {
  let images = e.target.files
  let urls = []
  for(let i =0; i < images.length; i++) {
    urls.push(
      URL.createObjectURL(images.item(i))
    )
  }
  pickedImages.value = urls
  picked.value = e.target.files
}

const selectedImages = ref([])
const salin = (e, imgs = selectedImages.value) => {
  let images = ''
  // console.log(imgs)
  imgs.forEach(img => {
    images += `<img src="${img}"  class="cursor-pointer w-full shadow hover:shadow-lg" />`
  })
  let figure = `<figure class="grid grid-cols-1 gap-4 w-3/4 mx-auto md:grid-cols-${imgs.length}">${images}</figure>`
  emit('insertImg', figure)
  selectedImages.value = []
  document.querySelectorAll("img").forEach(img => img.classList.remove("picked"))
}

const selectImage = (e) => {
  // selectedImages.value.includes(e.target.src) ? selectedImages.value.splice(selectedImages.value.lastIndexOf(e.target.src),1) : selectedImages.value.push(e.target.src)
  if (selectedImages.value.includes(e.target.src)) {
    selectedImages.value.splice(selectedImages.value.lastIndexOf(e.target.src),1)
    e.target.classList.remove("picked")
  } else {
    selectedImages.value.push(e.target.src)
    e.target.classList.add("picked")
  }
}

const upload = async() => {
  const fd = new FormData()
  let fotos = []
  for (let i = 0; i< picked.value.length; i++) {
    fd.append("images[]", picked.value[i])
  }
  
  await axios.post(route('image.upload'), fd, {headers: { 'Content-Type': 'multipart/form-data'}})
            .then(res => {
              picked.value = null
              pickedImages.value = []
              listImages()
              salin(res.data.urls)
            })
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
      class="fixed bg-white z-40 rounded-xl shadow-xl overflow-hidden w-[400px] md:w-[600px]"
      :initial-value="{ x: innerWidth / 3.6, y: 140 }"
      :handle="handle"
      
    >
      <div ref="handle" class="cursor-move  p-2 flex items-center justify-between">
        <span>👋 Geser</span>
        <div class="handle-items flex items-center gap-2">
          <button>
            <Icon icon="mdi:folder" @click="toggleDisk('local')" />
          </button>
          <button>
            <Icon icon="mdi:amazon-drive" @click="toggleDisk('s3')" />
          </button>
          <button @click="salin" :disabled="selectedImages.length < 1" class="cursor-pointer" v-if="selectedImages.length > 0">
            <span class="hidden md:block text-sky-400">
              Masukkan ke Tulisan
            </span>
            <Icon icon="mdi:import" class="block md:hidden" />
          </button>
          <button @click="closeMe">
              <SvgIcon type="mdi" :path="mdiClose" class="text-red-400" />
          </button>
        </div>
      </div>
      <div class="text-sm bg-gray-100 p-4 flex h-[300px] overflow-y-auto">
        <div>
            <h1>Data Gambar</h1>
            <div>
              <div class="w-full h-full flex items-center justify-center" v-if="loading">
                  <SvgIcon type="mdi" :path="mdiRefresh" size="64" class=" text-gray-600 animate-spin" />
              </div>
              <div class="w-full grid grid-cols-4 gap-4 h[350px] overflow-y-auto" v-else>
                <img v-for="(image, im) in images" :key="im" :src="`${image.replace('public', '')}`" class="w-full object-cover cursor-pointer h-[100px]" @click="selectImage"/>
              </div>
            </div>
        </div>
        
      </div>
      <div class="w-full bg-white ">
        <div class="w-full flex p-2 items-center justify-between">
          <input type="file" id="images" name="images" multiple accept=".jpg,.png,.jpeg" @change="onFilePicked" ref="imagesInput" />
          <button class="text-sky-600" @click="upload">Upload</button>
        </div>
        <div v-if="picked" class="flex gap-2 p-4 flex-grow">
          <img v-for="(gambar,g) in pickedImages" :key="g" :src="gambar" class="w-[100px] aspect-square object-cover" />
        </div>
      </div>
    </Draggable>
  </div>
</template>
<style scoped>
.picked{
  filter: saturate(1.2) brightness(103%);
  scale: 1.1;
  position: relative;
  transition: all .35s cubic-bezier(0.39, 0.575, 0.565, 1);
  border: 2px solid rgb(62, 108, 183);
  border-radius: 10px;
}

img {
  transition: all .35s cubic-bezier(0.075, 0.82, 0.165, 1);
}

</style>