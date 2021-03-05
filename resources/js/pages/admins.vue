<template>
  <div class="container-fluid">
    <div class="row">
      <div class="col-12 col-md-12">
        <div class="card">
          <div class="card-header card-header-tabs card-header-primary">
            <div class="nav-tabs-navigation">
              <div class="nav-tabs-wrapper">
                <h4 class="card-title">Admins</h4>
                <ul class="nav nav-tabs" data-tabs="tabs">
                  <li class="nav-item">
                    <a class="nav-link active" @click="createModal = true">
                      <i class="material-icons">person</i> Add Admin
                      <div class="ripple-container"></div>
                    </a>
                  </li>
                  <!-- <i class="material-icons">code</i> Website
											<i class="material-icons">cloud</i> Server -->
                </ul>
              </div>
            </div>
          </div>
          <div v-if="allAdmins" class="card-body table-responsive">
            <table class="table table-hover">
              <thead class="text-warning">
                <th>ID</th>
                <th>Name</th>
                <th>UID</th>
                <th>Mobile</th>
                <th>Action</th>
              </thead>
              <tbody>
                <tr
                  v-if="allAdmins.total > 0 && !tableLoading"
                  v-for="(item, index) in allAdmins.data"
                  :key="'ad' + index"
                >
                  <td>{{ item.id }}</td>
                  <td>{{ item.name }}</td>
                  <td>{{ item.uid }}</td>
                  <td>{{ item.mobile }}</td>
                  <td>
                    <div
                      @click="openEditModal(index)"
                      :disabled="deleteLoading && deleteIndex == index"
                      class="btn btn-default"
                    >
                      Edit
                    </div>
                    <Poptip
                      confirm
                      title="Are you sure you want to delete this?"
                      @on-ok="deleteAdmin(index)"
                      ok-text="Delete"
                    >
                      <!-- <div
												:class="
													deleteLoading && deleteIndex == index
														? 'btn btn-primary btn-loading'
														: 'btn btn-primary'
												"
											> -->
                      <div
                        :class="
                          deleteLoading && deleteIndex == index
                            ? 'btn btn-primary btn-loading'
                            : 'btn btn-primary'
                        "
                      >
                        <Loader
                          v-if="deleteLoading && deleteIndex == index"
                          position="inline"
                          color="#999999"
                        />
                        <p v-else class="text-center">Delete</p>
                      </div>
                    </Poptip>
                  </td>
                </tr>
              </tbody>
            </table>
            <div class="d-flex justify-content-center" v-if="tableLoading">
              <Loader />
            </div>
            <div
              class="d-flex justify-content-center"
              v-if="!tableLoading && allAdmins.total == 0"
            >
              <p>No data</p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- create modal -->
    <Modal
      title="Add Admin"
      v-model="createModal"
      :footer-hide="true"
      :mask-closable="false"
      :closable="false"
    >
      <form v-on:submit.prevent="addAdmin">
        <div class="row">
          <div class="col-12">
            <div class="form-group">
              <label class="bmd-label-floating">Name</label>
              <input v-model="admin.name" type="text" class="form-control" />
              <p v-if="error.name" class="text-danger">*{{ error.name }}</p>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label class="bmd-label-floating">Mobile</label>
              <input v-model="admin.mobile" type="text" class="form-control" />
              <p v-if="error.mobile" class="text-danger">*{{ error.mobile }}</p>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label class="bmd-label-floating">UID</label>
              <input v-model="admin.uid" type="text" class="form-control" />
              <p v-if="error.uid" class="text-danger">*{{ error.uid }}</p>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-12">
            <div class="form-group">
              <label class="bmd-label-floating">Password</label>
              <input
                v-model="admin.password"
                type="password"
                class="form-control"
              />
              <p v-if="error.password" class="text-danger">
                *{{ error.password }}
              </p>
            </div>
          </div>
        </div>
        <button
          type="submit"
          class="btn btn-primary pull-right d-flex align-items-center"
          :disabled="modal_loading"
        >
          <template v-if="modal_loading">
            <Loader position="inline" color="white" />
            <!-- <p class="text-center">Adding</p> -->
          </template>
          <template v-else>
            <p>Add</p>
          </template>
        </button>
        <button @click="closeModal" class="btn btn-default pull-right">
          <p>Cancel</p>
        </button>
        <div class="clearfix"></div>
      </form>
    </Modal>
    <!-- create modal end -->

    <!-- edit modal -->
    <Modal
      title="Edit Admin"
      v-model="editModal"
      :footer-hide="true"
      :mask-closable="false"
      :closable="false"
    >
      <form v-on:submit.prevent>
        <div class="row">
          <div class="col-12">
            <div class="form-group" style="">
              <label class="bmd-label-floating">Name</label>
              <input v-model="editData.name" type="text" class="form-control" />
              <p v-if="error.name" class="text-danger">*{{ error.name }}</p>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group" style="">
              <label class="bmd-label-floating">Mobile</label>
              <input
                v-model="editData.mobile"
                type="text"
                class="form-control"
              />
              <p v-if="error.mobile" class="text-danger">*{{ error.mobile }}</p>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group" style="">
              <label class="bmd-label-floating">UID</label>
              <input v-model="editData.uid" type="text" class="form-control" />
              <p v-if="error.uid" class="text-danger">*{{ error.uid }}</p>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-12">
            <div class="form-group" style="">
              <template v-if="isClickedChangePassword">
                <label class="bmd-label">New Password</label>
                <input
                  v-model="editData.password"
                  type="password"
                  class="form-control"
                />
                <p v-if="error.password" class="text-danger">
                  *{{ error.password }}
                </p>
              </template>
              <p
                @click="isClickedChangePassword = !isClickedChangePassword"
                class="btn btn-primary"
              >
                {{ isClickedChangePassword ? "Cancel" : "Change Password" }}
              </p>
            </div>
          </div>
        </div>
        <button
          @click="editAdmin"
          class="btn btn-primary pull-right d-flex align-items-center"
          :disabled="modal_loading"
        >
          <template v-if="modal_loading">
            <Loader position="inline" color="white" />
            <!-- <p class=".text-center">Adding</p> -->
          </template>
          <template v-else>
            <p>Edit</p>
          </template>
        </button>
        <button @click="closeModal" class="btn btn-default pull-right">
          <p>Cancel</p>
        </button>
        <div class="clearfix"></div>
      </form>
    </Modal>
    <!-- edit modal end  -->
  </div>
</template>
<script>
import { mapGetters } from "vuex";
export default {
  data() {
    return {
      allAdmins: [],
      tableLoading: true,
      createModal: false,
      editModal: false,
      modal_loading: false,
      admin: {
        name: "",
        uid: "",
        mobile: "",
        password: "",
        role: "Admin",
      },
      // admin: {
      // 	name: "",
      // 	uid: "",
      // 	mobile: "",
      // 	country: "",
      // 	division: "",
      // 	district: "",
      // 	upazila: "",
      // 	city: "",
      // 	ward: "",
      // 	area: "",
      // 	password: "",
      // 	role: "Admin",
      // },
      error: {
        name: false,
        uid: false,
        mobile: false,
        password: false,
      },
      editIndex: -1,
      deleteIndex: -1,
      deleteLoading: false,
      isClickedChangePassword: false,
      editData: {},
      page: 1,
    };
  },
  computed: {
    ...mapGetters({}),
  },
  methods: {
    clearData() {
      this.admin = {
        name: "",
        uid: "",
        mobile: "",
        password: "",
        role: "Admin",
      };
      this.editIndex = -1;
      this.editData = {};
      this.isClickedChangePassword = false;
    },

    clearErrorData() {
      this.error = {
        name: false,
        uid: false,
        mobile: false,
        password: false,
      };
    },

    openModal() {
      this.createModal = true;
    },

    openEditModal(index) {
      this.clearErrorData();
      this.editModal = true;
      this.editData = _.clone(this.allAdmins.data[index]);
      this.editIndex = _.clone(index);
    },

    closeModal() {
      this.clearErrorData();
      this.createModal = false;
      this.modal_loading = false;
      this.editIndex = -1;
      this.editData = {};
      this.editModal = false;
    },

    async editAdmin() {
      if (!this.validateAdminEditData()) return;
      this.modal_loading = true;
      const res = await this.callApi(
        "post",
        `/app/admin/edit/${this.editData.id}`,
        this.editData
      );
      if (res.status == 200) {
        this.allAdmins.data[this.editIndex] = _.clone(this.editData);
        this.editData = {};
        this.editIndex = -1;
      } else {
        this.nswr();
      }
      this.clearData();
      this.closeModal();
    },

    async deleteAdmin(index) {
      this.deleteLoading = true;
      this.deleteIndex = index;
      const res = await this.callApi(
        "post",
        `/app/admin/delete/${this.allAdmins.data[index].id}`
      );
      if (res.status == 200) {
        this.allAdmins.data.splice(index, 1);
      } else {
        this.nswr();
      }
      this.deleteIndex = -1;
      this.deleteLoading = false;
    },

    async addAdmin() {
      this.clearErrorData();
      if (!this.validateAdmin()) {
        return;
      }
      this.modal_loading = true;
      const res = await this.callApi(
        "post",
        "/app/admin/create/new",
        this.admin
      );
      if (res.status == 201) {
        this.allAdmins.data.unshift(res.data);
        this.allAdmins.total += 1;

		let obj = _.clone(this.allAdmins);

      } else if (res.status == 403) {
        if (res.data.type && res.data.type == "uid") {
          this.error.uid = res.data.message;
          this.modal_loading = false;
          return;
        }
      } else {
        this.nswr();
      }
      this.modal_loading = false;
      this.closeModal();
    },

    async paginate(page) {
      // if (this.page == page) {
      // 	return;
      // }
      // if (page < 1 || page > this.allAdmins.last_page) return;
      // this.page = page;
      this.tableLoading = true;
      const res = await this.callApi(
        "get",
        `/app/admin/paginate/all?page=${this.page}`
      );
      if (res.status == 200) {
        this.allAdmins = res.data;
      } else {
        this.nswr();
      }
      this.tableLoading = false;
    },

    validateAdmin() {
      if (this.admin.name.trim() == "") {
        this.error.name = "Name is required!";
        return false;
      }
      if (this.admin.mobile.trim() == "") {
        this.error.mobile = "Mobile number is required!";
        return false;
      }
      if (this.admin.uid.trim() == "") {
        this.error.uid = "UID is required!";
        return false;
      }
      if (this.admin.password.trim() == "") {
        this.error.password = "Password is required!";
        return false;
      }
      if (this.admin.password.length < 6) {
        this.error.password = "Password length should be at least 6!";
        return false;
      }
      return true;
    },

    validateAdminEditData() {
      if (!this.editData.name || this.editData.name.trim() == "") {
        this.error.name = "Full name is required!";
        return false;
      }

      if (!this.editData.mobile || this.editData.mobile.trim() == "") {
        this.error.mobile = "Mobile number is required!";
        return false;
      }

      if (!this.editData.uid || this.editData.uid.trim() == "") {
        this.error.uid = "UID is required!";
        return false;
      }
      if (this.isClickedChangePassword) {
        if (!this.editData.password || this.editData.password.trim() == "") {
          this.error.password = "Password is required!";
          return false;
        }
        if (!this.editData.password || this.editData.password.length < 6) {
          this.error.password = "Password length should be at least 6!";
          return false;
        }
      }
      return true;
    },
  },
  async created() {
    this.paginate(1);
  },
};
</script>

<style >
.btn-loading {
  cursor: not-allowed;
  opacity: 0.5;
}
</style>