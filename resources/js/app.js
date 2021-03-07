require("./bootstrap");
window.Vue = require("vue");
Vue.component("mainapp", require("./mainapp.vue").default);
import router from "./router";
import store from "./store";
// import districts from "./store/districts";
import ViewUI from "view-design";
import "view-design/dist/styles/iview.css";
import locale from "view-design/dist/locale/en-US";
import commons from "./commons";

import vueDebounce from "vue-debounce";
Vue.use(vueDebounce);

import { Datetime } from 'vue-datetime'
// You need a specific loader for CSS files
import 'vue-datetime/dist/vue-datetime.css'

Vue.component('datetime', Datetime);

Vue.use(ViewUI, { locale });
Vue.mixin(commons);

const app = new Vue({
    el: "#app",
    router,
    store
    // districts
});
