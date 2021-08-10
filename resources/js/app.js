require('./bootstrap');

window.Vue = require("vue");

import Home from "./components/Home.vue";

const app = Vue.createApp({});
app.component(Home.name, Home);
app.mount("#app");
