<script setup>
import { ref, computed, defineAsyncComponent, onBeforeMount } from 'vue';
import { usePage } from '@inertiajs/vue3';
import { Icon } from '@iconify/vue';
import axios from 'axios';
import {ElNotification} from 'element-plus'

import dayjs from 'dayjs';
import localeData from 'dayjs/plugin/localeData'
import 'dayjs/locale/id'
dayjs.extend(localeData)
dayjs().localeData()
dayjs.locale("id");

// import 'element-plus/dist/style.css'

const page  =usePage()
const props = defineProps({ptks: Array, jabatans: Object, selectedSurat: Object})

const Kop = defineAsyncComponent(() => import('@/Components/General/Kop.vue'))

const tahun = computed(() => tanggal.value.substr(0,4))
const loading = ref(false)
const tanggal = ref('2024-02-14')
const lastNumber = ref(null)
const getLastNumber =  async() => {
	await axios.get(route('surat.last', { _query: {klasifikasi: '800'}}))
				.then(res => {
					if (res.data.latest == null && res.data.latestAll == null) {
						lastNumber.value = '001'
					} else if (res.data.latest) {
						let no = parseInt(res.data.latest.no_surat)
						no+=1

						lastNumber.value = (no.length > 2) ? no : ((no.length === 2 )? ('0'+no) : ('00'+no))
					}
				})
}


const surat = ref({
		kode: computed(() => `800/${lastNumber.value}/35.07.101.408.012/${tahun.value}`),
		klasifikasi_id: '800',
		no_surat: computed(() =>lastNumber.value),
		kategori: 'SkTugas',
		perihal: 'SK Pembagian Tugas',
		penerima: 'Guru',
		tanggal: computed(()=>tanggal.value),
		tembusan: 'Korwil',
		status: 'arsip'
})

const semester = computed(() => {
	return parseInt(tanggal.value.split("-")[1]) > 6 ? 'Ganjil (I)' : 'Genap (II)'
})

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
	} else {
		getLastNumber()

	}
})

</script>

<template>
	<div class="p-4 bg-slate-100">
		<div class="w-full flex justify-end gap-2 my-2 sticky top-12 bg-slate-100">
				<el-form-item label="Tanggal Surat">
					<el-date-picker v-model="tanggal" format="DD-MM-YYYY" value-format="YYYY-MM-DD" type="date"></el-date-picker>
					</el-form-item>
				<el-button-group>
					<el-button @click="simpan" :loading="loading">
						<Icon icon="mdi:hdd" />
					</el-button>
				</el-button-group>
		</div>
		<div class="cetak p-3 bg-slate-300 print:bg-white ">
			<div class="sheet break-inside-avoid break-after-always bg-white  px-4 pb-4 font-serif w-full md:w-[900px] print:w-full mx-auto">
				<Kop />
				<div class="meta font-bold  my-4 text-center">
					<!-- {{tes}} -->
					<h3>KEPUTUSAN</h3>
					<h3>KEPALA SEKOLAH DASAR NEGERI 1 BEDALISODO KECAMATAN WAGIR</h3>
					<h3>NOMOR : {{surat.kode}}</h3>
					<h3>Tentang</h3>
					<h3>PEMBAGIAN TUGAS GURU DALAM KEGIATAN PROSES BELAJAR MENGAJAR ATAU BIMBINGAN DAN PENYULUHAN SEMESTER {{semester.toUpperCase()}} TAHUN PELAJARAN 2023/2024</h3>
				</div>
				<div class="content print:text-[.8rem]">
					<table class=font-serif>
						<tr>
							<td class="px-1 align-top w-[15%]">Menimbang</td>
							<td class="px-1 align-top">:</td>
							<td class="px-1 align-top">Bahwa dalam rangka memperlancar pelaksanaan proses belajar mengajar di Sekolah Dasar Negeri 1 Bedalisodo perlu menetapkan pembagian tugas guru</td>
						</tr>
						<tr>
							<td class="px-1 align-top">Mengingat</td>
							<td class="px-1 align-top">:</td>
							<td class="px-1 align-top">
								<ul class="list-decimal pl-4">
									<li>Undang - undangNomor 20 Tahun 2003 tentang Sisdiknas;</li>
									<li>Undang - undang No. 14 tahun 2005 tentang Guru dan Dosen;</li>
									<li>Peraturan Pemerintah Nomor 19 Tahun 2005 tentang Standar Nasional Pendidikan;</li>
									<li>Peraturan Pemerintah Nomor 74 Tahun 2008 tentang Guru;</li>
									<li>Permendiknas Nomor 41 Tahun 2007 tentang Standar Proses untuk Satuan Pendidikan Dasar dan Menengah;</li>
									<li>Permendiknas No. 39 Tahun 2009 Tentang Beban Kerja Guru dan Pengawas;</li>
									<li>Permeneg PAN dan RB. Nomor 16 Tahun 2009 tentang Jabatan Fungsional Guru dan Angka Kreditnya.</li>
									<li>Permendikbud No.15 Tahun 2020 tentang Pemenuhan Beban Kerja Guru, Kepala Sekolah , Dan Pengawas</li>
									<li>Surat Edaran Sekjen Kemendikbud Nomor 15 Tahun 2020 Tentang Penyelenggaraaan Belajar Dari Rumah Dalam Masa Darurat COVID - 19</li>
								</ul>
							</td>
						</tr>
						<tr>
							<td class="px-1 align-top">Mengingat pula</td>
							<td class="px-1 align-top">:</td>
							<td class="px-1 align-top">Saran, usul, pendapat Rapat Dewan Guru tentang Pembagian Tugas Guru dalam kegiatan Proses Belajar Mengajar atau Bimbingan dan Penyuluhan pada Semester {{semester }} {{page.props.tapel.label}}, pada tanggal {{dayjs(tanggal).subtract(2, 'day').format('DD MMMM YYYY')}}</td>
						</tr>
						<tr>
							<td colspan="3" class="text-center pt-6 font-bold">
								MEMUTUSKAN:
							</td>
						</tr>
						<tr>
							<td class="px-1 align-top">Menetapkan</td>
							<td class="px-1 align-top">:</td>
							<td class="px-1 align-top"></td>
						</tr>
						<tr>
							<td class="px-1 align-top">Pertama</td>
							<td class="px-1 align-top">:</td>
							<td class="px-1 align-top">Pembagian tugas guru dalam kegiatan proses belajar atau bimbingan dan penyuluhan seperti tersebut pada lampiran I ini.</td>
						</tr>
						<tr>
							<td class="px-1 align-top">Kedua</td>
							<td class="px-1 align-top">:</td>
							<td class="px-1 align-top">
								<ul class="list-disc pl-4">
									<li>Menugaskan guru untuk melaksanakan tugas bimbingan seperti tersebut pada lampiran II keputusan ini</li>
									<li>Menugaskan guru untuk melaksanakan kegiatan lainnya disekolah seperti tersebut pada lampiran III keputusan ini</li>
								</ul>
							</td>
						</tr>
						<tr>
							<td class="px-1 align-top">Ketiga</td>
							<td class="px-1 align-top">:</td>
							<td class="px-1 align-top">Masing – masing guru melaporkan pelaksanaan tugasnya secara tertulis dan berkala Kepada Sekolah Dasar</td>
						</tr>
						<tr>
							<td class="px-1 align-top">Keempat</td>
							<td class="px-1 align-top">:</td>
							<td class="px-1 align-top">Segala biaya yang timbul akibat pelaksanaan keputusan ini, dibebankan pada anggaran yang sesuai.</td>
						</tr>
						<tr>
							<td class="px-1 align-top">Kelima</td>
							<td class="px-1 align-top">:</td>
							<td class="px-1 align-top">Apabila terdapat kekeliruan dalam keputusan ini, akan dibetulkan sebagaimana mestinya.</td>
						</tr>
						<tr>
							<td class="px-1 align-top">Keenam</td>
							<td class="px-1 align-top">:</td>
							<td class="px-1 align-top">Keputusan ini berlaku sejak ditetapkan.</td>
						</tr>
						
					</table>
				</div>
				<div class="ttd mt-4 w-full flex justify-around print:text-[.8rem]">
					<div class="colspan-2 text-center pt-8">
						<p>Mengetahui,</p>
						<p>Pengawas SD Kecamatan Wagir</p>

						<p class="mt-16 underline font-bold">Dr. <span class="uppercase">Jayeng Budi Yuwono Putro</span>, S. Pd., M.A</p>
						<p>NIP. 196903221998071001</p>
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
						<li>Kepala Kanwil Dinas Pendidikan Prov. Jawa Timur</li>
						<li>Kepala Dinas Pendidikan Kabupaten Malang</li>
						<li>Koordinator Wilayah Bidang Pendidikan Kecamatan Wagir</li>
					</ul>
				</div>
			</div>
			<div class="sheet break-inside-avoid break-after-always mt-4 bg-white px-4 pb-4 font-serif w-full md:w-[900px] print:w-full mx-auto">
				<Kop />
				<div class="meta text-right">
					<p>Lampiran II: Keputusan Kepala SD Negeri 1 Bedalisodo</p>
					<p>Nomor: {{surat.kode}}</p>
					<p>Tanggal: {{dayjs(tanggal).format('DD MMMM YYYY')}}</p>
				</div>
				<div class="title text-center uppercase font-bold">
					<h3>Pembagian Tugas Guru</h3>
					<h3>Dalam Proses Belajar Mengajar</h3>
					<h3>{{page.props.sekolah.nama}}</h3>
				</div>
				<table>
					<thead>
						<tr>
							<th class="border border-black px-1">No</th>
							<th class="border border-black px-1">Nama / NIP</th>
							<th class="border border-black px-1">GOL. RUANG</th>
							<th class="border border-black px-1">STATUS</th>
							<th class="border border-black px-1">JABATAN GURU</th>
							<th class="border border-black px-1">TUGAS MENGAJAR</th>
							<th class="border border-black px-1">JUMLAH JAM</th>
							<th class="border border-black px-1">JUMLAH SISWA</th>
						</tr>
					</thead>
					<tbody>
						<tr v-for="(ptk, p) in props.ptks" :key="ptk.nip">
							<td class="border border-black px-1">{{p+1}}</td>
							<td class="border border-black px-1">
								<p>{{ptk.nama}}</p>
								<p v-if="ptk.bio == 'PNS' || ptk.bio == 'PPPK'">{{ptk.nip}}</p>
							</td>
							<td class="border border-black px-1 text-center" contenteditable>{{ptk.pangkat}}</td>
							<td class="border border-black px-1 text-center" contenteditable>{{ptk.bio}}</td>
							<td class="border border-black px-1 text-center" contenteditable>{{jabatans[ptk.role]}}</td>
							<td class="border border-black px-1 text-center" contenteditable>
								<span v-if="ptk.role == 'ks'">Manajerial</span>
								<span v-else-if="ptk.role == 'gkel'">{{ptk.rombels[0].label}}</span>
								<span v-else-if="ptk.role == 'pjg'">-</span>
								<span v-else>Kelas I - VI</span>
							</td>
							<td class="border border-black px-1 text-center" contenteditable>
								<span v-if="ptk.role == 'gen'">18</span>
								<span v-else>24</span>
							</td>
							<td class="border border-black px-1 text-center" contenteditable>
								<span v-if="ptk.role == 'gkel'">{{ptk.rombels[0].siswas.length}}</span>
								<span v-else>{{props.ptks.reduce((a,c) => a+ (c.rombels[0] ? c.rombels[0].siswas.length : 0),0)}}</span>
							</td>

						</tr>
					</tbody>
				</table>
				<div class="ttd mt-8 w-full flex justify-around print:text-[.8rem]">
					<div class="colspan-2 text-center">
						<p>Mengetahui,</p>
						<p>Pengawas SD Kecamatan Wagir</p>

						<p class="mt-16 underline font-bold">Dr. <span class="uppercase">Jayeng Budi Yuwono Putro</span>, S. Pd., M.A</p>
						<p>NIP. 196903221998071001</p>
					</div>
					<div class="text-center">
						<p>&nbsp;</p>
						<p>Kepala SD Negeri 1 Bedalisodo</p>
						
						<p class="mt-16 underline font-bold">{{page.props.sekolah.ks.nama}}, {{page.props.sekolah.ks.gelar_belakang}}</p>
						<p>NIP. {{page.props.sekolah.ks.nip}}</p>
					</div>
				</div>	
			</div>
			<div class="sheet break-inside-avoid break-after-always mt-4 bg-white px-4 pb-4 font-serif w-full md:w-[900px] print:w-full mx-auto">
				<Kop />
				<div class="meta text-right">
					<p>Lampiran II: Keputusan Kepala SD Negeri 1 Bedalisodo</p>
					<p>Nomor: {{surat.kode}}</p>
					<p>Tanggal: {{dayjs(tanggal).format('DD MMMM YYYY')}}</p>
				</div>
				<div class="title text-center uppercase font-bold">
					<h3>Pembagian Tugas Guru Dalam Membimbing</h3>
				</div>
				<table class="print:text-sm">
					<thead>
						<tr>
							<th class="border border-black px-1">No</th>
							<th class="border border-black px-1">Nama / NIP</th>
							<th class="border border-black px-1">PENUGASAN DALAM MEMBIMBING</th>
							<th class="border border-black px-1">SASARAN BIMBINGAN</th>
						</tr>
					</thead>
					<tbody>
						<tr v-for="(ptk, p) in props.ptks" :key="ptk.nip">
							<td class="border border-black px-1 align-top">{{p+1}}</td>
							<td class="border border-black px-1 align-top">
								<p>{{ptk.nama}}</p>
								<p v-if="ptk.bio == 'PNS' || ptk.bio == 'PPPK'">{{ptk.nip}}</p>
							</td>
							<td class="border border-black px-1 align-top" >{{ptk.tugas[0]? ptk.tugas[0].label : '-'}}</td>
							<td class="border border-black px-1 text-left align-top" >
								<span v-if="ptk.role == 'ks'">
									<ul class="list-decimal pl-6 text-left">
										<li v-for="guru in props.ptks" :key="guru.id">{{guru.nama}}</li>
									</ul>
								</span>
								<span v-else>
									{{ptk.tugas[0]? ptk.tugas[0].deskripsi : '-'}}
								</span>
							</td>

						</tr>
					</tbody>
				</table>
				<div class="ttd mt-8 w-full flex justify-around print:text-[.8rem]">
					<div class="colspan-2 text-center">
						<p>Mengetahui,</p>
						<p>Pengawas SD Kecamatan Wagir</p>

						<p class="mt-16 underline font-bold">Dr. <span class="uppercase">Jayeng Budi Yuwono Putro</span>, S. Pd., M.A</p>
						<p>NIP. 196903221998071001</p>
					</div>
					<div class="text-center">
						<p>&nbsp;</p>
						<p>Kepala SD Negeri 1 Bedalisodo</p>
						
						<p class="mt-16 underline font-bold">{{page.props.sekolah.ks.nama}}, {{page.props.sekolah.ks.gelar_belakang}}</p>
						<p>NIP. {{page.props.sekolah.ks.nip}}</p>
					</div>
				</div>	
			</div>
			<div class="sheet break-inside-avoid break-after-always mt-4 bg-white px-4 pb-4 font-serif w-full md:w-[900px] print:w-full mx-auto">
				<Kop />
				<div class="meta text-right">
					<p>Lampiran II: Keputusan Kepala SD Negeri 1 Bedalisodo</p>
					<p>Nomor: {{surat.kode}}</p>
					<p>Tanggal: {{dayjs(tanggal).format('DD MMMM YYYY')}}</p>
				</div>
				<div class="title text-center uppercase font-bold">
					<h3>Pembagian Tugas Guru Dalam 8 Standar Pendidikan</h3>
				</div>
				<table>
					<thead>
						<tr>
							<th class="border border-black px-1">No</th>
							<th class="border border-black px-1">Nama / NIP</th>
							<th class="border border-black px-1">PENUGASAN DALAM STANDAR</th>
							<th class="border border-black px-1">SASARAN PELAKSANAAN</th>
						</tr>
					</thead>
					<tbody>
						<tr v-for="(ptk, p) in props.ptks" :key="ptk.nip">
							<td class="border border-black px-1">{{p+1}}</td>
							<td class="border border-black px-1">
								<p>{{ptk.nama}}</p>
								<p v-if="ptk.bio == 'PNS' || ptk.bio == 'PPPK'">{{ptk.nip}}</p>
							</td>
							<td class="border border-black px-1" contenteditable>{{ptk.tugas[1]? ptk.tugas[1].label : '-'}}</td>
							<td class="border border-black px-1 text-left" contenteditable>
								<!-- <span v-if="ptk.role == 'ks'">
									<ul class="list-decimal pl-6 text-left">
										<li v-for="guru in props.ptks" :key="guru.id">{{guru.nama}}</li>
									</ul>
								</span> -->
								<span>
									{{ptk.tugas[1]? ptk.tugas[1].deskripsi : '-'}}
								</span>
							</td>

						</tr>
					</tbody>
				</table>
				<div class="ttd mt-8 w-full flex justify-around print:text-[.8rem]">
					<div class="colspan-2 text-center">
						<p>Mengetahui,</p>
						<p>Pengawas SD Kecamatan Wagir</p>

						<p class="mt-16 underline font-bold">Dr. <span class="uppercase">Jayeng Budi Yuwono Putro</span>, S. Pd., M.A</p>
						<p>NIP. 196903221998071001</p>
					</div>
					<div class="text-center">
						<p>&nbsp;</p>
						<p>Kepala SD Negeri 1 Bedalisodo</p>
						
						<p class="mt-16 underline font-bold">{{page.props.sekolah.ks.nama}}, {{page.props.sekolah.ks.gelar_belakang}}</p>
						<p>NIP. {{page.props.sekolah.ks.nip}}</p>
					</div>
				</div>	
			</div>
			
		</div>
	</div>
</template>

<style>
	@media print {
		.sheet {
			page-break-after: always!important;
		}
	}
</style>