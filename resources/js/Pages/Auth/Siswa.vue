<script setup>
import { computed, ref, defineAsyncComponent } from 'vue';
import {usePage, Head, router} from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { read, utils } from 'xlsx';

router.on('start', () => loading.value = true)
router.on('finish', () => loading.value = false)
const page = usePage()
import { paginate } from '@/Plugins/misc';
import { Icon } from '@iconify/vue';
import axios from 'axios';

const confirmDialog = ref(null)
const ConfirmDialog = defineAsyncComponent(() => import('@/Components/General/ConfirmDialog.vue'))

const loading = ref(false)
const Loading = defineAsyncComponent(() => import('@/Components/General/Loading.vue'))
const openForm = (item) => {
    selectedSiswa.value = item
}
const FormSiswa = defineAsyncComponent(() => import('@/Components/Dashboard/Siswa/FormSiswa.vue'))
const selectedSiswa = ref(null)

const closeForm = () => {
    selectedSiswa.value = null
    router.reload({only: ['siswas']})
}

const search = ref('')
const currentPage = ref(0)
const itemsPerPage = ref(10)
const siswas = computed(() => {
    let items = page.props.siswas.filter((siswa) => siswa.nama.toLowerCase().includes(search.value.toLowerCase()))
    return paginate(items, itemsPerPage.value, currentPage.value)
})

const onFileSiswaPicked = (e) => {
    let file = e.target.files[0]

    let reader = new FileReader();

    reader.onload = async(ev) => {
        let wb = await read(ev.target.result)
        let datas = await utils.sheet_to_json(wb.Sheets[wb.SheetNames[0]])
        loading.value = true
        await axios.post(route('dashboard.siswa.impor'), {siswas: JSON.stringify(datas)}).then(res => {
            loading.value = false
            router.reload({only: ['siswas']})
        })
    }

    reader.readAsArrayBuffer(file)
}


const hapus = async(item) => {
    if (confirmDialog.value.open(`Yakin menghapus data siswa ${item.nama}?`)) {
        loading.value = true
        await axios.delete(route('dashboard.siswa.destroy', {id: item.id}))
                    .then(res => {
                        loading.value = false
                        router.reload({only: ['siswas']})
                    }).catch(err => {
                        loading.value = false
                        console.log(err)
                    })
    }
}
</script>

<template>
<Head title="Data Siswa" />   
<AdminLayout title="Data Siswa">
    <div class="wrapper w-[95vw] md:w-full overflow-x-auto oveflow-x-hidden p-3 mb-20">
        <div class="table w-full p-3 bg-white">
            <div class="table-header grid grid-cols-2 justify-between items-center w-full mb-3" >
                <div class="hidden md:block ">
                    <h1 class="text-lg font-bold">Data Siswa</h1>
                </div>
                <div class="flex items-center justify-end gap-2">
                    <input type="file" ref="fileInput" @change="onFileSiswaPicked" class="hidden" accept=".xls, .xlsx, .ods">
                    <button class="flex items-center gap-1 text-teal-600 hover:text-teal-800 hover:font-bold px-3 py-1 rounded hover:shadow" @click="$refs.fileInput.click()">
                        <span class="hidden md:block">Impor Siswa</span>
                        <Icon icon="mdi:import" class="text-xl" />
                    </button>
                    <button class="flex items-center gap-1 text-teal-600 hover:text-teal-800 hover:font-bold hover:shadow px-3 py-1" @click="openForm({mode: 'create'})">
                        <span class="hidden md:block">Entri Siswa</span>
                        <Icon icon="mdi:human-child" class="text-xl" />
                    </button>
                    <input type="text" v-model="search" class="w-[50%] rounded-lg py-1" placeholder="Cari Nama" />
                </div>
            </div>
            <table class="table border border-collapse w-full">
                <thead>
                    <tr class="bg-slate-300 shadow">
                        <th class="py-2 px-2">No</th>
                        <th class="py-2 px-2">NISN</th>
                        <th class="py-2 px-2">Nama</th>
                        <th class="py-2 px-2">Foto</th>
                        <th class="py-2 px-2">JK</th>
                        <th class="py-2 px-2">Tempat, Tanggal Lahir</th>
                        <th class="py-2 px-2">Alamat</th>
                        <th class="py-2 px-2">No. HP</th>
                        <th class="py-2 px-2">Opsi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(siswa,s) in siswas.current" :key="s" class="even:bg-slate-200" >
                        <td class="py-1 px-2 text-center">{{ siswa.no }}</td>
                        <td class="py-1 px-2">{{ siswa.nisn }}</td>
                        <td class="py-1 px-2">
                            <button class="text-teal-800 hover:font-bold hover:text-teal-600" @click="openForm(siswa)">
                                {{ siswa.nama }}
                            </button>
                        </td>
                        <td class="py-1 px-2"><img :src="siswa.foto_url ? siswa.foto_url : (siswa.jk && siswa.jk == 'Laki-laki') ? '/img/siswa.png' : '/img/siswi.png'" alt="Siswa" class="rounded-full aspect-square bg-slate-400 border border-slate-400 hover:bg-slate-100 shadow hover:shadow-lg w-3/4 mx-auto mt-4 cursor-pointer object-cover w-20" /> </td>
                        <td class="py-1 px-2">{{ siswa.jk }}</td>
                        <td class="py-1 px-2">{{ siswa.tempat_lahir }}, {{ siswa.tanggal_lahir }}</td>
                        <td class="py-1 px-2">{{ siswa.alamat }}, RT.{{ siswa.rt }} RW.{{ siswa.rw }}</td>
                        <td class="py-1 px-2">{{ siswa.hp }}</td>
                        <td class="py-1 px-2">
                            <div class="flex items-center justify-center gap-1">
                                <button @click="hapus(siswa)">
                                    <Icon icon="mdi:delete" class="text-red-600 hover:text-red-400 text-2xl" />
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="table-footer w-full grid grid-cols-3 gap-2 p-2">
                <div class="pagination-info my-2">
                   Jumlah Data: {{ siswas.total }} |
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
                <div class="page-nav flex flex-wrap col-span-3">
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
<Loading v-if="loading" />
<FormSiswa :selectedSiswa="selectedSiswa" @close="closeForm" v-if="selectedSiswa !== null" />
<ConfirmDialog ref="confirmDialog" />
</template>