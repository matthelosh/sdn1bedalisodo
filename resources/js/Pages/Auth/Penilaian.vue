<script setup>
import { ref, computed, defineAsyncComponent } from 'vue';
import { Head, usePage } from '@inertiajs/vue3';
import { Icon } from '@iconify/vue';

const AdminLayout = defineAsyncComponent(() => import('@/Layouts/AdminLayout.vue'));

const page = usePage()
const role = page.props.auth.user.userable.role;
const rombels = computed(() => {
    let items = []
    page.props.rombels.forEach((item,index) => {
        item.no = index+1
        items.push(item)
    })

    return items
})

const headers = ref([
    { text: 'No', value: 'no'},
    { text: 'Kode', value: 'kode'},
    { text: 'Label', value: 'label'},
    { text: 'Wali Kelas', value: 'guru.nama'},
    { text: 'Siswa', value: 'siswa'},
    { text: 'Kurikulum', value: 'kurikulum'},
    { text: 'Penilaian', value: 'penilaian'},

])
</script>

<template>
<Head title="Penilaian" />
<AdminLayout>
    <div class="wrapper p-4">
        <div class="toolbar h-12 flex items-center justify-between bg-slate-50 shadow p-3">
            <h1 class="flex items-center gap-1">
                <Icon icon="mdi:order-bool-ascending-variant" />
                Penilaian
            </h1>
            <div class="toolbar-items flex gap-2">
                
            </div>
        </div>
        <div class="content p-3 bg-white">
            <DataTable :items="rombels" :headers="headers">
                <template #item-siswa="item">
                    {{ item.siswas.length }}
                </template>
                <template #item-penilaian="item">
                    <div class="flex flex-wrap gap-2">
                        <button class="py-1 px-3 rounded bg-sky-400 hover:bg-sky-600 active:bg-sky-500 text-white">
                            Harian
                        </button>
                        <button class="py-1 px-3 rounded bg-sky-400 hover:bg-sky-600 active:bg-sky-500 text-white">
                            Tengah Semester
                        </button>
                        <button class="py-1 px-3 rounded bg-sky-400 hover:bg-sky-600 active:bg-sky-500 text-white">
                            Akhir Semester
                        </button>
                    </div>
                </template>
            </DataTable>
        </div>
    </div>
   
</AdminLayout>
</template>