<template>
  <nav
    class="navbar navbar-expand-lg navbar-transparent navbar-absolute fixed-top"
  >
    <div class="container-fluid">
      <div class="navbar-wrapper">
        <a class="navbar-brand" href="javascript:;">Dashboard</a>
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
        <form class="navbar-form">
          <div class="input-group no-border">
            <input
              type="text"
              value=""
              class="form-control"
              placeholder="Search..."
            />
            <button type="submit" class="btn btn-white btn-round btn-just-icon">
              <i class="material-icons">search</i>
              <div class="ripple-container"></div>
            </button>
          </div>
        </form>
        <ul class="navbar-nav">
          <li class="nav-item">
            <router-link class="nav-link" to="/">
              <i class="material-icons">dashboard</i>
              <p class="d-lg-none d-md-block">Stats</p>
            </router-link>
          </li>

          <!-- notifications -->
          <li class="nav-item dropdown">
            <a
              class="nav-link"
              href="http://example.com"
              id="navbarDropdownMenuLink"
              data-toggle="dropdown"
              aria-haspopup="true"
              aria-expanded="false"
              @click="getNotifications"
            >
              <i class="material-icons">notifications</i>
              <span v-if="totalNotification > 0" class="notification">{{
                totalNotification
              }}</span>
              <p class="d-lg-none d-md-block">Some Actions</p>
            </a>
            <div
              class="dropdown-menu dropdown-menu-right"
              aria-labelledby="navbarDropdownMenuLink"
              style="min-height: 230px; min-width: 260px"
            >
              <Loader
                style="transform: translateY(90px)"
                v-if="isLoading"
                position="center"
                color="e7e7e7"
              />
              <template v-if="!isLoading && totalNotification == 0">
                <p
                  style="
                    transform: translateY(90px);
                    padding: 0.625rem 1.25rem;
                    font-size: 0.8125rem;
                    text-align: center;
                  "
                >
                  No notificaiton found
                </p>
              </template>
              <template v-if="!isLoading && totalNotification > 0">
                <div
                  v-for="(item, index) in allNotifications"
                  :key="'noti' + index"
                >
                  <router-link
                    class="dropdown-item border-bottom"
                    :to="item.link"
                    >{{ item.title }}</router-link
                  >
                </div>
              </template>
            </div>
          </li>
          <!-- notifications end -->

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
