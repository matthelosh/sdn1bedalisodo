<script setup>
import axios from 'axios';
import { ref, computed, onMounted } from 'vue';
import { usePage } from '@inertiajs/vue3';

const page = usePage()

const layouts = ref([])
const listLayout = async() => {
    await axios.post(route('dashboard.setting.layout.index'))
            .then(res => {
                layouts.value = res.data.layouts
            })
}

const setLayout = async(name) => {
    axios.post(route('dashboard.setting.layout.activate'), {name: name, _method: 'put'})
        .then(res => {
            listLayout()
        })
}

onMounted(() => {
    listLayout()
})
</script>

<template>
    <div class="w-full shadow-lg">
        <div class="toolbar h-10 bg-slate-400 text-white flex p-3">
            Layout
        </div>
        <div class="content p-2">
            <ul>
                <li
                    v-for="layout in layouts"
                    class="hover:bg-slate-200 transition-all hover:cursor-pointer p-1"
                    :class="layout.name == page.props.layout ? 'bg-sky-400 text-white' : ''"
                    @click="setLayout(layout.name)"
                >
                    {{ layout.name }}
                </li>
            </ul>
        </div>
        
    </div>
</template>