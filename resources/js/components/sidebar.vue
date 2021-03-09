<template>
  <div
    class="sidebar"
    data-color="purple"
    data-background-color="white"
    data-image="/img/sidebar-1.jpg"
  >
    <!--
			Tip 1: You can change the color of the sidebar using: data-color="purple | azure | green | orange | danger"

			Tip 2: you can also add an image using data-image tag
		-->
    <div class="logo">
      <a href="/" class="simple-text logo-normal"> Evoting System </a>
    </div>
    <div class="sidebar-wrapper">
      <ul class="nav">
        <!-- <li :class="$route.path == '/' ? 'nav-item active' : 'nav-item'">
          <router-link class="nav-link" to="/">
            <i class="material-icons">dashboard</i>
            <p>Dashboard</p>
          </router-link>
        </li>
        <li :class="$route.path == '/profile' ? 'nav-item active' : 'nav-item'">
          <router-link class="nav-link" to="/profile">
            <i class="material-icons">person</i>
            <p>Profile</p>
          </router-link>
        </li> -->
        <!-- admin options  -->
        <template v-if="authUser.role == 'admin'">
          <li :class="$route.path == '/users' ? 'nav-item active' : 'nav-item'">
            <router-link class="nav-link" to="/users">
              <i class="material-icons">person</i>
              <p>Users</p>
            </router-link>
          </li>
          <li
            :class="
              $route.path == '/elections' ? 'nav-item active' : 'nav-item'
            "
          >
            <router-link class="nav-link" to="/elections">
              <i class="material-icons">source</i>
              <p>Elections</p>
            </router-link>
          </li>
          <li
            :class="
              $route.path == '/election_posts' ? 'nav-item active' : 'nav-item'
            "
          >
            <router-link class="nav-link" to="/election_posts">
              <i class="material-icons">bookmarks</i>
              <p>Election Posts</p>
            </router-link>
          </li>
          <li
            :class="
              $route.path == '/election_candidates'
                ? 'nav-item active'
                : 'nav-item'
            "
          >
            <router-link class="nav-link" to="/election_candidates">
              <i class="material-icons">groups</i>
              <p>Election Candidate</p>
            </router-link>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="/app/user/logout">
              <i class="material-icons">logout</i>
              <p>Logout</p>
            </a>
          </li>
        </template>
        <!-- admin options end -->

        <!-- alumni options -->
        <template v-if="authUser.role == 'alumni'">
          <li
            :class="
              $route.path == '/alumni/elections' ||
              $route.name == 'alumni_candidates'
                ? 'nav-item active'
                : 'nav-item'
            "
          >
            <router-link class="nav-link" to="/alumni/elections">
              <i class="material-icons">source</i>
              <p>Elections</p>
            </router-link>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="/app/user/logout">
              <i class="material-icons">logout</i>
              <p>Logout</p>
            </a>
          </li>
        </template>
        <!-- alumni options end -->
      </ul>
    </div>
  </div>
</template>

<script>
import { mapGetters } from "vuex";
export default {
  computed: {
    ...mapGetters({
      // sidebar: "getSidebar",
    }),
  },
  data() {
    return {
      // accountDropDown: false,
    };
  },

  watch: {
    "$route.path": function () {
      // this.$store.commit("setSidebar", false);
    },
  },
};
</script>
<style >
.show_sidebar {
  transform: translateX(0px);
  transition-delay: 0.2s;
}
</style>
