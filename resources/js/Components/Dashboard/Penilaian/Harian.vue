<script setup>
import { ref, computed, defineAsyncComponent } from 'vue';
import { Icon } from '@iconify/vue';

const props = defineProps({ rombel: Object, mapel: String })



const headers = ref([
    { text: 'No', value: 'no'},
    { text: 'Kode', value: 'kode'},
    { text: 'Rombel', value: 'rombel'},
    { text: 'Mapel', value: 'mapel'},
    { text: 'Semester', value: 'semester'},
    { text: 'Periode', value: 'periode'},
    { text: 'Opsi', value: 'opsi'},
]);

const items = ref([])
</script>

<template>
    <div class="wrapper w-full ">
        <div class="toolbar flex items-center justify-between mb-2">
            <div></div>
            <div class="toolbar-items">
                <button class="flex items-center text-sm uppercase bg-sky-400 text-white py-1 px-3 rounded shadow-lg">
                    <Icon icon="mdi:plus-circle" />
                    Tambah
                </button>
            </div>
        </div>
        <DataTable :headers="headers" :items="items">

        </DataTable>
        
    </div>
</template>