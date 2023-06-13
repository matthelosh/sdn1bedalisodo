<script setup>
import { ref, onMounted, defineAsyncComponent } from 'vue';
import { useDraggable } from '@vueuse/core';
import { isClient } from '@vueuse/shared';
import 'jodit/build/jodit.min.css'
import { JoditEditor } from 'jodit-vue'
import { Icon } from '@iconify/vue';
import axios from 'axios';
const FileManager = defineAsyncComponent(() => import('@/Components/File/FileManager.vue'))

onMounted(() => {
    listCategory()
})

const post = ref({
    category_id: 'art',
    featured_image: '/img/no-image.jpg',
    content: ''
})

const categories = ref([])
const listCategory = async() => {
    axios.post(route('category.index'))
            .then(res => {
                categories.value = res.data.categories
            })
}

const emit = defineEmits(['close'])

const el = ref(null)

const innerWidth = isClient ? window.innerWidth : 200

const {x,y,style} = useDraggable(el, {
    initialValue: { x: innerWidth / 4.2, y: 80 },
})

const showFileManager = ref(false)
const customButtons = [
    {
        name: 'uploadImage',
        iconURL: '/icons/img-upload.png',
        
        exec: function (editor) {
            showFileManager.value = true
        }

    }
]

const postSettings = ref(false)
const setting = () => {
    postSettings.value = !postSettings.value
}

const onFeaturedImgPicked = (e) => {
    post.value.featured_image = URL.createObjectURL(e.target.files[0])
}

const close = () => {  
    emit('close')
}

</script>

<template>
    <div class="w-full bg-white rounded p-3" >
        <div class="w-full flex justify-between items-center">
            <Icon icon="mdi:typewriter" class="hidden md:block" />
            <div class="toolbar-items flex items-center justify-end gap-1 w-full">
                <select class="focus:ring-0 rounded" v-model="post.category_id" >
                <option v-for="(category, c) in categories" :key="c" :value="category.kode">{{ category.label }}</option>
                </select>
                <button class="p-2 bg-sky-400 text-white rounded">
                    Simpan
                </button>
                <button class="bg-sky-600 text-white rounde py-2 px-4 rounded">
                    Rilis
                </button>
                <button @click="setting">
                    <Icon icon="mdi:cog-box" class="text-sky-800 text-4xl" />
                </button>
                <button @click="close">
                    <Icon icon="mdi:close-circle" class="text-red-400 text-2xl" />
                </button>
            </div>
        </div>
        <div class="w-full py-4 flex justify-between items-center gap-2">
            <textarea rows="1" placeholder="Tulis Judul" class="border-none focus:ring-0 font-bold text-lg text-sky-800 w-full" />
            
        </div>
        <div class="w-full gap-2 content-box" :class="postSettings ? 'flex' : ''">
            <JoditEditor v-model="post.content" :extra-buttons="customButtons" />
            <div class="w-1/4 bg-sky-100" v-if="postSettings">
                <input type="file" ref="featuredImg" class="hidden" @change="onFeaturedImgPicked">
                <img :src="post.featured_image" alt="" class="w-full" @click="$refs.featuredImg.click()">
                <p class="text-gray-600 text-center">Klik gambar untuk menambah sampul</p>
            </div>
        </div>
    </div>
    <FileManager v-if="showFileManager" @close="showFileManager = false" class="border-sky-600 border-2" />
</template>