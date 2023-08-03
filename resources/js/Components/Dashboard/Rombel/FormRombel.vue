<script setup>
import { Icon } from '@iconify/vue';

const emit = defineEmits(['close'])
</script>

<template>
    <div class="w-full bg-slate-100 bg-opacity-20 backdrop-blur-sm fixed top-0 right-0 bottom-0 left-0 flex items-center justify-center z-40" @click.self="close">
        <div class="dialog bg-white rounded shadow w-[400px] max-w-[800px]">
            <div class="toolbar h-10 flex items-center justify-between w-full p-2 bg-slate-200">
                <h1>Data Rombongan Belajar</h1>
                <div class="toolbar-items flex items-center gap-2 justify-end">
                    <button @click="emit('close')">
                        <Icon icon="mdi:close-box" class="text-2xl text-red-600 hover:text-red-400 active:text-red-800" />
                    </button>
                </div>
            </div>
            <div class="content p-2">
                <form ref="formGuru">
                    <label for="Label" class="w-full flex items-center  justify-between gap-2">
                        Gelar
                        <div class="w-[60%] flex gap-1">
                            <input type="text" class="bg-slate-200 border-0 rounded py-1 w-full" placeholder="Depan">
                            <input type="text" class="bg-slate-200 border-0 rounded py-1 w-full" placeholder="Belakang">
                        </div>
                    </label>
                    <label for="jk" class="w-full flex items-center  justify-between">
                        Jenis Kelamin
                        <select class="bg-slate-200 border-0 rounded py-1 w-[60%]">
                            <option value="Laki-laki">Laki-laki</option>    
                            <option value="Perempuan">Perempuan</option>    
                        </select>
                    </label>
                    <label for="tempat_lahir" class="w-full flex items-center  justify-between">
                        Tempat Lahir
                        <input type="text" class="bg-slate-200 border-0 rounded py-1 w-[60%]" placeholder="Tempat Lahir">
                    </label>
                    <label for="tanggal_lahir" class="w-full flex items-center  justify-between">
                        Tanggal Lahir
                        <input type="date" class="bg-slate-200 border-0 rounded py-1 w-[60%]">
                    </label>
                    <label for="agama" class="w-full flex items-center  justify-between ">
                        Agama
                        <select class="bg-slate-200 border-0 rounded py-1 w-[60%]">
                            <option value="Islam">Islam</option>    
                            <option value="Kristen">Kristen</option>    
                            <option value="Katolik">Katolik</option>    
                            <option value="Hindu">Hindu</option>    
                            <option value="Budha">Budha</option>    
                            <option value="Konghuchu">Konghuchu</option>    
                        </select>
                    </label>
                    <label for="hp" class="w-full flex items-center  justify-between ">
                        No. HP
                        <input type="text" class="bg-slate-200 border-0 rounded py-1 w-[60%]" placeholder="No. HP">
                    </label>
                    <label for="alamat" class="w-full ">
                        Alamat
                        <textarea type="text" class="bg-slate-200 border-0 rounded py-1 w-full" placeholder="Alamat"></textarea>
                    </label>
                    <div class="flex items-center justify-center py-2">
                        <button class="py-1 px-3 rounded bg-slate-800 text-white hover:bg-slate-400">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<style scoped>
label {
    border-bottom: 1px solid #dbd9d9;
    padding: .3rem 0;
}
</style>