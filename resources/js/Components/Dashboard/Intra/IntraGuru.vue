<script setup>
import { onMounted, ref, computed, defineAsyncComponent } from 'vue';
import { usePage } from '@inertiajs/vue3';
import { Icon } from '@iconify/vue';

const page = usePage();

const Kurikulum = defineAsyncComponent(() => import('./Kurikulum/index.vue'))
const Rencana = defineAsyncComponent(() => import('./Rencana/index.vue'))
const selectedRombel = ref(null);

const mode = ref('list');
const items = computed(() => {
    if (page.props.auth.user.userable.role == 'gkel') {
        return page.props.rombels[0].mapels
    } else { return page.props.rombels}
})

const mapel = computed(() => {
    // return (page.props.auth.user.userable.role !== 'gkel') ? page.props.auth.user.userable.role.splice(0,1) : null
    return page.props.auth.user.userable.role !== 'gkel' ? page.props.auth.user.userable.role.slice(1) : null
})

const rencana = (item) => {
    selectedRombel.value = item
    mode.value = 'rencana'
}

const kurikulum = (item) => {
    selectedRombel.value = item
    mode.value = 'kurikulum'
    console.log(item)
}

const role = page.props.auth.user.userable.role
</script>

<template>
    <div class="wrapper w-full max-w-screen ">
        <div class="toolbar sticky top-0 z-40 p-3 h-12 flex items-center justify-between bg-slate-500 text-white">
            <h1>Perangkat Guru {{ mapel?.toUpperCase() }}</h1>
            <div class="toolbar-items flex items-center gap-2">
                <button v-if="mode !== 'list'" @click="mode = 'list'">
                    <Icon icon="mdi:close-circle" class="text-red-400 hover:text-red-600 active:text-red-500 text-2xl"  />
                </button>
            </div>
        </div>
        <div class="content w-full bg-white p-2 " v-if="mode == 'list'">
            <!-- Jika User adalah Wali Kelas -->
            <div class="w-full" v-if="role == 'gkel'">
                <table class="w-full border">
                    <thead>
                        <tr>
                            <th class="border hidden md:table-cell">No</th>
                            <th class="border">Mapel</th>
                            <th class="border hidden md:table-cell">Kurikulum</th>
                            <th class="border">Perangkat</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="(mapel, m) in items" :key="m"
                        >
                            <td class="border py-1 px-2 text-center">{{ m+1 }}</td>
                            <td class="border py-1 px-2 ">{{ mapel.label }}</td>
                            <td class="border py-1 px-2 ">{{ mapel.label }}</td>
                            <td class="border px-2 w-[75%] md:w-[40%]">
                                <div class="flex flex-wrap gap-2 p-1 justify-center">
                                    <button
                                        class=" bg-teal-600 px-1 py-1 rounded-sm hover:bg-teal-500 active:bg-teal-400 text-white gap-1 flex items-center"
                                        @click="kurikulum(mapel)"
                                    >
                                        <Icon icon="mdi:book-education" />
                                        Kurikulum
                                    </button>
                                    <button
                                        class=" bg-green-600 px-1 py-1 rounded-sm hover:bg-green-500 active:bg-green-400 text-white gap-1 flex items-center"
                                    >
                                        <Icon icon="mdi:notebook-multiple" />
                                        Rencana
                                    </button>
                                    <button
                                        class=" bg-red-600 px-1  py-1 rounded-sm hover:bg-red-500 active:bg-red-400 text-white flex items-center gap-1"
                                    >
                                    <Icon icon="mdi:book-cog" />
                                        Pelaksanaan
                                    </button>
                                    <button
                                        class=" bg-sky-600 hover:bg-sky-500 active:bg-sky-400 px-1  py-1 rounded-sm text-white flex items-center gap-1"
                                    >
                                        <Icon icon="mdi:lead-pencil" />
                                        Evaluasi
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <!-- Jika User Guru Mapel -->
            <div class="w-full" v-else>
                <table class="w-full border">
                    <thead>
                        <tr>
                            <th class="border hidden md:table-cell">No</th>
                            <th class="border">Mapel</th>
                            <th class="border hidden md:table-cell">Kurikulum</th>
                            <th class="border hidden md:table-cell">Wali Kelas</th>
                            <th class="border hidden md:table-cell">Jumlah Peserta</th>
                            <th class="border">Perangkat</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="(rombel, index) in items" :key="index"
                            class="odd:bg-slate-100"
                        >
                            <td class="border hidden md:table-cell text-center px-2">{{ index+1 }}</td>
                            <td class="border px-2 text-center">{{ rombel.label }}</td>
                            <td class="border hidden md:table-cell px-2">{{ rombel.kurikulum }}</td>
                            <td class="border hidden md:table-cell px-2">{{ rombel.guru?.nama }}</td>
                            <td class="border hidden md:table-cell px-2 text-center">{{ rombel.siswas?.length }}</td>
                            <td class="border px-2 w-[75%] md:w-[40%]">
                                <div class="flex flex-wrap gap-1 p-1 justify-center">
                                    <button
                                        class=" bg-teal-600 px-1 py-1 rounded-sm hover:bg-teal-500 active:bg-teal-400 text-white gap-1 flex items-center"
                                        @click="kurikulum(rombel)"
                                    >
                                        <Icon icon="mdi:book-education" />
                                        Kurikulum
                                    </button>
                                    <button
                                        class=" bg-green-600 px-1 py-1 rounded-sm hover:bg-green-500 active:bg-green-400 text-white gap-1 flex items-center"
                                        @click="rencana(rombel)"
                                    >
                                        <Icon icon="mdi:notebook-multiple" />
                                        Rencana
                                    </button>
                                    <button
                                        class=" bg-red-600 px-1  py-1 rounded-sm hover:bg-red-500 active:bg-red-400 text-white flex items-center gap-1"
                                    >
                                    <Icon icon="mdi:book-cog" />
                                        Pelaksanaan
                                    </button>
                                    <button
                                        class=" bg-sky-600 hover:bg-sky-500 active:bg-sky-400 px-1  py-1 rounded-sm text-white flex items-center gap-1"
                                    >
                                        <Icon icon="mdi:lead-pencil" />
                                        Evaluasi
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        
    </div>
    <Rencana v-if="mode=='rencana'" :rombel="selectedRombel" :mapel="mapel" />
    <Kurikulum v-if="mode=='kurikulum'" :rombel="selectedRombel" :mapel="mapel" />
</template>