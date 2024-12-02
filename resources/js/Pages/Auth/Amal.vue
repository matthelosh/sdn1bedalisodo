<script setup>
import { ref, computed, onBeforeMount, defineAsyncComponent } from "vue";
import { usePage, Head } from "@inertiajs/vue3";
import { Icon } from "@iconify/vue";
import axios from "axios";
import { h } from "vue";
import { ElNotification } from "element-plus";
import { terbilang } from "@/Plugins/terbilang";

const page = usePage();

const loading = ref(false);
const Layout = defineAsyncComponent(() => import("@/Layouts/AdminLayout.vue"));
const saldo = ref(0);
const mutasis = ref([]);
const headers = ref([
    { text: "Tanggal", value: "tanggal" },
    { text: "Mutasi", value: "jenis" },
    { text: "Jumlah", value: "nilai" },
    { text: "Keterangan", value: "keterangan" },
]);
const formShown = ref(false);
const mutasi = ref({});

// Function
const simpan = async () => {
    loading.value = true;
    await axios
        .post(route("dashboard.amal.mutasi.store"), mutasi.value)
        .then((res) => {
            loading.value = false;
            ElNotification({ title: "Info", message: "Mutasi disimpan" });
            list();
        })
        .catch((err) => {
            loading.value = false;
            ElNotification({ title: "Error", message: err.response.data.msg });
        });
};

const list = async () => {
    loading.value = true;
    await axios.post(route("dashboard.amal")).then((res) => {
        loading.value = false;
        saldo.value = res.data.saldo;
        mutasis.value = res.data.mutasis;
    });
};

onBeforeMount(() => {
    list();
});
</script>

<template>
    <Layout>
        <Head title="Manajemen Amal Jumat" />
        <div class="wrapper">
            <div
                class="toolbar h-12 flex items-center justify-between p-3 bg-slate-200"
            >
                <h3 class="flex items-center gap-1">
                    <Icon icon="mdi:cash" />
                    Manajemen Amal Jumat
                </h3>
            </div>
            <div class="content pb-24">
                <div class="saldo w-full p-10 bg-sky-50">
                    <h3 class="text-center text-xl mb-4">Saldo:</h3>
                    <h1 class="text-center text-4xl font-bold text-blue-700">
                        Rp. {{ saldo.toLocaleString() }}
                    </h1>
                    <h2 class="text-center text-blue-600 text-xl font-bold">
                        {{ terbilang(saldo) }} Rupiah
                    </h2>
                </div>
                <div class="mutasi sticky offset-0">
                    <div
                        class="title bg-slate-200 p-3 flex items-center justify-between"
                    >
                        <span class="title"> Mutasi </span>
                        <div class="tool-items">
                            <button
                                class="bg-slate-50 p-2 rounded-full hover:bg-slate-400 active:bg-slate-300"
                                @click="formShown = true"
                            >
                                <Icon icon="mdi:plus" />
                            </button>
                        </div>
                    </div>
                </div>
                <div class="mx-4 mt-4">
                    <DataTable :headers="headers" :items="mutasis" />
                </div>
            </div>
        </div>
        <el-dialog v-model="formShown">
            <template #header>
                <h3>Formulir Mutasi</h3>
            </template>
            <div class="dialog-body">
                <el-form v-model="mutasi" label-position="top">
                    <el-form-item label="Tanggal">
                        <el-date-picker
                            value-format="YYYY-MM-DD"
                            format="YYYY-MM-DD"
                            type="date"
                            v-model="mutasi.tanggal"
                            placeholder="Tanggal Mutasi"
                        />
                    </el-form-item>
                    <el-form-item label="Mutasi">
                        <el-select
                            v-model="mutasi.jenis"
                            placeholder="Jenis Mutasi"
                        >
                            <el-option
                                v-for="jenis in ['masuk', 'keluar']"
                                :key="jenis"
                                :value="jenis"
                                >{{ jenis }}</el-option
                            >
                        </el-select>
                    </el-form-item>
                    <el-form-item label="Jumlah">
                        <el-input
                            placeholder="Jumlah Rupiah"
                            v-model="mutasi.nilai"
                            type="number"
                        >
                            <template #prefix>Rp.&nbsp; </template>
                        </el-input>
                    </el-form-item>
                    <el-form-item label="Keterangan">
                        <el-input
                            type="textarea"
                            v-model="mutasi.keterangan"
                            placeholder="Masukkan keterangan"
                        ></el-input>
                    </el-form-item>
                    <el-form-item>
                        <el-button
                            :loading="loading"
                            type="primary"
                            @click="simpan"
                            >Simpan</el-button
                        >
                    </el-form-item>
                </el-form>
            </div>
        </el-dialog>
    </Layout>
</template>
