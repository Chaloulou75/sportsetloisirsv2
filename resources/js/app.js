import "./bootstrap";
import "maz-ui/css/main.css";
import "preline";
import "../css/app.css";

import { createApp, h } from "vue";
import { createInertiaApp } from "@inertiajs/vue3";
import { resolvePageComponent } from "laravel-vite-plugin/inertia-helpers";
import { ZiggyVue } from "../../vendor/tightenco/ziggy/dist/vue.m";
import PrimeVue from "primevue/config";
import Lara from "@/presets/lara";

const appName =
    window.document.getElementsByTagName("title")[0]?.innerText ||
    "Sports et Loisirs";

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob("./Pages/**/*.vue")
        ),
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue, Ziggy)
            .use(PrimeVue, {
                unstyled: true,
                pt: Lara,
            })
            .mount(el);
    },
    progress: {
        color: "#4B5563",
        showSpinner: true,
    },
});
