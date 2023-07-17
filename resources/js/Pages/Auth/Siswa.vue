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

const loading = ref(false)
const Loading = defineAsyncComponent(() => import('@/Components/General/Loading.vue'))

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
</script>

<template>
<Head title="Data Siswa" />   
<AdminLayout title="Data Siswa">
    <div class="wrapper w-full oveflow-x-hidden p-3">
        <div class="table w-full p-3 bg-white">
            <div class="table-header grid grid-cols-2 justify-between items-center w-full mb-3" >
                <div class="hidden md:block ">
                    <h1 class="text-lg font-bold">Data Siswa</h1>
                </div>
                <div class="flex items-center justify-end gap-2">
                    <input type="file" ref="fileInput" @change="onFileSiswaPicked" class="hidden" accept=".xls, .xlsx, .ods">
                    <button class="flex items-center gap-1 hover:text-gray-600 border px-3 py-1 rounded border-transparent hover:border-gray-600" @click="$refs.fileInput.click()">
                        <span class="hidden md:block">Impor Siswa</span>
                        <Icon icon="mdi:import" class="text-xl" />
                    </button>
                    <input type="text" v-model="search" class="w-[50%] rounded-lg py-1" placeholder="Cari Nama" />
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
</template>