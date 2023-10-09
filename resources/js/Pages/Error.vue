<script setup>
import { ref, onMounted } from 'vue';
import {Link, Head, usePage} from '@inertiajs/vue3';
import { Icon } from '@iconify/vue';

onMounted(() => {
    console.log(page)
})

const page = usePage()

</script>

<template>
    <Head title="Halaman Error 404" />
    <div class="container w-screen">
        <div>
            <h1>
                <span>Error: File Halaman tidak ditemukan.</span>
                <small>
                    <Icon icon="mdi:arrow-left" />
                    <Link href="/">Kembali</Link>
                </small>
            </h1>
        </div>
    </div>
    <!-- <FileBrowser /> -->
</template>