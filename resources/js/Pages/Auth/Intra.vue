<script setup>
import { ref, defineAsyncComponent, computed } from 'vue';
import { Head, usePage } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const page = usePage()
const comp = computed(() => {
    return page.props.auth.user.level == 'admin' ? 'IntraAdmin' : 'IntraGuru';
})

const is = computed(() => defineAsyncComponent(()=>import(`../../Components/Dashboard/Intra/${comp.value}.vue`)))
const Loading = defineAsyncComponent(() => import('@/Components/General/Loading.vue')) 
const loading = ref(false)
</script>
<template>
<Head title="Pembelajaran" />
<AdminLayout title="Pembelajaran">
    <component :is="is">
        <slot />
    </component>
</AdminLayout>
</template>