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

const page = usePage()
const props = defineProps({selectedSurat: Object, ptks: Array})

const sekolah = computed(() => page.props.sekolah)
const Kop = defineAsyncComponent(() => import('@/Components/General/Kop.vue'));
const loading = ref(false)
const tanggal = ref(dayjs(new Date()).format('YYYY-MM-DD'))
const lastNumber = ref(null)
const siswas = ref([])
const selectedSiswa = ref({})
const surat = ref({
		kode: computed(() => `094/${lastNumber.value}/35.07.101.408.012/${tanggal.value.substring(0,4)}`),
		klasifikasi_id: '094',
		no_surat: computed(() =>lastNumber.value),
		kategori: 'Sppd',
		perihal: 'Peserta Kegiatan ..',
		penerima: [],
		tanggal: computed(()=>tanggal.value),
		tembusan: 'Korwil',
		status: 'arsip'
})

const getSiswas = async() => {
	await axios.post(route('dashboard.rombel.index', {
		_query: {
			tapel: page.props.tapel.kode,
			tingkat: '6'
		}
	})).then(res => {
		siswas.value = res.data.rombels[0].siswas
		console.log(res.data.rombels)
	}).catch(err => {
		console.log(err)
	})
}

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

const simpan = async() => {

}
onBeforeMount(() => {
	getLastNumber()
	getSiswas()
})
</script>

<template>
	<div class="wrapper">
		<div class="w-full flex justify-end items-center p-2 gap-2 sticky top-12 bg-slate-100">
			<el-form-item label="Nomor Surat">
					<el-input type="text" v-model="lastNumber" placeholder="Nomor Surat"></el-input>
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
			<div class="sheet w-full md:w-[900px] print:w-[100%] mx-auto p-6 print:p-0 bg-white break-after-page break-inside-avoid-page">
				<Kop />
				<div class="meta text-center pt-4">
					<h3 class=" uppercase font-bold underline leading-4">Surat Keterangan Lulus</h3>
					<h4 class="leading-4">Nomor: 800/{{lastNumber}}/35.07.101.408.012/{{ tanggal.substring(0,4) }}</h4>
				</div>
				<div class="content">
					<p>Yang bertanda tangan di bawah ini, Kepala {{ page.props.sekolah.nama }}</p>
					<table class="mt-4">
						<tr>
							<td>Nama</td>
							<td class="px-2">:</td>
							<td>{{page.props.sekolah.ks.nama}}, {{page.props.sekolah.ks.gelar_belakang}}</td>
						</tr>
						<tr>
							<td>NIP</td>
							<td class="px-2">:</td>
							<td>{{ page.props.sekolah.ks.nip }}</td>
						</tr>
						<tr>
							<td>Pangkat/Gol.Ruang</td>
							<td class="px-2">:</td>
							<td>{{ page.props.sekolah.ks.pangkat }}</td>
						</tr>
						<tr>
							<td>Jabatan</td>
							<td class="px-2">:</td>
							<td>Kepala Sekolah</td>
						</tr>
					</table>
					<p class="mt-6">Menerangkan dengan sebenarnya bahwa:</p>
					<table class="mt-4">
						<tr>
							<td>Nama</td>
							<td class="px-2">:</td>
							<td></td>
						</tr>
						<tr>
							<td>NIS</td>
							<td class="px-2">:</td>
							<td></td>
						</tr>
						<tr>
							<td>NISN</td>
							<td class="px-2">:</td>
							<td></td>
						</tr>
						<tr>
							<td>Tempat, Tgl Lahir</td>
							<td class="px-2">:</td>
							<td></td>
						</tr>
						<tr>
							<td>Nama Orang Tua</td>
							<td class="px-2">:</td>
							<td></td>
						</tr>
						<tr>
							<td>Alamat</td>
							<td class="px-2">:</td>
							<td></td>
						</tr>
					</table>
					<p class="mt-6">Bahwa nama tersebut di atas benar-benar telah menyelesaikan pembelajaran di kelas VI {{ page.props.sekolah.nama }} Kecamatan {{ page.props.sekolah.kecamatan }} Kabupaten {{ page.props.sekolah.kabupaten }}.</p>
					<p class="mt-6">Demikian surat keterangan ini kami buat untuk digunakan sebagai persyaratan Pendaftaran Peserta Didik Baru (PPDB) di Sekolah Menengah Pertama (SMP) / Sederajat.</p>
				</div>
				<div class="ttd mt-20">
					<table class="w-full">
						<tr>
							<td class="w-[33%]"></td>
							<td class="w-[33%]"></td>
							<td>
								<p>Malang, 20 Mei {{ page.props.tapel.label.split("/")[1] }}</p>
								<p>Kepala Sekolah</p>
								<p class="mt-20 underline font-bold leading-4">{{ page.props.sekolah.ks.nama }}</p>
								<p class="leading-4">NIP. {{ page.props.sekolah.ks.nip }}</p>
							</td>
						</tr>
					</table>
				</div>
			</div>
		</div>
		{{ siswas }}
	</div>
</template>