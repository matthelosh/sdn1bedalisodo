<script setup>
import { reactive, ref, watch, onBeforeMount } from 'vue';
import { Icon } from '@iconify/vue';
import { usePage } from '@inertiajs/vue3';
import axios from 'axios';

const page = usePage()
const props = defineProps({selectedRombel: Object})
onBeforeMount(() => {
    if(props.selectedRombel) {
        rombel.value = props.selectedRombel
        getGurus()
    }
    // console.log(props.selectedRombel)
    listgrupWa()
})



const emit = defineEmits(['close'])

const loading = ref(false)

const grupWas = ref([])
const listgrupWa = async() => {
    await axios.post(route('dashboard.wa.grup.list'))
                .then(res => {
                    grupWas.value = res.data.grups
                })
}

const rombel = ref({
    tingkat: '0',
    pararel: '0'
})

watch(
    () => rombel, 
    (newV, oldV) => {
        rombel.value.pararel = newV.value.pararel ? newV.value.pararel : (/[a-zA-Z]/gi.test(newV.value.kode.substr(-1)) ? newV.value.kode.substr(-1) : '0')
        rombel.value.kode = page.props.tapel.kode+'-'+ newV.value.tingkat+((newV.value.pararel == '0' || !newV.value.pararel) ? '' : newV.value.pararel.toLocaleLowerCase())
        rombel.value.label = 'Kelas '+ romawi(newV.value.tingkat) + ((rombel.value.pararel == '0') ? '' : rombel.value.pararel)
        rombel.value.tapel = page.props.tapel.kode
        if(newV.value.kurikulum && gurus.value.length < 1) { getGurus() }
    },
    { deep: true }
)


const gurus = ref([])
const getGurus = async() => {
    await axios.post(route('dashboard.guru.index'))
                .then(res => {
                    gurus.value = res.data.gurus
                }).catch(err => {
                    console.log(err)
                })
}

const simpan = async() => {
    loading.value = true
    await axios.post(route('dashboard.rombel.store'), {rombel: JSON.stringify(rombel)})
                .then(res => {
                    loading.value = false
                    emit('close')
                }).catch(err => {
                    loading.value = false
                    console.log(err)
                })
}

const romawi = (num) => {
    let roman = {
        M: 1000,
        CM: 900,
        D: 500,
        CD: 400,
        C: 100,
        XC: 90,
        L: 50,
        XL: 40,
        X: 10,
        IX: 9,
        V: 5,
        IV: 4,
        I: 1
    }

    let str = ''
    for (var i of Object.keys(roman)) {
        var q = Math.floor(num / roman[i]);
        num -= q * roman[i];
        str += i.repeat(q);
    }

    return str;
}
</script>

<template>
    <div class="w-full bg-slate-100 bg-opacity-20 backdrop-blur-sm fixed top-0 right-0 bottom-0 left-0 flex items-center justify-center z-40" @click.self="close">
        <div class="dialog bg-white rounded shadow w-[500px] max-w-[800px]">
            <div class="toolbar h-10 flex items-center justify-between w-full p-2 bg-slate-200">
                <h1>Data Rombel {{ rombel?.label }}</h1>
                <div class="toolbar-items flex items-center gap-2 justify-end">
                    <button @click="emit('close')">
                        <Icon icon="mdi:close-box" class="text-2xl text-red-600 hover:text-red-400 active:text-red-800" />
                    </button>
                </div>
            </div>
            <div class="content p-2">
                <form ref="formGuru" @submit.prevent="simpan">
                    <fieldset :disabled="loading">
                        <label for="Label" class="w-full flex items-center  justify-between gap-2">
                            Kelas
                            <div class="w-[60%] flex gap-1">
                                <!-- <input type="text" class="bg-slate-200 border-0 rounded py-1 w-full" placeholder="Depan"> -->
                                <select class="bg-slate-200 border-0 rounded py-1 w-[60%]" v-model="rombel.tingkat" required>
                                    <option value="0">Tingkat</option>
                                    <option 
                                        v-for="tk in 6"
                                        :value="tk" :key="tk">{{ tk }}</option> 
                                </select>
                                <select class="bg-slate-200 border-0 rounded py-1 w-[60%]" v-model="rombel.pararel">
                                    <option value="0">Pararel</option>
                                    <option 
                                        v-for="pr in ['0','A','B','C']"
                                        :value="pr" :key="pr">{{ pr }}</option> 
                                </select>
                            </div>
                        </label>
                        <label for="kode" class="w-full flex items-center  justify-between">
                            Kode Rombel
                            <input type="text" class="bg-slate-200 border-0 rounded py-1 w-[60%]" placeholder="Kode Rombel" readonly v-model="rombel.kode">
                        </label>
                        <label for="label" class="w-full flex items-center  justify-between">
                            Label Rombel
                            <input type="text" class="bg-slate-200 border-0 rounded py-1 w-[60%]" placeholder="Label Rombel" readonly v-model="rombel.label">
                        </label>
                        <label for="kurikulum" class="w-full flex items-center  justify-between ">
                            Kurikulum
                            <select class="bg-slate-200 border-0 rounded py-1 w-[60%]" v-model="rombel.kurikulum"> 
                                <option
                                    v-for="kurikulum in ['Merdeka', 'K-13']" :key="kurikulum"
                                :value="kurikulum" :selected="kurikulum == rombel.kurikulum">{{ kurikulum }}</option>    
                            </select>
                        </label>
                        <label for="guru_id" class="w-full flex items-center  justify-between" >
                            Wali Kelas
                            <select class="bg-slate-200 border-0 rounded py-1 w-[60%]" v-model="rombel.guru_id" :disabled="gurus.length < 1">
                                <option 
                                    v-for="guru in gurus" :key="guru.id"
                                    :value="guru.nip">{{ guru.nama }}</option>    
                            </select>
                        </label>
                        <label for="grup" class="w-full flex items-center  justify-between" >
                            Grup WA
                            <select class="bg-slate-200 border-0 rounded py-1 w-[60%]" v-model="rombel.grupWa" :disabled="grupWas.length < 1">
                                <option 
                                    v-for="grup in grupWas" :key="grup.id"
                                    :value="grup.chat_id">{{ grup.label }}</option>    
                            </select>
                        </label>
                        <div class="flex items-center justify-center py-2">
                            <button class="py-1 px-3 rounded bg-slate-800 text-white hover:bg-slate-400 flex items-center" type="submit">
                                <Icon icon="mdi:refresh" class="animate-spin text-xl text-sky-400" v-if="loading" />
                                Simpan
                            </button>
                        </div>
                    </fieldset>
                </form>
            </div>
            <!-- {{ rombel }} -->
        </div>
    </div>
</template>

<style scoped>
label {
    border-bottom: 1px solid #dbd9d9;
    padding: .3rem 0;
}
</style>