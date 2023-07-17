<script setup>
import {usePage, Head, router} from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { defineAsyncComponent, ref } from 'vue';

const page = usePage()
const loading = ref(false)
const Loading = defineAsyncComponent(() => import('@/Components/General/Loading.vue'))

router.on('start', () => loading.value = true)
router.on('finish', () => loading.value = false)
</script>

<template>
<Head title="Inventaris" />
<Loading v-if="loading" />
<AdminLayout title="Inventaris">
    <div class="container w-full">
        <div class="toolbar h-10 bg-gray-100 sticky top-0 z-20 flex items-center justify-between">

        </div>
        <div class="content h-[200vh] relative bg-white p-4">
            <div v-for="i in 50" :key="i">
                <article>
                    <h1>Artikel {{ i }}</h1>
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Modi labore qui quibusdam cumque nisi nihil veniam iure, doloribus similique nemo, excepturi accusantium recusandae rerum, consequatur adipisci id laudantium. Ut, dicta. Lorem ipsum dolor sit, amet consectetur adipisicing elit. Quisquam neque iure debitis atque ut, cum voluptas porro beatae itaque voluptatum voluptates saepe quasi consequuntur dolor deserunt, nostrum sed sit amet! Lorem ipsum dolor, sit amet consectetur adipisicing elit. Blanditiis ullam ex, aliquam, cumque tempora at aperiam quo qui reprehenderit expedita adipisci dolores iste minus illo repellendus. Veritatis explicabo repellat dicta!</p>
                </article>
            </div>
        </div>
    </div>
</AdminLayout>
</template>