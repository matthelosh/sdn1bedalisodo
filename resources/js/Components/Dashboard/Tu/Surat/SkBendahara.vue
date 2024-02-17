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
const props = defineProps({selectedSurat: Object, ptks:Array})

const sekolah = computed(() => page.props.sekolah)
const Kop = defineAsyncComponent(() => import('@/Components/General/Kop.vue'));
const loading = ref(false)
const tanggal = ref('2024-02-14')
const lastNumber = ref(null)
const nipLama = ref(null)

const surat = ref({
		kode: computed(() => `800/${lastNumber.value}/35.07.101.408.012/${tanggal.value.substring(0,4)}`),
		klasifikasi_id: '800',
		no_surat: computed(() =>lastNumber.value),
		kategori: 'SkBendahara',
		perihal: 'Guru sebagai bendahara BOS',
		penerima: 'Guru',
		tanggal: computed(()=>tanggal.value),
		tembusan: 'Korwil',
		status: 'arsip'
})
const penerima = computed(() =>{
    return surat.value.penerima == 'Guru' ? {nama: 'Pilih penerima'} : props.ptks.filter(ptk => ptk.nip === surat.value.penerima)[0]
})
const lama = computed(() =>{
    return nipLama.value !== null ?  props.ptks.filter(ptk => ptk.nip === nipLama.value)[0] : {nama: 'Pilih Bendahara Sebelumnya'}
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
					<h3 class=" uppercase font-bold">Keputusan Kepala Sekolah</h3>
					<h3 class=" uppercase font-bold">{{ sekolah.nama }}</h3>
					<h4>Nomor: 800/{{lastNumber}}/35.07.101.408.012/{{ tanggal.substring(0,4) }}</h4>
					<h3 class="font-bold uppercase">Tentang</h3>
					<h3 class="font-bold uppercase mx-auto w-[90%] print:w-[90%]">Penetapan {{ surat.perihal }} di {{sekolah.nama}} </h3>
				</div>

				<div class="content pt-4 w-[80%] print:w-[90%] mx-auto text-justify">
					<table>
						<tr>
							<td class="px-1 align-top">Menimbang</td>
							<td class="px-1 align-top">:</td>
							<td class="px-1 align-top pb-4 print:pb-1">
                                <ul class="pl-4 list-[lower-alpha]">
                                    <li class="leading-4">
                                        Dalam rangka penertipan pengelolaan keuangan sekolah SDN 1 Bedalisodo ,maka perlu dibentuk Bendahara  Sekolah
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
                                        Undang – Undang nomor 20 tahun 2003 ,tentang sistim Pendidikan Nasional .
                                    </li>
                                    <li class="leading-4">
                                        Undang – Undang nomor 32 tahun 2005 , tentang Pemerentah Daerah.
                                    </li>
                                    <li class="leading-4">
                                        Keputusan Menteri Pendidikan Nasional Nomor : 2 004 / U / 2002,tentang Dewan Pendidikan dan Komite Sekolah. 
                                    </li>
                                    <li>
                                        Keputusan Menteri Pendidikan Nasional Nomor : 060 / U / 2002,tentang pedoman pendirian sekolah.
                                    </li>
                                    <li>
                                        Hasil Rapat komite sekolah dan Dewan Guru pada tanggal 22  Desember  2023  di SDN 1 Bedalisodo.
                                    </li>
								</ul>
							</td>
						</tr>
						<tr>
							<td class="py-2 text-center font-bold" colspan="3">MEMUTUSKAN</td>
						</tr>
						<tr>
							<td class="px-1">Menetapkan</td>
							<td>:</td>
							<td>
                                <el-select v-model="surat.penerima" placeholder="Penerima" class="print:hidden">
                                    <el-option v-for="ptk in ptks" :key="ptk.nip" :value="ptk.nip"> {{ ptk.nama }}</el-option>
                                </el-select>
                            </td>
						</tr>
						<tr>
							<td class="px-1 align-top uppercase">Pertama</td>
							<td class="px-1 align-top">:</td>
							<td class="px-1 align-top pb-2 leading-4">
                                <table>
                                    <tr>
                                        <td>Nama</td>
                                        <td>:</td>
                                        <td class="uppercase font-bold bg-yellow-100 print:bg-white" contenteditable>{{penerima?.nama}}, {{ penerima?.gelar_belakang ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td>NIP</td>
                                        <td>:</td>
                                        <td class=" bg-yellow-100 print:bg-white" contenteditable>{{penerima?.nip ?? '-'}}</td>
                                    </tr>
                                    <tr>
                                        <td>Tempat, tanggal lahir</td>
                                        <td>:</td>
                                        <td class=" bg-yellow-100 print:bg-white" contenteditable>{{penerima?.tempat_lahir ?? '-'}}, {{penerima?.tanggal_lahir ? dayjs(penerima?.tanggal_lahir).format('DD MMMM YYYY') : '-'}}</td>
                                    </tr>
                                    <tr>
                                        <td>Agama</td>
                                        <td>:</td>
                                        <td class=" bg-yellow-100 print:bg-white" contenteditable>{{penerima?.agama ?? '-'}}</td>
                                    </tr>
                                    <tr>
                                        <td>Pendidikan</td>
                                        <td>:</td>
                                        <td class=" bg-yellow-100 print:bg-white" contenteditable>{{penerima?.pendidikan ?? '-'}}</td>
                                    </tr>
                                    <tr>
                                        <td>Unit Kerja</td>
                                        <td>:</td>
                                        <td class=" bg-yellow-100 print:bg-white" contenteditable>{{sekolah.nama}} Kecamatan {{ sekolah.kecamatan }}</td>
                                    </tr>
                                </table>
							</td>
						</tr>
						<tr>
							<td class="px-1 align-top uppercase">Kedua</td>
							<td class="px-1 align-top">:</td>
							<td class="px-1 align-top pb-2 leading-4">
								Terhitung mulai tanggal <span class="bg-yellow-100 print:bg-white" contenteditable>{{ dayjs(surat.tanggal).format('DD MMMM YYYY') }}</span> sampai dengan <span class="bg-yellow-100 print:bg-white" contenteditable>{{ dayjs(surat.tanggal).add(1, 'year').format('DD MMMM YYYY') }}</span> sebagai Bendahara BOS di {{ sekolah.nama }}.
							</td>
						</tr>
						<tr>
							<td class="px-1 align-top uppercase">Ketiga</td>
							<td class="px-1 align-top">:</td>
							<td class="px-1 align-top pb-2 leading-4">
								Apabila terdapat kekeliruan dalam penetapan keputusan ini akan diadakan pembetulan sebagaimana mestinya
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
					<ul class="list-decimal pl-4">
						<li class="leading-4" v-for="(temb, t) in surat.tembusan" :key="t">{{ temb }}</li>
					</ul>
				</div>
			</div>

            <div class="sheet w-full md:w-[900px] print:w-[100%] mx-auto p-6 print:p-0 bg-white mt-4 print:m-0 break-after-page break-inside-avoid-page">
                <Kop />
                <div class="title text-center uppercase font-bold mt-10 print:mt-4 mb-4 print:text-[.9rem]">
                    <h3>Keputusan Kepala Sekolah</h3>
                    <h3>Nomor: <span class="bg-yellow-100 print:bg-white" contenteditable> {{ surat.kode }}</span></h3>
                    <h3>Tentang</h3>
                    <h3>Pergantian Bendahara BOS di {{ sekolah.nama }} Kecamatan {{ sekolah.kecamatan }}</h3>
                </div>

                <div class="content mt-6 print:text-[.9rem]">
                    <table class="w-[80%] print:w-[90%] mx-auto">
                        <tr>
                            <td class="align-top px-1">Menimbang</td>
                            <td class="align-top px-1">:</td>
                            <td class="align-top px-1">Dalam rangka penertiban pengelolaan keuangan sekolah,maka perlu dibentuk bendahara Sekolah</td>
                        </tr>
                        <tr>
                            <td class="align-top px-1">Mengingat</td>
                            <td class="align-top px-1">:</td>
                            <td class="align-top px-1">
                                <ul class="pl-4 list-decimal">
                                    <li>Undang -undang Nomor 17 tahun 2003 tentang Keuangan Daerah</li>
                                    <li>Undang-undang Nomor 20 tahun 2003 tentang Sistim Pendidikan daerah</li>
                                    <li>Undang-Undang Nomor 32 Tahun 2004 tentang Pemerintah Daerah</li>
                                    <li>Keputusan Menteri Pendidikan Nasional Nomor 060/U/2002,pedoman pendirian sekolah</li>
                                    <li> Hasil Rapat Komite Sekolah dan Dewan Guru pada tanggal 22 Desember 2023</li>
                                </ul>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="3" class="text-center font-bold uppercase pt-4 pb-2">Memutuskan</td>
                        </tr>
                        <tr>
                            <td class="align-top px-1">Menetapkan</td>
                            <td class="align-top px-1">:</td>
                            <td class="align-top px-1">
                                <el-select v-model="nipLama" placeholder="Bendahara Lama" class="print:hidden">
                                    <el-option v-for="bend in ptks" :key="bend.id" :value="bend.nip">{{ bend.nama }}</el-option>
                                </el-select>
                            </td>
                        </tr>
                        <tr>
                            <td class="align-top px-1 uppercase">Pertama</td>
                            <td class="align-top px-1">:</td>
                            <td class="align-top px-1">
                                <table>
                                    <tr>
                                        <td>Nama</td>
                                        <td>:</td>
                                        <td>{{ lama.nama }}, {{ lama.gelar_belakang ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td>NIP</td>
                                        <td>:</td>
                                        <td>{{ lama.nip ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td>Tempat, tanggal lahir</td>
                                        <td>:</td>
                                        <td>{{ lama.tempat_lahir ?? '-' }}, {{lama?.tanggal_lahir ? dayjs(lama?.tanggal_lahir).format('DD MMMM YYYY') : '-'}}</td>
                                    </tr>
                                    <tr>
                                        <td>Agama</td>
                                        <td>:</td>
                                        <td>{{ lama.agama ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td>Pendidikan</td>
                                        <td>:</td>
                                        <td>{{ lama.pendidikan ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td>Alamat</td>
                                        <td>:</td>
                                        <td>{{ lama.alamat ?? '-' }}</td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td class="align-top px-1 uppercase">Kedua</td>
                            <td class="align-top px-1">:</td>
                            <td class="align-top px-1">
                                <table>
                                    <tr>
                                        <td colspan="3">terhitung mulai tangga {{ dayjs(surat.tanggal).format('DD MMMM YYYY') }} tugas sebagai bendahara di {{sekolah.nama}} digantikan oleh:</td>
                                    </tr>
                                    <tr>
                                        <td>Nama</td>
                                        <td>:</td>
                                        <td>{{ penerima?.nama }}, {{ penerima?.gelar_belakang ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td>NIP</td>
                                        <td>:</td>
                                        <td>{{ penerima?.nip ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td>Tempat, tanggal lahir</td>
                                        <td>:</td>
                                        <td>{{ penerima?.tempat_lahir ?? '-' }}, {{penerima?.tanggal_lahir ? dayjs(penerima?.tanggal_lahir).format('DD MMMM YYYY') : '-'}}</td>
                                    </tr>
                                    <tr>
                                        <td>Agama</td>
                                        <td>:</td>
                                        <td>{{ penerima?.agama ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td>Pendidikan</td>
                                        <td>:</td>
                                        <td>{{ penerima?.pendidikan ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td>Alamat</td>
                                        <td>:</td>
                                        <td>{{ penerima?.alamat ?? '-' }}</td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td class="align-top px-1 uppercase">Ketiga</td>
                            <td class="align-top px-1">:</td>
                            <td class="align-top px-1">
                                Apabila dalam surat keputusan ini terdapat kekeliruan dapat dibetulkan di kemudian hari
                            </td>
                        </tr>
                    </table>
                    <p>Demikian surat ini dibuat untuk dipergunakan sebagaimana mestinya.</p>
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
            </div>
		</div>
	</div>
</template>