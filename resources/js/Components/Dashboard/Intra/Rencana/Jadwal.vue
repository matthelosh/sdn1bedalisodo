<script setup>
import { ref, computed, onMounted } from 'vue';
import { Icon } from '@iconify/vue';
import axios from 'axios';
import { usePage, router } from '@inertiajs/vue3';
import {toast} from 'vue3-toastify';

const page = usePage();

const notify = (text, type) => {
    toast(text, {
        type: type,
        position: toast.POSITION.TOP_RIGHT
    });
}

const jadwals = ref([
]);

const showJadwal = (hari, jamke) => {
    // return jadwals.value.find(item => item.hari == hari)
    if (jadwals.value.length > 0 ) {
        let jadwal = jadwals.value.find(item => item.hari == hari && (jamke >= parseInt(item.jamke) && jamke < (parseInt(item.jamke) + parseInt(item.jml_jam))))

        return jadwal
    } else {
        return '-';
    }
}

const jadwal = ref({
    hari: 'Senin',
    jamke: '1'
})
const showForm = ref(false)
const close = () => {
    showForm.value = false
    jadwal.value = { hari: 'Senin', jamke: '1'}
}

const simpan = async(e) => {
    e.preventDefault();
    let data = jadwal.value;
    data.tapel = page.props.tapel.kode
    data.guru_id = page.props.auth.user.userable.nip
    if(data.id) { data._method = 'PUT'}
    router.post(route('dashboard.jadwal.store'), data, {
        onSuccess: (page) => {
            notify("Jadwal berhasil disimpan", "success")
            listJadwals()
            jadwal.value = { hari: 'Senin',  jamke: '1'}
            showForm.value = false
        },
        onError: (err) => {
            Object.keys(err).forEach(k => {
                notify(err[k], "error")
            })
        }
    })
}

const edit = (item) => {
    jadwal.value = item
    showForm.value = true
}

const rombels = computed(() => {
    return page.props.rombels.map(item => {
        return { text: item.label, value: item.kode };
    })
})

const mapels = ref([])
const listMapels = async() => {
    await axios.post(route('dashboard.mapel.index'))
                .then(res => {
                    // console.log(res.data.rombels)
                    let items = []
                    res.data.mapels.forEach((item,index) => {
                        items.push({text: item.label, value: item.kode})
                    });
                    mapels.value = items
                })
}

const listJadwals = async() => {
    await axios.post(route('dashboard.jadwal.index'))
            .then(res => {
                jadwals.value = res.data.jadwals
            })
}
onMounted(() => {
    listJadwals()
    listMapels()
})
</script>

<template>
    <div class="w-full relative">
        <div class="toolbar h-10 bg-slate-200 flex items-center justify-between p-3 text-slate-800">
            <h1 class="flex gap-1">
                <Icon icon="mdi:calendar" class="text-2xl" />
                Jadwal Pelajaran
            </h1>
            <button @click="showForm = true">
                <Icon icon="mdi:plus-circle" class="text-xl hover:text-slate-200 active:text-slate-100" />
            </button>
        </div>
        <div class="content p-3 bg-white">
            <table class="border w-full">
                <thead>
                    <tr class="bg-slate-100">
                        <th rowspan="2" class="border p-2">Jamke</th>
                        <th colspan="6" class="border p-2">Hari</th>
                    </tr>
                    <tr class="bg-slate-100">
                        <th class="border p-2">Senin</th>
                        <th class="border p-2">Selasa</th>
                        <th class="border p-2">Rabu</th>
                        <th class="border p-2">Kamis</th>
                        <th class="border p-2">Jumat</th>
                        <th class="border p-2">Sabtu</th>
                    </tr>
                </thead>
                <tbody>
                    <tr 
                        v-for="jamke in 10" :key="jamke"
                    >
                        <td class="border text-center p-2">{{ jamke }}</td>
                        <td class="border text-center p-2">
                            <button class="hover:text-teal-500" @click="edit(showJadwal('Senin', jamke))">
                                {{showJadwal('Senin', jamke)?.rombel?.label}}
                            </button>
                        </td>
                        <td class="border text-center p-2">
                            <button class="hover:text-teal-500" @click="edit(showJadwal('Selasa', jamke))">
                                {{ showJadwal('Selasa', jamke)?.rombel?.label}}
                            </button>
                        </td>
                        <td class="border text-center p-2">
                            <button class="hover:text-teal-500" @click="edit(showJadwal('Rabu', jamke))">
                            {{ showJadwal('Rabu', jamke)?.rombel?.label}}
                            </button>
                        </td>
                        <td class="border text-center p-2">
                            <button class="hover:text-teal-500" @click="edit(showJadwal('Kamis', jamke))">
                                {{ showJadwal('Kamis', jamke)?.rombel?.label}}
                            </button>
                        </td>
                        <td class="border text-center p-2">
                            <button class="hover:text-teal-500" @click="edit(showJadwal('Jumat', jamke))">
                                {{ showJadwal('Jumat', jamke)?.rombel?.label}}
                            </button>
                        </td>
                        <td class="border text-center p-2">
                            <button class="hover:text-teal-500" @click="edit(showJadwal('Sabtu', jamke))">
                                {{ showJadwal('Sabtu', jamke)?.rombel?.label}}
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
            <!-- {{ jadwals }} -->
        </div>

        <div class="w-full bg-slate-100 bg-opacity-20 backdrop-blur-sm fixed top-0 right-0 bottom-0 left-0 flex items-center justify-center z-40" @click.self="close" v-if="showForm">
            <div class="dialog bg-white rounded shadow w-[400px] max-w-[800px]">
                <div class="toolbar p-3 flex items-center bg-slate-300">
                    Formulir Jadwal
                </div>
                <div class="content px-3 py-2">
                    <form ref="formJadwal" @submit.prevent="simpan">
                        <div class="row w-full my-2">
                            <label for="rombel_id">Rombel</label>
                            <select v-model="jadwal.rombel_id" class="w-full bg-slate-50 border-none">
                                <option 
                                    v-for="rombel in rombels"
                                    :value="rombel.value" :key="rombel">{{ rombel.text }}</option>
                            </select>
                        </div>
                        <div class="row w-full my-2">
                            <label for="hari">Hari</label>
                            <select v-model="jadwal.hari" class="w-full bg-slate-50 border-none">
                                <option 
                                    v-for="hari in ['Senin', 'Selasa', 'Rabu', 'Kamis','Jumat','Sabtu']"
                                    :value="hari" :key="hari">{{ hari }}</option>
                            </select>
                        </div>
                        <div class="row w-full my-2">
                            <label for="jml_jam">Mata Pelajaran</label>
                            <select v-model="jadwal.mapel_id" class="w-full bg-slate-50 border-none">
                                <option 
                                    v-for="mapel in mapels"
                                    :value="mapel.value" :key="mapel">{{ mapel.text }}</option>
                            </select>
                        </div>
                        <div class="row w-full my-2">
                            <label for="jml_jam">Jumlah Jam</label>
                            <input type="number" size="2" placeholder="Jumlah Jam Mengajar" v-model="jadwal.jml_jam" class="w-full bg-slate-50 border-none">
                        </div>
                        <div class="row w-full my-2">
                            <label for="jamke">Jam Ke</label>
                            <select v-model="jadwal.jamke" class="w-full bg-slate-50 border-none">
                                <option 
                                    v-for="jam in 10"
                                    :value="jam" :key="jam">{{ jam }}</option>
                            </select>
                        </div>
                        <div class="row w-full my-2 flex items-center justify-center">
                            <button class="bg-sky-400 hover:bg-sky-600 active:bg-sky-500 text-white py-1 px-3 rounded" @click="simpan"> 
                                Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>