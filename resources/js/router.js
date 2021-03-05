import Vue from "vue";
import Router from "vue-router";
Vue.use(Router);

// pages
import index from "./pages/index";
import login from "./pages/login";
import admins from "./pages/admins";
import divisions from "./pages/divisions";
import districts from "./pages/districts";
import upazilas from "./pages/upazilas";
import cities from "./pages/cities";
import wards from "./pages/wards";
import areas from "./pages/areas";
import customers from "./pages/customers";
import user_tree from "./pages/user_tree";
import profile from "./pages/profile";
import customer_add_point from "./pages/customer_add_point";
import customer_one_link from "./pages/customer_one_link";
import withdraw_request from "./pages/withdraw_request";
import admin_withdraw_request from "./pages/admin_withdraw_request";

// bring in all the modules routes
let routes = [
    { path: "/", name: "index", component: index },
    { path: "/login", name: "login", component: login },
    { path: "/admins", name: "admins", component: admins },
    { path: "/divisions", name: "divisions", component: divisions },
    { path: "/districts", name: "districts", component: districts },
    { path: "/upazilas", name: "upazilas", component: upazilas },
    { path: "/cities", name: "cities", component: cities },
    { path: "/wards", name: "wards", component: wards },
    { path: "/areas", name: "areas", component: areas },
    { path: "/customers", name: "customers", component: customers },
    { path: "/tree/:id", name: "user_tree", component: user_tree },
    { path: "/profile", name: "profile", component: profile },
    {
        path: "/customer/add-point",
        name: "customer_add_point",
        component: customer_add_point
    },
    {
        path: "/customer/one-link",
        name: "customer_one_link",
        component: customer_one_link
    },
    { path: '/customer/withdraw_reqeust', name: 'withdraw_request', component: withdraw_request },
    { path: '/withdraw_request', name: 'admin_withdraw_request', component: admin_withdraw_request }
];

export default new Router({
    mode: "history",
    routes
});
