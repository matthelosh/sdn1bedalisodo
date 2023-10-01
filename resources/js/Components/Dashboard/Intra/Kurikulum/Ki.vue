<script setup>
import { defineAsyncComponent, ref, computed, onBeforeMount } from 'vue';
import { usePage } from '@inertiajs/vue3';

const page = usePage();
const props = defineProps({ rombel: Object, mapel: String });

// onBeforeMount(() => {
//     rombels.value = page.props.rombels.filter
// })

const rombels = computed(() => page.props.rombels.filter(rombel => rombel.kurikulum == 'K-13'))

const FormCp = defineAsyncComponent(() => import('./Forms/FormCp.vue'));
const showForm = ref(false)
const selectedRombel = ref(null)
const selectedMapel = ref(null)
const openForm = (rombel) => {
    showForm.value = true
    selectedRombel.value = rombel
    selectedMapel.value = props.mapel
}

const closeForm = () => {
    showForm.value = false
    selectedRombel.value = null
}




</script>

<template>
    <div class="w-ful bg-white shadow">
        <div class="toolbar p-2 bg-slate-400 text-white font-bold">
            <h1>Kompetensi Inti </h1>
        </div>
        <div class="content bg-white">
            <ul>
                <li
                    v-for="rombel in rombels" :key="rombel.id"
                    class="px-2 py-3 hover:bg-slate-100 flex items-center gap-2 justify-between"
                >
                    {{ rombel.label }}
                    <button class="py-1 px-3 text-white rounded shadow hover:shadow-lg hover:bg-sky-500 active:bg-sky-400  bg-sky-600"
                        @click="openForm(rombel)"
                    >
                        KI
                    </button>
                </li>
            </ul>
        </div>
    </div>
    <FormCp v-if="showForm" @close="closeForm" :rombel="selectedRombel" :mapel="selectedMapel" />
</template>