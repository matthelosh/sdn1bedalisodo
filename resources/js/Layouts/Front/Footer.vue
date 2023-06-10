<script setup>
import { Link } from '@inertiajs/vue3';
import SvgIcon from '@jamescoyle/vue-icon';
import {mdiEmail, mdiHeart, mdiLaravel, mdiVuejs, mdiTailwind} from '@mdi/js';
import {ChevronDoubleRightIcon} from '@heroicons/vue/24/outline';
</script>
<template>
    <footer class="w-full min-h-[200px] bg-primary">
        <div class="grid grid-cols-1 md:grid-cols-3 m-4 my-0">
            <div class="footer-identitas p-3 flex flex-col justify-center items-center md:flex-row gap-3 text-sm">
                <img src="/img/logo.png" alt="Logo" class="h-20 drop-shadow" />
                <div class="footer-identitas--alamat">
                    <h1 class="leading-4 md:leading-5 tracking-wide text-center md:text-left text-lg font-thin text-yellow-400">SD Negeri 1 Bedalisodo</h1>
                    <p  class="leading-4 md:leading-5 tracking-wide text-center md:text-left text-accent">Jl. Raya Sengon No. 293, Dalisodo</p>
                    <p  class="leading-4 md:leading-5 tracking-wide text-center md:text-left text-accent">Kec. Wagir, Kode Pos 65158</p>
                    <p  class="leading-4 md:leading-5 tracking-wide text-center md:text-left text-accent flex items-center underline underline-offset-3 gap-1">
                        <SvgIcon type="mdi" :path="mdiEmail" /> <a href="mailto:admin@sdn1-bedalisodo.sch.id">admin@sdn1-bedalisodo.sch.id</a>
                    </p>
                </div>
            </div>
            <div class="p-3 text-white">
                <h1 class="text-lg font-bold tracking-wide text-yellow-200">Tulisan terbaru:</h1>
                <ul>
                    <li v-for="(post,p) in $page.props.posts" :key="p">
                        <Link class="flex items-center gap-1 text-justify text-accent hover:text-yellow-400" :href="route('Post.read', {kategori:post.category.label.toLowerCase(), slug: post.slug})"> <ChevronDoubleRightIcon class="h-3 " /> {{ post.title }}</Link>
                    </li>

                </ul>
            </div>
            <div class="p-3 text-white">
                <h1 class="text-lg font-bold tracking-wide text-yellow-200">Link Eksternal:</h1>
                <ul>
                    <li>
                        <a class="flex gap-1 items-center text-accent hover:text-yellow-400" href="https://kemdikbud.go.id" target="_blank"> <ChevronDoubleRightIcon class="h-3" /> Kemdikibudristek RI</a>
                    </li>
                    <li>
                        <a class="flex gap-1 items-center text-accent hover:text-yellow-400" href="https://guru.kemdikbud.go.id" target="_blank"> <ChevronDoubleRightIcon class="h-3" />Merdeka Mengajar</a>
                    </li>
                    <li>
                        <a class="flex gap-1 items-center text-accent hover:text-yellow-400" href="https://ditpsd.kemdikbud.go.id" target="_blank"> <ChevronDoubleRightIcon class="h-3" />Merdeka Belajar</a>
                    </li>
                    <li>
                        <a class="flex gap-1 items-center text-accent hover:text-yellow-400" href="https://dispendik.malangkab.go.id" target="_blank"> <ChevronDoubleRightIcon class="h-3" /> Dinas Pendidikan Kab. Malang</a>
                    </li>
                    <li>
                        <a class="flex gap-1 items-center text-accent hover:text-yellow-400" href="https://malang.kemenag.go.id" target="_blank"> <ChevronDoubleRightIcon class="h-3" /> Kemenag Kab. Malang</a>
                    </li>

                </ul>
            </div>
        </div>
        <div class="w-full h-12 flex flex-col md:flex-row justify-center md:justify-between items-center px-4 text-xs text-gray-100 bg-sky-800 bg-opacity-20">
            <div>
                Copyright &copy; {{ new Date().getFullYear() }} <span class="text-yellow-400">SD Negeri 1 Bedalisodo</span>
            </div>
            <div class="flex items-center">
                <p>
                    Dev. by <a href="https://wa.me/6285954944407">Mat Soleh</a> with 
                </p>
                <SvgIcon type="mdi" :path="mdiHeart" class="text-pink-100 animate-pulse" />
                <SvgIcon type="mdi" :path="mdiLaravel" class="text-red-100" />
                <SvgIcon type="mdi" :path="mdiVuejs" class="text-green-100" />
                <SvgIcon type="mdi" :path="mdiTailwind" class="text-sky-100" />
            </div>
        </div>
    </footer>
</template>