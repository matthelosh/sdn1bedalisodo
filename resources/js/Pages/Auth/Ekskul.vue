<script setup>
import { defineAsyncComponent, ref, computed, onMounted } from 'vue';
import { Head, router, usePage } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Icon } from '@iconify/vue';
import axios from 'axios';
const Loading = defineAsyncComponent(() => import('@/Components/General/Loading.vue'))
const ConfirmDialog = defineAsyncComponent(() => import('@/Components/General/ConfirmDialog.vue'))
const AturPeserta = defineAsyncComponent(() => import('@/Components/Dashboard/Ekskul/AturPeserta.vue'))

const page = usePage()
const pageTitle = ref("Manajemen Ekstrakurikuler")
const loading = ref(false)
const showForm = ref(false)
const gurus = ref([])
const ekskul = ref({
    guru_id: '0'
})
const selectedEkskul  = ref(null)
const items = ref([])
const ekskuls = computed(() => {
    let datas = []
    items.value.forEach((item,index) => {
        item.no = index+1
        datas.push(item)
    })
    return datas
})

const confirmDialog = ref(null)

const getGurus = async() => {
    await axios.post(route('dashboard.guru.index'))
                .then(res => {
                    gurus.value = res.data.gurus
                }).catch(err => {
                    console.log(err)
                })
}
const list = async() => {
    loading.value = true
    await axios.post(route('dashboard.ekskul.index'))
                .then(res => {
                    loading.value = false
                    items.value = res.data.ekskuls
                }).catch(err => {
                    loading.value = false
                })
}

const simpan = async() => {
    loading.value = true
    await axios.post(route('dashboard.ekskul.store'), {ekskul: JSON.stringify(ekskul.value)})
                .then(res => {
                    loading.value = false
                    showForm.value = false
                    ekskul.value = {}
                    list()
                }).catch(err => {
                    loading.value = false
                })
}

const openForm = () => {
    showForm.value = true
}

const closeForm = () => {
    showForm.value = false
    ekskul.value = {}
}

const yakinHapus = async(item) => {
    if( await confirmDialog.value.open(`Yakin Menghapus Data Ekskul ${item.label}`)) {
        hapus(item)
    } else {
        return false
    }
}

const hapus = async(item) => {
    loading.value = true
    await axios.delete(route('dashboard.ekskul.destroy', {id: item.id}))
                .then(res => {
                    list()
                    loading.value = false
                })
                .catch( err => {
                    console.log(err)
                    loading.value = false
                })
}

const setAvatar = (guru) => {
    let url = ''
    if (guru.jk == 'Laki-laki') {
        url = `/img/guru-l.png`
    } else {
        if(guru.agama == 'Islam') {
            url = `/img/guru-p-jilbab.png`
        } else {
            url = `/img/guru-p.png`
        }
    }

    return url
}

const aturPeserta = ( item ) => {
    selectedEkskul.value = item
}

const cetakPresensi = (item) => {
    let win = window.open("", "_blank", "width=600,height=800")
    let trs = ''
    item.pesertas.forEach((siswa,index) => {
        trs += `<tr>
                <td>${index+1}</td>
                <td>${siswa.nisn}</td>
                <td>${siswa.nama}</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>`
    })

    let html = `
            <!doctype html>
            <html>
                <head>
                    <title>Presensi Ekstrakurikuler</title>
                    <style>
                        *, html, body {
                            margin:0;
                            padding:0;
                        }
                        h1,h2,h3,h4,h5,h6 {margin:0;padding:0;}
                        table.daftar-hadir tbody tr:nth-child(odd) {background: #eee;}
                        table.daftar-hadir tbody tr td {padding: 8px 2px}
                        .w-24 {width: 70px}
                        .text-center {text-align:center}
                        .uppercase {text-transform: uppercase}
                    </style>
                </head>
                <body>
                    <table style="border-bottom: 3px double black; width:100%;">
                        <tr >
                            <td>
                                <img src="/img/malangkab.png" alt="" class="w-24">
                            </td>
                            <td>
                                <h4 class="text-center leading-4 font-bold">PEMERINTAH KABUPATEN <span class="uppercase">${page.props.sekolah.kabupaten}</span></h4>
                                <h4 class="text-center leading-4 font-bold">DINAS PENDIDIKAN</h4>
                                <h4 class="text-center leading-4 font-bold">KOORDINATOR WILAYAH KECAMATAN WAGIR</h4>
                                <h4 class="text-center  font-black text-xl"><span class="uppercase">${page.props.sekolah.nama}</span></h4>
                                <h4 class="text-center leading-4 font-bold"><span class="uppercase">NPSN: ${page.props.sekolah.npsn} NSS:${page.props.sekolah.nss}</span></h4>
                                <h5 class="text-center leading-4"><small>${page.props.sekolah.alamat}, ${page.props.sekolah.desa}, ${page.props.sekolah.kecamatan}, Kode Pos ${page.props.sekolah.kode_pos}</small></h5>
                                <h5 class="text-center leading-4"><small>Telp.: ${page.props.sekolah.telp}, Email: ${page.props.sekolah.email}, Website:${page.props.sekolah.website}</small></h5>    
                            </td>
                            <td>
                                <img src="/img/logo.png" class="aspect-square w-24" >
                            </td>
                        </tr>
                    </table>

                    <h3 style="text-align:center">Presensi Ekstrakurikuluer</h3>
                    <h2 style="text-align:center">${item.label}</h2>
                    <p style="margin-top:20px;">Bulan: ..................................</p>
                    <table class="daftar-hadir" border="1" style="border-collapse:collapse;width:100%;">
                        <thead>
                            <tr>
                                <th rowspan="2">No</th>
                                <th rowspan="2">NISN</th>
                                <th rowspan="2">Nama</th>
                                <th colspan=4>Tanggal / Tanda Tangan</th>
                            </tr>
                            <tr>
                                <th>........</th>
                                <th>........</th>
                                <th>........</th>
                                <th>........</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${trs}
                        </tbody>
                    </table>
                    <br />

                    <table style="width:100%; margin-top: 20px;">
                        <tr>
                            <td>
                                <p>&nbsp;</p>
                                <p>Kepala Sekolah,</p>
                                <br />
                                <br />
                                <br />
                                <p style="text-decoration:underline; font-weight: bold;">${page.props.sekolah.ks.nama}, ${page.props.sekolah.ks.gelar_belakang}</p>
                                <p>NIP. ${page.props.sekolah.ks.nip}</p>    
                            </td>
                            <td style="width:30%;"></td>
                            <td>
                                <p>Wagir, ........................</p>
                                <p>Pembina,</p>
                                <br />
                                <br />
                                <br />
                                <p style="text-decoration:underline; font-weight: bold;">${item.guru.nama}, ${item.guru.gelar_belakang}</p>
                                <p>NIP. ${item.guru.nip}</p>
                            </td>
                        </tr>
                    </table>
                </body>
            </html>
    `
    win.document.write(html)
}

onMounted(() => {
    list();
    getGurus();
})
</script>

<template>
    <Head :title="pageTitle" />
    <ConfirmDialog ref="confirmDialog" />
    <Loading v-if="loading" />
    <AdminLayout :title="pageTitle">
        <div class="toolbar sticky z-50 top-0 h-10 flex items-center justify-between p-3 bg-white">
            <h1 class="flex items-center">
                <Icon icon="mdi:basketball" class="text-red-600" />
                Data Ekstrakurikuler
            </h1>
            <div class="toolbar-items flex items-center justify-end gap-2">
                <button
                    @click="openForm"
                    class="flex items-center justify-center gap-1 text-sky-600 rounded border py-1 px-2 hover:bg-slate-200 active:bg-slate-400">
                        <Icon icon="mdi:plus-circle" class="text-sky-400" />
                        <span class="text-sm">Baru</span>
                </button>
                <button
                    v-if="selectedEkskul"
                    @click="selectedEkskul = null"
                    class="flex items-center justify-center gap-1 text-orange-600 rounded border border-orange py-1 px-2 hover:bg-orange-200 active:bg-orange-400">
                        <Icon icon="mdi:arrow-left" class="text-orange-400" />
                        <span class="text-sm">Kembali</span>
                </button>
            </div>
        </div>
        <div class="content py-3 px-4">
            <AturPeserta :ekskul="selectedEkskul" v-if="selectedEkskul !== null" />
            <div class="table w-full overflow-auto bg-white shadow hover:shadow-lg" v-else>
                <table class="w-full border">
                    <thead>
                        <tr>
                            <th class="py-3 border bg-slate-400 text-slate-800">No</th>
                            <th class="py-3 border bg-slate-400 text-slate-800">Kode</th>
                            <th class="py-3 border bg-slate-400 text-slate-800">Label</th>
                            <th class="py-3 border bg-slate-400 text-slate-800">Pembina</th>
                            <th class="py-3 border bg-slate-400 text-slate-800">Keterangan</th>
                            <th class="py-3 border bg-slate-400 text-slate-800">Peserta</th>
                            <th class="py-3 border bg-slate-400 text-slate-800">Adminsitrasi</th>
                            <th class="py-3 border bg-slate-400 text-slate-800">Opsi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-if="ekskuls.length < 1">
                            <td colspan="6" class="p-3 bg-slate-200 text-center">
                                <h1 class="text-lg font-bold text-slate-600">
                                    Belum ada data Ekstrakurikuler
                                </h1>
                            </td>
                        </tr>
                        <tr
                            v-for="item in items" :key="item.id"
                            class="odd:bg-slate-100"
                        >
                            <td class="text-center border py-2">{{ item.no }}</td>
                            <td class="text-center border py-2">{{ item.kode }}</td>
                            <td class="text-center border py-2">{{ item.label }}</td>
                            <td class="text-left border py-2 px-2">
                                <div class="w-full flex items-center gap-2">
                                    <img :src="setAvatar(item.guru)" :alt="item.guru.nama" class="w-[100px] aspect-square rounded-full shadow">
                                    <div>
                                        <p class="font-bold text-slate-600">{{ item.guru.nama }}</p>
                                        <p>{{ item.guru.alamat }}</p>
                                        <p>
                                            <Icon icon="mdi:cellphone-android" class="inline-block" />
                                            {{ item.guru.hp }}
                                        </p>
                                    </div>
                                </div>
                            </td>
                            <td class="text-center border py-2">{{ item.keterangan }}</td>
                            <td class="text-center border py-2">{{ item.pesertas.length }}</td>
                            <td class="text-center border py-2">
                                <ul>
                                    <li>
                                        <button class="bg-sky-400 py-1 px-2 rounded text-sm text-white hover:bg-sky-600" @click="cetakPresensi(item)">Presensi</button>
                                    </li>
                                </ul>
                            </td>
                            <td class="text-center border py-2">

                                <button @click="aturPeserta(item)">
                                    <Icon icon="mdi:account-multiple" class="text-sky-400 hover:text-sky-600 text-2xl" />
                                </button>
                                <button @click="yakinHapus(item)">
                                    <Icon icon="mdi:trash-can-outline" class="text-red-400 hover:text-red-600 text-2xl" />
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
       
    </AdminLayout>
    <div 
        v-if="showForm"
        class="dialog fixed z-50 bg-slate-200 top-0 right-0 bottom-0 left-0 flex items-center justify-center bg-opacity-60 backdrop-blur-sm">
            <div class="dialog-form w-11/12 md:w-[500px] bg-white rounded shadow">
                <div class="form-toolbar w-full py-1 px-2 flex items-center justify-between">
                    Isi Data Ekskul
                    <button @click="closeForm">
                        <Icon icon="mdi:close-circle" class="text-red-600 text-2xl hover:text-red-400" />
                    </button>
                </div>
                <div class="form-content p-3 bg-slate-100">
                    <form @submit.prevent="simpan">
                        <label for="Kode" class="w-full flex justify-between my-2">
                            Kode
                            <input type="text" placeholder="Kode Ekskul" class="py-1 border-none bg-slate-200 w-[60%] rounded" v-model="ekskul.kode" required>
                        </label>
                        <label for="label" class="w-full flex justify-between my-2">
                            Label
                            <input type="text" placeholder="Label Ekskul" class="py-1 border-none bg-slate-200 w-[60%] rounded" v-model="ekskul.label" required>
                        </label>
                        <label for="guru" class="w-full flex justify-between my-2">
                            Guru Pembina
                            <select class="py-1 border-none bg-slate-200 w-[60%] rounded" v-model="ekskul.guru_id" required>
                                <option value="0">Pilih Guru Pembina</option>
                                <option
                                    v-for="(guru,g) in gurus"
                                    :key="g" 
                                    :value="guru.nip">
                                    {{ guru.nama }}
                                </option>
                            </select>
                        </label>
                        <label for="keterangan" class="w-full flex justify-between my-2">
                            Keterangan
                            <textarea class="py-1 border-none bg-slate-200 w-[60%] rounded" placeholder="Keterangan" v-model="ekskul.keterangan" required>
                            </textarea>
                        </label>
                        <label for="submit" class="flex justify-center mt-4">
                            <button type="submit" 
                                class="bg-sky-600 text-slate-50 py-1 px-2 rounded hover:bg-sky-400 flex items-center gap-1" 
                                :disabled="loading">
                                <Icon icon="mdi:progress-clock" class="animate-spin" v-if="loading" />
                                Simpan
                            </button>
                        </label>
                    </form>
                </div>
            </div>
    </div>
</template>