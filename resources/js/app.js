import './bootstrap';
import '../css/app.css';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/vue.m';
import { MotionPlugin } from '@vueuse/motion';
import { setupCalendar } from 'v-calendar';


const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue'))
                            .catch(err => {
                                return resolvePageComponent(`./Pages/Error.vue`,import.meta.glob('./Pages/**/*.vue'))
                            }),
    // resolve: async (name) => {
    //     const page = await resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue'))
    //                 .then(res => {
    //                     console.log(res)
    //                     return res
    //                 })
    //                 .catch(err => {
    //                     return resolvePageComponent(`./Pages/Error.vue`,import.meta.glob('./Pages/**/*.vue'))
    //                 })
    //     return page
    // },
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue, Ziggy)
            .use(setupCalendar, {})
            .use(MotionPlugin)
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});
