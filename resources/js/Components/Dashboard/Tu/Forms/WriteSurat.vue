<script setup>
import { router } from '@inertiajs/vue3';
import { ref, onBeforeMount, defineAsyncComponent, computed } from 'vue';
import { vOnClickOutside } from '@vueuse/components'
import { Icon } from '@iconify/vue';
import axios from 'axios';
import { watch } from 'vue';

onBeforeMount(() => {
    listKlasifikasis()
})

const Sppd = defineAsyncComponent(() => import('./templates/Sppd.vue'))

const template = ref('0')

const temp = computed(() => surat.value.tipe)
const is = computed(() => import(`./templates/${template.value}.vue`))

const Kop = defineAsyncComponent(() => import('@/Components/General/Kop.vue'))

const emit = defineEmits(['close'])

const klasifikasis = ref([])
const listKlasifikasis = async(q=null) => {
    await axios.post(route('dashboard.tu.klasifikasi_surat.index', {_query: {q:q}}))
        .then(res => {
            klasifikasis.value =  res.data.klasifikasis
        }).catch(err => console.log(err))
}

const queryKlasifikasi = ref('')
const filteredKlasifikasi = computed(() => {
    return klasifikasis.value.filter( item => item.uraian.toLowerCase().includes(queryKlasifikasi.value.toLowerCase()))
})
const showKlasi = async(e) => {
    const listKlasifikasis = document.querySelector(".list-klasifikasi")
    listKlasifikasis.classList.remove("hidden")
}
const closeKlasifikasi = () => {
    const listKlasifikasis = document.querySelector(".list-klasifikasi")
    listKlasifikasis.classList.add("hidden")
}

const content =ref('Tulis isi')
const sifats = ref(['Sangat Rahasia', 'Rahasia', 'Terbatas', 'Biasa']);
const tipes = ref([
    {value: '0', label: 'Pilih template'}, 
    {value: 'sk', label:'Keputusan'}, 
    {value: 'sp', label: 'Perintah'}, 
    {value: 'Sppd', label: 'Perjalanan Dinas'}, 
    {value: 'su', label: 'Undangan'}, 
    {value: 'info', label:'Pemberitahuan'}
])

const surat = ref({
    sifat: 'Biasa',
    tipe: '0'
})

const tes = () => {
    // alert(surat.value.tipe)
}

const close = () => emit('close')

const pickTemplate = (e) => {
    // alert(e.target.value)
    template.value = e.target.value
}

const setKlasifikasi = (e) => {
    surat.value.klasifikasi_id = e.target.getAttribute("data-kode")
    queryKlasifikasi.value = e.target.getAttribute("data-label")
    closeKlasifikasi()
}
</script>

<template>
 <div class="w-full">
    <div class="toolbar h-10 flex items-center justify-between bg-slate-400 p-3 print:hidden">
        <h1 class="text-white">Tulis Surat</h1>
        <div class="toolbar-items flex gap-2 justify-end">
            <div class="klasifikasi relative" v-on-click-outside="closeKlasifikasi">
                <input type="text" placeholder="Klasifikasi" class="border-0 bg-slate-200 py-1 rounded" @focus="showKlasi" v-model="queryKlasifikasi" />
                <ul class="absolute hidden bg-white p-2 max-h-[400px] overflow-auto list-klasifikasi" >
                    <li v-for="(klasi, k) in filteredKlasifikasi" :key="k" class="my-1 cursor-pointer hover:bg-slate-200" @click.self="setKlasifikasi" :data-kode="klasi.kode" :data-label="klasi.uraian">
                        <button :data-kode="klasi.kode" class="text-left block" @click="setKlasifikasi" :data-label="klasi.uraian">{{ klasi.uraian }}</button>
                    </li>
                </ul>
            </div>
            <select v-model="surat.sifat" class="py-1 bg-slate-50 border-0 rounded">
                <option v-for="(sifat, s) in sifats" :key="s" :value="sifat">{{ sifat }}</option>
            </select>
            <select class="py-1 bg-slate-50 border-0 rounded" v-model="surat.tipe" @change="pickTemplate">
                <option v-for="(tipe, t) in tipes" :key="t" :value="tipe.value">{{ tipe.label }}</option>
            </select>
            <button @click="close">
                <Icon icon="mdi:close-box" class="text-slate-200 hover:text-slate-600 text-2xl" />
            </button>
        </div>
    </div>
    <div class="sheet w-10/12 mx-auto my-4 p-4 print:p-0 print:mt-0 print:w-full  print:shadow-none" v-if="surat.tipe !== 'Pilih template'">
        <component :is="is">
            Tes Komponen
        </component>
    </div>
    <div v-else-if="surat.tipe == '0'" class="w-full h-[50vh] flex items-center justify-center">
        <h1 class="text-slate-600 text-xl">Pilih Sifat dan Template Surat</h1>
    </div>
</div>

</template>