<script setup>
import { onMounted, ref, computed } from 'vue';
import { usePage } from '@inertiajs/vue3';

const page = usePage();

const items = computed(() => {
    if (page.props.auth.user.userable.role == 'gkel') {
        return page.props.rombels[0].mapels
    } else { return page.props.rombels}
})

const mapel = computed(() => {
    // return (page.props.auth.user.userable.role !== 'gkel') ? page.props.auth.user.userable.role.splice(0,1) : null
    return page.props.auth.user.userable.role !== 'gkel' ? page.props.auth.user.userable.role.slice(1) : null
})

const role = page.props.auth.user.userable.role
</script>

<template>
    <div class="wrapper w-full max-w-screen">
        <div class="toolbar bg-slate-200 sticky top-0 z-40 p-3 h-10 flex items-center justify-between">
            <h1>Perangkat Guru</h1>
        </div>
        <div class="content w-full bg-white p-3">
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
                                <div class="flex flex-wrap gap-1 p-1 justify-center">
                                    <button
                                        class=" bg-sky-400 px-1 rounded-sm hover:bg-sky-600 text-white hidden md:block"
                                    >
                                        Kaldik
                                    </button>
                                    <button
                                        class=" bg-sky-400 px-1 rounded-sm hover:bg-sky-600 text-white"
                                    >
                                        Jadwal
                                    </button>
                                    <button
                                        class=" bg-sky-400 px-1 rounded-sm hover:bg-sky-600 text-white hidden md:block"
                                    >
                                        AHE
                                    </button>
                                    <button
                                        class=" bg-sky-400 px-1 rounded-sm hover:bg-sky-600 text-white hidden md:block"
                                    >
                                        RPE
                                    </button>
                                    <button
                                        class=" bg-sky-400 px-1 rounded-sm hover:bg-sky-600 text-white hidden md:block"
                                    >
                                        Prota
                                    </button>
                                    <button
                                        class=" bg-sky-400 px-1 rounded-sm hover:bg-sky-600 text-white hidden md:block"
                                    >
                                        Prosem
                                    </button>
                                    <button
                                        class=" bg-sky-400 px-1 rounded-sm hover:bg-sky-600 text-white"
                                    >
                                        Jurnal
                                    </button>
                                    <button
                                        class=" bg-sky-400 px-1 rounded-sm hover:bg-sky-600 text-white hidden md:block"
                                    >
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
                            <th class="border">Perangkat</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="(rombel, index) in items" :key="index"
                            class="odd:bg-slate-100"
                        >
                            <td class="border hidden md:table-cell px-2">{{ index+1 }}</td>
                            <td class="border px-2 text-center">{{ rombel.label }}</td>
                            <td class="border hidden md:table-cell px-2">{{ rombel.kurikulum }}</td>
                            <td class="border hidden md:table-cell px-2">{{ rombel.guru?.nama }}</td>
                            <td class="border hidden md:table-cell px-2 text-center">{{ rombel.siswas?.length }}</td>
                            <td class="border px-2 w-[75%] md:w-[40%]">
                                <div class="flex flex-wrap gap-1 p-1 justify-center">
                                    <button
                                        class=" bg-sky-400 px-1 rounded-sm hover:bg-sky-600 text-white hidden md:block"
                                    >
                                        Kaldik
                                    </button>
                                    <button
                                        class=" bg-sky-400 px-1 rounded-sm hover:bg-sky-600 text-white"
                                    >
                                        Jadwal
                                    </button>
                                    <button
                                        class=" bg-sky-400 px-1 rounded-sm hover:bg-sky-600 text-white hidden md:block"
                                    >
                                        AHE
                                    </button>
                                    <button
                                        class=" bg-sky-400 px-1 rounded-sm hover:bg-sky-600 text-white hidden md:block"
                                    >
                                        RPE
                                    </button>
                                    <button
                                        class=" bg-sky-400 px-1 rounded-sm hover:bg-sky-600 text-white hidden md:block"
                                    >
                                        Prota
                                    </button>
                                    <button
                                        class=" bg-sky-400 px-1 rounded-sm hover:bg-sky-600 text-white hidden md:block"
                                    >
                                        Prosem
                                    </button>
                                    <button
                                        class=" bg-sky-400 px-1 rounded-sm hover:bg-sky-600 text-white"
                                    >
                                        Jurnal
                                    </button>
                                    <button
                                        class=" bg-sky-400 px-1 rounded-sm hover:bg-sky-600 text-white hidden md:block"
                                    >
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
</template>