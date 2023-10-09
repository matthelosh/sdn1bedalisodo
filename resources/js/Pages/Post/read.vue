<script setup>
import { usePage, Head } from '@inertiajs/vue3';
import { defineAsyncComponent, ref, computed } from 'vue';
import { Icon } from '@iconify/vue';

const page = usePage();

const PostLayout = defineAsyncComponent(() => import('@/Layouts/PostLayout.vue'))

const description = computed(() => {
    let text = page.props.post.content.match(/<p(.)>.*?<\/p\1>/g) ? page.props.post.content.match(/<p(.)>.*?<\/p\1>/g) : page.props.post.title
    return text
})

const post = computed(() => page.props.post)


</script>

<template>
<Head>
    <title>{{page.props.post.title}}</title>
    <meta name="description" :content="description" />
</Head>
<PostLayout>
    <div class="image w-full overflow-hidden mt-4 mb-3 rounded-xl h-64 md:h-[400px] relative shadow">
        <img :src=" post.featured_image !== '0' ? post.featured_image:'/img/kegiatan.jpg'" alt="Gambar fitur" class="featured-image h-64 md:h-[400px] w-full object-cover object-top">
        <div class="img-mask z-5 absolute top-0 right-0 bottom-0 left-0 lofi">
        </div>
        <div class="post-head absolute z-5 bottom-0 w-full bg-white bg-opacity-70 backdrop-blur p-2 box-border">
            <h1 class="text-xl font-bold text-gray-600">{{ page.props.post.title }}</h1>
            <p class="meta flex items-center mb-4">
                <Icon icon="mdi:account-tie" class="text-teal-600" /> 
                <span class="text-teal-800">
                    {{ post.author_id }}
                </span>
                &nbsp;
                <Icon icon="mdi:calendar-badge-outline"  class="text-orange-600" />
                <span class="text-orange-800">
                    {{ new Date(post.created_at).toDateString() }}
                </span>
                &nbsp;
                <Icon icon="mdi:star" class="text-yellow-400" v-if="post.starred" />
            </p>
        </div>
    </div>
    <div class="container bg-white p-4 rounded-xl shadow">
        
        <article v-html="page.props.post.content" class="text-gray-600">
        </article>
    </div>  
</PostLayout>
</template>

<style>
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
} */
</style>