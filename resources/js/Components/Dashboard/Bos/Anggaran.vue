<script setup>
import axios from 'axios';
import { ref, computed, onMounted, defineAsyncComponent } from 'vue';
import { Icon } from '@iconify/vue';
import { terbilang } from '@/Plugins/terbilang';

const Loading = defineAsyncComponent(() => import('@/Components/General/Loading.vue'))

const anggarans = ref([])
const loading = ref(false)

const list = async() => {
    loading.value = true
    await axios.post(route('dashboard.bos.anggaran.mata'))
                .then(res => {
                    anggarans.value = res.data.anggarans
                    loading.value = false
                })
}

const anggaran = ref({})

const newAnggaran = () => {
    anggarans.value.push({

    })
}

const simpan = async() => {
    console.log(anggaran.value)
    loading.value = true
    await axios.post(route('dashboard.bos.anggaran.store'), {anggaran: JSON.stringify(anggaran.value)})
                .then( res => {
                    loading.value = false
                    list()
                }).catch(err => {
                    loading.value = false
                })
}

onMounted(() => {
    list()
})
</script>

<template>
    <div class="w-full bg-white">
        <Loading v-if="loading" />
        <div class="toolbar w-full bg-slate-400 h-10 flex items-center justify-between p-3">
            <h1>Data Anggaran</h1>
            <div class="toolbar-items flex gap-2">
                <button class="bg-slate-50 py-1 px-2 rounded hover:bg-slate-200" @click="newAnggaran">Tambah</button>
            </div>
        </div>
        <div class="content w-full">
            <table class="w-full border text-sm">
                <thead>
                    <tr>
                        <th class="border">No</th>
                        <th class="border">Kode</th>
                        <th class="border">Sumber</th>
                        <th class="border">Tahun</th>
                        <th class="border">Tahap</th>
                        <th class="border">Mulai</th>
                        <th class="border">Selesai</th>
                        <th class="border">Uraian</th>
                        <th class="border">Nilai</th>
                        <th class="border">Keterangan</th>
                        <th class="border">Status</th>
                        <th class="border">Opsi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                        v-for="(item, i) in anggarans"
                        :key="item.id"
                        class="odd:bg-slate-100"
                    >
                        <td class="p-2 text-center border">{{ i+1 }}</td>
                        <td class="p-2 text-center border">
                            <span v-if="item.kode">
                                {{ item.kode }}
                            </span>
                            <input type="text" placeholder="Kode Anggaran" v-model="anggaran.kode" v-else class="w-full border-none bg-slate-100">
                        </td>
                        <td class="p-2 text-center border">
                            <span v-if="item.sumber_dana">
                                {{ item.sumber_dana }}
                            </span>
                            <input type="text" placeholder="Sumber Dana" v-model="anggaran.sumber_dana" v-else class="w-full border-none bg-slate-100">
                            <!-- {{ item.sumber_dana }} -->
                        </td>
                        <td class="p-2 text-center border">
                            <!-- {{ item.tahun_anggaran }} -->
                            <span v-if="item.tahun_anggaran">
                                {{ item.tahun_anggaran }}
                            </span>
                            <input type="text" placeholder="Tahun" v-model="anggaran.tahun_anggaran" v-else class="w-full border-none bg-slate-100">
                        </td>
                        <td class="p-2 text-center border">
                            <!-- {{ item.tahap }} -->
                            <span v-if="item.tahap">
                                {{ item.tahap }}
                            </span>
                            <input type="text" placeholder="Tahap" v-model="anggaran.tahap" v-else class="w-full border-none bg-slate-100">
                        </td>
                        <td class="p-2 text-center border">
                            <!-- {{ item.mulai }} -->
                            <span v-if="item.mulai">
                                {{ item.mulai }}
                            </span>
                            <input type="date" v-model="anggaran.mulai" v-else class="w-full border-none bg-slate-100">
                        </td>
                        <td class="p-2 text-center border">
                            <!-- {{ item.selesai }} -->
                            <span v-if="item.selesai">
                                {{ item.selesai }}
                            </span>
                            <input type="date" v-model="anggaran.selesai" v-else class="w-full border-none bg-slate-100">
                        </td>
                        <td class="p-2 text-center border">
                            <!-- {{ item.uraian }} -->
                            <span v-if="item.uraian">
                                {{ item.uraian }}
                            </span>
                            <input type="text" placeholder="Uraian" v-model="anggaran.uraian" v-else class="w-full border-none bg-slate-100">
                        </td>
                        <td class="p-2 text-center border">
                            
                            <span v-if="item.nilai">
                                Rp. {{ parseInt(item.nilai).toLocaleString("id-ID") }}
                            </span>
                            <input type="number" placeholder="Nilai" v-model="anggaran.nilai" v-else class="w-full border-none bg-slate-100">
                        </td>
                        <td class="p-2 text-center border">
                            <!-- {{ item.keterangan }} -->
                            <span v-if="item.keterangan">
                                {{ item.keterangan }}
                            </span>
                            <input type="text" placeholder="Keterangan" v-model="anggaran.keterangan" v-else class="w-full border-none bg-slate-100">
                        </td>
                        <td class="p-2 text-center border">
                            <!-- {{ item.status }} -->
                            <span v-if="item.status">
                                {{ item.status }}
                            </span>
                            <input type="text" placeholder="Status" v-model="anggaran.status" v-else class="w-full border-none bg-slate-100">
                        </td>
                        <td class="p-2 text-center border">
                            <button v-if="item.id">
                                <Icon icon="mdi:minus-circle" class="text-red-400 text-xl" />
                            </button>
                            <button v-else @click="simpan">
                                <Icon icon="mdi:floppy" class="text-sky-400 text-xl" />
                            </button>
                            <!-- {{ item }} -->
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>