<script setup>
import { computed, ref }  from 'vue';
import { read, utils } from 'xlsx';
import { Icon } from '@iconify/vue';

const fileRkas = ref(null);

const search = ref(null);
const rkas = ref([]);
const datas = computed(() => {
    return search.value == null ? rkas.value : rkas.value.filter(data => data.uraian.toLowerCase().includes(search.value.toLowerCase()))
});

const onFileRkasPicked =  async (ev) => {
    const file = ev.target.files[0]
    const reader = new FileReader()

    reader.onload = async(e) => {
        let wb = await read(e.target.result)
        rkas.value = await utils.sheet_add_json(wb.Sheets[wb.SheetNames[0]])
    }
    reader.readAsArrayBuffer(file);
}

const formRkas = ref(false)
const showForm = () => {
    formRkas.value = !formRkas.value
}
</script>

<template>
<div class="w-full p-3">
    <div class="toolbar w-full flex items-center justify-between sticky top-10 print:top-0 bg-white border-b py-1 px-2">
        <h1><span class="hidden">Rencana Anggaran Keuangan Sekolah</span></h1>
        <div class="toolbar-items flex gap-4 items-center justify-between print:hidden">
            <input type="file" ref="fileRkas" @change="onFileRkasPicked" class="hidden" accept=".xls,.xlsx,.ods,.csv" multiple>
            <button class="flex items-center gap-1 group text-gray-600 hover:font-bold hover:text-gray-800" @click="fileRkas.click()">
                <Icon icon="mdi:table-arrow-up" class="text-2xl" />
                <span class="hidden md:block">Impor Rencana</span>
            </button>
            <button class="flex items-center gap-1 justify-center group text-gray-600 hover:font-bold hover:text-gray-800" @click="showForm = true">
                <Icon icon="mdi:table-plus" class="text-2xl" />
                <span class="hidden md:block">Tambah Rencana</span>
            </button>
            <span class="relative">
                <input type="text" placeholder="Cari Anggaran" v-model="search" class="py-1 rounded" />
                <Icon icon="mdi:magnify" class="text-2xl text-gray-400 absolute top-[50%] -translate-y-[50%] right-2" />
            </span>
            
        </div>

    </div>
    <div class="content w-full overflow-x-auto my-4">
        <div class="table w-full overflow-x-auto bg-white">
            <table class="w-full border border-slate-400">
                <thead>
                    <tr class="bg-slate-200">
                        <th class="border py-1 border-slate-300 text-slate-800">No</th>
                        <th class="border py-1 border-slate-300 text-slate-800 hidden md:table-cell">Kode Rekening</th>
                        <th class="border py-1 border-slate-300 text-slate-800 hidden md:table-cell">Kode Kegiatan</th>
                        <th class="border py-1 border-slate-300 text-slate-800">Uraian</th>
                        <th class="border py-1 border-slate-300 text-slate-800">Volume</th>
                        <th class="border py-1 border-slate-300 text-slate-800">Satuan</th>
                        <th class="border py-1 border-slate-300 text-slate-800">Tarif</th>
                        <th class="border py-1 border-slate-300 text-slate-800">Jumlah</th>
                        <th class="border py-1 border-slate-300 text-slate-800">Opsi</th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
    </div>
</div>
</template>