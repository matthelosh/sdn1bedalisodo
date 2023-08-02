<script setup>
import { ref, defineAsyncComponent } from 'vue';
import {usePage, Head, router} from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import {Icon} from '@iconify/vue';

const page = usePage()

const ManageMember = defineAsyncComponent(() => import('@/Components/Dashboard/Rombel/ManageMember.vue'))
const ConfirmDialog = defineAsyncComponent(() => import('@/Components/General/ConfirmDialog.vue'))
const selectedRombel = ref(null);
const mode = ref('list')

const manageMember = (rombel) => {
    selectedRombel.value = rombel
    mode.value = 'member'
}

const closeMember = () => {
    mode.value = 'list'
    selectedRombel.value = null
    router.reload({only: ['rombels']})
}

const confirmDialog = ref(null)
const hapus = async (rombel) => {
    await confirmDialog.value.open("Anda akan menghapus rombel "+rombel.label+" beserta pesertanya.")
        .then(ok => {
            if (ok) {
                axios.delete(route('dashboard.rombel.destroy', {id: rombel.id}))
                    .then(res => {
                        router.reload({only: ['rombels']})
                    })
           }
        })
    
}
</script>

<template>
<Head title="Data Rombel" />   
<AdminLayout title="Data Rombel">
    <div class="wrapper w-full oveflow-x-hidden p-3">
        <div class="toolbar w-full">
            <div class="toolbar-items">
                <button class="flex items-center gap-1 py-1 px-2 bg-sky-200 rounded hover:bg-sky-600 hover:text-white group">
                    <Icon icon="mdi:plus-circle" class="text-sky-400 group-hover:text-orange-100" />
                    Baru
                </button>
            </div>
        </div>
        <div class="table w-full p-3 bg-white my-4" v-if="mode=='list'">
            <table class="table-border border-collapse w-full">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="py-1 px-2 border">No</th>
                        <th class="py-1 px-2 border">Tapel</th>
                        <th class="py-1 px-2 border">Kode</th>
                        <th class="py-1 px-2 border">Label</th>
                        <th class="py-1 px-2 border">Peserta</th>
                        <th class="py-1 px-2 border">Kurikulum</th>
                        <th class="py-1 px-2 border">Wali Kelas</th>
                        <th class="py-1 px-2 border">Opsi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(rombel,g) in $page.props.rombels" :key="g" class="odd:bg-gray-50" >
                        <td class="py-1 px-2 border text-center">{{ g+1 }}</td>
                        <td class="py-1 px-2 border">{{ rombel.tapel.label }}</td>
                        <td class="py-1 px-2 border">{{ rombel.kode }}</td>
                        <td class="py-1 px-2 border">{{ rombel.label }}</td>
                        <td class="py-1 px-2 border text-center">
                            <span class="flex items-center justify-center gap-2">
                                <div class="bg-sky-300 px-1 flex flex-col justify-center items-center h-full">
                                    <Icon icon="mdi:human-male" />
                                    {{ rombel.siswas.filter(siswa => siswa.jk == 'Laki-laki').length }} 
                                </div> 
                                <div class="bg-pink-300 px-1 flex flex-col justify-center items-center h-full">
                                    <Icon icon="mdi:human-female" />
                                    {{ rombel.siswas.filter(siswa => siswa.jk == 'Perempuan').length }}
                                </div>
                                <div class="bg-slate-400 px-1 flex flex-col justify-center items-center h-full">
                                    <Icon icon="mdi:human-male-female" />
                                    {{ rombel.siswas.length }}
                                </div>
                            </span>
                        </td>
                        <td class="py-1 px-2 border">{{ rombel.kurikulum }}</td>
                        <td class="py-1 px-2 border">{{ rombel.guru.nama }}</td>
                        <td class="py-1 px-2 border">
                            <div class="w-full flex items-center justify-center gap-2">
                                <button @click="manageMember(rombel)">
                                    <Icon icon="mdi:account-group" class="text-sky-600 hover:text-sky-800 text-2xl" />
                                </button>
                                <button @click="hapus(rombel)" v-if="page.props.auth.user.level == 'admin'">
                                    <Icon icon="mdi:delete-forever" class="text-red-300 hover:text-red-600 text-2xl" />
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <ManageMember v-else-if="mode == 'member'" :rombel="selectedRombel" @close="closeMember" />
    </div>
</AdminLayout>
<ConfirmDialog ref="confirmDialog" />
</template>