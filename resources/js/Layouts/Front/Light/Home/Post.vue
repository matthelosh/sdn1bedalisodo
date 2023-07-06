<script setup>
import { ref, computed } from 'vue';
import { usePage, Link } from '@inertiajs/vue3';
import { Icon } from '@iconify/vue';

const category = ref('all')
const categories = ref([
    {code: 'all', label: 'Semua'},
    {code: 'inf', label: 'Pengumuman'},
    {code: 'brt', label: 'Berita'},
    {code: 'art', label: 'Artikel'},
    {code: 'mtr', label: 'Materi'},
    {code: 'crt', label: 'Cerita'},
    {code: 'ase', label: 'Penilaian'}
]);
const page = usePage();

const posts = computed(() => {
    return category.value == 'all' ? page.props.posts.slice(0, 6) : page.props.posts.filter(item => item.category_id == category.value).slice(0,6)
})
</script>
<template>
<div class="w-full bg-gray-200 py-8">
    <div class="title w-10/12 md:w-8/12 bg-transparent mx-auto my-6 flex items-center justify-center">
        <Icon icon="mdi:newspaper-variant" class="text-gray-800 text-2xl" />
        <h1 class="text-center text-xl font-bold text-gray-800">Tulisan Baru</h1>
    </div>
    <div class="container w-11/12 md:w-8/12 mx-auto grid grid-cols-1 md:grid-cols-6 gap-2">
        <div class="category col-span-1">
            <label for="category" class="w-full  md:hidden">
                <select v-model="category" id="category" class="rounded w-full border-gray-300 shadow">
                    <option 
                        v-for="(cat, c) in categories" :key="c"
                        :value="cat.code">{{ cat.label }}</option>
                </select>
            </label>
            <ul class="hidden md:block">
                <li>
                    <h1 class="font-bold text-lg">Kategori:</h1>
                </li>
                <li
                    v-for="(cat, c) in categories" :key="c"
                    @click="category = cat.code"
                    class="cursor-pointer my-2 flex justify-between items-center px-1" 
                    :class="category == cat.code ? 'font-bold' : ''"
                >
                    {{ cat.label }}
                    <Icon icon="mdi:chevron-right-circle" v-if="category == cat.code" class="text-xl text-gray-600" />
                </li>
            </ul>
        </div>
        <div class="container w-full mx-auto grid grid-cols-2 md:grid-cols-3 gap-2 md:gap-4 md:col-span-5">
            <div class="card bg-white shadow hover:shadow-lg col-span-2 md:col-span-1"  v-for="(post,p) in posts" :key="p" v-if="posts.length > 0">
                    <div class="hidden md:block relative card-header h-10 bg-transparent md:h-[125px] w-full overflow-hidden bg-cover group" :style="`background-image: url('${post.featured_image}');`">
                        <div class="absolute z-10 text-white bg-gradient-to-tr hover:from-transparent hover:to-transparent transition from-[#89786799] to-sky-600 text-xl  top-0 right-0 bottom-0 left-0 p-1 group-hover:bg-gradient-1 flex items-end">
                            <Link 
                            :href="`/${post.category.label.toLowerCase()}`"
                            class="group-hover:shadow group-hover:bg-sky-600 p-2 leading-6 text-white tracking-wide truncate hover:text-ellipsis">
                                {{ post.category.label }}
                            </Link>
                            
                        </div>
                    </div>
                    <div class="card-content p-3 ">
                        <p class="flex items-center gap-2 text-sm text-orange-400 mb-2">
                            <span class="flex gap-1 items-center">
                                <Icon icon="mdi:account-tie" class="text-lg text-gray-600" />
                                {{ post.author.name }}
                            </span>

                            <span class="flex items-center">
                                <Icon icon="mdi:calendar" class="text-lg text-gray-600" />
                                {{ new Date(post.created_at).toLocaleString("id-ID", {year: "numeric",month: "short",day: "numeric",}) }}
                            </span>
                            <span class="flex gap-1 items-center">
                                <Icon icon="mdi:eye" class="text-lg text-gray-600" />
                                {{ post.views.length }}
                            </span>
                        </p>
                        <Link 
                            :href="`/${post.category.label.toLowerCase()}/${post.slug}`" 
                            class="text-slate-600 font-bold text-lg leading-3 my-4">
                            {{ post.title }}
                        </Link>
                        <p v-html="post.content.substr(0, 100)" class="mt-2"></p>
                    </div>
                </div>
            <div class="col-span-2 md:col-span-3 text-center">
                <h1 v-if="posts.length < 1" class="text-slate-600 font-bold tracking-wide bg-white p-3 rounded">Belum Ada Post pada Kategori ini</h1>
                <button v-else class="mx-auto py-1 px-2 my-3 rounded-xl bg-sky-400 text-white font-bold">Lihat Semua</button>
            </div>
        </div>
    </div>
</div>
</template>
<style>
    .tes {
        background-color: #4a81e18a;
    }
</style>