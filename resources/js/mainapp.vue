<template>
  <diV>
    <template v-if="$route.path != '/login' && $route.path != '/register'">
      <div class="wrapper">
        <Sidebar></Sidebar>
        <div class="main-panel">
          <Header></Header>
          <div class="content">
            <router-view></router-view>
            <Footer></Footer>
          </div>
        </div>
      </div>
    </template>
    <template v-else>
      <router-view></router-view>
    </template>
  </diV>
</template>

<script>
import Header from "./components/header";
import Sidebar from "./components/sidebar";
import Footer from "./components/footer";
export default {
  components: {
    Header,
    Sidebar,
    Footer,
  },

  data() {
    return {};
  },

  methods: {},
  watch: {
    "$route.name": function (newVal, oldVal) {
      //  this.activeRoute = newVal;
    },
  },
  beforeCreate() {
    if (!window.authUser) {
      try {
        let url =
          this.$route.path == "/login" || this.$route.path == "/register"
            ? this.$route.path
            : "/login";
        this.$router.push(url).catch(() => {});
      } catch (error) {
        console.log("0014 route.path::");
      }
      return;
    }

    this.$store.commit("setAuthUser", window.authUser);
    if (this.$route.path == "/login" || this.$route.path == "/register") {
      return this.$router.push("/").catch(() => {});
    }
  },

  async created() {},
};
</script>