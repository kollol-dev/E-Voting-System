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
import election_commision from "./pages/election_commision";
import election_policy from "./pages/election_policy";

// alumni
import alumni_elections from "./pages/alumni/elections";
import alumni_posts from "./pages/alumni/posts";
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
    { path: "/election_commision", name: "election_commision", component: election_commision },
    { path: "/election_policy", name: "election_policy", component: election_policy },

    // alumni
    { path: "/alumni/elections", name: "alumni_elections", component: alumni_elections },
    { path: "/alumni/elections/posts/:id", name: "alumni_posts", component: alumni_posts },
    { path: "/alumni/elections/candidate/:id", name: "alumni_candidates", component: alumni_candidates },
];

export default new Router({
    mode: "history",
    routes
});
