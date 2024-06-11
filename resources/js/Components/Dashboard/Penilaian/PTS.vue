<script setup>
import { ref, computed, onMounted } from 'vue';
import { Icon } from '@iconify/vue';
import { router, usePage } from '@inertiajs/vue3'
// import { ElTable } from 'element-plus';
import { utils, writeFile, writeFileXLSX } from 'xlsx'

const props = defineProps({rombel: Object})

const kunci = ref('')
const nilais = ref([])

const hitungSkor = (jawab, index) => {
    let res = 0
    for (let j=0; j < jawab.length; j++) {
        res += jawab[j].toLowerCase() === kunci.value[j].toLowerCase()
    }

    nilais.value[index].skor = (res/kunci.value.length*100)
}

const unduh = () => {
    const ws = utils.json_to_sheet(nilais.value)
    const wb = utils.book_new()

    utils.book_append_sheet(wb,ws, "Skor Kelas"+props.rombel.label)

    writeFileXLSX(wb, "Skor "+props.rombel.label, { compression: true})
}

onMounted(() => {
    props.rombel.siswas.forEach((siswa, index) => {
        nilais.value.push({nisn: siswa.nisn, nama:siswa.nama, jawaban: '', skor: 0})
    })
})

</script>

<template>
    <div class="wrapper w-full">
        <el-row>
            <el-col :span="12">
                Masukkan kunci jawaban dan jawaban siswa
            </el-col>
            <el-col :span="12">
                <el-form-item label="Masukkan Kunci Jawaban">
                    <el-input v-model="kunci" placeholder="Kunci Jawaban">
                        <template #suffix>
                            <span class="text-center px-2 bg-slate-200 font-black">
                                {{ kunci.length }}
                            </span>
                        </template>
                    </el-input>
                </el-form-item>
                <el-button type="primary" @click="unduh">Download Excel</el-button>
            </el-col>
        </el-row>
        <el-table :data="props.rombel.siswas" height="80vh">
            <el-table-column type="index" label="#" />
            <el-table-column label="NISN" prop="nisn" width="125" />
            <el-table-column label="Nama" prop="nama" width="250" />
            <el-table-column label="Jenis Kelamin" prop="jk" width="100" />
            <el-table-column label="Jawaban">
                <template #default="scope">
                    <el-input placeholder="Masukkan jawaban siswa" v-model="nilais[scope.$index].jawaban" @input="hitungSkor($event, scope.$index)">
                        <template #prefix>
                            <span class="px-2 bg-slate-200 font-black">{{ nilais[scope.$index].jawaban.length }}</span>
                        </template>
                    </el-input>
                </template>
            </el-table-column>
            <el-table-column label="Skor">
                <template #default="scope">
                   {{ nilais[scope.$index].skor }} 
                </template>
            </el-table-column>
        </el-table>
    </div>
</template>