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
      <a href="/" class="simple-text logo-normal"> AHM </a>
    </div>
    <div class="sidebar-wrapper">
      <ul class="nav">
        <li :class="$route.path == '/' ? 'nav-item active' : 'nav-item'">
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
        </li>
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
