<script setup>
import { ref, computed, defineAsyncComponent, onBeforeMount } from 'vue';
import { Head } from '@inertiajs/vue3';
import { Icon } from '@iconify/vue';
import axios from 'axios';
import Compressor from 'compressorjs'
import QrcodeVue from 'qrcode.vue';
import { UploadFilled } from '@element-plus/icons-vue'

const emit = defineEmits(['close'])
const props = defineProps({headers:Array, items: Array})
const mode = ref('list')
</script>

<template>
<div>
    <div class="content w-full p-3 bg-white ">
        <div class="table overflow-x-auto w-full h-[650px]" >
            <data-table :items="props.items" :headers="props.headers" show-index >
                <template #item-kode="item">
                    <el-button text type="primary" class="text-sm" @click="edit(item)">{{item.kode}}</el-button>
                </template>
                <template #item-arsip="{arsip}">
                    <el-link :href="arsip.url" target="_blank" :underline="false" class="my-4" v-if="arsip">
                        <qrcode-vue :value="arsip.url" :size="50" level="L" :foreground="'#363636'" />
                    </el-link>
                </template>
                <template #item-opsi="item">
                    <el-button-group rounded>
                        <el-button size="small" type="primary" @click="addArsip(item)">
                            <Icon icon="mdi:archive" />
                        </el-button>
                        <el-popconfirm placement="bottom-end" title="Yakin menghapus surat ini?">
                            <template #reference>
                                <el-button size="small"  type="danger">
                                    <Icon icon="mdi:delete" />
                                </el-button>
                            </template>
                        </el-popconfirm>
                    </el-button-group>
                </template>
            </data-table>
        </div>
        
    </div>
</div>
</template>