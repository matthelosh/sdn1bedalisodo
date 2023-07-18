<script setup>
import {usePage, Head, router} from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { defineAsyncComponent, ref } from 'vue';
import { Icon } from '@iconify/vue';

const page = usePage()
const loading = ref(false)
const Loading = defineAsyncComponent(() => import('@/Components/General/Loading.vue'))
const FormGuru = defineAsyncComponent(() => import('@/Components/Dashboard/Guru/FormGuru.vue'))

router.on('start', () => loading.value = true)
router.on('finish', () => loading.value = false)

const showForm = ref(false)
</script>

<template>
<Head title="Data Guru" />   
<AdminLayout title="Data Guru">
    <div class="wrapper w-full oveflow-x-hidden p-3">
        <div class="toolbar w-full h-10 flex items-center justify-between p-3 bg-slate-600 text-white">
            <h1 class="flex items-center">
                <Icon icon="mdi:account-tie" class="text-xl" />
                    Data Guru
            </h1>
            <button class="py-1 px-3 rounded bg-slate-100 text-slate-800" @click="showForm = true">
                Tambah
            </button>
        </div>
        <div class="table w-full p-3 bg-white">
            <table class="table-border border-collapse w-full">
                <thead>
                    <tr>
                        <th class="py-1 px-2 border">No</th>
                        <th class="py-1 px-2 border">NIP</th>
                        <th class="py-1 px-2 border">Nama</th>
                        <th class="py-1 px-2 border">JK</th>
                        <th class="py-1 px-2 border">Jabatan</th>
                        <th class="py-1 px-2 border">Opsi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(guru,g) in $page.props.gurus" :key="g" class="odd:bg-slate-200" >
                        <td class="py-1 px-2 border text-center">{{ g+1 }}</td>
                        <td class="py-1 px-2 border">{{ guru.nip }}</td>
                        <td class="py-1 px-2 border">{{ guru.nama }}</td>
                        <td class="py-1 px-2 border">{{ guru.jk }}</td>
                        <td class="py-1 px-2 border">{{ guru.role == "ks" ? "Kepala Sekolah" : (guru.role == "gkel" ? "Guru Kelas" : "Guru Mapel") }}</td>
                        <td class="py-1 px-2 border text-center">Opsi</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</AdminLayout>
<Loading v-if="loading" />
<FormGuru v-if="showForm" @close="showForm = !showForm" />
</template>