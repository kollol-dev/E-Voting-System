import Vue from "vue";
import Router from "vue-router";
Vue.use(Router);

// pages
import index from "./pages/index";
import login from "./pages/login";
import register from "./pages/register";
import users from "./pages/users";
import elections from "./pages/elections";
import profile from "./pages/profile";

// bring in all the modules routes
let routes = [
    { path: "/", name: "index", component: index },
    { path: "/login", name: "login", component: login },
    { path: "/register", name: "register", component: register },
    { path: "/users", name: "users", component: users },
    { path: "/profile", name: "profile", component: profile },
    { path: "/elections", name: "elections", component: elections },
];

export default new Router({
    mode: "history",
    routes
});
