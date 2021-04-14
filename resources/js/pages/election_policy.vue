<template>
  <div class="container-fluid">
    <div class="row">
      <div class="col-12 col-md-12">
        <div class="card">
          <div class="card-header card-header-tabs card-header-primary">
            <div class="nav-tabs-navigation">
              <div class="nav-tabs-wrapper">
                <h4 class="card-title">Election Policy</h4>
                <ul
                  v-if="
                    electionCommision.policy != '' && authUser.role == 'admin'
                  "
                  class="nav nav-tabs"
                  data-tabs="tabs"
                >
                  <li class="nav-item">
                    <a class="nav-link active" @click="openEditModal">
                      <i class="material-icons">source</i> Add Election Policy
                      <div class="ripple-container"></div>
                    </a>
                  </li>
                </ul>
              </div>
            </div>
          </div>
          <div v-if="allElectionPolicies" class="card-body table-responsive">
            <table class="table table-hover">
              <thead class="text-warning">
                <th>Policy</th>
                <th v-if="authUser.role == 'Admin'">Action</th>
              </thead>
              <tbody>
                <tr
                  v-if="allElectionPolicies.length > 0 && !tableLoading"
                  v-for="(item, index) in allElectionPolicies"
                  :key="'ad' + index"
                >
                  <td>
                    <img v-if="item.policy" :src="item.policy" alt="" />
                  </td>

                  <td v-if="authUser.role == 'admin'">
                    <div @click="openEditModal" class="btn btn-default">
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

    <!-- create modal -->
    <Modal
      title="Update Election Policy"
      v-model="createModal"
      :footer-hide="true"
      :mask-closable="false"
      :closable="false"
    >
      <form v-on:submit.prevent>
        <div class="row">
          <div class="col-12">
            <div class="form-group">
              <label class="bmd-label-floating">Policy</label>
              <Upload
                ref="upload"
                multiple
                type="drag"
                action="/app/upload/file"
                :on-success="handleSuccess"
                :format="['jpg', 'jpeg', 'png']"
                :max-size="20480"
                :on-format-error="handleFormatError"
                :on-exceeded-size="handleMaxSize"
              >
                <div style="padding: 20px 0">
                  <Icon
                    type="ios-cloud-upload"
                    size="52"
                    style="color: #3399ff"
                  ></Icon>
                  <p>Click or drag files here to upload</p>
                </div>
              </Upload>
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
            <p>Update</p>
          </template>
        </button>
        <button @click="closeModal" class="btn btn-default pull-right">
          <p>Cancel</p>
        </button>
        <div class="clearfix"></div>
      </form>
    </Modal>
    <!-- create modal end  -->
  </div>
</template>
<script>
export default {
  data() {
    return {
      allElectionPolicies: [],
      tableLoading: true,
      createModal: false,
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

      uploadFile: false,
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

    openEditModal() {
      this.clearErrorData();
      this.createModal = true;
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
      this.$refs.upload.clearFiles();
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

    handleRemove() {
      this.electionCommision.policy = "";
      this.$refs.upload.clearFiles();
    },

    handleSuccess(res, file) {
      this.editData.policy = res.url;
    },

    handleFormatError(file) {
      this.$Notice.warning({
        title: "The file format is incorrect",
        desc:
          "File format of " +
          file.name +
          " is incorrect, please select jpg or png.",
      });
    },

    handleMaxSize(file) {
      this.$Notice.warning({
        title: "Exceeding file size limit",
        desc: "File  " + file.name + " is too large, no more than 2M.",
      });
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