<script setup>
import { ref } from 'vue';
const emit = defineEmits(['close'])
const onPaste = async(evt) => {
    let datas = evt.clipboardData.getData("text");
    
    // console.log(datas)
    text.value = datas
    datas.split("\r").forEach(data => console.log(data))
}

const items = ref([])
const text = ref('Pastekan data di sini')
const table = ref('')
</script>

<template>
    <div class="fixed top-0 right-0 bottom-0 left-0 bg-white backdrop-blur-sm bg-opacity-60 flex items-center justify-center overflow-y-auto z-50" @click.self="emit('close')">
        <textarea class="content w-[80%] bg-slate-400 h-64 shadow-lg rounded border-slate-100 border-4" @paste.prevent="onPaste" @focus="text=''">{{ text }}</textarea>
        <div id="table"></div>
    </div>
    
</template>