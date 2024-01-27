<script setup>
import { ref, defineAsyncComponent, computed, onBeforeMount } from 'vue';
import { usePage, Head } from '@inertiajs/vue3';
import axios from 'axios';
import { Icon } from '@iconify/vue';
import QrcodeVue from 'qrcode.vue';

import {terbilang} from '@/Plugins/terbilang.js'

const page = usePage();
const sekolah = computed(() => page.props.sekolah)
const anggarans = page.props.anggarans;
const kosong = ref(false)
const anggaran = ref({})
const changeAnggaran = (e) => {
    anggaran.value = anggarans[e.target.value]
    listBkus()
}

const Kop = defineAsyncComponent(() => import('@/Components/General/Kop.vue'))

onBeforeMount(() => {
    anggaran.value = anggarans[0]
    listBkus()
});

const listBkus = async() => {
    await axios.post(route('dashboard.bos.bku.index', {_query: {bulan: bulan.value, anggaran_id: anggaran.value.kode}})).then(res => {
        bkus.value = res.data.bkus
    }).catch(err => console.log(err));
}

const mode = ref('kuitansi');

// const bulans = ref(['Januari', ''])
const bulan = ref('all');
const bkus = ref([]);
const currentTransaction = ref(1)
const kuitansis = computed(() => {
    if(currentTransaction.value =='all') {
        return bkus.value
    } else {
        return [bkus.value[parseInt(currentTransaction.value)]]
    }
})

const check = (bukti) => {
    const label = bukti.label.split(".")
    return {type: label.slice(-1)[0] , url: window.location.origin+bukti.url}
}

const colsBukti = ref('3')

</script>

<template>
    <div>
        <Head>
            <title>Kuitansi {{ anggaran.sumber_dana }} {{anggaran.tahun_anggaran}}</title>
        </Head>
        <div>
            <div class="w-full h-10 px-2 bg-white flex items-center justify-between mb-4 print:mb-0 sticky top-10 print:hidden z-10">
                <div class="flex gap-2 items-center h-full">
                    <select @change="changeAnggaran" class="h-8 text-sm py-1" >
                        <option v-for="(anggaran, index) in anggarans" :key="anggaran.kode" :value="index">
                            {{ anggaran.uraian }}
                        </option>
                    </select>
                    <select name="bulan" id="bulan" v-model="bulan" class="h-8 text-sm py-1" @change="listBkus">
                        <option value="all">Semua Bulan</option>
                        <option 
                            v-for="bulan in 12"
                            :value="bulan">
                            {{ bulan }}
                        </option>
                    </select>
                </div>
                <div v-if="bkus.length < 1">
                    <h1>Tidak ada transaksi di anggaran {{ anggaran.uraian }}</h1>
                </div>
                <div class="flex items-center justify-end gap-2 h-full" v-else>
                    <el-button type="primary" @click="kosong = !kosong">{{ kosong ? 'Tampilkan Foto' : 'Sembunyikan Foto' }}</el-button>
                    <label class="flex items-center gap-2">
                    <select v-model="mode" class="h-8 py-1 text-sm">
                        <option value="kuitansi">Kuitansi</option>
                        <option value="bukti">Bukti</option>
                    </select>
                    </label>
                    <button @click="currentTransaction--" :disabled="currentTransaction == 0">
                        <Icon icon="mdi:chevron-double-left" class="hover:text-sky-600"  />
                    </button>
                    <button @click="currentTransaction++" :disabled="currentTransaction >= (bkus.length-1)">
                        <Icon icon="mdi:chevron-double-right" class="hover:text-sky-600" />
                    </button>
                    <select name="transaksi" id="transaksi" v-model="currentTransaction" class="h-8 text-sm py-1 w-[200px] overflow-hidden text-ellipsis truncate">
                        <option value="all">Semua Transaksi</option>
                        <option 
                            class="w-[100px] overflow-hidden truncate text-ellipsis whitespace-break-spaces"
                            v-for="(bku,b) in bkus"
                            :value="b">
                            {{bku.no_bukti+'. '+ bku.uraian.substr(0,100) }}
                        </option>
                    </select>
                </div>
            </div>
            <div
                v-for="(bku, bk) in kuitansis" :key="bk" 
                class=" w-3/4 mx-auto shadow-md p-4 print:p-0 print:shadow-none print:w-full print:h-[33cm] bg-slate-100 print:bg-white break-inside-avoid-page break-after-all my-4 print:m-0 cetak lembar">
                <div class="header w-full">
                    <Kop v-if="mode == 'kuitansi'" />
                </div>
                <div class="meta w-3/4 print:w-full mx-auto grid grid-cols-5 mt-2">
                    <div class="col-span-2">
                        <table>
                            <tr>
                                <td class="leading-4">Mata Anggaran</td>
                                <td class="leading-4">:</td>
                                <td class="leading-4">{{ anggaran.uraian }}</td>
                            </tr>
                            <tr>
                                <td class="leading-4">Tahun Anggaran</td>
                                <td class="leading-4">:</td>
                                <td class="leading-4"> Tahap {{ anggaran.tahap }} Tahun {{ anggaran.tahun_anggaran }}</td>
                            </tr>
                            <tr>
                                <td class="leading-4">Sumber Dana</td>
                                <td class="leading-4">:</td>
                                <td class="leading-4">{{ anggaran.uraian.includes('Daerah') ? 'APBD' : 'APBN' }}</td>
                            </tr>
                        </table>
                    </div>
                    <div></div>
                    <div class="col-span-2">
                        <table>
                            <tr>
                                <td class="leading-4">No Bukti</td>
                                <td class="leading-4">:</td>
                                <td class="leading-4 font-bold">{{ bku?.no_bukti }}</td>
                            </tr>
                            <tr>
                                <td class="leading-4">Tanggal</td>
                                <td class="leading-4">:</td>
                                <td class="leading-4">{{ new Date(bku?.tanggal).toLocaleString("id-ID", {day: "numeric", month: "short", year: "numeric"}) }}</td>
                            </tr>
                        </table>
                    </div>

                </div>
                <div class="content w-full py-2 border-b-2 border-b-gray-600 border-b-dashed" v-if="mode=='kuitansi'">
                    <h1 class="text-center font-black text-2xl underline">KUITANSI</h1>
                    <table class="mx-auto">
                        <tr>
                            <td class="border py-1 px-3">Kode Rekening: {{ bku?.kode_rekening }}</td>
                            <td class="border py-1 px-3">Kode Kegiatan: {{ bku?.kode_kegiatan }}</td>
                        </tr>
                    </table>
                    <table class="mx-auto my-2 table-detail">
                        <tr>
                            <td class="border border-black py-1 px-3">Sudah diterima dari</td>
                            <td class="border border-black py-1 px-3">:</td>
                            <td class="border border-black py-1 px-3">Bendahara BOS Reguler {{ sekolah?.nama }}</td>
                        </tr>
                        <tr>
                            <td class="border border-black py-1 px-3">Uang Sebesar</td>
                            <td class="border border-black py-1 px-3">:</td>
                            <td class="border border-black py-1 px-3">
                                <span class="font-bold italic font-serif text-gray-800">
                                    {{ terbilang(bku?.nilai) }} Rupiah
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td class="border border-black py-1 px-3">Untuk Keperluan</td>
                            <td class="border border-black py-1 px-3">:</td>
                            <td class="border border-black py-1 px-3">{{ bku?.uraian }}</td>
                        </tr>
                    </table>
                    <h1 class="font-bold text-lg text-slate-800 font-serif text-center">Bukti / Berkas Terlampir</h1>
                </div>
                <div class="w-2/4 mx-auto my-4" v-if="mode=='kuitansi'">
                    <h1 class="font-mono text-sm font-bold border-dashed border-b-gray-400 border-b-2 border-l-0 border-t-0 border-r-0">Terbilang</h1>
                    <h1 class="terbilang border-2 border-black my-4 p-2 text-center bg-gray-100 font-black text-2xl">Rp. {{ bku?.nilai.toLocaleString("id-ID") }},-</h1>
                </div>
                <div class="w-full my-2 grid grid-cols-3" v-if="mode=='kuitansi'">
                    <div class="ks">
                        <p class="text-center">Menyetujui,</p>
                        <p class="text-center">Kepala Sekolah</p>

                        <h3 class="underline font-black tracking-widest text-center mt-20">{{ sekolah?.ks.nama }}</h3>
                        <p class="text-center leading-3">NIP. {{ sekolah?.ks.nip }}</p>
                    </div>
                    <div class="bendahara">
                        <p class="text-center">&nbsp;</p>
                        <p class="text-center">Bendahara</p>

                        <h3 class="underline font-black tracking-widest text-center mt-20">{{ sekolah?.bendahara.nama }}</h3>
                        <p class="text-center leading-3">NIP. {{ sekolah?.bendahara.nip }}</p>
                    </div>
                    <div class="penerima">
                        <p class="text-center">Malang, ..........................................</p>
                        <p class="text-center">yang Menerima</p>

                        <h3 class="underline font-black tracking-widest text-center mt-20">...........................................</h3>
                        <p class="text-center leading-3 print:hidden">NIP. <span class="text-white ">{{ sekolah?.ks.nip }}</span></p>
                    </div>
                </div>
                <h1 class="text-2xl text-gray-200 mt-10 text-center" v-if="mode=='kuitansi'">Tempelkan Bukti Di bawah atau di belakang</h1>
                
                <!-- <div class="buktis w-full grid grid-cols-2 gap-2" v-if="bku" > -->
                    <!-- <div v-for="(bukti, bkt) in bku.buktis" :key="bkt"> -->
                        <!-- <object v-if="check(bukti) == 'pdf'" :data="bukti.url" class="w-full" width="100%" height="100%"></object> -->
                        <!-- <figure v-if="check(bukti).type !== 'pdf'"> -->
                            <!-- <img :src="bukti.url"  /> -->
                        <!-- </figure> -->
                        
                    <!-- </div> -->
                    
                <!-- </div> -->
                <div class="bukti mt-6" v-if="mode == 'bukti'">
                <h1 class="text-center w-[80%] mx-auto">Bukti Transaksi: "<span class="font-semibold text-slate-800">{{bku.uraian}}</span>" {{ kosong }}</h1>
                <hr class="border-1 border-slate-700 my-3 w-[60%] mx-auto ">
                <div v-if="bku.buktis.length > 0 && !kosong" class="flex flex-wrap gap-2 justify-center">
                    <div v-for="(bukti, b) in bku.buktis" :key="b" class="w-full flex basis-[25%]">
                        <figure v-if="bukti.tipe == 'foto'" class="w-full border-2 p-2 ">
                            <img :src="bukti.url" class="w-full" />
                            <figcaption class="text-center">{{bukti.label}}</figcaption>
                            <!-- <label class="print:hidden">
                                Lebar:
                                <select v-model="colsBukti">
                                    <option value="3">Penuh</option>
                                    <option value="2">Setengah</option>
                                    <option value="1">Sepertiga</option>
                                </select>
                            </label> -->
                        </figure>

                        <div v-else class="bg-gray-50 p-2 border-2">
                            Bukti berjenis Dokumen. Link unduh: 
                            <p class="flex gap-2">
                                <qrcode-vue :value="check(bukti).url" :size="100" level="L" :foreground="'#363636'" />
                                <a :href="check(bukti).url" target="_blank" class="text-sky-600">{{check(bukti).url}}</a>
                            </p>
                        </div>
                    </div>
                </div>

                </div>
            </div>
        </div>
    </div>
</template>
