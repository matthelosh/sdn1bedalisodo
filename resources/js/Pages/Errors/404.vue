<script setup>
import { ref } from 'vue';
import {Link, Head} from '@inertiajs/vue3';
import { mdiArrowLeft } from '@mdi/js';
import SvgIcon from '@jamescoyle/vue-icon';
import FileBrowser from '@/Components/File/FileBrowser.vue'

const content = ref('Tulis sesuatu..')

const simpan = (content) => {
    console.log(content)
}
</script>

<template>
    <Head title="Halaman Error 404" />
    <div class="container">
        <div>
            <h1>
                <span>Error: File Halaman tidak ditemukan.</span>
                <small>
                    <SvgIcon type="mdi" :path="mdiArrowLeft" />
                    <Link href="/">Kembali</Link>
                </small>
            </h1>
        </div>
    </div>
    <!-- <FileBrowser /> -->
</template>

<style scoped>
.container {
    width: 100vw;
    height: 100vh;
    background: rgb(125, 51, 51);
    
    display: flex;
    justify-content: center;
    align-items: center;
}

.container h1 {
    font-size: 3rem;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    color: #efefef;
}

.container h1 small {
    font-size: 1.5rem;
    color: rgb(250, 207, 128);
    display: flex;
    align-items: center;
    gap: 5px;
}
.container h1 small:hover {
    color: rgb(250, 236, 209);
}
</style>