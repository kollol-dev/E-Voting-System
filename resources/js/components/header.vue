<template>
  <nav
    class="navbar navbar-expand-lg navbar-transparent navbar-absolute fixed-top"
  >
    <div class="container-fluid">
      <div class="navbar-wrapper">
        <a class="navbar-brand" href="javascript:;">Evoting System</a>
      </div>
      <button
        class="navbar-toggler"
        type="button"
        data-toggle="collapse"
        aria-controls="navigation-index"
        aria-expanded="false"
        aria-label="Toggle navigation"
      >
        <span class="sr-only">Toggle navigation</span>
        <span class="navbar-toggler-icon icon-bar"></span>
        <span class="navbar-toggler-icon icon-bar"></span>
        <span class="navbar-toggler-icon icon-bar"></span>
      </button>
      <div class="collapse navbar-collapse justify-content-end">
        <ul class="navbar-nav">
          <li class="nav-item">
            <router-link class="nav-link" to="/">
              <i class="material-icons">dashboard</i>
              <p class="d-lg-none d-md-block">Stats</p>
            </router-link>
          </li>

          <!-- setting dropdown -->
          <li class="nav-item dropdown">
            <a
              class="nav-link"
              href="javascript:;"
              id="navbarDropdownProfile"
              data-toggle="dropdown"
              aria-haspopup="true"
              aria-expanded="false"
            >
              <i class="material-icons mr-1">person</i>
              <p class="d-lg-none d-md-block">Account</p>
            </a>
            <div
              class="dropdown-menu dropdown-menu-right"
              aria-labelledby="navbarDropdownProfile"
            >
              <router-link class="dropdown-item" to="/profile">
                <i class="material-icons mr-1">person</i> Profile
              </router-link>
              <router-link
                v-if="authUser.role == 'Customer'"
                class="dropdown-item"
                to="/customer/withdraw_reqeust"
              >
                <i class="material-icons mr-1">source</i>
                Create Withdraw Request</router-link
              >
              <div class="dropdown-divider"></div>

              <a class="dropdown-item" href="/app/user/logout">
                <i class="material-icons mr-1">logout</i> Log out</a
              >
            </div>
          </li>
          <!-- setting dropdown end -->
        </ul>
      </div>
    </div>
  </nav>
</template>

<script>
export default {
  data() {
    return {
      allNotifications: [],
      totalNotification: 0,
      isLoading: false,
    };
  },

  methods: {
    async getNotifications() {
      if (this.allNotifications > 0) return;
      this.isLoading = true;
      const res = await this.callApi("get", "/app/notification/get/all");
      if (res.status == 200) {
        this.allNotifications = res.data;
      }
      this.isLoading = false;
    },
  },

  async created() {
    const res = await this.callApi("get", "/app/notification/count/all");
    if (res.status == 200) {
      this.totalNotification = res.data;
    }
  },
};
</script>
