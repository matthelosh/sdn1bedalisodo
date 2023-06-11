<script setup>
import { ref, onBeforeMount } from 'vue';
import { useDraggable } from '@vueuse/core';
import { isClient } from '@vueuse/shared';
import 'jodit/build/jodit.min.css'
import { JoditEditor } from 'jodit-vue'
import FileManager from '@/Components/File/FileManager.vue'

const props = defineProps({text: String})
const emit = defineEmits(['simpan'])
onBeforeMount(() => {
    // console.log(props.text)
    content.value = props.text ? props.text : 'Write Some text'
})

const content = ref('')
const el = ref(null)
const handle = ref<HTMLElement | null>(null)



const innerWidth = isClient ? window.innerWidth : 200

const {x,y,style} = useDraggable(el, {
    initialValue: { x: innerWidth / 4.2, y: 80 },
//   preventDefault: true,
})

const showFileManager = ref(false)
// const insertImage = () => {
//     showFileManager.value = true
// }
const customButtons = [
    {
        name: 'uploadImage',
        iconURL: '/icons/img-upload.png',
        
        exec: function (editor) {
            // let url = insertImage()
            // editor.selection.insertHTML(url);
            showFileManager.value = true
        }

    }
]

const simpan = () => {
    emit('simpan', content.value)
}
</script>

<template>
    <div class="w-full bg-white rounded p-3">
        <button class="bg-sky-600 hover:bg-sky-400 active:bg-orange-400 text-white py-2 px-3 rounded-full my-4" @click="simpan">Simpan</button>
        <JoditEditor v-model="content" :extra-buttons="customButtons" />
        
    </div>
    <FileManager v-if="showFileManager" @close="showFileManager = false" />
</template>