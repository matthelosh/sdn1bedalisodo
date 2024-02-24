<script setup>
import { ref, computed } from 'vue'
import { usePage, Link, Head } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue';
import QrcodeVue from 'qrcode.vue';
const page = usePage()
const details = computed(() => page.props.details)
const guru = computed(() => details.value.userable)
const activeNames = ref(['1','2', '3'])
const arsipHeaders = ref([
    {text: 'Label', value: 'label'},
    {text: 'Deskripsi', value: 'deskripsi'},
    {text: 'Keterangan', value: 'keterangan'},
    {text: 'Lokasi', value: 'lokasi'},
])
</script>

<template>
<AdminLayout>
    <Head :title="`Profil ${page.props.auth.user.userable.nama}`" />
    <div class="wrapper p-6">
        <el-card>
            <template #header>
                <div class="w-full flex justify-between items center">
                    <h3 class="uppercase font-bold text-blue-600">Detail Profil {{ page.props.details.userable.nama }}</h3>
                </div>
            </template>
            <div class="card-body">
                <el-collapse v-model="activeNames">
                    <el-collapse-item name="1">
                        <template #title>
                            <h3 class="font-bold uppercase">Akun</h3>
                        </template>
                        <div class="content">
                            <table>
                                <tr>
                                    <td>Username</td>
                                    <td>:</td>
                                    <td>{{ details.name }}</td>
                                </tr>
                            </table>
                        </div>
                    </el-collapse-item>
                    <el-collapse-item name="2">
                        <template #title>
                            <h3 class="font-bold uppercase">DATA GURU</h3>
                        </template>
                        <div class="content">
                            <table>
                                <tr>
                                    <td>Nama</td>
                                    <td>:</td>
                                    <td> {{ guru.gelar_depan }} {{ guru.nama }}, {{ guru.gelar_belakang }}</td>
                                </tr>
                                <tr>
                                    <td>NIP</td>
                                    <td>:</td>
                                    <td>{{ guru.nip }}</td>
                                </tr>
                                <tr>
                                    <td>JK</td>
                                    <td>:</td>
                                    <td>{{ guru.jk }}</td>
                                </tr>
                                <tr>
                                    <td>Alamat</td>
                                    <td>:</td>
                                    <td>{{ guru.alamat }}</td>
                                </tr>
                                <tr>
                                    <td>HP/WA</td>
                                    <td>:</td>
                                    <td>{{ guru.hp }}</td>
                                </tr>
                            </table>
                        </div>
                    </el-collapse-item>
                    <el-collapse-item name="2">
                        <template #title>
                            <h3 class="font-bold uppercase">ARSIP DOKUMEN</h3>
                        </template>
                        <div class="content">
                            <Link class="py-1 px-2 rounded  bg-sky-600 mb-2 text-white"  :href="route('arsip.index', {_query: {nip: guru.nip}})">Kelola Arsip Saya</Link>
                            <data-table class="my-2" :items="guru.arsips" :headers="arsipHeaders">
                                <template #item-lokasi="{url}">
                                    <div>
                                        <a :href="url" target="_blank">
                                                <qrcode-vue :value="url" :size="70" level="L" :foreground="'#363636'" />
                                            </a>
                                            <p>{{ url }}</p>
                                    </div>
                                </template>
                            </data-table>
                        </div>
                    </el-collapse-item>
                </el-collapse>
            </div>
        </el-card>
    </div>
</AdminLayout>
</template>