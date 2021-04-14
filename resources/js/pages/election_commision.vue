<template>
  <div class="container-fluid">
    <div class="row">
      <div class="col-12 col-md-12">
        <div class="card">
          <div class="card-header card-header-tabs card-header-primary">
            <div class="nav-tabs-navigation">
              <div class="nav-tabs-wrapper">
                <h4 class="card-title">Election Commission</h4>
                <ul
                  v-if="authUser.role == 'admin'"
                  class="nav nav-tabs"
                  data-tabs="tabs"
                >
                  <li class="nav-item">
                    <a class="nav-link active" @click="createModal = true">
                      <i class="material-icons">source</i> Add Election
                      Commission
                      <div class="ripple-container"></div>
                    </a>
                  </li>
                </ul>
              </div>
            </div>
          </div>
          <div v-if="allElectionCommittees" class="card-body table-responsive">
            <table class="table table-hover">
              <thead class="text-warning">
                <th>ID</th>
                <th>User Name</th>
                <th>Position</th>
                <th v-if="authUser.role == 'admin'">Action</th>
              </thead>
              <tbody>
                <tr
                  v-if="allElectionCommittees.total > 0 && !tableLoading"
                  v-for="(item, index) in allElectionCommittees.data"
                  :key="'ad' + index"
                >
                  <td>{{ item.id }}</td>
                  <td>{{ item.name ? item.name : "N/A" }}</td>
                  <td>{{ item.position }}</td>
                  <td v-if="authUser.role == 'admin'">
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
                      @on-ok="deleteElectionCommission(index)"
                      ok-text="Delete"
                    >
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
              v-if="!tableLoading && allElectionCommittees.total == 0"
            >
              <p>No data</p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- create modal -->
    <Modal
      title="Add Election Commission"
      v-model="createModal"
      :footer-hide="true"
      :mask-closable="false"
      :closable="false"
    >
      <form v-on:submit.prevent="addElectionCommission">
        <div class="row">
          <div class="col-12">
            <div class="form-group">
              <label class="bmd-label-floating">User</label>
              <Input v-model="electionCommision.name"></Input>
              <p v-if="error.name" class="text-danger">
                <Icon type="md-alert" /> {{ error.name }}
              </p>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label class="bmd-label-floating">Position</label>
              <input
                v-model="electionCommision.position"
                type="text"
                class="form-control"
              />
              <p v-if="error.position" class="text-danger">
                <Icon type="md-alert" /> {{ error.position }}
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
        <button
          type="button"
          @click="closeModal"
          class="btn btn-default pull-right"
        >
          <p>Cancel</p>
        </button>
        <div class="clearfix"></div>
      </form>
    </Modal>
    <!-- create modal end -->

    <!-- edit modal -->
    <Modal
      title="Edit Election Commission"
      v-model="editModal"
      :footer-hide="true"
      :mask-closable="false"
      :closable="false"
    >
      <form v-on:submit.prevent>
        <div class="row">
          <div class="col-12">
            <div class="form-group">
              <label class="bmd-label-floating">User</label>
              <Input v-model="editData.name"></Input>
              <p v-if="error.name" class="text-danger">
                <Icon type="md-alert" /> {{ error.name }}
              </p>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label class="bmd-label-floating">Position</label>
              <input
                v-model="editData.position"
                type="text"
                class="form-control"
              />
              <p v-if="error.position" class="text-danger">
                <Icon type="md-alert" /> {{ error.position }}
              </p>
            </div>
          </div>
        </div>
        <button
          @click="editElectionCommission"
          class="btn btn-primary pull-right d-flex align-items-center"
          :disabled="modal_loading"
        >
          <template v-if="modal_loading">
            <Loader position="inline" color="white" />
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
      allElectionCommittees: [],
      tableLoading: true,
      createModal: false,
      editModal: false,
      modal_loading: false,

      electionCommision: {
        name: "",
        position: "",
      },
      error: {
        name: false,
        position: false,
      },
      editIndex: -1,
      deleteIndex: -1,
      deleteLoading: false,
      editData: {},
      page: 1,
      allUsers: [],
    };
  },

  computed: {
    ...mapGetters({}),
  },

  methods: {
    clearData() {
      this.electionCommision = {
        name: "",
        position: "",
      };
      this.editIndex = -1;
      this.editData = {};
    },

    clearErrorData() {
      this.error = {
        name: false,
        position: false,
      };
    },

    openEditModal(index) {
      this.clearErrorData();
      this.editModal = true;
      this.editData = _.clone(this.allElectionCommittees.data[index]);
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

    async addElectionCommission() {
      this.clearErrorData();
      if (this.electionCommision.name.trim() == "") {
        this.error.name = "Name is required!";
        return false;
      }

      if (this.electionCommision.position == "") {
        this.error.position = "Position is required!";
        return false;
      }
      this.modal_loading = true;
      const res = await this.callApi(
        "post",
        "/app/admin/election/commision/add/new",
        this.electionCommision
      );
      if (res.status == 201) {
        res.data.user = {
          name: this.electionCommision.name,
        };
        this.allElectionCommittees.data.push(res.data);
        this.allElectionCommittees.total += 1;
        this.clearData();
      } else if (res.status == 401 && res.data.name) {
        for (let i of res.data.name) this.e(i);
      } else if (res.status == 401 && res.data.date_and_time) {
        for (let i of res.data.date_and_time) this.e(i);
      } else {
        this.nswr();
      }
      this.modal_loading = false;
      this.closeModal();
    },

    async editElectionCommission() {
      if (!this.editData.name || this.editData.name.trim() == "") {
        this.error.name = "Name is required!";
        return false;
      }

      if (!this.editData.position || this.editData.position == "") {
        this.error.date_and_time = "Position is required!";
        return false;
      }
      this.modal_loading = true;
      const res = await this.callApi(
        "post",
        `/app/admin/election/commision/update/${this.editData.id}`,
        this.editData
      );
      if (res.status == 200) {
        this.allElectionCommittees.data[this.editIndex] = _.clone(
          this.editData
        );
        this.clearData();
      } else {
        this.nswr();
      }
      this.clearData();
      this.closeModal();
    },

    async deleteElectionCommission(index) {
      this.deleteLoading = true;
      this.deleteIndex = index;
      const res = await this.callApi(
        "post",
        `/app/admin/election/commision/delete/${this.allElectionCommittees.data[index].id}`
      );
      if (res.status == 200) {
        this.allElectionCommittees.data.splice(index, 1);
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
        `/app/admin/election/commision/paginate/all?page=${this.page}`
      );
      if (res.status == 200) {
        this.allElectionCommittees = res.data;
      } else {
        this.nswr();
      }
      this.tableLoading = false;
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
.theme-color .vdatetime-popup__header,
.theme-color .vdatetime-calendar__month__day--selected > span > span,
.theme-color .vdatetime-calendar__month__day--selected:hover > span > span {
  background: linear-gradient(60deg, #ab47bc, #8e24aa) !important;
}

.theme-color .vdatetime-year-picker__item--selected,
.theme-color .vdatetime-time-picker__item--selected,
.theme-color .vdatetime-popup__actions__button {
  color: #9124a3 !important;
}
</style>