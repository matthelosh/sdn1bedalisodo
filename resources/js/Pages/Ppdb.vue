<script setup>
import { ref, computed, defineAsyncComponent } from 'vue'
import { router, usePage } from '@inertiajs/vue3'
import { Icon } from '@iconify/vue'

const page = usePage()

const Kop = defineAsyncComponent(() => import('../Components/General/Kop.vue'))

const casis = ref({

})
</script>

<template>
    <div class="w-full p-6 mb-6">
        <Kop />
        <h1 class="uppercase text-center font-bold text-xl text-slate-700 my-6">Formulir PPDB Tahun Pelajaran 20{{ parseInt(page.props.tapel.kode.substring(0,2))+1 }} / 20{{ parseInt(page.props.tapel.kode.substring(2,4)) +1 }}</h1>

        <el-form label-width="150">
            <div class="border p-4 my-4">
                <h3 class="mb-4 text-slate-700 font-bold uppercase tracking-wider flex items-center gap-1"><Icon icon="mdi:card-account-details" /> Data Pribadi</h3>
                <el-form-item label="NISN" >
                    <el-input v-model="casis.nisn" placeholder="Nomor Induk Siswa Nasional" />
                </el-form-item>
                <el-form-item label="NIK" >
                    <el-input v-model="casis.nik" placeholder="Nomor Induk di KK" />
                </el-form-item>
                <el-form-item label="Nama">
                    <el-input v-model="casis.nama" placeholder="Nama Lengkap" />
                </el-form-item>
                <el-form-item label="Jenis Kelamin">
                    <el-select v-model="casis.jk" placeholder="Pilih Jenis Kelamin">
                        <el-option value="Laki-laki">Laki-laki</el-option>
                        <el-option value="Perempuan">Perempuan</el-option>
                    </el-select>
                </el-form-item>
                <el-form-item label="Tempat Lahir">
                    <el-input placeholder="Tempat Lahir" v-model="casis.tempat_lahir"></el-input>
                </el-form-item>
                <el-form-item label="Tanggal Lahir">
                    <el-date-picker v-model="casis.tanggal_lahir" placeholder="Tanggal Lahir"></el-date-picker>
                </el-form-item>
                <el-form-item label="Alamat">
                    <el-input placeholder="Alamat Jalan / Dusun" v-model="casis.alamat" />
                </el-form-item>
                <div class="w-full flex">
                    <el-form-item label="RT">
                        <el-input v-model="casis.rt" placeholder="RT"></el-input>
                    </el-form-item>
                    <el-form-item label="RW">
                        <el-input v-model="casis.rw" placeholder="RW"></el-input>
                    </el-form-item>
                </div>
                <el-form-item label="Desa">
                    <el-input placeholder="Desa" v-model="casis.desa"></el-input>
                </el-form-item>
                <el-form-item label="Kecamatan">
                    <el-input placeholder="Kecamatan" v-model="casis.kecamatan"></el-input>
                </el-form-item>
                <el-form-item label="Kabupaten">
                    <el-input placeholder="Kabupaten" v-model="casis.kabupaten"></el-input>
                </el-form-item>
                <el-form-item label="Kode Pos">
                    <el-input placeholder="Kode Pos" v-model="casis.kode_pos"></el-input>
                </el-form-item>
                <el-form-item label="No. HP">
                    <el-input placeholder="No. HP / Whatsapp" v-model="casis.hp"></el-input>
                </el-form-item>
                <el-form-item label="Email">
                    <el-input placeholder="Email" v-model="casis.email"></el-input>
                </el-form-item>
                <el-form-item label="Asal TK">
                    <el-input placeholder="Asal TK" v-model="casis.asal_tk"></el-input>
                </el-form-item>
            </div>
            <div class="border p-4 my-4">
                <h3 class="mb-4 text-slate-700 font-bold uppercase tracking-wider flex items-center gap-1"><Icon icon="mdi:human-male-female-child" /> Data Orang Tua</h3>
                <el-form-item label="Nama Ayah">
                    <el-input placeholder="Nama Ayah" v-model="casis.nama_ayah" /> 
                </el-form-item>
                <el-form-item label="NIK Ayah">
                    <el-input placeholder="NIK Ayah" v-model="casis.nik_ayah" /> 
                </el-form-item>
                <el-form-item label="Nama Ibu">
                    <el-input placeholder="Nama Ibu" v-model="casis.nama_ibu" /> 
                </el-form-item>
                <el-form-item label="NIK Ibu">
                    <el-input placeholder="NIK Ibu" v-model="casis.nik_ibu" /> 
                </el-form-item>
                <el-form-item label="Nama Wali">
                    <el-input placeholder="Nama Wali" v-model="casis.nama_wali" /> 
                </el-form-item>
                <el-form-item label="NIK Wali">
                    <el-input placeholder="NIK Wali" v-model="casis.nik_wali" /> 
                </el-form-item>
                <el-form-item label="Hubungan Wali">
                    <el-select v-model="casis.hubungan_wali" placeholder="Hubungan Wali">
                        <el-option value="Kakek/Nenek">Kakek/Nenek</el-option>
                        <el-option value="Paman/Bibi">Paman/Bibi</el-option>
                        <el-option value="Saudara">Saudara</el-option>
                        <el-option value="Orang Tua Asuh">Orang Tua Asuh</el-option>
                        <el-option value="Lainnya">Lainnya</el-option>
                    </el-select> 
                </el-form-item>
            </div>
            <div class="flex justify-center">
                <el-button type="primary">Daftar</el-button>
            </div>
        </el-form>
    </div>
</template>

