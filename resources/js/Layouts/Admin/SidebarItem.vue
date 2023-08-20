<script setup>
import { Link, usePage, router } from '@inertiajs/vue3';
import {Icon} from '@iconify/vue';

const page = usePage();
const user = page.props.auth.user

const visit = (e) => {
    e.preventDefault()
    let li = e.target.closest('li')
    let child = li.querySelector("ul.children")
    child.classList.toggle('hidden')
    // console.log(li)
}

const role = (menu) => {
    return menu.roles == user.level || menu.roles == 'all'
}
</script>

<template>
    <ul>
        <li v-for="(menu,m) in page.props.menus" :key="m" :class="menu.children.length > 0 ? 'group': ''">
            <Link :href="route().has(menu.url) && menu.url !== '#' ? route(menu.url) : ''"  class="hover:bg-white p-2 hover:shadow flex items-center gap-2 w-full" :class="menu.url == route().current() ? 'bg-teal-100 shadow font-bold':''"  v-if="menu.children.length < 1">
                <Icon :icon="'mdi:'+menu.icon" />
                {{ menu.label }}
            </Link>
            <a href="javascipt:void(0);" class="hover:bg-white p-2 hover:shadow flex items-center gap-2 w-full" @click="visit" v-else>
                <Icon :icon="'mdi:'+menu.icon" />
                {{ menu.label }}
            </a>
            <ul class="children transition-all duration-300" v-if="menu.children" :class="menu.children.map(ch => ch.url).includes(route().current()) ? 'block' : 'hidden'">
                <li v-for="(child, c) in menu.children" :key="c" >
                    <Link :href="route().has(child.url) ? route(child.url) : '#'"  class=" hover:bg-white py-2 px-6 flex items-center gap-2 hover:shadow" :class="child.url == route().current() ? 'bg-teal-100 shadow font-bold':''" v-if="role(child)">
                        <Icon :icon="'mdi:'+child.icon" />
                        {{ child.label }} 
                    </Link>
                </li>
            </ul>
        </li>
    </ul>
</template>
