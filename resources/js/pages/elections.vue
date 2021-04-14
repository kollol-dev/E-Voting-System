<template>
  <div class="container-fluid">
    <div class="row">
      <div class="col-12 col-md-12">
        <div class="card">
          <div class="card-header card-header-tabs card-header-primary">
            <div class="nav-tabs-navigation">
              <div class="nav-tabs-wrapper">
                <h4 class="card-title">Elections</h4>
                <ul class="nav nav-tabs" data-tabs="tabs">
                  <li class="nav-item">
                    <a class="nav-link active" @click="createModal = true">
                      <i class="material-icons">source</i> Add Election
                      <div class="ripple-container"></div>
                    </a>
                  </li>
                </ul>
              </div>
            </div>
          </div>
          <div v-if="allElections" class="card-body table-responsive">
            <table class="table table-hover">
              <thead class="text-warning">
                <th>ID</th>
                <th>Name</th>
                <th>Date</th>
                <th>Candiate Last Application Date</th>
                <th>Action</th>
              </thead>
              <tbody>
                <tr
                  v-if="allElections.total > 0 && !tableLoading"
                  v-for="(item, index) in allElections.data"
                  :key="'ad' + index"
                >
                  <td>{{ item.id }}</td>
                  <td>{{ item.name }}</td>
                  <td>{{ item.date_and_time | formatDateTime }}</td>
                  <td>
                    {{
                      item.candidate_application_expiration_date_time
                        | formatDateTime
                    }}
                  </td>
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
                      @on-ok="deleteElection(index)"
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
              v-if="!tableLoading && allElections.total == 0"
            >
              <p>No data</p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- create modal -->
    <Modal
      title="Add Election"
      v-model="createModal"
      :footer-hide="true"
      :mask-closable="false"
      :closable="false"
    >
      <form v-on:submit.prevent="addElection">
        <div class="row">
          <div class="col-12">
            <div class="form-group">
              <label class="bmd-label-floating">Name</label>
              <input v-model="election.name" type="text" class="form-control" />
              <p v-if="error.name" class="text-danger">
                <Icon type="md-alert" /> {{ error.name }}
              </p>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label class="bmd-label-floating">Date and Time</label>
              <datetime
                v-model="election.date_and_time"
                input-class="form-control"
                type="datetime"
                class="theme-color"
                zone="Asia/Dhaka"
                value-zone="Asia/Dhaka"
                use12-hour
              ></datetime>
              <p v-if="error.date_and_time" class="text-danger">
                <Icon type="md-alert" /> {{ error.date_and_time }}
              </p>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label class="bmd-label-floating"
                >Candiate Last Application Date</label
              >
              <datetime
                v-model="election.candidate_application_expiration_date_time"
                input-class="form-control"
                type="datetime"
                class="theme-color"
                zone="Asia/Dhaka"
                value-zone="Asia/Dhaka"
                use12-hour
              ></datetime>
              <p
                v-if="error.candidate_application_expiration_date_time"
                class="text-danger"
              >
                <Icon type="md-alert" />
                {{ error.candidate_application_expiration_date_time }}
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
      title="Edit Election"
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
              <label class="">Date and time</label>
              <datetime
                v-model="editData.date_and_time"
                input-class="form-control"
                type="datetime"
                class="theme-color"
                zone="Asia/Dhaka"
                value-zone="Asia/Dhaka"
                use12-hour
              ></datetime>
              <p v-if="error.date_and_time" class="text-danger">
                <Icon type="md-alert" /> {{ error.date_and_time }}
              </p>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group" style="">
              <label class="">Candiate Last Application Date</label>
              <datetime
                v-model="editData.candidate_application_expiration_date_time"
                input-class="form-control"
                type="datetime"
                class="theme-color"
                zone="Asia/Dhaka"
                value-zone="Asia/Dhaka"
                use12-hour
              ></datetime>
              <p
                v-if="error.candidate_application_expiration_date_time"
                class="text-danger"
              >
                <Icon type="md-alert" />
                {{ error.candidate_application_expiration_date_time }}
              </p>
            </div>
          </div>
        </div>
        <button
          @click="editElection"
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
      allElections: [],
      tableLoading: true,
      createModal: false,
      editModal: false,
      modal_loading: false,

      election: {
        name: "",
        date_and_time: "",
        candidate_application_expiration_date_time: "",
      },
      error: {
        name: false,
        date_and_time: false,
        candidate_application_expiration_date_time: false,
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
      this.election = {
        name: "",
        date_and_time: "",
        candidate_application_expiration_date_time: "",
      };
      this.editIndex = -1;
      this.editData = {};
      this.isClickedChangePassword = false;
    },

    clearErrorData() {
      this.error = {
        name: false,
        date_and_time: false,
        candidate_application_expiration_date_time: false,
      };
    },

    openEditModal(index) {
      this.clearErrorData();
      this.editModal = true;
      this.editData = _.clone(this.allElections.data[index]);
      this.editIndex = _.clone(index);
      this.editData.date_and_time = new Date(
        this.editData.date_and_time
      ).toISOString();
    },

    closeModal() {
      this.clearErrorData();
      this.createModal = false;
      this.modal_loading = false;
      this.editIndex = -1;
      this.editData = {};
      this.editModal = false;
    },

    async addElection() {
      this.clearErrorData();
      if (this.election.name.trim() == "") {
        this.error.name = "Name is required!";
        return false;
      }

      if (this.election.date_and_time == "") {
        this.error.date_and_time = "Select date and time!";
        return false;
      }
      if (this.election.candidate_application_expiration_date_time == "") {
        this.error.candidate_application_expiration_date_time =
          "Select date and time for candidate expiration date!";
        return false;
      }
      this.modal_loading = true;
      const res = await this.callApi(
        "post",
        "/app/admin/election/add/new",
        this.election
      );
      if (res.status == 201) {
        this.allElections.data.push(res.data);
        this.allElections.total += 1;
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

    async editElection() {
      if (!this.editData.name || this.editData.name.trim() == "") {
        this.error.name = "Name is required!";
        return false;
      }

      if (!this.editData.date_and_time || this.editData.date_and_time == "") {
        this.error.date_and_time = "Select date and time!";
        return false;
      }

      if (
        !this.editData.candidate_application_expiration_date_time ||
        this.editData.candidate_application_expiration_date_time == ""
      ) {
        this.error.candidate_application_expiration_date_time =
          "Select date and time for candidate expiration date!";
        return false;
      }
      this.modal_loading = true;
      const res = await this.callApi(
        "post",
        `/app/admin/election/update/${this.editData.id}`,
        this.editData
      );
      if (res.status == 200) {
        this.allElections.data[this.editIndex] = _.clone(this.editData);
        this.clearData();
      } else {
        this.nswr();
      }
      this.clearData();
      this.closeModal();
    },

    async deleteElection(index) {
      this.deleteLoading = true;
      this.deleteIndex = index;
      const res = await this.callApi(
        "post",
        `/app/admin/election/delete/${this.allElections.data[index].id}`
      );
      if (res.status == 200) {
        this.allElections.data.splice(index, 1);
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
        `/app/admin/election/paginate/all?page=${this.page}`
      );
      if (res.status == 200) {
        this.allElections = res.data;
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