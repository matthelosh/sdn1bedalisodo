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
const props = defineProps({selectedSurat: Object})

const sekolah = computed(() => page.props.sekolah)
const Kop = defineAsyncComponent(() => import('@/Components/General/Kop.vue'));
const loading = ref(false)
const tanggal = ref('2024-02-14')
const lastNumber = ref(null)
const surat = ref({
		kode: computed(() => `800/${lastNumber.value}/35.07.101.408.012/${tanggal.value.substring(0,4)}`),
		klasifikasi_id: '800',
		no_surat: computed(() =>lastNumber.value),
		kategori: 'SkGtt',
		perihal: 'Operator Data Pokok Pendidikan Dasar (Dapodik)',
		penerima: 'Guru',
		tanggal: computed(()=>tanggal.value),
		tembusan: 'Korwil',
		status: 'arsip'
})

const getLastNumber =  async() => {
	await axios.get(route('surat.last', { _query: {tahun: tanggal.value.substring(0,4)}}))
				.then(res => {
					if (res.data.latest == null) {
						lastNumber.value = '001'
					} else if (res.data.latest) {
						let no = parseInt(res.data.latest.no_surat)
						no+=1

						lastNumber.value = (no.length > 2) ? no : ((no.length === 2 )? ('0'+no) : ('00'+no))
					}
				})
}

const onNamaChanged = (e) => {
	surat.value.penerima = e.target.innerText
}

const simpan = async() => {
	loading.value = true
	axios.post(route('surat.store', {_query: {klasifikasi:'800'}}), {data: JSON.stringify(surat.value)})
			.then(res => {
				ElNotification({title: 'Info', message: 'Surat disimpan'})
				loading.value = false
			}).catch(err => {
				loading.value = false
				console.log(err)
			})
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
})
</script>

<template>
	<div class="wrapper">
		<div class="w-full flex justify-end items-center p-2 gap-2 sticky top-12 bg-slate-100">
			<el-form-item label="Nomor Surat">
					<el-input type="text" v-model="lastNumber" placeholder="Nomor Surat"></el-input>
				</el-form-item>
				<el-form-item label="Perihal">
					<el-select v-model="surat.perihal" placeholder="Perihal">
						<el-option v-for="perihal in ['Guru Sukwan', 'Penjaga Sekolah']" :key="perihal" :value="perihal"> {{ perihal }}</el-option>
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
					<h3 class="font-bold uppercase mx-auto w-[75%] print:w-[90%]">Penetapan {{ surat.perihal }}  {{sekolah.nama}} Kecamatan {{ sekolah.kecamatan }} Kabupaten {{ sekolah.kabupaten }}</h3>
					<h3 class="font-bold uppercase mx-40">Tahun {{ surat.tanggal.substring(0,4) }}</h3>
				</div>

				<div class="content pt-4 w-[80%] print:w-[90%] mx-auto text-justify">
					<table>
						<tr>
							<td class="px-1 align-top">Menimbang</td>
							<td class="px-1 align-top">:</td>
							<td class="px-1 align-top pb-4 print:pb-1">
                                <ul class="pl-4 list-[lower-alpha]">
                                    <li>
                                        <p class="text-justify mb-1 leading-4">Bahwa pengelolaan Pendataan Pendidikan Dasar (Dikdas) yang dilaksanakan Pemerintah harus dilaksanakan dengan tertib, jelas, akurat dan dapat dipertanggungjawabkan ;</p>
                                    </li>
                                    <li>
                                        <p class="text-justify mb-1 leading-4">Bahwa untuk menjamin kelancaran, keamanan dan keberhasilan pelaksanaan pengelolaan Pendataan Pendidikan Dasar (Dikdas) di SD Negeri 1 Bedalisodo   Kecamatan Wagir Kabupaten Malang ;</p>
                                    </li>
                                    <li>
                                        <p class="text-justify mb-1 leading-4">
                                            Bahwa  berdasarkan pertimbangan sebagaimana dimaksud pada huruf a, huruf b, perlu menetapkan operator sekolah / petugas yang menangani Pendataan Pendidikan Dasar (Dikdas) di SD Negeri 1 Bedalisodo   Kecamatan Wagir Kabupaten Malang ;
                                        </p>
                                    </li>
                                </ul>
							</td>
						</tr>
						<tr>
							<td class="px-1 align-top">Mengingat</td>
							<td class="px-1 align-top">:</td>
							<td class="px-1 align-top">
								<ul class="pl-4 list-decimal mb-4">
									<li class="leading-4">
                                        Instruksi Menteri Pendidikan Nomor 2 Tahun 2011 tentang Kegiatan Pengelolaan Data Pendidikan ;
                                    </li>
                                    <li class="leading-4">
                                        Peraturan Menteri Pendidikan dan Kebudayaan Republik Indonesia Nomor 76 Tahun 2012 tentang Petunjuk Teknis Penggunaan dan Pertanggungjawaban Keuangan Dana Bantuan Operasional Sekolah Tahun Anggaran 2014;
                                    </li>
                                    <li class="leading-4">
                                        Surat Kementrian Pendidikan dan Kebudayaan Direktorak Jendral Pendidikan Dasar Nomor : 3015/c/LK/2014 tentang Penjaringan Data Pokok Pendidikan Dasar Tahun Pelajaran 2014/2015 Tanggal 10 Juli 2014.
                                    </li>
								</ul>
							</td>
						</tr>
						<tr>
							<td class="px-1 align-top">Memperhatikan</td>
							<td class="px-1 align-top">:</td>
							<td class="px-1 align-top">Standar Operasional Prosedur Pendataan Ditjen Dikdas</td>
						</tr>
						<tr>
							<td class="py-2 text-center font-bold" colspan="3">MEMUTUSKAN</td>
						</tr>
						<tr>
							<td class="px-1">Menetapkan</td>
							<td>:</td>
							<td></td>
						</tr>
						<tr>
							<td class="px-1 align-top uppercase">Pertama</td>
							<td class="px-1 align-top">:</td>
							<td class="px-1 align-top pb-2 leading-4">
                                Dengan Keputusan Kepala SD Negeri 1 Bedalisodo   Kecamatan Wagir Kabupaten Malang ditetapkan operator Pendataan Pendidikan Dasar (Dikdas) di SD Negeri 1 Bedalisodo   Kecamatan Wagir Kabupaten Malang.
							</td>
						</tr>
						<tr>
							<td class="px-1 align-top uppercase">Kedua</td>
							<td class="px-1 align-top">:</td>
							<td class="px-1 align-top pb-2 leading-4">
								Nama Operator Pendataan Pendidikan Dasar (Dikdas) di SD Negeri Bedalisodo 1     Kecamatan Wagir Kabupaten Malang sebagaimana tercantum dalam Lampiran Keputusan ini.
							</td>
						</tr>
						<tr>
							<td class="px-1 align-top uppercase">Ketiga</td>
							<td class="px-1 align-top">:</td>
							<td class="px-1 align-top pb-2 leading-4">
								Apabila terdapat kekeliruan dalam penetapan keputusan ini akan diadakan pembetulan sebagaimana mestinya
							</td>
						</tr>
						<tr>
							<td class="px-1 align-top uppercase">Keempat</td>
							<td class="px-1 align-top">:</td>
							<td class="px-1 align-top pb-2 leading-4">
								Keputusan ini berlaku pada <span class="bg-yellow-100 print:bg-white" contenteditable>{{page.props.tapel.label}}</span>
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
					<p>Tembusan Yth.</p>
					<ul class="list-decimal pl-4">
						<li class="leading-4">Koordinator Wilayah Bidang Pendidikan Kecamatan Wagir</li>
						<li class="leading-4">Yang bersangkutan</li>
						<li class="leading-4">Arsip</li>
					</ul>
				</div>
			</div>

            <div class="sheet w-full md:w-[900px] print:w-[100%] mx-auto p-6 print:p-0 bg-white mt-4">
                <div class="ttd mt-4  mx-auto print:w-full grid grid-cols-12 w-[90%] justify-around print:text-[.8rem]">
					<div class="text-center pt-8 col-span-6">
                        
                    </div>
					<div class="col-span-6">
                        <table class="text-sm">
							<tr>
								<td class="align-top leading-4">Lampiran 1</td>
								<td class="align-top leading-4">:</td>
								<td class="align-top leading-4">Keputusan Kepala {{ sekolah.nama }}</td>
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
                    <h3>OPERATOR DAPODIK PENDIDIKAN DASAR (DIKDAS)</h3>
                    <h3>{{ sekolah.nama }} Kecamatan {{ sekolah.kecamatan }} Kabpuaten {{ sekolah.kabupaten }}</h3>
                </div>

                <div class="content mt-6">
                    <table class="w-[85%] mx-auto print:text-[.8rem]">
                        <thead>
                            <tr>
                                <th class="border border-black bg-slate-300 py-2">NO.</th>
                                <th class="border border-black bg-slate-300 py-2">NAMA</th>
                                <th class="border border-black bg-slate-300 py-2">JABATAN DALAM DINAS</th>
                                <th class="border border-black bg-slate-300 py-2">KEDUDUKAN DALAM TIM</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="px-2 border border-black text-center">1</td>
                                <td class="px-2 border border-black">
                                    <p>{{ sekolah.ks.nama }}, {{ sekolah.ks.gelar_belakang }}</p>
                                    <p>NIP. {{sekolah.ks.nip}}</p>
                                </td>
                                <td class="px-2 border border-black">Kepala Sekolah</td>
                                <td class="px-2 border border-black">Penganggung Jawab</td>
                            </tr>
                            <tr>
                                <td class="px-2 border border-black text-center">2</td>
                                <td class="px-2 border border-black">
                                    <p>ROSI NURFADILAH</p>
                                    <p>NIP. -</p>
                                </td>
                                <td class="px-2 border border-black">Guru</td>
                                <td class="px-2 border border-black">Operator Dadpodikdas</td>
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