import './bootstrap';
import '../css/app.css';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/vue.m';
import { MotionPlugin } from '@vueuse/motion';
import { setupCalendar } from 'v-calendar';
import Vue3EasyDataTable from 'vue3-easy-data-table';
import 'vue3-easy-data-table/dist/style.css';
import Vue3Toasity from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';
import ElementPlus from 'element-plus';
import 'element-plus/dist/index.css'
import ElementTiptapPlugin from 'element-tiptap-vue3-fixed';
import 'element-tiptap-vue3-fixed/lib/style.css'
import { install } from 'vue3-recaptcha-v2'

const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    // .catch(err => {
    //     console.log(err)
    //     return resolvePageComponent(`./Pages/Error.vue`,import.meta.glob('./Pages/**/*.vue'))
    // }),
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue, Ziggy)
            .use(setupCalendar, {})
            .use(MotionPlugin)
            .use(Vue3Toasity)
            .use(ElementPlus)
            .use(ElementTiptapPlugin)
            .use(install, {
                sitekey: '6LcTy9EpAAAAAGVNbsWbXDKR3eFpoKtIsNpwl6jJ',
                cnDomain: false
            })
            .component('DataTable', Vue3EasyDataTable)
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});
