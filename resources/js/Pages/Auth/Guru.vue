<script setup>
import {usePage, Head} from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const page = usePage()
</script>

<template>
<Head title="Data Guru" />   
<AdminLayout title="Data Guru">
    <div class="wrapper w-full oveflow-x-hidden p-3">
        <div class="table w-full p-3 bg-white">
            <table class="table-border border-collapse w-full">
                <thead>
                    <tr>
                        <th class="py-1 px-2 border">No</th>
                        <th class="py-1 px-2 border">NIP</th>
                        <th class="py-1 px-2 border">Nama</th>
                        <th class="py-1 px-2 border">JK</th>
                        <th class="py-1 px-2 border">Jabatan</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(guru,g) in $page.props.gurus" :key="g" class="odd:bg-slate-200" >
                        <td class="py-1 px-2 border text-center">{{ g+1 }}</td>
                        <td class="py-1 px-2 border">{{ guru.nip }}</td>
                        <td class="py-1 px-2 border">{{ guru.nama }}</td>
                        <td class="py-1 px-2 border">{{ guru.jk }}</td>
                        <td class="py-1 px-2 border">{{ guru.role == "ks" ? "Kepala Sekolah" : (guru.role == "gkel" ? "Guru Kelas" : "Guru Mapel") }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</AdminLayout>
</template>