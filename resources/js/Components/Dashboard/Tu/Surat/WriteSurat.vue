<script setup>
import { ref, computed, defineAsyncComponent, onMounted } from 'vue';
import { usePage } from '@inertiajs/vue3';
import { Icon } from '@iconify/vue';

const page  = usePage();
const props = defineProps({selectedSurat: Object});
const emit = defineEmits(['close']);

import SkPbm from './SkPbm.vue';
import SkGtt from './SkGtt.vue';
import Sppd from './Sppd.vue';
import SkOperator from './SkOperator.vue';
import Undangan from './Undangan.vue';
import SKelulusan from './SKelulusan.vue';
import Lain from './Lain.vue';

const mode = ref('list')


let comps = { SkPbm, SkGtt, SkOperator, Sppd, Undangan, SKelulusan, Lain };
const ptks = ref([])
const jabatans = ref(
		{ ks: 'Kepala Sekolah',
			gkel: 'Guru Kelas',
			gor: 'Guru PJOK',
			gpai: 'Guru PAI',
			gen: 'Guru B. Inggris',
			pjg: 'Penjaga Sekolah'}
		)

const categories  =ref([
		{ kode: 'SkPbm', label: 'SK PBM'},
		{ kode: 'SkGtt', label: 'SK GTT'},
		{ kode: 'SkOperator', label: 'SK Operator'},
		{ kode: 'SkBendahara', label: 'SK Bendahara BOS'},
		{ kode: 'SkPanitia', label: 'SK Panitia'},
		{ kode: 'Sppd', label: 'SPPD'},
		{ kode: 'SkTugas', label: 'Surat Tugas'},
		{ kode: 'Undangan', label: 'UNDANGAN'},
		{ kode: 'SKelulusan', label: 'SK KELULUSAN'},
		{ kode: 'SKetNisn', label: 'SK KETERANGAN NISN'},
		{ kode: 'Lain', label: 'Lainnya'},
	])

const init = async() => {
    await axios.post(route('dashboard.guru.index'))
                .then(res => ptks.value = res.data.gurus)
}

const buka = (category) => {	
	mode.value = category
}

const close = () => {
	mode.value = 'list'
}

const cetak = async() => {
	let host = window.location.host
	let el = document.querySelector(".cetak")
	let cssUrl = page.props.app_env == 'local' ? 'http://localhost:5173/resources/css/app.css' : `/build/assets/app.css`
	let html = `<!doctype html>
				<html>
					<head>
						<title>Surat Keluar</title>
						<link rel="stylesheet" href="${cssUrl}" />
					</head>
					<body>
						${el.outerHTML}
					</body>

				</html>
	`
	let win = window.open(host+'/print',"_blank","height=600,width=1024")
	await win.document.write(html)
    setTimeout(() => {
        win.print();
        win.close();
    }, 1500);
}

onMounted(() => {
	init()
	if (props.selectedSurat !== null) {
		// console.log(props.selectedSurat)
		mode.value = props.selectedSurat.kategori
	}
})
</script>

<template>
	<div class="wrapper pb-14">
		<div class="toolbar flex items-center justify-between h-12 bg-slate-200 p-3 sticky top-0 z-20">
			<h3>Pilih Template Surat</h3>
			<div class="toolbar-items flex items-center">
				<el-button circle text type="primary" @click="cetak">
					<Icon icon="mdi:printer" class="text-xl" />
				</el-button>
				<el-button circle text type="primary" @click="close">
					<Icon icon="mdi:menu" class="text-xl" />
				</el-button>
				
				<el-button circle text type="danger" @click="emit('close')">
					<Icon icon="mdi:close-circle" class="text-xl" />
				</el-button>
			</div>
		</div>
		<div class="content ">
			<div class="list p-6 grid grid-cols-1 md:grid-cols-6  gap-10" v-if="mode=='list'">
				<div class="box" v-for="category in categories" :key="category.kode">
					<div class="thumb w-full bg-slate-100 border shadow-xl hover:shadow-sm transition-all hover:cursor-pointer  h-[175px] md:h-auto p-2" @click="buka(category.kode)">
						<div class="kop h-4 md:h-8 w-full bg-slate-300 mb-4"></div>
						<div class="kop h-1 md:h-2 w-full bg-slate-300 mb-1 md:mb-2" v-for="i in 10" :key=i></div>
						<div class="ttd w-[30px] h-[30px] rounded-full bg-blue-100 flex items-center justify-center ml-[60%] mt-3">
							<div class="rounded-full w-[23px] h-[23px] bg-white bg-opacity-50 flex items-center">
								<div class="w-full h-[10px] bg-blue-100"></div>
							</div>
						</div>
					</div>
					<p class="text-center text-slate-700 mt-2">{{category.label}}</p>
				</div>
			</div>
			<div v-else>
				<component :is="comps[mode]" :ptks="ptks" :jabatans="jabatans" :selectedSurat="props.selectedSurat" ></component>
			</div>
		</div>
	</div>
</template>