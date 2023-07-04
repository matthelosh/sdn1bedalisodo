<script setup>
import { ref, watch } from 'vue';
import { Icon } from '@iconify/vue';

const show = ref(false)
const items = ref([])
const message = ref('')
const resolved = ref(null)
const rejected = ref(null)
const open = async (text, datas) => {
    show.value = true
    message.value = text
    return new Promise((resolve, reject) => {
        resolved.value = resolve
        rejected.value = reject
    })
}

const okey = () => {
    show.value = false
    resolved.value(true)
}
const nope = () => {
    show.value = false
    resolved.value(false)
}

defineExpose({
    open
})
</script>

<template>
    <div class="overlay fixed top-0 right-0 bottom-0 left-0 z-50 bg-black bg-opacity-50 flex items-center justify-center" @click.self="show=false" v-if="show" >
        <div class="dialog max-w-[400px] min-h-[250px]  rounded-xl shadow bg-white flex items-center justify-center">
            <div class="w-full px-4">
                <Icon icon="mdi:comment-question" class="text-red-400 h-32 animate-bounce mx-auto text-8xl" />
                <h1 class="text-xl text-gray-800 mb-4 text-center">{{ message }}</h1>
                <div class="flex justify-around gap-2 w-full">
                    <button @click="nope" class="px-3 py-2 rounded hover:bg-red-600 active:bg-orange-400 bg-red-400 text-white">Batal</button>
                    <button @click="okey" class="px-3 py-2 rounded hover:bg-sky-600 active:bg-orange-400 bg-sky-400 text-white">Yakin</button>
                </div>
            </div>
        </div>
    </div>
</template>