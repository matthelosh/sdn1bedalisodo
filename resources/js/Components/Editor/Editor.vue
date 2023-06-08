<script setup>
import { ref, onMounted,computed } from 'vue'
import { usePage } from '@inertiajs/vue3'
import docx2html from 'docx2html'
import mammoth from 'mammoth'
import EditorJS from '@editorjs/editorjs'
import Header from '@editorjs/header'
import RawTool from '@editorjs/raw'
import ImageTool from '@editorjs/image'
import Quote from '@editorjs/quote'
import List from '@editorjs/list'
import Table from '@editorjs/table'
import Underline from '@editorjs/underline'
import Marker from '@editorjs/marker'
import LinkTool from '@editorjs/link'
import editorJsParser from 'editorjs-html'
import uploader from '@ajite/editorjs-image-base64'
import Gambar from '@/Components/Editor/Classes/Gambar'
import * as docx from 'docx'

const page = usePage()

const editorParser = editorJsParser()
onMounted(() => {
    myEditor()
    // console.log(page.props.csrf_token)
    // console.log(document.querySelector('meta[name="csrf-token"]'))
})


const html = ref(`
    <p>Tulis Artike di sini.</p>
`)

const myEditor = () => {
    window.editor = new EditorJS({
        holder: 'editorjs',
        autofocus: false,
        placeholder: 'Tulis Artikel',
        inlineToolbar: true,
        tools: {
            header: {class: Header, inlineToolbar: true},
            raw: RawTool,
            // image: {
            //     class: ImageTool,
            //     config: {
            //         // additionalRequestHeaders: {
            //         //     // 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
            //         // },
            //         // endpoints: {
            //         //     byFile: 'http://sdn1bedalisodo.test/post/upload-image',
            //         //     byUrl: 'http://sdn1bedalisodo.test/post/upload-image',
            //         // }
            //         uploader
            //     }
            // },
            image: Gambar,
            marker: Marker,
            quote: Quote,
            link: LinkTool,
            list: {
                class: List,
                inlineToolbar: true,
                config: { defaultStyle: 'unordered'}
            }, 
            table: Table,
            underline: Underline
        },
        onReady: async () => {
            await editor.blocks.renderFromHTML(html.value)
        },
        
    })
}

const simpan = () => {

    editor.save().then(savedData =>{
        let html = editorParser.parse(savedData).join()
        console.log(html)
    })
}

const tes = ref('')
const onDocxPicked = async (ev) => {
    const file = ev.target.files[0]
    // await docx2html(file).then(html => {
    //     let text = html.toString().match(/<h(.)>.*?<\/h\1>/g)
    //     console.log(html.toString().split(/(body>)/))
        
    // })
    // html.value = mammoth.extractRawText(file)
    const reader  =new FileReader()
    reader.onload = async (e) => {
        await mammoth.convertToHtml({arrayBuffer: e.target.result})
                    .then(res => {
                        let text = new DOMParser().parseFromString(res.value, "text/html")
                        text.querySelectorAll("img").forEach(img => img.src = '/img/siswa.png')
                        let images = [...text.images].map(ee => ee.src)
                        // console.log(images)
                        html.value = text.querySelector("body").innerHTML
                        myEditor()
                    })
                    .catch(err => console.log(err))
    }
    reader.readAsArrayBuffer(file)
}
</script>

<template>
    <div class="w-3/4 bg-white mx-auto my-6 rounded-xl p-4 shadow-lg">
        <input type="file" ref="File Dokumen" @change="onDocxPicked">
        <div class="editor w-full m-0" id="editorjs"></div>
        <button @click="simpan">Simpan</button>
        <article v-html="tes"></article>
    </div>
</template>

<style>
    .editor .ce-header{
        font-size: 1.3rem;
        font-weight: 800;
        color: rgb(53, 51, 51);
    }

    .editor .gambar {
        padding: 20px 0;
    }

    .editor .gambar input {
        width: 100%;
        padding: 10px;
        border: 1px solid #e4e4e4;
        border-radius: 3px;
        outline: none;
        font-size: 14px;
    }
</style>