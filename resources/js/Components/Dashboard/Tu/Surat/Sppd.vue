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
	axios.post(route('surat.store', {_query: {klasifikasi:'094'}}), {data: JSON.stringify(surat.value)})
			.then(res => {
				ElNotification({title: 'Info', message: 'Surat disimpan'})
				loading.value = false
			}).catch(err => {
				loading.value = false
				console.log(err)
			})
}

const tujuan = ref('Isi lokasi tujuan')
const onChangedTujuan = (e) => {
	tujuan.value = e.target.innerText
}
const pejabatTujuan = ref({
	nama: '..............................................',
	nip: '-'
})
const onPejabatTujuanChanged = (e, text) => {
	pejabatTujuan.value[text] = e.target.innerText
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
		<div class="w-full flex justify-end items-center px-2 gap-2 sticky top-12 bg-slate-100">
				<el-form-item label="Nomor Surat" class="pt-4">
					<el-input type="text" v-model="lastNumber" placeholder="Nomor Surat"></el-input>
				</el-form-item>
				<el-popover title="Perihal SPPD" placement="bottom-start">
					<el-input type="textarea" v-model="surat.perihal" placeholder="Tujuan SPPD" />
					<template #reference>
						<el-button type="primary">Perihal</el-button>
					</template>
				</el-popover>
				<!-- <el-form-item label="Penerima" class="pt-4">
					<el-select v-model="surat.penerima" placeholder="Penerima" multiple collapse-tags value-key="id">
						<el-option v-for="ptk in props.ptks" :key="ptk.nip"  :value="ptk" :label="ptk.nama" /> 
					</el-select>
				</el-form-item> -->
				<el-form-item label="Tanggal Surat" class="pt-4">
					<el-date-picker v-model="tanggal" format="DD-MM-YYYY" value-format="YYYY-MM-DD" type="date"></el-date-picker>
					</el-form-item>
				<el-button-group>
					<el-button @click="simpan" :loading="loading">
						<Icon icon="mdi:hdd" />
					</el-button>
				</el-button-group>
		</div>
		<div class="cetak w-full bg-slate-300 print:bg-white py-4 font-serif">
			<div class="sheet w-full md:w-[900px] mx-auto px-3 pb-3 bg-white break-inside-avoid-page">
				<Kop />
				<div class="meta text-center my-4">
					<h3 class="underline uppercase font-bold">Surat Tugas</h3>
					<h4>Nomor: {{surat.klasifikasi_id}}/{{lastNumber}}/35.07.101.408.012/{{ tanggal.substring(0,4) }}</h4>
				</div>

				<div class="content">
					<p>Yang bertanda tangan di bawah ini:</p>
					<table class="w-[90%] mx-auto">
						<tr>
							<td class="px-1 align-top">Nama</td>
							<td class="px-1 align-top">:</td>
							<td class="px-1 align-top">{{ sekolah.ks.nama }}</td>
						</tr>
						<tr>
							<td class="px-1 align-top">NIP</td>
							<td class="px-1 align-top">:</td>
							<td class="px-1 align-top">{{ sekolah.ks.nip }}</td>
						</tr>
						
						<tr>
							<td class="px-1 align-top">Pangkat, Gol. Ruang</td>
							<td class="px-1 align-top">:</td>
							<td class="px-1 align-top">Penata, {{ sekolah.ks.pangkat }}</td>
						</tr>
						
						<tr>
							<td class="px-1 align-top">Jabatan</td>
							<td class="px-1 align-top">:</td>
							<td class="px-1 align-top">Kepala Sekolah</td>
						</tr>
						
						<tr>
							<td class="px-1 align-top">Alamat Dinas</td>
							<td class="px-1 align-top">:</td>
							<td class="px-1 align-top">{{ sekolah.nama }}, {{ sekolah.alamat }}, {{ sekolah.desa }}</td>
						</tr>
					</table>
					<p class="text-center my-2">menugaskan:</p>
					<div class="mx-auto w-[90%] pb-4 print:hidden">
						<el-form-item class="print:hidden w-[80%] mx-auto" label="Pilih Pneerima tugas">
						<el-select v-model="surat.penerima" placeholder="Penerima" value-key="nip" multiple  class="print:hidden">
                            <el-option v-for="ptk in props.ptks" :key="ptk.nip" :value="ptk">{{ ptk.nama }}</el-option>
                        </el-select>
					</el-form-item>
						<table class="w-[80%] mx-auto">
							<thead>
								<tr>
									<th class="border px-2 border-black">#</th>
									<th class="border px-2 border-black">NAMA/NIP</th>
									<th class="border px-2 border-black">JABATAN</th>
								</tr>
							</thead>
							<tbody>
								<tr v-for="(ptk, i) in surat.penerima" :key="ptk.id">

									<td class="border border-black px-2 text-center">
										{{ i+1 }}
									</td>
									<td class="border border-black px-2">
										<p>{{ ptk.nama }}</p>
										<p>NIP. {{ ptk.nip }}</p>
									</td>
									<td class="border border-black px-2 bg-yellow-100 print:bg-white" contenteditable>Isi jabatan</td>
								</tr>
							</tbody>
						</table>
					</div>
					<table class="w-[90%] mx-auto">
						<tr>
							<td class="px-1 align-top">Unit Kerja</td>
							<td class="px-1 align-top">:</td>
							<td class="px-1 align-top">{{ sekolah.nama }}</td>
						</tr>
						
						<tr>
							<td class="px-1 align-top">Hari, tanggal</td>
							<td class="px-1 align-top">:</td>
							<td class="px-1 align-top">{{ dayjs(tanggal).format('dddd, DD MMMM YYYY') }}</td>
						</tr>
						
						<tr>
							<td class="px-1 align-top">Pukul</td>
							<td class="px-1 align-top">:</td>
							<td class="px-1 align-top bg-yellow-100 border-b print:border-none print:bg-white" contenteditable>08.00 s/d selesai</td>
						</tr>

						<tr>
							<td class="px-1 align-top">Tempat</td>
							<td class="px-1 align-top">:</td>
							<td class="px-1 align-top bg-yellow-100 border-b print:border-none print:bg-white" contenteditable @change="onChangedTujuan">{{ tujuan }}</td>
						</tr>
						
						<tr>
							<td class="px-1 align-top">Ditugaskan sebagai</td>
							<td class="px-1 align-top">:</td>
							<td class="px-1 align-top w-[70%] " >{{ surat.perihal }}</td>
						</tr>
						
					</table>
					<p class="my-4">Demikian surat tugas ini dibuat, agar dilaksanakan dengan penuh tanggung jawab.</p>
				</div>
				<div class="ttd mt-4 w-full flex justify-around print:text-[.8rem]">
					<div class="colspan-2 text-center pt-8">
					</div>
					<div class="text-left">
						<p>{{dayjs(tanggal).format('DD MMMM YYYY')}}</p>
						<p>Kepala SD Negeri 1 Bedalisodo</p>
						
						<p class="mt-16 underline font-bold">{{page.props.sekolah.ks.nama}}, {{page.props.sekolah.ks.gelar_belakang}}</p>
						<p>NIP. {{page.props.sekolah.ks.nip}}</p>
					</div>
				</div>
				
			</div>
			<div class="sheet w-full md:w-[900px] mx-auto my-4 print:m-0 px-3 pb-3 bg-white break-inside-avoid-page">
				<Kop />
				<div class="meta text-center my-4">
					<h3 class="underline uppercase font-bold">Surat Perintah Perjalanan Dinas (SPPD)</h3>
					<h4>Nomor: {{surat.klasifikasi_id+'/'+lastNumber}}/35.07.101.408.012/{{ tanggal.substring(0,4) }}</h4>
				</div>
				<div class="content my-4">
					<table class="w-[90%] mx-auto">
						<tr>
							<td class="px-2 align-top border border-black text-center">1</td>
							<td class="px-2 align-top border border-black">Pejabat berwenang yang memberi pemerintah</td>
							<td class="px-2 align-top border border-black">Kepala {{ page.props.sekolah.nama }}</td>
						</tr>
						<tr>
							<td class="px-2 align-top border border-black text-center">2</td>
							<td class="px-2 align-top border border-black">Nama/NIP Pegawai yang diperintah</td>
							<td class="px-2 align-top border border-black">
								<ul class="pl-4 list-decimal">
									<li v-for="(penerima,p) in surat.penerima" :key="penerima.id">
										<p>
											{{ penerima.nama }}, {{ penerima.gelar_belakang ?? '' }}
										</p>
										<p>NIP.{{ penerima.nip }}</p>
									</li>
								</ul>
							</td>
						</tr>
						<tr>
							<td class="px-2 align-top border border-black text-center">3</td>
							<td class="px-2 align-top border border-black">
								<ul class="pl-4 list-[lower-alpha]">
									<li>Pangkat dan golongan ruang gaji menurut PP. No. 6 tahun 1997</li>
									<li>Jabatan / instansi</li>
									<li>Tingkat menurut peraturan perjalanan dinas</li>
								</ul>
							</td>
							<td class="px-2 align-top border border-black">
								<ul class="pl-4 list-[lower-alpha]">
									<li>
										<p>&nbsp;</p>
										<ul class="pl-4 list-decimal">
											<li v-for="(penerima,p) in surat.penerima" :key="penerima.id">
												{{ penerima.pangkat }}
											</li>
										</ul>
									</li>
									<li>{{ sekolah.nama }}</li>
									<li>-</li>
								</ul>
							</td>
						</tr>
						<tr>
							<td class="px-2 align-top border border-black text-center">4</td>
							<td class="px-2 align-top border border-black">Maksud perjalanan dinas</td>
							<td class="px-2 align-top border border-black font-bold">{{ surat.perihal }}</td>
						</tr>
						<tr>
							<td class="px-2 align-top border border-black text-center">5</td>
							<td class="px-2 align-top border border-black">Alat transportasi yang diperlukan</td>
							<td class="px-2 align-top border border-black font-bold bg-yellow-100 print:bg-white" contenteditable>Sepeda motor</td>
						</tr>
						<tr>
							<td class="px-2 align-top border border-black text-center">6</td>
							<td class="px-2 align-top border border-black">
								<ul class="pl-4 list-[lower-alpha]">
									<li>Tempat berangkat</li>
									<li>Tempat tujuan</li>
								</ul>
							</td>
							<td class="px-2 align-top border border-black font-bold bg-yellow-100 print:bg-white" >
								<ul class="pl-4 list-[lower-alpha]">
									<li class="bg-yellow-100 print:bg-white" contenteditable>Dalisodo Kec. Wagir</li>
									<li class="bg-yellow-100 print:bg-white" contenteditable>{{ tujuan }}</li>
								</ul>
							</td>
						</tr>
						<tr>
							<td class="px-2 align-top border border-black text-center">7</td>
							<td class="px-2 align-top border border-black">
								<ul class="pl-4 list-[lower-alpha]">
									<li>Lamanya perjalanan</li>
									<li>Tanggal berangkat</li>
									<li>Tanggal harus kembali</li>
								</ul>
							</td>
							<td class="px-2 align-top border border-black font-bold bg-yellow-100 print:bg-white" >
								<ul class="pl-4 list-[lower-alpha]">
									<li class="bg-yellow-100 print:bg-white" contenteditable>1 hari</li>
									<li class="bg-yellow-100 print:bg-white" contenteditable>{{ dayjs(tanggal).format('dddd, DD MMMM YYYY') }}</li>
									<li class="bg-yellow-100 print:bg-white" contenteditable>{{ dayjs(tanggal).format('dddd, DD MMMM YYYY') }}</li>
								</ul>
							</td>
						</tr>
						<tr>
							<td class="px-2 align-top border border-black text-center">8</td>
							<td class="px-2 align-top border border-black">Pengikut</td>
							<td class="px-2 align-top border border-black font-bold bg-yellow-100 print:bg-white" contenteditable>-</td>
						</tr>
						<tr>
							<td class="px-2 align-top border border-black text-center">9</td>
							<td class="px-2 align-top border border-black">
								<p>Pembebanan anggaran:</p>
								<p>a. Instansi</p>
							</td>
							<td class="px-2 align-top border border-black font-bold bg-yellow-100 print:bg-white" contenteditable>
								<p class="text-white">t</p>
								<p class="bg-yellow-100 print:bg-white" contenteditable>{{ page.props.sekolah.nama }}</p>
							</td>
						</tr>
						<tr>
							<td class="px-2 align-top border border-black text-center">10</td>
							<td class="px-2 align-top border border-black">Keterangan lain-lain</td>
							<td class="px-2 align-top border border-black font-bold bg-yellow-100 print:bg-white" contenteditable>Surat perintah ini supaya dilaksanakan dengan rasa tanggung jawab</td>
						</tr>
					</table>
				</div>
				<div class="ttd mt-4 w-full flex justify-around print:text-[.8rem]">
					<div class="colspan-2 text-center pt-8">
						
					</div>
					<div class="text-left">
						<table>
							<tr>
								<td >Dikeluarkan di</td>
								<td >:</td>
								<td >Dalisodo</td>
							</tr>
							<tr>
								<td >Pada tanggal</td>
								<td >:</td>
								<td >{{dayjs(tanggal).format('DD MMMM YYYY')}}</td>
							</tr>
						</table>
						<p>Kepala SD Negeri 1 Bedalisodo</p>
						
						<p class="mt-16 underline font-bold">{{page.props.sekolah.ks.nama}}, {{page.props.sekolah.ks.gelar_belakang}}</p>
						<p>NIP. {{page.props.sekolah.ks.nip}}</p>
					</div>
				</div>
			</div>
			<div class="sheet w-full md:w-[900px] mx-auto my-4 print:m-0 px-3 py-3 bg-white break-inside-avoid-page">
				<div class="ttd my-4 w-full flex justify-around print:text-[.8rem]">
					<div class="colspan-2 text-center pt-8">
						
					</div>
					<div class="text-left">
						<table>
							<tr>
								<td >Berangkat dari</td>
								<td >:</td>
								<td >Dalisodo</td>
							</tr>
							<tr>
								<td >Pada tanggal</td>
								<td >:</td>
								<td >{{dayjs(tanggal).format('DD MMMM YYYY')}}</td>
							</tr>
							<tr>
								<td >Ke</td>
								<td >:</td>
								<td class="bg-yellow-100 print:bg-white" contenteditable>{{tujuan}}</td>
							</tr>
						</table>
						<p>Kepala SD Negeri 1 Bedalisodo</p>
						
						<p class="mt-16 underline font-bold">{{page.props.sekolah.ks.nama}}, {{page.props.sekolah.ks.gelar_belakang}}</p>
						<p>NIP. {{page.props.sekolah.ks.nip}}</p>
					</div>
				</div>

				<table class="w-full ">
					<tr>
						<td class="align-top w-[5%] border-b border-s border-t border-black p-2">1</td>
						<td class="align-top w-[47.5%] border-t border-b border-black p-2">
							<table>
								<tr>
									<td >Tiba di</td>
									<td >:</td>
									<td class="bg-yellow-100 print:bg-white" contenteditable>{{ tujuan }}</td>
								</tr>
								<tr>
									<td >Pada tanggal</td>
									<td >:</td>
									<td >{{dayjs(tanggal).format('DD MMMM YYYY')}}</td>
								</tr>
							</table>
							<p>Mengetahui pihak lembaga (instansi)</p>
							
							<p class="mt-16 underline font-bold bg-yellow-100 print:bg-white uppercase" contenteditable @blur="onPejabatTujuanChanged($event, 'nama')">{{ pejabatTujuan.nama }}</p>
							<p>NIP. <span contenteditable class="bg-yellow-100 print:bg-white" @blur="onPejabatTujuanChanged($event, 'nip')">{{ pejabatTujuan.nip }}</span></p>
						</td>
						<td class="align-top w-[47.5%] border border-black p-2">
							<table>
								<tr>
									<td >Berangkat (kembali) dari</td>
									<td >:</td>
									<td class="bg-yellow-100 print:bg-white" contenteditable>{{ tujuan }}</td>
								</tr>
								<tr>
									<td >Pada tanggal</td>
									<td >:</td>
									<td >{{dayjs(tanggal).format('DD MMMM YYYY')}}</td>
								</tr>
							</table>
							<p>Mengetahui pihak lembaga (instansi)</p>
							
							<p class="mt-16 underline font-bold uppercase">{{pejabatTujuan.nama}}</p>
							<p>NIP. {{ pejabatTujuan.nip }}</p>
						</td>
					</tr>
					<tr>
						<td class="align-top w-[5%] border-b border-s border-t border-black p-2">2</td>
						<td class="align-top w-[47.5%] border-t border-b border-black p-2">
							<table>
								<tr>
									<td >Tiba kembali</td>
									<td >:</td>
									<td >{{ sekolah.desa }}</td>
								</tr>
								<tr>
									<td >Pada tanggal</td>
									<td >:</td>
									<td >{{dayjs(tanggal).format('DD MMMM YYYY')}}</td>
								</tr>
							</table>
							<p>Kepala {{ sekolah.nama }}</p>
							
							<p class="mt-16 underline font-bold">{{ sekolah.ks.nama }}</p>
							<p>NIP. {{sekolah.ks.nip}}</p>
						</td>
						<td class="align-top w-[47.5%] border border-black p-2">
							<p class="text-justify">Telah diperiksa dengan keterangan bahwa perjalanan tersebut di atas benar dilakukan atas perintahnya dan semata-mata untuk kepentingan jabaran dalam waktu yang sesingkat-singkatnya.</p>
						</td>
					</tr>
					<tr>
						<td class="align-top w-[5%] border-b border-s border-t border-black p-2">3</td>
						<td class="align-top w-[47.5%] border-t border-b border-black p-2 font-bold">
							CATATAN LAIN-LAIN
						</td>
						<td class="align-top w-[47.5%] border border-black p-2 h-[150px]">
							
						</td>
					</tr>
				</table>
				<p class="font-bold">PERHATIAN</p>:
				<p class="text-justify">Pejabat yang berwenang menerbitkan SPPD, pegawai yang melakukan perjalanan dinas, para pejabat yang mengesahkan tanggal berangkat/tiba serta bendaharawan bertanggung-jawab berdasarkan peraturan-peraturan keuangan negara. Apabila negara menderita kerugian akibat kesalahan, kelalaian dan kealpaan (angka 3, Lampiran Surat Edaran Menteri Keuangan tanggal 30 April 1974, no. B 296/MK/I/4/1974)</p>
			</div>
		</div>
	</div>
</template>