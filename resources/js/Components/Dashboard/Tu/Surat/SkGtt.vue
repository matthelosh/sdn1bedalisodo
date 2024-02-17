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
		perihal: 'Guru Sukwan',
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
						let no = parseInt(res.data.latest)
						no+=1
						no = no.toString()
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
		<div class="cetak w-full bg-slate-300 print:bg-white py-4 font-serif">
			<div class="sheet w-full md:w-[900px] mx-auto px-3 pb-3 bg-white">
				<Kop />
				<div class="meta text-center ">
					<h3 class="underline uppercase font-bold">Keputusan Kepala Sekolah</h3>
					<h4>Nomor: 800/{{lastNumber}}/35.07.101.408.012/{{ tanggal.substring(0,4) }}</h4>
					<h3 class="font-bold uppercase">Tentang</h3>
					<h3 class="font-bold uppercase">Pengangkatan {{ surat.perihal }} di {{sekolah.nama}}</h3>
				</div>

				<div class="content">
					<table>
						<tr>
							<td class="px-1 align-top">Menimbang</td>
							<td class="px-1 align-top">:</td>
							<td class="px-1 align-top">
								<p class="text-justify mb-4">Dalam rangka meningkatkan mutu pendidikan di {{sekolah.nama}}, diperlukan tenaga pendidik guru yang mencukupi sesuai dengan jumlah rombongan belajar yang ada dan struktur kurikulum terbaru.</p>
							</td>
						</tr>
						<tr>
							<td class="px-1 align-top">Mengingat</td>
							<td class="px-1 align-top">:</td>
							<td class="px-1 align-top">
								<ul class="pl-4 list-decimal mb-4">
									<li>Undang-undang No. 20 tahun 2003 tentang Sistem Pendidikan Nasional;</li>
									<li>Peraturan Pemerintah No. 19 tahun 2005 tentang Standar Nasional Pendidikan;</li>
									<li>Peraturan Pemerintah No. 17 tahun 2010 tentan pengelolaan dan penyelenggaraan pendidikan;</li>
									<li>Peraturan Mendiknas nomor 50 tahun 2007 tentang pengelolaan pendidikan oleh pemerintah daerah;</li>
									<li>Peraturan Mendiknas nomor 50 tahun 2007 tentang pengelolaan pendidikan oleh pemerintah daerah;</li>
									<li>Hasil musyawarah dengan wali murid, komite sekolah dan dewan guru.</li>
								</ul>
							</td>
						</tr>
						<tr>
							<td class="py-2 text-center font-bold" colspan="3">MEMUTUSKAN</td>
						</tr>
						<tr>
							<td clas="px-1">Menetapkan</td>
							<td>:</td>
							<td></td>
						</tr>
						<tr>
							<td class="px-1 align-top">Pertama</td>
							<td class="px-1 align-top">:</td>
							<td class="px-1 align-top">
								<table class="mb-4">
									<tr>
										<td>Nama</td>
										<td>:</td>
										<td contenteditable @blur="onNamaChanged">{{ surat.penerima }}</td>
									</tr>
									<tr>
										<td>Tempat, tanggal lahir</td>
										<td>:</td>
										<td contenteditable class="bg-yellow-100 print:bg-white">--------------------------</td>
									</tr>
									<tr>
										<td>Agama</td>
										<td>:</td>
										<td contenteditable  class="bg-yellow-100 print:bg-white">--------------------------</td>
									</tr>
									<tr>
										<td>Pendidikan</td>
										<td>:</td>
										<td contenteditable  class="bg-yellow-100 print:bg-white">--------------------------</td>
									</tr>
									<tr>
										<td>Alamat tempat tinggal</td>
										<td>:</td>
										<td contenteditable  class="bg-yellow-100 print:bg-white">--------------------------</td>
									</tr>
									
								</table>
							</td>
						</tr>
						<tr>
							<td class="px-1 align-top">Kedua</td>
							<td class="px-1 align-top">:</td>
							<td class="px-1 align-top">
								<p class="text-justify">
									Terhitung mulai tanggal <span contenteditable class="bg-yellow-100 print:bg-white">--,----------</span> ditugaskan sebagai tenaga Guru Tidak Tetap (GTT) di {{sekolah.nama}} Kecamatan {{sekolah.kecamatan}} Kabupaten {{sekolah.kabupaten}}.
								</p>
							</td>
						</tr>
						<tr>
							<td class="px-1 align-top">Ketiga</td>
							<td class="px-1 align-top">:</td>
							<td class="px-1 align-top">
								<p class="text-justify">Honor yang dapat diberikan oleh pihak sekolah sebesar Rp. <span contenteditable class="bg-yellow-100 print:bg-white honor">500.000</span> ( <span contenteditable class="bg-yellow-100 print:bg-white honor">lima ratus ribu rupiah</span> )</p>
							</td>
						</tr>
						<tr>
							<td class="px-1 align-top">Keempat</td>
							<td class="px-1 align-top">:</td>
							<td class="px-1 align-top">
								<p class="text-justify">Apabila terdapat kekeliruan dalam surat keputusan ini, maka akan dibetulkan sebagaimana mestinya.</p>
							</td>
						</tr>
					</table>
				</div>
				<div class="ttd mt-4 w-full flex justify-around print:text-[.8rem]">
					<div class="colspan-2 text-center pt-8">
					</div>
					<div class="text-center">
						<table>
							<tr>
								<td class="px-1">Ditetapkan di</td>
								<td class="px-1">:</td>
								<td class="px-1">Dalisodo</td>
							</tr>
							<tr>
								<td class="px-1">Pada tanggal</td>
								<td class="px-1">:</td>
								<td class="px-1">{{dayjs(tanggal).format('DD MMMM YYYY')}}</td>
							</tr>
						</table>
						<p>Kepala SD Negeri 1 Bedalisodo</p>
						
						<p class="mt-16 underline font-bold">{{page.props.sekolah.ks.nama}}, {{page.props.sekolah.ks.gelar_belakang}}</p>
						<p>NIP. {{page.props.sekolah.ks.nip}}</p>
					</div>
				</div>
				<div class="tembusan font-serif mt-4 print:text-[.8rem]">
					<p>Tembusan Yth.</p>
					<ul class="list-decimal pl-4">
						<li>Kepala Dinas Pendidikan Kabupaten Malang</li>
						<li>Koordinator Wilayah Bidang Pendidikan Kecamatan Wagir</li>
						<li>Yang bersangkutan</li>
						<li>Arsip</li>
					</ul>
				</div>
			</div>
		</div>
	</div>
</template>