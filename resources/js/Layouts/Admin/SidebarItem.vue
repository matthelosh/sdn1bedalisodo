<script setup>
import { Link, usePage, router } from '@inertiajs/vue3';
import {Icon} from '@iconify/vue';

const page = usePage();


</script>

<template>
    <ul>
        <li v-for="(menu,m) in page.props.menus" :key="m" :class="menu.children.length > 0 ? 'group': ''">
            <Link :href="route().has(menu.url) ? route(menu.url) : '#'"  class="hover:bg-white p-2 flex items-center gap-2">
                <Icon :icon="'mdi:'+menu.icon" />
                {{ menu.label }}
            </Link>
            <ul class="children hidden group-hover:block transition-all duration-300">
                <li v-for="(child, c) in menu.children" :key="c" >
                    <Link :href="route().has(child.url) ? route(child.url) : '#'"  class=" hover:bg-white py-2 px-6 flex items-center gap-2">
                        <Icon :icon="'mdi:'+child.icon" />
                        {{ child.label }}
                    </Link>
                </li>
            </ul>
        </li>
    </ul>
</template>
