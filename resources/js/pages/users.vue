<template>
  <div class="container-fluid">
    <div class="row">
      <div class="col-12 col-md-12">
        <div class="card">
          <div class="card-header card-header-tabs card-header-primary">
            <div class="nav-tabs-navigation">
              <div class="nav-tabs-wrapper">
                <h4 class="card-title">Users</h4>
              </div>
            </div>
          </div>
          <div v-if="allUsers" class="card-body table-responsive">
            <table class="table table-hover">
              <thead class="text-warning">
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Status</th>
                <th>Action</th>
              </thead>
              <tbody>
                <tr
                  v-if="allUsers.total > 0 && !tableLoading"
                  v-for="(item, index) in allUsers.data"
                  :key="'ad' + index"
                >
                  <td>{{ item.id }}</td>
                  <td>{{ item.name }}</td>
                  <td>{{ item.email }}</td>
                  <td style="text-transform: capitalize">{{ item.status }}</td>
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
                      @on-ok="deleteUser(index)"
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
              v-if="!tableLoading && allUsers.total == 0"
            >
              <p>No data</p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- edit modal -->
    <Modal
      title="Edit User"
      v-model="editModal"
      :footer-hide="true"
      :mask-closable="false"
      :closable="false"
    >
      <form v-on:submit.prevent>
        <div class="row">
          <div class="col-12">
            <div class="form-group" style="">
              <label class="">Name</label>
              <input v-model="editData.name" type="text" class="form-control" />
              <p v-if="error.name" class="text-danger">
                <Icon type="md-alert" /> {{ error.name }}
              </p>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group" style="">
              <label class="">Email</label>
              <input
                v-model="editData.email"
                type="text"
                class="form-control"
                style="margin-top: 30px"
              />
              <p v-if="error.email" class="text-danger">
                <Icon type="md-alert" /> {{ error.email }}
              </p>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group" style="">
              <label class="">Status</label>
              <Select filterable v-model="editData.status" class="form-control">
                <Option value="approved">Approved</Option>
                <Option value="pending">Pending</Option>
              </Select>
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
                  <Icon type="md-alert" /> {{ error.password }}
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
          @click="editUser"
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
      allUsers: [],
      tableLoading: true,

      editModal: false,
      modal_loading: false,

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
      this.editIndex = -1;
      this.editData = {};
      this.isClickedChangePassword = false;
    },

    clearErrorData() {
      this.error = {
        name: false,
        email: false,
        password: false,
      };
    },

    openEditModal(index) {
      this.clearErrorData();
      this.editModal = true;
      this.editData = _.clone(this.allUsers.data[index]);
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

    async editUser() {
      if (!this.validateUserEditData()) return;
      this.modal_loading = true;
      const res = await this.callApi(
        "post",
        `/app/admin/update/user/${this.editData.id}`,
        this.editData
      );
      if (res.status == 200) {
        this.allUsers.data[this.editIndex] = _.clone(this.editData);
        this.editData = {};
        this.editIndex = -1;
      } else {
        this.nswr();
      }
      this.clearData();
      this.closeModal();
    },

    async deleteUser(index) {
      this.deleteLoading = true;
      this.deleteIndex = index;
      const res = await this.callApi(
        "post",
        `/app/admin/delete/user/${this.allUsers.data[index].id}`
      );
      if (res.status == 200) {
        this.allUsers.data.splice(index, 1);
      } else {
        this.nswr();
      }
      this.deleteIndex = -1;
      this.deleteLoading = false;
    },

    async paginate(page) {
      this.tableLoading = true;
      const res = await this.callApi(
        "get",
        `/app/admin/paginate/users/all?page=${this.page}`
      );
      if (res.status == 200) {
        this.allUsers = res.data;
      } else {
        this.nswr();
      }
      this.tableLoading = false;
    },

    validateUserEditData() {
      if (!this.editData.name || this.editData.name.trim() == "") {
        this.error.name = "Name is required!";
        return false;
      }

      if (!this.editData.email || this.editData.email.trim() == "") {
        this.error.email = "Email is requiredEmail";
        return false;
      }

      if (this.isClickedChangePassword) {
        if (!this.editData.password || this.editData.password.trim() == "") {
          this.error.password = "Password is required!";
          return false;
        }
        if (!this.editData.password || this.editData.password.length < 8) {
          this.error.password = "Password length should be at least 8!";
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