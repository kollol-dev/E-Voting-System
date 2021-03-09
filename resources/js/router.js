import Vue from "vue";
import Router from "vue-router";
Vue.use(Router);

// pages
import index from "./pages/index";
import login from "./pages/login";
import register from "./pages/register";
import users from "./pages/users";
import profile from "./pages/profile";
import elections from "./pages/elections";
import election_posts from "./pages/election_posts";
import election_candidates from "./pages/election_candidates";

// alumni
import alumni_elections from "./pages/alumni/elections";
import alumni_candidates from "./pages/alumni/candidates";

// bring in all the modules routes
let routes = [
    { path: "/", name: "index", component: index },
    { path: "/login", name: "login", component: login },
    { path: "/register", name: "register", component: register },
    { path: "/users", name: "users", component: users },
    { path: "/profile", name: "profile", component: profile },
    { path: "/elections", name: "elections", component: elections },
    { path: "/election_posts", name: "election_posts", component: election_posts },
    { path: "/election_candidates", name: "election_candidates", component: election_candidates },

    // alumni
    { path: "/alumni/elections", name: "alumni_elections", component: alumni_elections },
    { path: "/alumni/elections/candidate/:id", name: "alumni_candidates", component: alumni_candidates },
];

export default new Router({
    mode: "history",
    routes
});
