// import './bootstrap';
import '../css/app.css';

import { createApp, h } from 'vue';
import { createInertiaApp, Link, Head } from '@inertiajs/inertia-vue3';
import { InertiaProgress } from '@inertiajs/progress';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/vue.m';

import MainLayout from '@/Layouts/MainLayout.vue';

import VueTippy from 'vue-tippy'
import 'tippy.js/dist/tippy.css'

const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Codewars';

createInertiaApp({
    title: (title) => `${title} | ${appName}`,
    // resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    resolve: (name) => {
        const page = resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob("./Pages/**/*.vue")
        );

        page.then((module) => {
            module.default.layout ??=  MainLayout;
        });

        return page;
    },

    setup({ el, app, props, plugin }) {
        return createApp({ render: () => h(app, props) })
            .use(plugin)
            .use(ZiggyVue, Ziggy)
            .use(VueTippy,
                // https://vue-tippy.netlify.app/installation
                {
                    directive: 'tippy', // => v-tippy
                    component: 'tippy', // => <tippy/>
                    componentSingleton: 'tippy-singleton', // => <tippy-singleton/>,
                    defaultProps: {
                        placement: 'bottom',
                        allowHTML: true,
                        arrow: true,
                        delay: [300, 200],
                    }, // => Global default options * see all props
                })
            .component("Link", Link)
            .component("Head", Head)
            .mount(el);
    },
});

InertiaProgress.init({ color: '#4B5563' });
