<script setup>
import { Head, usePage, router, Link } from '@inertiajs/vue3';
import { ref, onMounted, computed } from 'vue';
import SvgIcon from '@jamescoyle/vue-icon';
import { mdiEye, mdiRefresh } from '@mdi/js';
import {Icon} from '@iconify/vue';
import { toast } from 'vue3-toastify';

const page = usePage()
const loading = ref(false)
const showPassword = ref(false)
const user = ref({
    name: null,
    password: null,
    remember: false
})

const errorText = ref(null)

const togglePassword = () => {
    showPassword.value = !showPassword.value
}

const login = async() => {
    loading.value = true
    // showPassword.value = false
    router.post(route('login'), user.value, {
        onError: err => {
            Object.keys(err).forEach(k => {
                notify(err[k], 'error')
                errorText.value = err[k]
            })
            loading.value = false
        }
    })
}

const notify = (text, type) => {
    toast(text, {
        type: type,
        position: toast.POSITION.TOP_RIGHT
    })
}
</script>

<template>
    <Head title="Login" />  
    <div class="w-screen h-screen absolute flex items-center justify-center bg-slate-100 after:content after:absolute after:top-0 after:right-0 after:bottom-0 after:left-0 bg-[url('/img/mushola1.jpg')] bg-cover after:bg-center after:bg-opacity-20 after:bg-slate-100 after:backdrop-blur after:bg-blend-invert after:-z-0">
        <!-- <h1 class="text-white font-sans mb-8 text-2xl">Selamat Datang</h1> -->
        <div class="w-10/12 md:w-1/4 bg-white bg-opacity-60 backdrop-blur p-3 rounded shadow-sm shadow-lime-200 border-t-4 border-orange-600 z-10">
            <h1 class=" text-2xl">Login</h1>
            <form ref="loginForm" @submit.prevent="login" class=" text-shadow">
                <div class="row flex items-center justify-between gap-1 mb-2">
                    <label for="name">Username</label>
                    <input id="name" type="text" placeholder="Username" class="rounded bg-opacity-70 border-none text-gray-900 w-[60%]" :readonly="loading" v-model="user.name" required>
                </div>
                <div class="row flex items-center justify-between gap-1 mb-2">
                    <label for="password">Password</label>
                    <div class="input-group relative w-[60%]">
                        <input id="password" :type="showPassword ? 'text' : 'password'" placeholder="Password" class="rounded bg-opacity-70 border-none  text-gray-900 w-full" :readonly="loading" v-model="user.password" required>
                        <!-- <button
                            class="absolute z-20 right-2 py-1 cursor-pointer top-[50%] -translate-y-[50%]" @click.self="togglePassword" 
                        > -->
                            <Icon icon="mdi:eye" :class=" showPassword ? 'text-lime-600' : 'text-gray-400' " class="hover:text-lime-600 text-4xl absolute z-20 right-2 py-1 cursor-pointer top-[50%] -translate-y-[50%]" @click="togglePassword" />
                        <!-- </button> -->
                    </div>
                </div>
                <div class="row flex items-center justify-center gap-1 mt-8 mb-2">
                    <button type="submit" class="bg-lime-600 text-white hover:bg-lime-400 hover:text-gray-800 active:bg-lime-200 active:text-gray-800 py-2 px-4 rounded-xl w-full flex items-center justify-center gap-2" :disabled="loading">
                        Login
                        <Icon icon="mdi:refresh" class="animate-spin" v-if="loading" />
                    </button>
                </div>
            </form>
            <div v-if="page.props.errors && !loading" class="text-red-600 font-bold">{{ errorText }}</div>
            <Link href="/" class="text-lime-800 flex items-center gap-1 hover:text-lime-600">
                <Icon icon="mdi:arrow-left" />
                Beranda
            </Link>
        </div>
        
    </div>
</template>