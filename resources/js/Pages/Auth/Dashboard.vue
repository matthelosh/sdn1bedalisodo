<script setup>
import { ref, defineAsyncComponent } from 'vue';
import { Head } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Icon } from '@iconify/vue';
import axios from 'axios';

const AlertBox = defineAsyncComponent(() => import('@/Components/General/Alert.vue'))
const alertBox = ref(null)


const waFail = ref(false)

const message = ref({
    isGroup: false
})

const loading = ref(false)

const kirim = async() => {
    loading.value = true
    // message.text = attachment.value !== null ? attachment.value+message.value.text : message.value.text;
    await axios.post(route('dashboard.wa.send'), {data: JSON.stringify(message.value)})
                .then(res =>  {
                    loading.value = false
                    let color = res.data.status == 'ok' ? 'green' : 'red'
                    alertBox.value.open(`${res.data.msg}`, color)
                    message.value = {isGroup: false}
                    attachment.value = false
                }).catch(err => {
                    console.log(err)
                    loading.value = false
                })
}
const kirimKeUsers = async() => {
    await axios.post(route('dashboard.wa.users.send'), {data: JSON.stringify(message.value)})
                .then(res =>  {
                    console.log(res)
                }).catch(err => console.log(err))
}

const groups = ref([])

const listGroup = async() => {
    // alert('hi')
    await axios.post(route('dashboard.wa.group.index')).then(res => {
        groups.value = res.data.groups
    }).catch(err => console.log(err))
}

const onChatChanged = async(e) => {
    listGroup()
    console.log(e.target.value)
    if(e.target.value == true) {
        // listGroup()
    }
}

const attachment = ref(null)

const onFilePicked = async(e) => {
    let file = e.target.files[0]
    let reader = new FileReader();
    reader.onload = () => {
        let base64 = reader.result.replace("data:", "").replace(/^.+,/, "");
        // console.log(base64)
        message.value.media = base64
    }
    reader.readAsDataURL(file)
    attachment.value = URL.createObjectURL(file)
}

</script>

<template>
<Head title="Dashboard" />
<AdminLayout>
    <div class="wrapper w-full max-w-screen p-3 gap-3 grid grid-cols-1 md:grid-cols-3">
        <div class="form-wa w-full bg-white p-3 shadow hover:shadow-lg transition">
            <form @submit.prevent="kirim">
                <h1 class="text-lg font-bold text-slate-500 flex items-center mb-2 border-b pb-2 gap-1">
                    <Icon icon="mdi:whatsapp" class="text-green-500 text-xl" />
                    Kirim Pesan Whatsapp
                </h1>
                <fieldset :disabled="loading">
                    <label for="no_wa" class="w-full flex justify-between my-2">
                        Percakapan
                        <select required v-model="message.isGroup" class="w-[60%] py-1 border-none bg-slate-100" @change="onChatChanged">
                            <option :value="false">Pribadi</option>
                            <option :value="true">Grup</option>
                        </select>
                    </label>
                    <label for="penerima" class="w-full flex justify-between my-2" >
                        Penerima
                        <input type="text" v-model="message.chatId" placeholder="No. WA" class="w-[60%] py-1 border-none bg-slate-100" required v-if="!message.isGroup">
                        <span class="relative w-[60%] " v-else>
                            <!-- <input type="text" v-model="message.chatId" placeholder="Grup WA" class="py-1" required > -->
                            <select v-model="message.chatId" class="py-1 w-full border-none bg-slate-100">
                                <option 
                                    v-for="group in groups" :key="group.id"
                                    :value="group.chatId"
                                    class=" whitespace-pre-wrap w-[100%]">
                                        {{ group.name }}
                                </option>
                            </select>
                        </span>
                    </label>
                    <label for="text" class="w-full flex justify-between my-2">
                        Isi Pesan
                        <textarea v-model="message.text" placeholder="Isi Pesan" class="w-[60%] py-1 border-none bg-slate-100 resize-y" required />
                    </label>
                    <label for="text" class="w-full flex justify-between my-2">
                        Lampirkan file
                        <span class="w-[60%]">
                            <input type="file" ref="fileAttachment" class="bg-pink-400 w-full hidden" @change="onFilePicked">
                            <button @click.prevent="$refs.fileAttachment.click()">
                                <Icon icon="mdi:attachment" class="text-xl text-slate-600" />
                            </button>
                            <img :src="attachment" alt="Lampiran" class="w-full" v-if="attachment !== null">
                        </span>
                    </label>
                    <label for="text" class="w-full flex justify-between my-2">
                        <p>&nbsp</p>
                        <button class="w-[60%] py-1 bg-sky-400 text-white flex gap-1 items-center justify-center">
                            <Icon icon="mdi:loading" v-if="loading" class="animate-spin" />
                            <Icon icon="mdi:send" v-else />
                            Kirim
                        </button>
                    </label>
                </fieldset>
            </form>
            <!-- {{ groups }} -->
        </div>
        <!-- <div class="form-wa w-full bg-white p-3">
            <form @submit.prevent="kirimKeUsers">
                <label for="text" class="w-full flex justify-between my-2">
                    Isi Pesan
                    <textarea v-model="message.text" placeholder="Isi Pesan" class="w-[60%] py-1" required />
                </label>
                <label for="text" class="w-full flex justify-between my-2">
                    <p>&nbsp</p>
                    <button class="w-[60%] py-1 bg-sky-400 text-white flex gap-1 items-center justify-center">
                        <Icon icon="mdi:send" />
                        Kirim
                    </button>
                </label>
            </form>
        </div> -->
    </div>
</AdminLayout>
<AlertBox ref="alertBox" />
</template>
