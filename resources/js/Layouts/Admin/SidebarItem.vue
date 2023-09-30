<script setup>
import { ref } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import {Icon} from '@iconify/vue';

const page = usePage();
const user = page.props.auth.user

const childOpen = ref(false)
const toggleChild = (e) => {
    e.preventDefault()
    let li = e.target.closest('li')
    let child = li.querySelector("ul.children")
    let chev = li.querySelector(".chev")
    child.classList.toggle('hidden')
    // chev.classList.toggle("opened")
    if(child.classList.contains("hidden")) {
        chev.classList.remove("opened")
    } else {
        chev.classList.add("opened")
    }
    
    // console.log(li)
}

const role = (menu) => {
    return menu.roles == user.level || menu.roles == 'all'
}
</script>

<template>
    <ul>
        <li v-for="(menu,m) in page.props.menus" :key="m" :class="menu.children.length > 0 ? 'group': ''">
            <Link :href="route().has(menu.url) && menu.url !== '#' ? route(menu.url) : ''"  class="hover:bg-orange-400 hover:text-white p-2 flex items-center gap-2 w-full" :class="menu.url == route().current() ? 'bg-orange-400 text-white  font-bold':''"  v-if="menu.children.length < 1">
                <Icon :icon="'mdi:'+menu.icon" />
                {{ menu.label }}
            </Link>
            <a href="javascipt:void(0);" class="hover:bg-orange-400 hover:text-white p-2 flex items-center gap-2 w-full relative" @click="toggleChild" v-else>
                <Icon :icon="'mdi:'+menu.icon" />
                {{ menu.label }}
                <Icon icon="mdi:chevron-right"  class="chev absolute right-2 transition-all" />
            </a>
            <ul class="children transition-all duration-300" v-if="menu.children" :class="menu.children.map(ch => ch.url).includes(route().current()) ? 'block' : 'hidden'">
                <li v-for="(child, c) in menu.children" :key="c" >
                    <Link :href="route().has(child.url) ? route(child.url) : '#'"  class=" hover:bg-orange-400 hover:text-white py-2 px-6 flex items-center gap-2 " :class="child.url == route().current() ? 'bg-orange-400 text-white font-bold':''" v-if="role(child)">
                        <Icon :icon="'mdi:'+child.icon" />
                        {{ child.label }} 
                    </Link>
                </li>
            </ul>
        </li>
    </ul>
</template>

<style scoped>
    .chev.opened {
        transform: rotate(90deg);
    }
</style>
