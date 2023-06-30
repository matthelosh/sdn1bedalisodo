<script setup>
import { computed, ref } from 'vue';
import {usePage, Head} from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const page = usePage()
import { paginate } from '@/Plugins/misc';
import { Icon } from '@iconify/vue';

const search = ref('')
const currentPage = ref(0)
const itemsPerPage = ref(10)
const siswas = computed(() => {
    let items = page.props.siswas.filter((siswa) => siswa.nama.toLowerCase().includes(search.value.toLowerCase()))
    return paginate(items, itemsPerPage.value, currentPage.value)
})
</script>

<template>
<Head title="Data Siswa" />   
<AdminLayout title="Data Siswa">
    <div class="wrapper w-full oveflow-x-hidden p-3">
        <div class="table w-full p-3 bg-white">
            <div class="table-header grid grid-cols-3 justify-between items-center w-full mb-3" >
                <div class="hidden md:block ">
                    <h1 class="text-lg font-bold">Data Siswa</h1>
                </div>
                <div class="hidden md:block "></div>
                <div>
                    <input type="text" v-model="search" class="w-full rounded-lg" placeholder="Cari Nama" />
                </div>
            </div>
            <table class="table-border border-collapse w-full">
                <thead>
                    <tr>
                        <th class="py-1 px-2 border">No</th>
                        <th class="py-1 px-2 border">NISN</th>
                        <th class="py-1 px-2 border">Nama</th>
                        <th class="py-1 px-2 border">JK</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(siswa,s) in siswas.current" :key="s" class="odd:bg-slate-200" >
                        <td class="py-1 px-2 border text-center">{{ siswa.no }}</td>
                        <td class="py-1 px-2 border">{{ siswa.nisn }}</td>
                        <td class="py-1 px-2 border">{{ siswa.nama }}</td>
                        <td class="py-1 px-2 border">{{ siswa.jk }}</td>
                    </tr>
                </tbody>
            </table>
            <div class="table-footer w-full grid grid-cols-3">
                <div class="pagination-info">
                   Jumlah Data: {{ siswas.total }}
                   Jumlah Halaman: {{ siswas.page_length }}
                </div>
                <div class="page-option">
                    <label for="page_length">Items:
                        <select name="page_length" id="page_length" v-model="itemsPerPage">
                            <option value="5">5</option>
                            <option value="10">10</option>
                            <option value="15">15</option>
                            <option value="25">25</option>
                        </select>
                    </label>
                </div>
                <div class="page-nav flex flex-wrap">
                    <button class="flex items-center justify-center p-2 border" :disabled="currentPage == 0" @click="currentPage--">
                        <Icon icon="mdi:chevron-double-left" />
                    </button>
                    
                    <button v-for="b in siswas.page_length" :key="b" class="flex items-center justify-center p-2 border flex-grow" :class="b == (currentPage+1) ? 'bg-sky-400 text-white': ''" @click="currentPage = (b-1)">
                        <span>{{ b }}</span>
                    </button>

                    <button class="flex items-center justify-center p-2 border" :disabled="currentPage == (siswas.page_length-1)" @click="currentPage++">
                        <Icon icon="mdi:chevron-double-right" />
                    </button>
                </div>
            </div>
        </div>
    </div>
</AdminLayout>
</template>