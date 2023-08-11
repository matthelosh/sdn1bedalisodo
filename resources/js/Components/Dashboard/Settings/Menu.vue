<script setup>
import { ref, onMounted, defineAsyncComponent, computed } from 'vue';
import { Icon } from '@iconify/vue';
import axios from 'axios';

const loading = ref(false)
const Loading = defineAsyncComponent(() => import('@/Components/General/Loading.vue'))

const ConfirmDialog = defineAsyncComponent(() => import('@/Components/General/ConfirmDialog.vue'))
const confirmDialog = ref(null)

const menus = ref([])
const listMenus = async() => {
    loading.value = true
    await axios.post(route('dashboard.menu.index'))
                .then(res => {
                    menus.value = res.data.menus
                    loading.value = false
                })
                .catch(err => {
                    loading.value = false
                    console.log(err)
                })
}


const showForm = ref(false)
const newMenu = () => {
    // showForm.value = !showForm.value
    mode.value = mode.value == 'list' ? 'form' : 'list'
    menu.value = {}
}

const edit = (item) => {
    menu.value = item
    mode.value = 'form'
    
}

const mode = ref('list')
const menu = ref({})

const simpan = async() => {
    loading.value = true
    await axios.post(route('dashboard.menu.store'), { menu: JSON.stringify(menu.value)})
            .then(res => {
                loading.value = false
                listMenus()
                mode.value = 'list'
            }).catch( err => {
                loading.value = false
                console.log(err)
            })
}

const parents = computed(() => {
    let parents = [
        { value: '0', text: 'Induk'}
    ]

    menus.value.forEach(item => {
        parents.push({value: item.id, text: item.label})
    })

    return parents
})

const hapus = async(item) => {
    if (await confirmDialog.value.open("Yakin menghapus Menu "+item.label+"?")) {
        loading.value = true
        await axios.delete(route('dashboard.menu.destroy', {id: item.id}))
                    .then(res => {
                        loading.value = false
                        listMenus()
                    }).catch(err => {
                        loading.value = false
                    })
    }
}

onMounted(() => {
    listMenus();
})
</script>

<template>
    <div class="w-full relative shadow">
        <div class="toolbar h-10 bg-slate-200 flex items-center justify-between p-3">
            <h1>Daftar Menu</h1>
            <button class="shadow rounded-full" @click="newMenu">
                <Icon icon="mdi:plus-circle" class=" text-xl transition" :class="mode == 'form' ? 'rotate-45 text-red-400 hover:text-red-600' : 'rotate-0 text-sky-400 hover:text-sky-600'" />
            </button>
        </div>
        <div class="content w-full p-3 bg-white">
            <div class="table w-full overflow-x-auto">
                <table class="w-full text-sm transition-all" v-if="mode == 'list'">
                    <thead>
                        <tr class="bg-slate-500 text-white">
                            <th class="px-2 py-1 border border-slate-400">ID</th>
                            <th class="px-2 py-1 border border-slate-400">Label</th>
                            <th class="px-2 py-1 border border-slate-400">URL</th>
                            <th class="px-2 py-1 border border-slate-400">Ikon</th>
                            <th class="px-2 py-1 border border-slate-400">Induk</th>
                            <th class="px-2 py-1 border border-slate-400">opsi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="(menu, m) in menus"
                            :key="m"
                            class="odd:bg-slate-200"
                        >
                            <td class="border px-2 border-slate-400">{{menu.id}}</td>
                            <td class="border px-2 border-slate-400">
                                <button class="text-teal-600 hover:underline" @click="edit(menu)">
                                    {{menu.label}}
                                </button>
                            </td>
                            <td class="border px-2 border-slate-400">{{menu.url}}</td>
                            <td class="border px-2 border-slate-400">
                                <div class="flex items-center gap-2">
                                    <Icon :icon="`mdi:${menu.icon}`" class="text-slate-600" />
                                    {{menu.icon}}
                                    
                                </div>
                            </td>
                            <td class="border px-2 border-slate-400 text-center">{{menu.parent_id}}</td>
                            <td class="border px-2 border-slate-400 text-center">
                                <button @click="hapus(menu)">
                                    <Icon icon="mdi:delete" class="text-red-600 hover:text-red-800" />
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <form ref="formMenu" @submit.prevent="simpan" v-if="mode=='form'" class="w-full transition-all">
                    <fieldset :disabled="loading">
                        <label for="label" class="w-full flex justify-between my-2">
                            <span>Label</span>
                            <input type="text" placeholder="Label" class="border-none bg-slate-200 p-1 px-2 w-[50%]" v-model="menu.label" required />
                        </label>
                        <label for="label" class="w-full flex justify-between my-2">
                            <span>URL</span>
                            <input type="text" placeholder="URL" class="border-none bg-slate-200 p-1 px-2 w-[50%]" v-model="menu.url" required />
                        </label>
                        <label for="icon" class="w-full flex justify-between my-2">
                            <span>Ikon</span>
                            <span class="relative  w-[50%]">
                                <input type="text" placeholder="Ikon" class="border-none bg-slate-200 p-1 px-2 w-full" v-model="menu.icon" required />
                                <Icon :icon="`mdi:${menu.icon}`" class="absolute right-2 top-[50%] -translate-y-[50%] text-slate-600" />
                            </span>
                        </label>
                        <label for="label" class="w-full flex justify-between my-2">
                            <span>Parent</span>
                            <select class="border-none bg-slate-200 p-1 px-2 w-[50%]" v-model="menu.parent_id" required >
                                <option
                                    v-for="(menu, mn) in parents" :key="mn"
                                    :value="menu.value">
                                    {{ menu.text }}
                                </option>
                            </select>
                        </label>
                        <label for="role" class="w-full flex justify-between my-2">
                            <span>Role</span>
                            <select class="border-none bg-slate-200 p-1 px-2 w-[50%]" v-model="menu.roles" required >
                                <option
                                    v-for="role in ['all','admin','guru']" :key="role"
                                    :value="role">
                                    {{ role }}
                                </option>
                            </select>
                        </label>
                        <label for="label" class="w-full flex justify-center mt-4 mb-2">
                            <button class="bg-sky-600 hover:bg-sky-800 text-white flex items-center gap-1 py-1 px-2">
                                <Icon icon="mdi:loading" class="animate-spin" v-if="loading" />
                                Simpan
                            </button>
                        </label>
                    </fieldset>
                </form>
            </div>

        </div>
        <Loading v-if="loading" />
        <ConfirmDialog ref="confirmDialog" />
    </div>
    
</template>