<script setup>
import { router, Head, usePage } from '@inertiajs/vue3';
import { ref, onMounted, computed } from 'vue';
import SvgIcon from '@jamescoyle/vue-icon';
import { mdiEye, mdiRefresh } from '@mdi/js';

const page = usePage()
const loading = ref(false)
const showPassword = ref(false)
const user = ref({})

const errorText = computed(() => {
    if (Object.keys(page.props.errors).length > 0 ) {
        return page.props.errors.name == 'These credentials do not match our records.' ? 'Username atau Password tidak sesuai.' : 'Ada kesalahan, mohon hubungi admin'
    } else {
        return null
    }
})
const login = async() => {
    loading.value = true
    router.post(route('login'), user.value)
}
</script>

<template>
    <Head title="Login" />  
    <div class="w-screen h-screen absolute flex items-center justify-center bg-gray-950 after:content after:absolute after:top-0 after:right-0 after:bottom-0 after:left-0 after:bg-[url('/img/mushola1.jpg')] after:bg-cover after:bg-center after:opacity-5 after:backdrop-blur after:bg-blend-invert after:-z-0">
        <!-- <h1 class="text-white font-sans mb-8 text-2xl">Selamat Datang</h1> -->
        <div class="w-10/12 md:w-1/4 bg-white bg-opacity-10 backdrop-blur p-3 rounded shadow-sm shadow-lime-200 border-t-4 border-orange-600 z-10">
            <h1 class="text-gray-50 text-2xl">Login</h1>
            <form ref="loginForm" @submit.prevent="login" class="text-white text-shadow">
                <div class="row flex items-center justify-between gap-1 mb-2">
                    <label for="name">Username</label>
                    <input id="name" type="text" placeholder="Username" class="rounded-xl bg-opacity-70 bg-gray-50 text-gray-900" :readonly="loading" v-model="user.name">
                </div>
                <div class="row flex items-center justify-between gap-1 mb-2">
                    <label for="password">Password</label>
                    <div class="input-group relative">
                        <input id="password" :type="showPassword ? 'text' : 'password'" placeholder="Password" class="rounded-xl bg-opacity-70 bg-gray-50 text-gray-900" :readonly="loading" v-model="user.password">
                        <button class="absolute z-20 right-2 py-1" @click="showPassword = !showPassword">
                            <SvgIcon type="mdi" :path="mdiEye" :class=" showPassword ? 'text-lime-600' : 'text-gray-400' " class="hover:text-lime-600" size="32" />
                        </button>
                    </div>
                </div>
                <div class="row flex items-center justify-center gap-1 mt-8 mb-2">
                    <button type="submit" class="bg-lime-600 hover:bg-lime-600 active:bg-lime-200 active:text-gray-800 py-2 px-4 rounded-xl w-full flex items-center justify-center gap-2" :disabled="loading">
                        Login
                        <SvgIcon type="mdi" :path="mdiRefresh" class="animate-spin" v-if="loading" />
                    </button>
                </div>
            </form>
            <div v-if="page.props.errors" class="text-red-400">{{ errorText }}</div>
        </div>
    </div>
</template>