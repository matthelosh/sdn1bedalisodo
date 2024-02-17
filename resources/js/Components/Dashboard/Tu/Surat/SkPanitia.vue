<script setup>
import { ref, computed, defineAsyncComponent, onBeforeMount } from 'vue';
import { usePage } from '@inertiajs/vue3'
import { Icon } from '@iconify/vue'
import {terbilang} from '@/Plugins/terbilang.js'
import {ElNotification} from 'element-plus'
import dayjs from 'dayjs';
import localeData from 'dayjs/plugin/localeData'
import 'dayjs/locale/id'
dayjs.extend(localeData)
dayjs().localeData()
dayjs.locale("id");

const page = usePage();
const props = defineProps({selectedSurat: Object, ptks: Array, legals: Array})
const emit = defineEmits(['close'])
const sekolah = computed(() => page.props.sekolah)
const Kop = defineAsyncComponent(() => import('@/Components/General/Kop.vue'));
const loading = ref(false)
const tanggal = ref('2024-02-14')
const lastNumber = ref(null)
const surat = ref({
		kode: computed(() => `800/${lastNumber.value}/35.07.101.408.012/${tanggal.value.substring(0,4)}`),
		klasifikasi_id: '800',
		no_surat: computed(() =>lastNumber.value),
		kategori: 'SkOperator',
		perihal: 'Operator Data Pokok Pendidikan Dasar (Dapodik)',
		penerima: [],
		tanggal: computed(()=>tanggal.value),
		tembusan: [],
		status: 'arsip'
})

const kegiatans = ref([

])

const getKegiatan = async() => {
    await axios.post(route('surat.kegiatan'))
                .then(res => kegiatans.value = res.data.kegiatans)
}
const cardinals = ref([
    '','KESATU','KEDUA','KETIGA','KEEMPAT','KELIMA','KEENAM','KETUJUH','KEDELAPAN', 'KESEMBILAN','KESEPULUH'
])



const getLastNumber =  async() => {
	await axios.get(route('surat.last', { _query: {tahun: tanggal.value.substring(0,4)}}))
				.then(res => {
					if (res.data.latest == null) {
						lastNumber.value = '001'
					} else if (res.data.latest) {
						let no = parseInt(res.data.latest)
						no+=1
                        no = no.toString()
						lastNumber.value = (no.length > 2) ? no : ((no.length == 2 )? ('0'+no) : ('00'+no))
                        
                        }
				})
}

const onNamaChanged = (e) => {
	surat.value.penerima = e.target.innerText
}

const simpan = async() => {
	loading.value = true
    let data = surat.value
    // data.perihal = data.perihal.perihal
    // data.tembusan = data.tembusan.join(",")
    // data.penerima = data.penerima.reduce((a,c) => a += (c.nama+", "), '')
	axios.post(route('surat.store', {_query: {klasifikasi:'800'}}), {data: JSON.stringify(data)})
			.then(res => {
				ElNotification({title: 'Info', message: 'Surat disimpan'})
				loading.value = false
                // emit('close')
			}).catch(err => {
				loading.value = false
				console.log(err)
			})
}

const jabatan = (role) => {
    switch(role) {
        case 'ks':
            return 'Kepala Sekolah';
            break;
        case 'gpai':
            return 'Guru PAI';
            break;
        case 'gor':
            return 'Guru PJOK';
            break;
        case 'gen':
            return 'Guru B. Inggris';
            break;
        case 'pjg':
            return 'Penjaga Sekolah';
            break;
        default:
            return 'Guru Kelas'
            break;
    }
}
onBeforeMount(() => {
	if (props.selectedSurat !== null) {
		lastNumber.value = props.selectedSurat.no_surat
		tanggal.value = props.selectedSurat.tanggal
		surat.value.perihal = props.selectedSurat.perihal
		surat.value.penerima = props.selectedSurat.penerima
	} else {
		getLastNumber()

	}
    getKegiatan()
})
</script>

<template>
	<div class="wrapper">
		<div class="w-full flex justify-end items-center p-2 gap-2 sticky top-12 bg-slate-100">
			<el-form-item label="Nomor Surat">
					<el-input type="text" v-model="lastNumber" placeholder="Nomor Surat"></el-input>
				</el-form-item>
				<el-form-item label="Perihal">
					<el-select v-model="surat.perihal" placeholder="Perihal" value-key="id">
						<el-option v-for="kegiatan in kegiatans" :key="kegiatan.id" :value="kegiatan"> {{ kegiatan.perihal }}</el-option>
					</el-select>
				</el-form-item>
				<el-form-item label="Tanggal Surat">
					<el-date-picker v-model="tanggal" format="DD-MM-YYYY" value-format="YYYY-MM-DD" type="date"></el-date-picker>
					</el-form-item>
				<el-button-group>
					<el-button @click="simpan" :loading="loading">
						<Icon icon="mdi:hdd" />
					</el-button>
				</el-button-group>
		</div>
		<div class="cetak w-full bg-slate-300 print:bg-white py-4 print:p-0 font-serif">
			<div class="sheet w-full md:w-[900px] print:w-[100%] mx-auto p-6 print:p-0 bg-white">
				<Kop />
				<div class="meta text-center pt-4">
					<h3 class=" uppercase font-bold">Keputusan Kepala Sekolah</h3>
					<h3 class=" uppercase font-bold">{{ sekolah.nama }}</h3>
					<h4>Nomor: 800/{{lastNumber}}/35.07.101.408.012/{{ tanggal.substring(0,4) }}</h4>
					<h3 class="font-bold uppercase">Tentang</h3>
					<h3 class="font-bold uppercase mx-auto w-[75%] print:w-[90%]">Penetapan {{ surat.perihal.perihal }}  {{sekolah.nama}} Kecamatan {{ sekolah.kecamatan }} Kabupaten {{ sekolah.kabupaten }}</h3>
					<h3 class="font-bold uppercase mx-40">Tahun {{ surat.tanggal.substring(0,4) }}</h3>
				</div>

				<div class="content pt-4 w-[80%] print:w-[90%] mx-auto text-justify">
					<table>
						<tr>
							<td class="px-1 align-top">Menimbang</td>
							<td class="px-1 align-top">:</td>
							<td class="px-1 align-top pb-4 print:pb-1">
                                <ul class="pl-4 list-[lower-alpha]">
                                    <li v-for="timbang in (surat.perihal.timbangs?.split(';'))" :key="timbang">
                                        <p class="text-justify mb-1 leading-4" v-html="timbang"></p>
                                    </li>
                                </ul>
							</td>
						</tr>
						<tr>
							<td class="px-1 align-top">Mengingat</td>
							<td class="px-1 align-top">:</td>
							<td class="px-1 align-top">
								<ul class="pl-4 list-decimal mb-4">
									<li v-for="legal in surat.perihal.ingats?.split(';')" :key="legal" class="leading-4 "  >
                                        {{legal}}
                                    </li>
                                    
								</ul>
							</td>
						</tr>
						<tr v-if="surat.perihal.notices">
							<td class="px-1 align-top">Memperhatikan</td>
							<td class="px-1 align-top">:</td>
							<td class="px-1 align-top">
                                <p v-if="surat.perihal.notices.length < 2">{{ surat.perihal.notices[0]?.split(';') }}</p>
                            </td>
						</tr>
						<tr>
							<td class="py-2 text-center font-bold" colspan="3">MEMUTUSKAN</td>
						</tr>
						<tr>
							<td class="px-1">Menetapkan</td>
							<td>:</td>
							<td></td>
						</tr>
						<tr v-for="(tetap,t) in surat.perihal.tetaps?.split(';')" :key="t">
							<td class="px-1 align-top uppercase">{{ cardinals[t+1] }}</td>
							<td class="px-1 align-top">:</td>
							<td class="px-1 align-top pb-2 leading-4" v-html="tetap">
                                
							</td>
						</tr>
					</table>
				</div>
				<div class="ttd mt-4  mx-auto print:w-full grid grid-cols-12 w-[80%] justify-around print:text-[.8rem]">
					<div class="text-center pt-8 col-span-8">
                        
                    </div>
					<div class="col-span-4">
						<table>
							<tr>
								<td class="align-top leading-4">Ditetapkan di</td>
								<td class="align-top leading-4">:</td>
								<td class="align-top leading-4">Dalisodo</td>
							</tr>
							<tr>
								<td class="align-top leading-4">Pada tanggal</td>
								<td class="align-top leading-4">:</td>
								<td class="align-top leading-4">{{dayjs(tanggal).format('DD MMM YYYY')}}</td>
							</tr>
							<tr>
								<td class="leading-4" colspan="3">
                                    <p>Kepala SD Negeri 1 Bedalisodo</p>
                                </td>
							</tr>
						</table>
						
						<p class="mt-16 underline font-bold leading-4">{{page.props.sekolah.ks.nama}}, {{page.props.sekolah.ks.gelar_belakang}}</p>
						<p class="leading-4">NIP. {{page.props.sekolah.ks.nip}}</p>
					</div>
				</div>
				<div class="tembusan font-serif mt-4 print:text-[.8rem]">
                    <el-form-item label="Tembusan" class="print:hidden w-[40%]">
                        <el-select placeholder="Tembusan" v-model="surat.tembusan" multiple collapse-tags collapse-tags-tooltip>
                            <el-option v-for="tembusan in [
                                'Dinas Pendidikan Kab. Malang',
                                'Korwil Dinas Pendidikan Kec. Wagir',
                                'Kemenag Kab. Malang',
                                'PGRI Kab. Malang',
                                'Yang bersangkutan',
                                'Arsip'
                            ]" :key="tembusan" :value="tembusan">{{ tembusan }}</el-option>
                        </el-select>
                    </el-form-item>
					<p>Tembusan Yth.</p>
					<ul class="pl-4">
						<li class="leading-4" v-for="(tembusan,t) in surat.tembusan" :key="(t+tembusan)" :value="tembusan">{{t+1}}. {{ tembusan }}</li>
					</ul>
				</div>
			</div>

            <div class="sheet w-full md:w-[900px] print:w-[100%] mx-auto p-6 print:p-0 bg-white mt-4">
                <div class="ttd mt-4  mx-auto print:w-full grid grid-cols-12 w-[90%] justify-around print:text-[.8rem]">
					<div class="text-center pt-8 col-span-6">
                        <el-select v-model="surat.penerima" placeholder="Penerima" value-key="nip" multiple class="print:hidden">
                            <el-option v-for="ptk in props.ptks" :key="ptk.nip" :value="ptk">{{ ptk.nama }}</el-option>
                        </el-select>
                    </div>
					<div class="col-span-6">
                        <table class="text-sm">
							<tr>
								<td class="align-top leading-4">Lampiran 1</td>
								<td class="align-top leading-4">:</td>
								<td class="align-top leading-4">{{ surat.perihal.perihal }}</td>
							</tr>
							<tr>
								<td class="align-top leading-4">Nomor</td>
								<td class="align-top leading-4">:</td>
								<td class="align-top leading-4">{{surat.kode}}</td>
							</tr>
							<tr>
								<td class="align-top leading-4">tanggal</td>
								<td class="align-top leading-4">:</td>
								<td class="align-top leading-4">{{dayjs(tanggal).format('DD MMM YYYY')}}</td>
							</tr>
						</table>
						
					</div>
                </div>
                <div class="title text-center uppercase font-bold mt-10 mb-4">
                    <h3>PENETAPAN</h3>
                    <h3>SUSUNAN PANITIA {{ surat.perihal.perihal }}</h3>
                    <h3>{{ sekolah.nama }} Kecamatan {{ sekolah.kecamatan }} Kabupaten {{ sekolah.kabupaten }}</h3>
                    <h3> <span v-if="surat.perihal.periode == 'semester'">{{ dayjs(surat.tanggal).month() > 6 ? 'Semester Ganjil (I)' : 'Semester Genap (II)' }}</span> {{ page.props.tapel.label }}</h3>
                </div>

                <div class="content mt-6">
                    <table class="w-[85%] mx-auto print:text-[.8rem]">
                        <thead>
                            <tr>
                                <th class="border border-black bg-slate-300 py-2">NO.</th>
                                <th class="border border-black bg-slate-300 py-2">NAMA</th>
                                <th class="border border-black bg-slate-300 py-2">JABATAN DALAM DINAS</th>
                                <th class="border border-black bg-slate-300 py-2">TUGAS DALAM PANITIA</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(ptk, p) in surat.penerima" :key="p">
                                <td class="px-2 border border-black text-center">{{p+1}}</td>
                                <td class="px-2 border border-black">
                                    <p>{{ ptk.nama }}, {{ ptk.gelar_belakang }}</p>
                                    <p v-if="ptk.bio == 'PNS' || ptk.bio == 'PPPK'">NIP. {{ ptk.nip }}</p>
                                </td>
                                <td class="px-2 border border-black">{{ jabatan(ptk.role) }}</td>
                                <td class="px-2 border border-black bg-yellow-100 print:bg-white" contenteditable>Isi jabatan panitia</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="ttd mt-8  mx-auto print:w-full grid grid-cols-12 w-[80%] justify-around print:text-[.8rem]">
					<div class="text-center pt-8 col-span-8">
                        
                    </div>
					<div class="col-span-4">
						<table>
							<tr>
								<td class="leading-4" colspan="3">
                                    <p>Kepala SD Negeri 1 Bedalisodo</p>
                                </td>
							</tr>
						</table>
						
						<p class="mt-16 underline font-bold leading-4">{{page.props.sekolah.ks.nama}}, {{page.props.sekolah.ks.gelar_belakang}}</p>
						<p class="leading-4">NIP. {{page.props.sekolah.ks.nip}}</p>
					</div>
				</div>
            </div>
		</div>
	</div>
</template>