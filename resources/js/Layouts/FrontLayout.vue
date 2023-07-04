<script setup>
import { computed, defineAsyncComponent } from 'vue';
import { usePage } from '@inertiajs/vue3';

const page = usePage();
const Layout = page.props.layout;

const is = computed(
    () => defineAsyncComponent(() => import(`./Front/${Layout}.vue`))
)
</script>

<template>
<component :is="is">
    <slot />
</component>
</template>