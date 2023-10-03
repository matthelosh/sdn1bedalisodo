<script setup>
import { computed } from 'vue';
import { Icon } from '@iconify/vue';
import { Link } from '@inertiajs/vue3';

const url = computed(() => {
    const paths = window.location.pathname.split("/")
    return paths
})
</script>

<template>
    <div class="w-full flex items-center gap-1 py-2 px-4 bg-slate-200">
        <Link href="/" class="flex items-center gap-1 text-teal-700 hover:underline">
            <Icon icon="mdi:home" />
            Beranda
        </Link>
        <Icon icon="mdi:chevron-double-right" />
        <Link :href="`/${url[1]}`" class="flex items-center gap-1 text-teal-700 hover:underline">
            {{url[1]}}
        </Link>
        <Icon icon="mdi:chevron-double-right" />
        <span>{{ url[2] }}</span>
    </div>
</template>