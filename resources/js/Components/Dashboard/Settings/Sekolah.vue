<script setup>
import { ref, onMounted } from 'vue';
import { usePage, router } from '@inertiajs/vue3';
import {Icon} from '@iconify/vue';
import axios from 'axios';

const sekolah = ref({});
const mode = ref('list');
const page = usePage();

const loading = ref(false)

const edit = () => {
    mode.value = 'edit';
    let data = page.props.sekolah;
    sekolah.value = {
        npsn: data.npsn,
        nss: data.nss,
        nama: data.nama,
        alamat: data.alamat,
        rt: data.rt,
        rw: data.rw,
        desa: data.desa,
        kecamatan: data.kecamatan,
        kabupaten: data.kabupaten,
        provinsi: data.provinsi,
        kode_pos: data.kode_pos,
        telp: data.telp,
        email: data.email,
        website: data.website,
        facebook: data.facebook,
        youtube: data.youtube,
        instagram: data.instagram,
        motto: data.motto,
        ks: data.ks.nip,
        bendahara: data.bendahara.nip,
        operator: data.operator.nip
    }
}

const simpan = async() => {
    loading.value = true
    await axios.put(route('dashboard.sekolah.update'), {sekolah: sekolah.value})
        .then(res => {
            router.reload({only: ['sekolahs']})
            mode.value = 'list'
            loading.value = false
        }).catch(err => {
            console.log(err)
            loading.value = false
        })
}
</script>

<template>
<div class="wrapper bg-white rounded shadow hover:shadow-lg">
    <div class="toolbar h-10 bg-slate-400 p-2 flex items-center justify-between">
        <h1 class="text-white font-bold">Data Sekolah</h1>
        <button class="rounded-full bg-sky-400 hover:bg-sky-600 border border-white p-1" @click="edit" v-if="mode=='list'">
            <Icon icon="mdi:pencil" class="text-white text-xs" />
        </button>
        <button class="rounded-full  border-white" @click="mode = 'list'" v-if="mode=='edit'">
            <Icon icon="mdi:close-circle" class="text-red-400 text-xl hover:text-red-600" />
        </button>
    </div>
    <div class="content">
        <table class="text-sm text-slate-800 border w-full">
            <tr class="bg-slate-100">
                <td class="border-e px-2">NPSN</td>
                <td>:</td>
                <td>
                    <span v-if="mode=='list'">
                        {{ page.props.sekolah.npsn }}
                    </span>
                    <input type="text" placeholder="NPSN" v-model="sekolah.npsn" class="border-none py-0 px-1 bg-slate-300 shadow-inner rounded-sm text-sm" v-else>
                </td>
            </tr>
            <tr class="bg-white">
                <td class="border-e px-2">NSS</td>
                <td>:</td>
                <td>
                    <span v-if="mode=='list'">
                        {{ page.props.sekolah.nss }}
                    </span>
                    <input type="text" placeholder="NSS" v-model="sekolah.nss" class="border-none py-0 px-1 bg-slate-300 shadow-inner rounded-sm text-sm" v-else>
                </td>
            </tr>
            <tr class="bg-slate-100">
                <td class="border-e px-2">Nama</td>
                <td>:</td>
                <td>
                    <span v-if="mode=='list'">
                        {{ page.props.sekolah.nama }}
                    </span>
                    <input type="text" placeholder="Nama" v-model="sekolah.nama" class="border-none py-0 px-1 bg-slate-300 shadow-inner rounded-sm text-sm" v-else>
                </td>
            </tr>
            <tr class="bg-white">
                <td class="border-e px-2">Alamat</td>
                <td>:</td>
                <td>
                    <span v-if="mode=='list'">
                        {{ page.props.sekolah.alamat }}
                    </span>
                    <input type="text" placeholder="Nama" v-model="sekolah.alamat" class="border-none py-0 px-1 bg-slate-300 shadow-inner rounded-sm text-sm" v-else>
                </td>
            </tr>
            <tr class="bg-slate-100">
                <td class="border-e px-2">Desa</td>
                <td>:</td>
                <td>
                    <span v-if="mode=='list'">
                        {{ page.props.sekolah.desa }}
                    </span>
                    <input type="text" placeholder="Desa" v-model="sekolah.desa" class="border-none py-0 px-1 bg-slate-300 shadow-inner rounded-sm text-sm" v-else>
                </td>
            </tr>
            <tr class="bg-white">
                <td class="border-e px-2">Kecamatan</td>
                <td>:</td>
                <td>
                    <span v-if="mode=='list'">
                        {{ page.props.sekolah.kecamatan }}
                    </span>
                    <input type="text" placeholder="Kecamatan" v-model="sekolah.kecamatan" class="border-none py-0 px-1 bg-slate-300 shadow-inner rounded-sm text-sm" v-else>
                </td>
            </tr>
            <tr class="bg-slate-100">
                <td class="border-e px-2">Kabupaten</td>
                <td>:</td>
                <td>
                    <span v-if="mode=='list'">
                        {{ page.props.sekolah.kabupaten }}
                    </span>
                    <input type="text" placeholder="Kabupaten" v-model="sekolah.kabupaten" class="border-none py-0 px-1 bg-slate-300 shadow-inner rounded-sm text-sm" v-else>
                </td>
            </tr>
            <tr class="bg-white">
                <td class="border-e px-2">Kode Pos</td>
                <td>:</td>
                <td>
                    <span v-if="mode=='list'">
                        {{ page.props.sekolah.kode_pos }}
                    </span>
                    <input type="text" placeholder="Kode Pos" v-model="sekolah.kode_pos" class="border-none py-0 px-1 bg-slate-300 shadow-inner rounded-sm text-sm" v-else>
                </td>
            </tr>
            <tr class="bg-slate-100">
                <td class="border-e px-2">Telp</td>
                <td>:</td>
                <td>
                    <span v-if="mode=='list'">
                        {{ page.props.sekolah.telp }}
                    </span>
                    <input type="text" placeholder="Telepon" v-model="sekolah.telp" class="border-none py-0 px-1 bg-slate-300 shadow-inner rounded-sm text-sm" v-else>
                </td>
            </tr>
            <tr class="bg-white">
                <td class="border-e px-2">Email</td>
                <td>:</td>
                <td>
                    <span v-if="mode=='list'">
                        {{ page.props.sekolah.email }}
                    </span>
                    <input type="text" placeholder="Email" v-model="sekolah.email" class="border-none py-0 px-1 bg-slate-300 shadow-inner rounded-sm text-sm" v-else>
                </td>
            </tr>
            <tr class="bg-slate-100">
                <td class="border-e px-2">Website</td>
                <td>:</td>
                <td>
                    <span v-if="mode=='list'">
                        {{ page.props.sekolah.website }}
                    </span>
                    <input type="text" placeholder="WebSite" v-model="sekolah.website" class="border-none py-0 px-1 bg-slate-300 shadow-inner rounded-sm text-sm" v-else>
                </td>
            </tr>
            <tr class="bg-white">
                <td class="border-e px-2">Kepala Sekolah</td>
                <td>:</td>
                <td>
                    <span v-if="mode=='list'">
                        {{ page.props.sekolah.ks.nama }}, {{ page.props.sekolah.ks.gelar_belakang }}
                    </span>
                    <input type="text" placeholder="Kepala Sekolah" v-model="sekolah.ks" class="border-none py-0 px-1 bg-slate-300 shadow-inner rounded-sm text-sm" v-else>
                </td>
            </tr>
            <tr class="bg-slate-100">
                <td class="border-e px-2">Operator</td>
                <td>:</td>
                <td>
                    <span v-if="mode=='list'">
                        {{ page.props.sekolah.operator.nama }}, {{ page.props.sekolah.operator.gelar_belakang }}
                    </span>
                    <input type="text" placeholder="Operator" v-model="sekolah.operator" class="border-none py-0 px-1 bg-slate-300 shadow-inner rounded-sm text-sm" v-else>
                </td>
            </tr>
            <tr class="bg-white">
                <td class="border-e px-2">Bendahara</td>
                <td>:</td>
                <td>
                    <span v-if="mode=='list'">
                        {{ page.props.sekolah.bendahara.nama }}, {{ page.props.sekolah.bendahara.gelar_belakang }}
                    </span>
                    <input type="text" placeholder="Bendahara" v-model="sekolah.bendahara" class="border-none py-0 px-1 bg-slate-300 shadow-inner rounded-sm text-sm" v-else>
                </td>
            </tr>
        </table>
        <div class="w-full flex items-center justify-center m-4" v-if="mode=='edit'">
            <button class="bg-sky-400 text-white py-1 px-3 rounded hover:bg-sky-600 active:bg-sky-800" @click="simpan" :disabled="loading">
                Simpan
            </button>
        </div>
    </div>
</div>
</template>