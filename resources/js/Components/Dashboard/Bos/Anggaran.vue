<script setup>
import axios from 'axios';
import { ref, computed, onMounted, defineAsyncComponent } from 'vue';
import { Icon } from '@iconify/vue';
import { terbilang } from '@/Plugins/terbilang';

const Loading = defineAsyncComponent(() => import('@/Components/General/Loading.vue'))
const ConfirmDialog  =defineAsyncComponent(() => import('@/Components/General/ConfirmDialog.vue'))

const anggarans = ref([])
const loading = ref(false)

const list = async() => {
    loading.value = true
    await axios.post(route('dashboard.bos.anggaran.mata'))
                .then(res => {
                    let items = []
                    res.data.anggarans.forEach(item => {
                        item.state = 'fix'
                        items.push(item)
                    })

                    anggarans.value = items
                    loading.value = false
                })
}

const anggaran = ref({})

const newAnggaran = () => {
    let last = anggarans.value.slice(-1)
    anggarans.value.push({
        silpa: last.silpa
    })
}

const toggleStatus = async(e,item) => {
    loading.value = true
    await axios.post(route('dashbaord.bos.anggaran.status.change', {id: item.id}), {status: e.target.value, _method: 'put'})
            .then(res => {
                loading.value = false
                list()
            }).catch(err => {
                console.log(err)
                loading.value  =false
            })
}

const simpan = async(i) => {
    // console.log(anggaran.value)
    loading.value = true
    await axios.post(route('dashboard.bos.anggaran.store'), {anggaran: JSON.stringify(anggarans.value[i])})
                .then( res => {
                    loading.value = false
                    list()
                }).catch(err => {
                    loading.value = false
                })
}

const edit = (item, index) => {
    anggarans.value[index].state = 'edit'
}

const confirmDialog = ref(null)
const hapus = async(item) => {
    if( await confirmDialog.value.open("Yakin Menghapus Anggaran " + item.uraian+" ?")) {
        axios.delete(route('dashboard.bos.anggaran.hapus', {id: item.id}))
                .then(res => {
                    list()
                }).catch(err => console.log( err))
    }

}
onMounted(() => {
    list()
})
</script>

<template>
    <div class="w-full bg-white">
        <Loading v-if="loading" />
        <ConfirmDialog ref="confirmDialog" />
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
                        <th class="border">Silpa</th>
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
                            <span v-if="item.state == 'fix'">
                                {{ item.kode }}
                            </span>
                            <input type="text" placeholder="Kode Anggaran" v-model="anggarans[i].kode" v-else class="w-full border-none bg-slate-100">
                        </td>
                        <td class="p-2 text-center border">
                            <span v-if="item.state == 'fix'">
                                {{ item.sumber_dana }}
                            </span>
                            <input type="text" placeholder="Sumber Dana" v-model="anggarans[i].sumber_dana" v-else class="w-full border-none bg-slate-100">
                            <!-- {{ item.sumber_dana }} -->
                        </td>
                        <td class="p-2 text-center border">
                            <!-- {{ item.tahun_anggaran }} -->
                            <span v-if="item.state == 'fix'">
                                {{ item.tahun_anggaran }}
                            </span>
                            <input type="text" placeholder="Tahun" v-model="anggarans[i].tahun_anggaran" v-else class="w-full border-none bg-slate-100">
                        </td>
                        <td class="p-2 text-center border">
                            <!-- {{ item.tahap }} -->
                            <span v-if="item.state == 'fix'">
                                {{ item.tahap }}
                            </span>
                            <input type="text" placeholder="Tahap" v-model="anggarans[i].tahap" v-else class="w-full border-none bg-slate-100">
                        </td>
                        <td class="p-2 text-center border">
                            <!-- {{ item.mulai }} -->
                            <span v-if="item.state == 'fix'">
                                {{ item.mulai }}
                            </span>
                            <input type="date" v-model="anggarans[i].mulai" v-else class="w-full border-none bg-slate-100">
                        </td>
                        <td class="p-2 text-center border">
                            <!-- {{ item.selesai }} -->
                            <span v-if="item.state == 'fix'">
                                {{ item.selesai }}
                            </span>
                            <input type="date" v-model="anggarans[i].selesai" v-else class="w-full border-none bg-slate-100">
                        </td>
                        <td class="p-2 text-center border">
                            <!-- {{ item.uraian }} -->
                            <span v-if="item.state == 'fix'">
                                {{ item.uraian }}
                            </span>
                            <input type="text" placeholder="Uraian" v-model="anggarans[i].uraian" v-else class="w-full border-none bg-slate-100">
                        </td>
                        <td class="p-2 text-center border">
                            
                            <span v-if="item.state == 'fix'">
                                Rp. {{ parseInt(item.nilai).toLocaleString("id-ID") }}
                            </span>
                            <input type="number" placeholder="Nilai" v-model="anggarans[i].nilai" v-else class="w-full border-none bg-slate-100">
                        </td>
                        <td class="p-2 text-center border">
                            
                            <span v-if="item.state == 'fix'">
                                Rp. {{ parseInt(item.silpa).toLocaleString("id-ID") }}
                            </span>
                            <input type="number" placeholder="Silpa" v-model="anggarans[i].silpa" v-else class="w-full border-none bg-slate-100">
                        </td>
                        <td class="p-2 text-center border">
                            <!-- {{ item.keterangan }} -->
                            <span v-if="item.state == 'fix'">
                                {{ item.keterangan }}
                            </span>
                            <input type="text" placeholder="Keterangan" v-model="anggarans[i].keterangan" v-else class="w-full border-none bg-slate-100">
                        </td>
                        <td class="p-2 text-center border">
                            <!-- {{ item.status }} -->
                            <!-- <span v-if="item.status">
                                {{ item.status }}
                            </span>
                            <input type="text" placeholder="Status" v-model="anggaran.status" v-else class="w-full border-none bg-slate-100"> -->
                            <select :value="item.status" class="border-none py-1" :class="item.status == 'aktif' ? 'bg-lime-300':'bg-red-300'" @change="toggleStatus($event, item)">
                                <option value="aktif">Aktif</option>
                                <option value="nonaktif">Non Aktif</option>
                            </select>
                        </td>
                        <td class="p-2 text-center border">
                            <button v-if="item.state == 'fix'" @click="edit(item, i)">
                                <Icon icon="mdi:pencil" class="text-orange-400 text-xl" />
                            </button>
                            <button v-else @click="simpan(i)">
                                <Icon icon="mdi:floppy" class="text-sky-400 text-xl" />
                            </button>
                            <!-- {{ item }} -->
                            <button @click="hapus(item)">
                                <Icon icon="mdi:delete" class="text-red-400 text-xl" />
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>