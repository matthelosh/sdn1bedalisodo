<script setup>
import { usePage, Head } from '@inertiajs/vue3';
import SvgIcon from '@jamescoyle/vue-icon';
import { mdiAccountTie, mdiCalendarBadgeOutline, mdiStar } from '@mdi/js';
import { defineAsyncComponent, ref, computed } from 'vue';

const page = usePage();
const props = defineProps({post: Object})

const PostLayout = defineAsyncComponent(() => import('@/Layouts/PostLayout.vue'))

const description = computed(() => {
    let text = props.post.content.match(/<p(.)>.*?<\/p\1>/g) ? props.post.content.match(/<p(.)>.*?<\/p\1>/g) : props.post.title
    return text
})

const post = computed(() => props.post)


</script>

<template>
<Head>
    <title>{{post.title}}</title>
    <meta name="description" :content="description" />
</Head>
<PostLayout>
    <div class="w-full my-6 ">
        <h1 class="text-xl md:text-6xl font-light text-slate-800 font-serif py-4 px-4 md:px-0 tracking-wider w-full md:w-8/12 mx-auto">{{ post.title }}</h1>
        <p class="w-full md:w-8/12 mx-auto px-4 md:px-0">Ditulis Oleh <span class="text-orange-600 font-bold">{{ post.author.userable.nama }}</span>, Ditulis pada <span class="text-orange-600 font-bold">{{ new Date(post.created_at).toLocaleDateString("id-ID") }}</span>, Dilihat <span class="bg-orange-600 font-bold px-2 rounded text-white">{{ post.views.length }}</span> kali</p>
        <div class="feature-image w-full mx-auto relative h-[200px] md:h-[500px]  border-b-2 border-gray-50 shadow-inner rounded transition-all my-4 md:my-8" :style="`background: url('${post.featured_image}'); background-repeat:no-repeat; background-size: cover; background-attachment: fixed;`">
          <div class="img-mask z-5 absolute top-0 right-0 bottom-0 left-0 flex items-center justify-center bg-white bg-opacity-10  hover:bg-opacity-0  transition-all">
          </div>
        </div>
        <article v-html="post.content" class="text-gray-800 w-full px-4 md:px-0 md:w-8/12 mx-auto font-serif text-justify leading-loose text-lg tracking-wide">
        </article>
    </div>  
</PostLayout>
</template>

<style>
article p:nth-child(1)::first-letter {
  font-family: "Merriweather", serif;
  initial-letter: 3.5 3;
  font-weight: bold;
  line-height: 1;
  margin-right: 1rem;
  color: var(--surface-1);
  background: #be4bdb;
  padding: 0.5rem;
  border-radius: 5px;
  box-shadow: 0.5rem 0.5rem 0 #3b5bdb;
}
article p {
  margin-top: 1.5rem;
}

article figure {
  margin: 5px auto;
}

article a {
  color: purple;
  font-weight: 900;
}

article a:hover {
  text-decoration: underline;
}

article figure figcaption {
  text-align:center;
  color: #6a6a6a;
}
 /* .aden {
    position: absolute;
    top: 0;
    right: 0;
    bottom: 0;
    left: 0;
    backdrop-filter: contrast(90%) brightness(120%) saturate(85%) sepia(0%) hue-rotate(20deg) grayscale(0%) invert(0%) blur(0px);
    mix-blend-mode: darken;
    background: linear-gradient(to right, rgba(66, 10, 14, 0.2) 1, rgba(66, 10, 14, 0));
    opacity: 1;
}
._1977 {
    backdrop-filter: contrast(110%) brightness(110%) saturate(130%) sepia(0%) hue-rotate(0deg) grayscale(0%) invert(0%) blur(0px);
  mix-blend-mode: screen;
  background: rgba(243, 106, 188, 0.3);
  opacity: 1;
}
.amaro {
    backdrop-filter: contrast(90%) brightness(110%) saturate(150%) sepia(0%) hue-rotate(-10deg) grayscale(0%) invert(0%) blur(0px);
  mix-blend-mode: normal;
  background: rgba(0, 0, 0, 0.0);
  opacity: 1;
}
.brooklyn {
    backdrop-filter: contrast(90%) brightness(110%) saturate(100%) sepia(0%) hue-rotate(0deg) grayscale(0%) invert(0%) blur(0px);
  mix-blend-mode: overlay;
  background: radial-gradient(50% 50%, circle closest-corner, rgba(168, 223, 193, 0.4) 1, rgba(183, 196, 200, 0.2));
  opacity: 1;
}
.clarendon {
    backdrop-filter: contrast(120%) brightness(125%) saturate(100%) sepia(0%) hue-rotate(0deg) grayscale(0%) invert(0%) blur(0px);
  mix-blend-mode: overlay;
  background: rgba(127, 187, 227, 0.2);
  opacity: 1;
} 
.lofi {
    backdrop-filter: contrast(150%) brightness(100%) saturate(110%) sepia(0%) hue-rotate(0deg) grayscale(0%) invert(0%) blur(0px);
  mix-blend-mode: multiply;
  background: radial-gradient(50% 50%, circle closest-corner, rgba(0, 0, 0, 0) 70, rgba(34, 34, 34, 1));
  opacity: 1;
}

.xpro2 {
    backdrop-filter: contrast(100%) brightness(100%) saturate(100%) sepia(30%) hue-rotate(0deg) grayscale(0%) invert(0%) blur(0px);
  mix-blend-mode: color-burn;
  background: rgba(62, 162, 253, 0.5);
  opacity: 1;
}
.toaster {
    backdrop-filter: contrast(110%) brightness(110%) saturate(130%) sepia(0%) hue-rotate(0deg) grayscale(0%) invert(0%) blur(0px);
  mix-blend-mode: screen;
  background: radial-gradient(50% 50%, circle closest-corner, rgba(15, 78, 128, 1) 1, rgba(59, 0, 59, 1));
  opacity: 0.5;
}  */
</style>