<script setup>
import { router } from '@inertiajs/vue3';
import { ref, onBeforeMount, defineAsyncComponent } from 'vue';
import 'jodit/build/jodit.min.css'
// import { JoditEditor } from 'jodit-vue'
import { Icon } from '@iconify/vue';
import axios from 'axios';

const Kop = defineAsyncComponent(() => import('@/Components/General/Kop.vue'))

const emit = defineEmits(['close'])

const content =ref('Tulis isi')

const surat = ref({

})

const close = () => emit('close')
</script>

<template>
 <div class="w-full">
    <div class="toolbar h-10 flex items-center justify-between bg-slate-400 p-3 print:hidden">
        <h1 class="text-white">Tulis Surat</h1>
        <div class="toolbar-items flex gap-2 justify-end">
            <button @click="close">
                <Icon icon="mdi:close-box" class="text-slate-200 hover:text-slate-600 text-2xl" />
            </button>
        </div>
    </div>
    <div class="sheet w-10/12 mx-auto my-4 print:mt-0 print:w-full bg-white shadow print:shadow-none">
        <Kop />
        <div class="meta">
            <table>
                <tr>
                    <td>No</td>
                    <td>:</td>
                    <td></td>
                </tr>
                <tr>
                    <td>Lampiran</td>
                    <td>:</td>
                    <td></td>
                </tr>
                <tr>
                    <td>Perihal</td>
                    <td>:</td>
                    <td></td>
                </tr>
            </table>
        </div>
        <div class="tujuan">
            <p>Kepada</p>
        </div>
    </div>
    <!-- <JoditEditor v-model="content" ref="jodit" /> -->
</div>

</template>