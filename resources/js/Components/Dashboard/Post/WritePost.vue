<script setup>
import { ref, onBeforeMount } from 'vue';
import axios from 'axios';
import {
  Doc,
  Text,
  Paragraph,
  // text extensions
  Bold,
  Underline,
  Italic,
  Strike,
  Code,
  Heading,
  Link,
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

const FileManager = defineAsyncComponent(() => import('@/Components/File/FileManager.vue'));
const showFm = ref(false)

const props = defineProps({selectedPost: Object});
defineEmits(['close']);

const upload = ref(null)

const extensions = [
  Doc,
  Text,
  Paragraph,
  Heading,
  Bold,
  Underline,
  Italic,
  TextAlign,
  Indent,
  Blockquote,
  LineHeight,
  Link,
  Strike,
  BulletList,
  OrderedList,
  Image.configure({
    bubble: true,
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
  Iframe.configure({ bubble: true }),
  HorizontalRule.configure({ bubble: true }),
  Print.configure({ bubble: true }),
  SelectAll.configure({ bubble: true }),
  Fullscreen.configure({ bubble: true }),
  CodeView.configure({
    bubble: true,
    codemirror,
    codemirrorOptions: {
      styleActiveLine: true,
      autoCloseTags: false,
    },
  }),
  CodeBlock,
  Code
];

const post = ref({})
const content = ref('Halo')

onBeforeMount(() => {
  if(props.selectedPost) {
    post.value = props.selectedPost
  }
})
</script>

<template>
  <FileManager v-if="showFm" />
  <element-tiptap v-model:content="post.content" :extensions="extensions" class="shadow-lg">
  </element-tiptap>
</template>
