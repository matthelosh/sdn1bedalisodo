<script setup>
import { defineAsyncComponent, ref, computed, onBeforeMount } from 'vue';
import { usePage } from '@inertiajs/vue3';

const page = usePage();
const props = defineProps({ rombel: Object, mapel: String });

// onBeforeMount(() => {
//     rombels.value = page.props.rombels.filter
// })

const rombels = computed(() => page.props.rombels)

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
            <h1>Kurikulum {{ props.mapel.toUpperCase(0) }} </h1>
        </div>
        <div class="content bg-white">
            <ul>
                <li
                    v-for="(rombel, i) in rombels" :key="rombel.id"
                    class="px-2 py-3 my-2 flex items-center gap-2 justify-between"
                    :class="i <= (rombels.length-1) ? 'border-b border-dashed' : ''"
                >
                    {{ rombel.label }}
                    <div class="opsi flex items-center gap-2">
                        <button 
                            class="py-1 px-3 text-white rounded shadow-lg hover:shadow hover:bg-sky-500 active:bg-sky-400  bg-sky-600"
                            @click="openForm(rombel)"
                        >
                            {{ rombel.kurikulum == 'K-13' ? 'KI' : 'CP' }}
                        </button>
                        <button 
                            class="py-1 px-3 text-white rounded shadow-lg hover:shadow hover:bg-sky-500 active:bg-sky-400  bg-sky-600"
                        >
                            {{ rombel.kurikulum == 'K-13' ? 'KD' : 'TP' }}
                        </button>
                        <button 
                            class="py-1 px-3 text-white rounded shadow-lg hover:shadow hover:bg-sky-500 active:bg-sky-400  bg-sky-600"
                        >
                            {{ rombel.kurikulum == 'K-13' ? 'Silabus' : 'ATP' }}
                        </button>
                    </div>
                </li>
            </ul>
        </div>
    </div>
    <FormCp v-if="showForm" @close="closeForm" :rombel="selectedRombel" :mapel="selectedMapel" />
</template>