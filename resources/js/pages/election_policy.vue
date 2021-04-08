<template>
  <div class="container-fluid">
    <div class="row">
      <div class="col-12 col-md-12">
        <div class="card">
          <div class="card-header card-header-tabs card-header-primary">
            <div class="nav-tabs-navigation">
              <div class="nav-tabs-wrapper">
                <h4 class="card-title">Election Policty</h4>
              </div>
            </div>
          </div>
          <div v-if="allElectionPolicies" class="card-body table-responsive">
            <table class="table table-hover">
              <thead class="text-warning">
                <th>Policy</th>
                <th>Action</th>
              </thead>
              <tbody>
                <tr
                  v-if="allElectionPolicies.length > 0 && !tableLoading"
                  v-for="(item, index) in allElectionPolicies"
                  :key="'ad' + index"
                >
                  <td>{{ item.policy ? item.policy : "N/A" }}</td>

                  <td>
                    <div
                      @click="openEditModal(index)"
                      :disabled="deleteLoading && deleteIndex == index"
                      class="btn btn-default"
                    >
                      Edit
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
            <div class="d-flex justify-content-center" v-if="tableLoading">
              <Loader />
            </div>
            <div
              class="d-flex justify-content-center"
              v-if="!tableLoading && allElectionPolicies.length == 0"
            >
              <p>No data</p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- edit modal -->
    <Modal
      title="Edit Election Policy"
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
              <Input type="textarea" v-model="editData.policy"></Input>
              <p v-if="error.policy" class="text-danger">
                <Icon type="md-alert" /> {{ error.policy }}
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
export default {
  data() {
    return {
      allElectionPolicies: [],
      tableLoading: true,
      editModal: false,
      modal_loading: false,

      electionCommision: {
        policy: "",
      },
      error: {
        policy: false,
      },
      editIndex: -1,
      editData: {},
      page: 1,
      allUsers: [],
    };
  },
  methods: {
    clearData() {
      this.electionCommision = {
        policy: "",
      };
      this.editIndex = -1;
      this.editData = {};
    },

    clearErrorData() {
      this.error = {
        policy: false,
      };
    },

    openEditModal(index) {
      this.clearErrorData();
      this.editModal = true;
      this.editData = _.clone(this.allElectionPolicies[index]);
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

    async editElectionCommission() {
      if (!this.editData.policy || this.editData.policy.trim() == "") {
        this.error.policy = "Policy is required!";
        return false;
      }
      this.modal_loading = true;
      const res = await this.callApi(
        "post",
        `/app/admin/election/policy/edit`,
        this.editData
      );
      if (res.status == 200) {
        this.allElectionPolicies[0] = _.clone(this.editData);
        this.clearData();
      } else {
        this.nswr();
      }
      this.clearData();
      this.closeModal();
    },

    async paginate() {
      this.tableLoading = true;
      const res = await this.callApi("get", `/app/admin/election/policy/get`);
      if (res.status == 200) {
        this.allElectionPolicies = res.data;
      } else {
        this.nswr();
      }
      this.tableLoading = false;
    },
  },

  async created() {
    this.paginate();
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