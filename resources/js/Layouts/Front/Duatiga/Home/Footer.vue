<script setup>
import { computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import { Icon } from '@iconify/vue';

const page = usePage();

const recentPosts = computed(() => page.props.posts.slice(-5))
</script>
<template>
    <footer class="w-full min-h-[200px] bg-primary">
        <div class="grid grid-cols-1 md:grid-cols-3 m-4 my-0">
            <div class="footer-identitas p-3 flex flex-col justify-center items-center md:flex-row gap-3 text-sm">
                <img src="/img/logo.png" alt="Logo" class="h-20 drop-shadow" />
                <div class="footer-identitas--alamat">
                    <h1 class="leading-4 md:leading-5 tracking-wide text-center md:text-left text-lg font-thin text-yellow-400">{{ page.props.sekolah.nama }}</h1>
                    <p  class="leading-4 md:leading-5 tracking-wide text-center md:text-left text-accent">NSS: {{page.props.sekolah.nss}}, NPSN:{{ page.props.sekolah.npsn }}</p>
                    <p  class="leading-4 md:leading-5 tracking-wide text-center md:text-left text-accent">{{page.props.sekolah.alamat}}, {{ page.props.sekolah.desa }}</p>
                    <p  class="leading-4 md:leading-5 tracking-wide text-center md:text-left text-accent">Kec. {{ page.props.sekolah.kecamatan }}, Kode Pos {{ page.props.sekolah.kode_pos }}</p>
                    <p  class="leading-4 md:leading-5 tracking-wide text-center md:text-left text-accent flex items-center underline underline-offset-3 gap-1">
                        <Icon icon="mdi:mailbox" /> <a :href="`mailto:${page.props.sekolah.email}`">{{ page.props.sekolah.email }}</a>
                    </p>
                </div>
            </div>
            <div class="p-3 text-white">
                <h1 class="text-lg font-bold tracking-wide text-yellow-200">Tulisan terbaru:</h1>
                <ul>
                    <li v-for="(post,p) in recentPosts" :key="p">
                        <Link class="flex items-center gap-1 text-justify text-accent hover:text-yellow-400 truncate text-ellipsis"  :href="route('Post.read', {kategori:post.category.label.toLowerCase(), slug: post.slug})"> <Icon icon="mdi:chevron-double-right" class="h-3 " /> {{ post.title }}</Link>
                    </li>

                </ul>
            </div>
            <div class="p-3 text-white">
                <h1 class="text-lg font-bold tracking-wide text-yellow-200">Link Eksternal:</h1>
                <ul>
                    <li>
                        <a class="flex gap-1 items-center text-accent hover:text-yellow-400" href="https://kemdikbud.go.id" target="_blank"> <Icon icon="mdi:chevron-double-right" class="h-3" /> Kemdikibudristek RI</a>
                    </li>
                    <li>
                        <a class="flex gap-1 items-center text-accent hover:text-yellow-400" href="https://guru.kemdikbud.go.id" target="_blank"> <Icon icon="mdi:chevron-double-right" class="h-3" />Merdeka Mengajar</a>
                    </li>
                    <li>
                        <a class="flex gap-1 items-center text-accent hover:text-yellow-400" href="https://ditpsd.kemdikbud.go.id" target="_blank"> <Icon icon="mdi:chevron-double-right" class="h-3" />Merdeka Belajar</a>
                    </li>
                    <li>
                        <a class="flex gap-1 items-center text-accent hover:text-yellow-400" href="https://dispendik.malangkab.go.id" target="_blank"> <Icon icon="mdi:chevron-double-right" class="h-3" /> Dinas Pendidikan Kab. Malang</a>
                    </li>
                    <li>
                        <a class="flex gap-1 items-center text-accent hover:text-yellow-400" href="https://malang.kemenag.go.id" target="_blank"> <Icon icon="mdi:chevron-double-right" class="h-3" /> Kemenag Kab. Malang</a>
                    </li>

                </ul>
            </div>
        </div>
        <div class="w-full h-12 flex flex-col md:flex-row justify-center md:justify-between items-center px-4 text-xs text-gray-100 bg-sky-800 bg-opacity-20">
            <div>
                Copyright &copy; {{ new Date().getFullYear() }} <span class="text-yellow-400">{{page.props.sekolah.nama}}</span>
            </div>
            <div class="flex items-center">
                <p>
                    Dev. by <a href="https://wa.me/6285954944407">Mat Soleh</a> with 
                </p>
                <Icon icon="mdi:heart"  class="text-pink-100 animate-pulse" />
                <Icon icon="mdi:laravel"  class="text-red-100" />
                <Icon icon="mdi:vuejs"  class="text-green-100" />
                <Icon icon="mdi:tailwind" class="text-sky-100" />
            </div>
        </div>
    </footer>
</template>