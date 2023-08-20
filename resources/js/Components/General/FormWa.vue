<script setup>
import { ref, defineAsyncComponent, onMounted } from 'vue';
import { Icon } from '@iconify/vue';
import axios from 'axios';


const loading = ref(false)
const props = defineProps({hp: String, isGroup: Boolean})

const emit = defineEmits(['close'])

onMounted(() => {
    if(props.hp) {
        let chatId = '';
        let hp = props.hp.replace(/[+-]/gi, "")
        if(hp[0] == '0') {
            chatId = "62"+props.hp.slice(1)
        } else {
            chatId = hp
        }
        listGroup()
        message.value.isGroup = props.isGroup
        message.value.chatId = chatId
    }
})



const AlertBox = defineAsyncComponent(() => import('@/Components/General/Alert.vue'))
const alertBox = ref(null)

const message = ref({})

const kirim = async() => {
    loading.value = true
    // message.text = attachment.value !== null ? attachment.value+message.value.text : message.value.text;
    await axios.post(route('dashboard.wa.send'), {data: JSON.stringify(message.value)})
                .then(res =>  {
                    console.log(res)
                    loading.value = false
                    let color = res.data.status == 'ok' ? 'green' : 'red'
                    alertBox.value.open(`${res.data.msg}`, color)
                    // message.value = {isGroup: false}
                    // attachment.value = null
                }).catch(err => {
                    console.log(err)
                    loading.value = false
                })
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
    // alert(file.name)
    let reader = new FileReader();
    reader.onload = () => {
        let base64 = reader.result.replace("data:", "").replace(/^.+,/, "");
        // console.log(base64)
        message.value.media = {file: base64, type: file.type, name: file.name}
    }
    reader.readAsDataURL(file)
    attachment.value = URL.createObjectURL(file)
}


</script>
<template>
    <div class="wrapper overlay fixed top-0 right-0 bottom-0 left-0 z-50 bg-black bg-opacity-50 flex items-center justify-center backdrop-blur-sm">
        <div class="form-wa bg-white p-3 shadow hover:shadow-lg transition w-[95vw] md:w-[500px]">
            <h1 class="text-lg font-bold text-slate-500 flex items-center justify-between mb-2 border-b pb-2 gap-1">
                <span class=" flex items-center ">
                    <Icon icon="mdi:whatsapp" class="text-green-500 text-xl" />
                    Kirim Pesan Whatsapp
                </span>
                <button @click="emit('close')">
                    <Icon icon="mdi:close-box" class="text-2xl text-red-600 hover:text-red-500 active:text-red-400" />
                </button>
            </h1>
            <form @submit.prevent="kirim">
                
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
    </div>
    <AlertBox ref="alertBox" />
</template>