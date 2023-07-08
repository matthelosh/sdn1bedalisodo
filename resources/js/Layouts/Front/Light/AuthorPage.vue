<script setup>
import Navbar from './Home/Navbar.vue'
import Footer from './Home/Footer.vue'
import { Icon } from '@iconify/vue'
const props = defineProps({author:Object})

const defaultAvatar = ($event, guru) => {
    let image = guru.jk == 'Laki-laki' ? 'guru-l' : (guru.agama == 'Islam' ? 'guru-p-jilbab' : 'guru-p')
    $event.target.src = `/img/${image}.png`
    // return `/img/${image}.png`
}

const capitalize = (text) => {
    let res = ''
    text =  text.split(" ")
    text.forEach(t => {
        res += (t[0].toUpperCase()+t.slice(1).toLowerCase())+" "
    })

    return res
}
</script>

<template>
<div class="wrapper w-full bg-sky-50">
    <Navbar />
    <main class="w-full md:w-8/12 mx-auto p-4 md:p-0">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 my-8">
            <figure>
                <img
                    :src="`/storage/images/guru/${props.author.userable.nip}.jpg`"
                    @error="defaultAvatar($event, props.author.userable)"
                    class="rounded-full border-4 border-gray-600 w-[200px] md:w-[300px] hover:border-sky-600 mx-auto shadow" 
                    
                    alt="Guru">
            </figure>
            <div class="bio md:col-span-2">
                <h1 class="text-4xl md:text-[4rem] font-serif capitalize">Tentang {{ capitalize(props.author.userable.nama )}}</h1>
                <p class="mb-3 text-justify font-serif">Lorem ipsum, dolor sit amet consectetur adipisicing elit. Tempora accusantium cumque reiciendis libero odit, saepe veritatis? Nam eos magnam architecto adipisci harum ipsa at molestias necessitatibus aperiam! Quasi, voluptatem aperiam?</p>
                <p class="mb-3 text-justify font-serif">Lorem ipsum dolor sit, amet consectetur adipisicing elit. Cumque reiciendis facilis quidem praesentium dolore sed aut sequi cum similique, minima quaerat suscipit at. Vitae nihil autem illo non! Suscipit, obcaecati.</p>
                <div class="sosmed flex flex-wrap justify-end gap-4 my-2">
                    <Icon icon="mdi:facebook" class="text-sky-800 text-4xl" />
                    <Icon icon="mdi:twitter" class="text-sky-600 text-4xl" />
                    <Icon icon="mdi:instagram" class="text-red-300 text-4xl" />
                    <Icon icon="mdi:whatsapp" class="text-green-600 text-4xl" />
                    <Icon icon="mdi:youtube" class="text-red-800 text-4xl" />
                </div>
            </div>
        </div>
        

        <div class="posts my-4">
            <h1 class="text-lg font-bold">Beberapa Artikel yang ditulis:</h1>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 bg-sky-300">
                <h1 class="font-bold text-center text-lg">Tanggal</h1>
                <h1 class="font-bold text-center text-lg">Judul</h1>
                <h1 class="font-bold text-center text-lg">Kategori</h1>
            </div>
            <div 
                class="post grid grid-cols-1 md:grid-cols-3 gap-4 bg-sky-100 my-3"
                v-for="(post,p) in props.author.posts"
                :key="p"
                >
                <div class="tanggal">
                    <h1 class="text-center">{{ new Date(post.created_at).toLocaleString("id-ID") }}</h1>
                </div>
                <div class="title">
                    <h1>{{ post.title }}</h1>
                </div>
                <div class="category">
                    <h1 class="text-center">{{ post.category.label }}</h1>
                </div>
            </div>
        </div>
    </main>
    <Footer />
</div>
</template>