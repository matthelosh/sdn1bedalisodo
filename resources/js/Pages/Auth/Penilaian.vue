<script setup>
import { ref, computed, defineAsyncComponent } from 'vue';
import { Head, usePage } from '@inertiajs/vue3';
import { Icon } from '@iconify/vue';

const AdminLayout = defineAsyncComponent(() => import('@/Layouts/AdminLayout.vue'));

const FormPenilaian = defineAsyncComponent(() => import('@/Components/Dashboard/Penilaian/Forms/FormPenilaian.vue'));
const Harian = defineAsyncComponent(() => import('@/Components/Dashboard/Penilaian/Harian.vue'));
const PTS = defineAsyncComponent(() => import('@/Components/Dashboard/Penilaian/PTS.vue'));
const PAS = defineAsyncComponent(() => import('@/Components/Dashboard/Penilaian/PAS.vue'));

defineProps({rombel: Object, mapel:String});

const page = usePage()
const role = page.props.auth.user.userable.role;
const mode = ref('list');
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

const comp = ref(null)
const is = computed(() => {
    switch(comp.value) {
        case "Harian":
            return Harian;
            break;
        case "PTS":
            return PTS;
            break;
        case "PAS":
            return PAS;
            break;
            
    }
})

const show = (komponen, rombel) => {
    console.log(komponen)
    comp.value = komponen
    selectedRombel.value = rombel
    mode.value = komponen
}

const close = () => {
    comp.value = null
    mode.value = 'list'
}

const mapel = computed(() => {
    // return (page.props.auth.user.userable.role !== 'gkel') ? page.props.auth.user.userable.role.splice(0,1) : null
    return page.props.auth.user.userable.role !== 'gkel' ? page.props.auth.user.userable.role.slice(1) : null
})

const formPenilaian = ref(false)
const openForm = () => {
    formPenilaian.value = true
}

const selectedRombel = ref(null)
</script>

<template>
<Head title="Penilaian" />
<AdminLayout>
    <div class="wrapper p-3 shadow-lg w-full ">
        <div class="toolbar h-12 flex items-center justify-between bg-slate-100 p-3 rounded-t-lg">
            <h1 class="flex items-center gap-1">
                <Icon icon="mdi:order-bool-ascending-variant" />
                Penilaian {{ comp }} 
            </h1>
            <div class="toolbar-items flex gap-2">
                <button v-if="mode !== 'list'" @click="close">
                    <Icon icon="mdi-close-circle" class="text-2xl text-red-400 hover:text-red-500 active:text-red-300" />
                </button>
                <button v-else @click="openForm" class="flex items-center gap-1 bg-teal-500 py-1 px-2 rounded-lg text-white shadow-lg">
                    Buat Penilaian
                </button>
            </div>
        </div>
        <div class="content p-3 bg-slate-50 rounded-b-lg shadow-lg">
            <DataTable :items="rombels" :headers="headers" v-if="mode == 'list'">
                <template #item-siswa="item">
                    {{ item.siswas.length }}
                </template>
                <template #item-penilaian="item">
                    <div class="flex flex-wrap gap-2">
                        <button class="py-1 px-3 rounded bg-sky-400 hover:bg-sky-600 active:bg-sky-500 text-white" @click="show('Harian', item)">
                            Harian
                        </button>
                        <button class="py-1 px-3 rounded bg-sky-400 hover:bg-sky-600 active:bg-sky-500 text-white" @click="show('PTS', item)">
                            Tengah Semester
                        </button>
                        <button class="py-1 px-3 rounded bg-sky-400 hover:bg-sky-600 active:bg-sky-500 text-white" @click="show('PAS', item)">
                            Akhir Semester
                        </button>
                    </div>
                </template>
            </DataTable>
            <component :is="is" v-if="mode !=='list'" :mapel="mapel" :rombel="selectedRombel" />
        </div>
        <FormPenilaian :rombel="rombel" :mapel="mapel" :periode="'harian'" v-if="formPenilaian" @close="formPenilaian = false" />
    </div>
   
</AdminLayout>
</template>