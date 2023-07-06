<script setup>
import { ref } from 'vue';
import { Link } from '@inertiajs/vue3';
import { Icon } from '@iconify/vue';
import Navbar from './Home/Navbar.vue'
import Footer from './Home/Footer.vue'

const props = defineProps({results: Array});
const results = ref(props.results)

const categories = ref([
    {code: 'all', label: 'Semua'},
    {code: 'inf', label: 'Pengumuman'},
    {code: 'brt', label: 'Berita'},
    {code: 'art', label: 'Artikel'},
    {code: 'mtr', label: 'Materi'},
    {code: 'crt', label: 'Cerita'},
    {code: 'ase', label: 'Penilaian'}
]);
</script>

<template>
    <div class="w-full bg-slate-200">
        <img src="/img/blob1.svg" alt="" class="absolute z-10 -top-16 -left-8 w-32 md:hidden opacity-20">
        <Navbar />
        <div class="container w-full md:w-8/12 mx-auto relative p-3 md:py-8">
            <h1 class="md:text-2xl text-center mb-4">Hasil Pencarian dengan kata kunci: <span class="font-bold">"{{ route().params.q }}"</span></h1>
            <div class="container w-full mx-auto grid grid-cols-2 md:grid-cols-4 gap-2 md:gap-4 md:col-span-5">
                <div class="card bg-white shadow hover:shadow-lg col-span-2 md:col-span-1"  v-for="(post,p) in results" :key="p" v-if="results.length > 0">
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
                <div class="col-span-2 md:col-span-4 min-h-[50vh] flex items-center justify-center" v-else>
                    <h1 class="text-slate-600  text-2xl font-bold tracking-wide bg-white p-3 rounded">Belum Ada Post pada Kategori ini</h1>
                </div>
                
            </div>
        </div>
        <Footer />
    </div>
</template>