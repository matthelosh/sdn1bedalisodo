<script setup>
import { ref, computed, defineAsyncComponent } from 'vue';
import { Head, usePage, router } from '@inertiajs/vue3';
import { Icon } from '@iconify/vue'

import { paginate } from '@/Plugins/misc';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { formatDate } from '@vueuse/shared';

import 'element-plus/es/components/table/style/css';
import 'element-plus/es/components/pagination/style/css';

import moment from 'moment';
moment.locale('id');

const loading = ref(false)
const Loading = defineAsyncComponent(() => import('@/Components/General/Loading.vue'))

router.on('start', () => loading.value = true)
router.on('finish', () => loading.value = false)

const WritePost = defineAsyncComponent(() => import('@/Components/Dashboard/Post/WritePost.vue'))
// import PostEditor from '@/Components/Dashboard/Post/PostEditor.vue'

const page = usePage();

const current = ref(0)
const search = ref(null)
const mode = ref('list')

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
const selectedPost = ref(null)
const edit = async(post) => {
    selectedPost.value = post
    mode.value = 'write'
}

const close = () => {
    selectedPost.value = null
    mode.value = 'list';
    router.reload({only: ['posts']})
}

const tanggal = (tanggal) => {
    let date = new Date(tanggal)
    return date.getDate()+'-'+date.getMonth()+'-'+date.getFullYear()+' : '+ date.getHours()+'.'+date.getMinutes()+'.'+date.getSeconds()
}
</script>

<template>
<Head title="Post" />   
<AdminLayout title="Postingan">
    <Loading v-if="loading" />
        <div class="p-4">
            <transition name="slide">
            <div class="w-full bg-white overflow-auto shadow-lg" v-if="mode == 'list'" :key="'tr1'">
                <div class="w-full h-12 bg-white flex items-center justify-between p-2">
                    <div class="flex gap-2">
                        <h1 class="text-sky-800 text-lg font-bold">Tulisan</h1>
                    </div>
                    <div class="toolbar-items flex gap-1">
                        <button class="text-teal-400 hover:bg-teal-400 hover:text-white flex items-center border border-teal-400 px-2 text-sm rounded" @click="mode='write'">
                            <Icon icon="mdi:typewriter" />
                            Baru
                        </button>
                        
                        <div class="relative">
                            <input type="text" placeholder="Cari" class="h-8 border-gray-400 rounded" v-model="search">
                            <Icon icon="mdi:magnify" class="absolute right-2 top-2" />
                        </div>
                    </div>
                </div>
                <div class="content w-full overflow-auto">
                    <el-table :data="posts.current" size="small" stripe height="500">
                        <el-table-column label="Judul" width="350">
                            <template #default="scope">
                                <p class="text-blue-600 hover:underline text-left cursor-pointer" @click="edit(scope.row)">
                                    {{ scope.row.title }}
                                </p>
                            </template>
                        </el-table-column>
                        <el-table-column label="Kategori" >
                            <template #default="scope">
                                {{ scope.row.category.label }}
                            </template>
                        </el-table-column>
                        <el-table-column label="Penulis" >
                            <template #default="scope">
                                {{ scope.row.author.userable.nama }}
                            </template>
                        </el-table-column>
                        <el-table-column label="Penulis" >
                            <template #default="scope">
                                {{ moment(scope.row.created_at).format('d MMM Y') }}
                            </template>
                        </el-table-column>
                        <el-table-column label="Status" prop="status"  />
                        <el-table-column label="Opsi">
                            <template #default="scope">
                                <button class="bg-red-400 text-white py-1 px-2 rounded-md shadow-lg my-4 hover:shadow-sm active:shadow-none active:bg-red-500">
                                    <Icon icon="mdi:delete"  />
                                </button>
                            </template>
                        </el-table-column>
                    </el-table>
                    <el-pagination small layout="prev, pager, next" :total="30" />
                </div>
            </div>
            <div v-else-if="mode == 'write'">
                <WritePost  :key="'tr2'" @close="close" :selectedPost="selectedPost" />
                <!-- <PostEditor /> -->
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