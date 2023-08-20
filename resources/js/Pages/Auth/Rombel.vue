<script setup>
import { ref, defineAsyncComponent } from 'vue';
import {usePage, Head, router} from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import {Icon} from '@iconify/vue';

const page = usePage()

const ManageMapel = defineAsyncComponent(() => import('@/Components/Dashboard/Rombel/ManageMapel.vue'))
const ManageMember = defineAsyncComponent(() => import('@/Components/Dashboard/Rombel/ManageMember.vue'))
const ConfirmDialog = defineAsyncComponent(() => import('@/Components/General/ConfirmDialog.vue'))
const FormRombel = defineAsyncComponent(() => import('@/Components/Dashboard/Rombel/FormRombel.vue'))

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

const openForm = () => {
    mode.value = 'form'
}

const edit = (item) => {
    mode.value = 'form'
    selectedRombel.value = item
}

const closeForm = () => {
    mode.value = 'list'
    selectedRombel.value = null
    router.reload({only: ['rombels']})
}

const manageMapel = (item) => {
    mode.value = 'manage-mapel'
    selectedRombel.value = item
}

const closeMapel = () => {
    mode.value = 'list'
    selectedRombel.value = null
    router.reload({only:['rombels']})
}


const FormWa = defineAsyncComponent(() => import('@/Components/General/FormWa.vue'))
const formWa = ref(false)

const openFormWa = (item) => {
    // alert('hi')
    selectedRombel.value = item
    formWa.value = true
}

const closeFormWa = () => {
    selectedRombel.value = null
    formWa.value = false
}
</script>

<template>
<Head title="Data Rombel" />   
<AdminLayout title="Data Rombel">
    <div class="wrapper w-full oveflow-x-hidden p-3" v-if="mode=='list'">
        <div class="toolbar w-full">
            <div class="toolbar-items">
                <button class="flex items-center gap-1 py-1 px-2 bg-sky-200 rounded hover:bg-sky-600 hover:text-white group" @click="openForm">
                    <Icon icon="mdi:plus-circle" class="text-sky-400 group-hover:text-orange-100" />
                    Baru
                </button>
            </div>
        </div>
        <div class="w-full overflow-x-auto bg-white my-4" >
            <table class="table-border border-collapse w-full">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="py-1 px-2 border hidden md:table-cell">No</th>
                        <th class="py-1 px-2 border hidden md:table-cell">Tapel</th>
                        <th class="py-1 px-2 border hidden md:table-cell">Kode</th>
                        <th class="py-1 px-2 border">Label</th>
                        <th class="py-1 px-2 border">Peserta</th>
                        <th class="py-1 px-2 border hidden md:table-cell">Kurikulum</th>
                        <th class="py-1 px-2 border hidden md:table-cell">Mapel</th>
                        <th class="py-1 px-2 border">Wali Kelas</th>
                        <th class="py-1 px-2 border">Opsi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(rombel,g) in $page.props.rombels" :key="g" class="odd:bg-gray-50" >
                        <td class="py-1 px-2 border text-center hidden md:table-cell">{{ g+1 }}</td>
                        <td class="py-1 px-2 border hidden md:table-cell">{{ rombel.tapel.label }}</td>
                        <td class="py-1 px-2 border hidden md:table-cell">{{ rombel.kode }}</td>
                        <td class="py-1 px-2 border text-center">
                            <button class="group py-1 px-2 bg-sky-400 text-white rounded hover:bg-sky-600" @click="edit(rombel)">
                                {{ rombel.label }}
                            </button>
                        </td>
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
                        <td class="py-1 px-2 border hidden md:table-cell">{{ rombel.kurikulum }}</td>
                        <td class="py-1 px-2 border hidden md:table-cell text-center">
                            <button @click="manageMapel(rombel)" class="text-sky-600 font-bold">
                                {{ rombel.mapels?.length }}
                            </button>
                        </td>
                        <td class="py-1 px-2 border">{{ rombel.guru.nama }}</td>
                        <td class="py-1 px-2 border">
                            <div class="w-full flex items-center justify-center gap-2">
                                <button :title="`Kirim Wa ke Grup Kelas ${rombel.label}`" v-if="rombel.grupwa" @click="openFormWa(rombel)">
                                    <Icon icon="mdi:whatsapp" class="text-green-600 text-2xl hover:text-green-500 active:text-green-400" />
                                </button>
                                <button @click="manageMapel(rombel)">
                                    <Icon icon="mdi:bookshelf" class="text-sky-600 hover:text-sky-800 text-2xl" />
                                </button>
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
        
    </div>
    <ManageMapel v-else-if="mode == 'manage-mapel'" :rombel="selectedRombel" @close="closeMapel" />
    <ManageMember v-else-if="mode == 'member'" :rombel="selectedRombel" @close="closeMember" />
    <FormRombel v-else-if="mode == 'form'" :selectedRombel="selectedRombel" @close="closeForm" />
</AdminLayout>
<ConfirmDialog ref="confirmDialog" />
<FormWa :hp="selectedRombel.grupwa.chat_id" :isGroup="true" @close="closeFormWa" v-if="formWa" />
</template>