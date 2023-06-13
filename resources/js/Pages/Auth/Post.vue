<script setup>
import { ref, computed, defineAsyncComponent } from 'vue';
import { Head, usePage, Link } from '@inertiajs/vue3';
import { Icon } from '@iconify/vue';

import { paginate } from '@/Plugins/misc';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const WritePost = defineAsyncComponent(() => import('@/Components/Dashboard/Post/WritePost.vue'))

const page = usePage();

const current = ref(0)
const search = ref(null)
const mode = ref('write')

const posts = computed(() => {
    let items = []
    if(search.value == null) {
        items = page.props.posts
    } else {
        items = page.props.posts.filter((item) => {
            return item.title.toLowerCase().includes(search.value.toLowerCase()) || item.content.toLowerCase().includes(search.value.toLowerCase())
        })
    }
    return paginate(items, 10, current.value)
})

const prev = (e) => {
    if(current.value > 0) {
        current.value -=1
    }
        
}
const next = (e) => {
    if (current.value < posts.value.page_length-1) {
        current.value +=1
    }
        
}
</script>

<template>
<Head title="Post" />   
<AdminLayout title="Postingan">
    
        <div class="p-4">
            <transition name="slide">
            <div class="w-full bg-white overflow-auto" v-if="mode == 'list'" :key="'tr1'">
                <div class="w-full h-12 bg-white flex items-center justify-between p-2">
                    <div class="flex gap-2">
                        <h1 class="text-sky-800 text-lg font-bold">Tulisan</h1>
                    </div>
                    <div class="toolbar-items flex gap-1">
                        <button class="text-sky-400 hover:bg-sky-400 hover:text-white flex items-center border border-sky-400 px-2 text-sm rounded" @click="mode='write'">
                            <Icon icon="mdi:typewriter" />
                            Baru
                        </button>
                        
                        <div class="relative">
                            <input type="text" placeholder="Cari" class="h-8 border-gray-400 rounded" v-model="search">
                            <Icon icon="mdi:magnify" class="absolute right-2 top-2" />
                        </div>
                    </div>
                </div>
                <table class="table borrder border-collapse w-full">
                    <thead>
                        <tr class="bg-sky-200">
                            <th class="py-1 px-2">No</th>
                            <th class="py-1 px-2">Judul</th>
                            <th class="py-1 px-2">Penulis</th>
                            <th class="py-1 px-2">Tanggal</th>
                            <th class="py-1 px-2">Status</th>
                            <th class="py-1 px-2">Opsi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="(post, p) in posts.current"
                            :key="p"
                            class="even:bg-sky-100 hover:bg-sky-50"
                        >
                            <td class="py-1 px-2 text-center">{{  (p+1) + (10 * current)}}</td>
                            <td class="py-1 px-2">{{ post.title }}</td>
                            <td class="py-1 px-2">{{ post.author_id }}</td>
                            <td class="py-1 px-2">{{ post.created_at }}</td>
                            <td class="py-1 px-2">{{ post.status }}</td>
                            <td class="py-1 px-2">Opsi</td>
                        </tr>
                    </tbody>
                </table>
                <div class="bg-sky-200 flex items-center justify-between pl-3 flex-wrap w-full">
                    Jml Halaman: {{ posts.page_length }} - {{ current }}
                    <div class="flex items-center h-full flex-wrap">
                        <button @click="prev" class="flex justify-center w-8 border border-gray-500  py-1"><Icon icon="mdi:chevron-double-left" /></button>
                        <button v-for="b in posts.page_length" :key="b" class="flex justify-center w-8 border border-gray-500" :class="(b-1) == current ? 'bg-sky-600 text-white': ''" @click="current=(b-1)">{{ b }}</button>
                        <button @click="next" class="flex justify-center w-8 border border-gray-500 py-1"><Icon icon="mdi:chevron-double-right" /></button>
                    </div>
                </div>
            </div>
            <div v-else-if="mode == 'write'">
                <WritePost  :key="'tr2'" @close="mode = 'list'" />
            </div>
            </transition>
        </div>
</AdminLayout>
</template>

<style>
.slide-enter-active,
.slide-leave-active {
    transition: all .35s linear;
}

.slide-leave-to
.slide-enter-from {
    transform: translateY(20px);
    opacity: 0;
}
</style>