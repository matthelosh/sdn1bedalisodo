<script setup>
import { ref, onBeforeMount } from 'vue';
import { Icon } from '@iconify/vue';
import { usePage } from '@inertiajs/vue3';
import axios from 'axios';

const page = usePage();
const emit = defineEmits(['close']);
const props = defineProps({
    rombel: Object, mapel: String, selectedMateri: Object
})

const materi = ref({

});

const simpan = async() => {
    let data = materi.value
    data.mapel_id = props.mapel
    data.tingkat = props.rombel.tingkat
    await axios.post(route('dashboard.materi.store'), data)
                .then(res => {
                    emit('close')
                })
                .catch(err => {
                    console.log(err)
                })
}

onBeforeMount(() => {
    if(props.selectedMateri !== null) materi.value = props.selectedMateri
})

</script>

<template>
    <div class="w-full bg-slate-100 bg-opacity-20 backdrop-blur-sm fixed top-0 right-0 bottom-0 left-0 flex items-center justify-center z-40" @click.self="close">
        <div class="dialog bg-white rounded shadow-lg w-full md:w-1/3">
            <div class="toolbar h-10 flex items-center justify-between w-full p-2 bg-slate-400 text-white">
                <h1>Materi Pembelajaran {{ mapel.toUpperCase() }} Kelas {{ rombel.label }}</h1>
                <div class="toolbar-items flex items-center gap-2 justify-end">
                    <button @click="emit('close')">
                        <Icon icon="mdi:close-box" class="text-2xl text-red-600 hover:text-red-500 active:text-red-400" />
                    </button>
                </div>
            </div>
            <div class="content p-2">
                <form ref="formMateri" @submit.prevent="simpan">
                    <div class="row w-full my-2">
                        <label for="bab">
                            Bab Pembelajaran
                        </label>
                        <textarea placeholder="Masukkan Bab Pembelajaran" v-model="materi.bab" class="border-none bg-slate-50 w-full" />
                    </div>
                    <div class="row w-full my-2">
                        <label for="pokok">
                            Materi Pokok
                        </label>
                        <textarea placeholder="Materi Pokok" v-model="materi.pokok" class="border-none bg-slate-50 w-full" />
                    </div>
                    <div class="row w-full my-2">
                        <label for="ringkasan">
                            Ringkasan Materi
                        </label>
                        <textarea placeholder="Ringkasan Materi" v-model="materi.ringkasan" class="border-none bg-slate-50 w-full" />
                    </div>
                    <div class="row w-full my-2 flex items-center justify-center">
                        <button class="bg-sky-400 hover:bg-sky-500 active:bg-sky-200 text-white py-2 px-3 rounded  shadow-lg hover:shadow-none transition-all">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>