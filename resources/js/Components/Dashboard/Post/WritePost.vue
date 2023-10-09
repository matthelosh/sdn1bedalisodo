<script setup>
import { ref, onBeforeMount } from 'vue';
import { usePage } from '@inertiajs/vue3';
import axios from 'axios';
import {
  Doc,
  Text,
  History,
  Paragraph,
  Dropcursor,
  // text extensions
  FontFamily,
  Bold,
  Underline,
  Italic,
  Strike,
  Code,
  Heading,
  Link,
  Color,
  // ListItem,
  BulletList,
  OrderedList,
  TextAlign,
  LineHeight,
  Indent,
  Blockquote,
  CodeBlock,
  Image,
  Table,
  Iframe,
  HorizontalRule,
  Fullscreen,
  Print,
  SelectAll,
  CodeView,
} from 'element-tiptap-vue3-fixed';
import codemirror from 'codemirror';
import 'codemirror/lib/codemirror.css'; // import base style
import 'codemirror/mode/xml/xml.js'; // language
import 'codemirror/addon/selection/active-line.js'; // require active-line.js
import 'codemirror/addon/edit/closetag.js';
import { defineAsyncComponent } from 'vue';
import { Icon } from '@iconify/vue';


const FileManager = defineAsyncComponent(() => import('@/Components/File/FileManager.vue'));
const showFm = ref(false)

const page = usePage();
const props = defineProps({selectedPost: Object});
const emit = defineEmits(['close']);

const upload = ref(null)

const extensions = [
  Doc,
  Text,
  History,
  Dropcursor,
  Paragraph.configure({ bubble: true }),
  Heading.configure({ bubble: true }),
  FontFamily,
  Color.configure({bubble: true}),
  Bold.configure({ bubble: true }),
  Underline.configure({ bubble: true }),
  Italic.configure({ bubble: true }),
  TextAlign.configure({ bubble: true }),
  
  Indent,
  Blockquote,
  LineHeight,
  Link.configure({ bubble: true }),
  Strike.configure({ bubble: true }),
  BulletList,
  OrderedList,
  Image.configure({
    defaultWidth: 400,
    draggable: true,
    uploadRequest: async(file) => {
      let fd = new FormData();
      fd.append("image", file)
      return new Promise(async(resolve, reject) => {
        await axios.post(route('post.image.upload'), fd).then(res => {
          resolve(res.data.url)
        })
      });
    }
  }),
  Table,
  //  TableHeader, TableRow, TableCell,
  Iframe,
  HorizontalRule,
  Print,
  SelectAll,
  Fullscreen,
  CodeView.configure({
    codemirror,
    codemirrorOptions: {
      styleActiveLine: true,
      autoCloseTags: false,
    },
    
  }),
  CodeBlock,
  Code
];

const post = ref({
  content: `<p>Tulis konten di sini.</p>`,
  category_id: 'art',
  featured_image: '/img/no-image.jpg',
})
const toolbarTitle = ref('Tulisan Baru')

const categories = ref([]);
const listCategory = async() => {
  await axios.post(route('category.index')).then(res => {
    categories.value = res.data.categories
  })
}

const fileFeaturedImage = ref(null);
const featuredImage = ref(null)

const onPickedFeaturedImage = (e) => {
  let file = e.target.files[0]
  post.value.featured_image = URL.createObjectURL(file)
  featuredImage.value = file
}

// Simpan
const simpan = async() => {
  let fd = new FormData();
  let data = post.value;
  data.author_id = page.props.auth.user.name;
  fd.append("post", JSON.stringify(data));
  if(featuredImage.value !== null) fd.append("featured_image", featuredImage.value);

  await axios.post(route('dashboard.post.store'), fd)
            .then(res => {
              alert('Tulisan tersimpan')
              emit('close')
            })
            .catch(err => {
              console.log(err)
            })
}

onBeforeMount(() => {
  listCategory()
  if(props.selectedPost !== null) {
    post.value = props.selectedPost
    toolbarTitle.value = "Perbarui Tulisan"
  }
})
</script>

<template>
  <div class="border p-2 rounded-lg shadow-lg">
    <div class="toolbar w-full flex justify-between px-3 rounded-lg">
      <div class="toolbar-left flex items-center gap-2">
        <h1 class="text-sm text-slate-500 flex items-start gap-1">
          <Icon icon="mdi:typewriter" class="text-lg" />
          <span>
            {{ toolbarTitle }}
          </span>
        </h1>
      </div>
      <div class="toolbar-right flex items-center gap-2">
        <select v-model="post.category_id" class="py-1 rounded-lg bg-slate-50 border-none shadow-md text-sm tracking-wide">
          <option
            v-for="cat in categories"
            :value="cat.kode">
            {{ cat.label }}
          </option>
        </select>
        <button title="Buka Manajer File" @click="showFm = !showFm" class="flex items-center">
          <Icon icon="mdi:folder" class="text-yellow-400 drop-shadow-lg text-4xl" />
        </button>
        <input type="file" ref="fileFeaturedImage" class="hidden" @change="onPickedFeaturedImage" accept=".png, .jpg, .jpeg, .webp, .PNG, .JPG, .JPEG, .WEBP">
        <img :src="post.featured_image" alt="No Image" class="h-8 shadow-lg hover:-rotate-6 transition-all hover:cursor-pointer" title="Gambar Sampul" @click="fileFeaturedImage.click()">
        <button class="text-sm py-1 px-3 rounded-lg bg-teal-400 text-white shadow-md my-1 flex items-center gap-1" @click="simpan">
          <Icon icon="mdi:hdd" />
          <span class="hidden sm:inline">Simpan</span>
        </button>
        <button @click="$emit('close')">
          <Icon icon="mdi:close-circle" class="text-red-500 text-2xl drop-shadow-lg hover:text-red-400 active:text-red-300 hover:drop-shadow-md transition-all" />
        </button>
      </div>
    </div>
    <div class="title w-full">
      <input type="text" placeholder="Judul Tulisan" class="w-full border-none focus:outline-none focus:ring-0 focus:bg-slate-50 font-bold tracking-wide text-xl" v-model="post.title">
    </div>
    <div class="content w-full py-3">
      <element-tiptap v-model:content="post.content" :extensions="extensions" class="text-xs text-slate-400">
      </element-tiptap>
    </div>
  </div>
  <FileManager v-if="showFm" @close="showFm = false" />
</template>
