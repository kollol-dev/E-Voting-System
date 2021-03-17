<template>
  <div class="container-fluid">
    <div class="row">
      <div class="col-12 col-md-12">
        <div class="card">
          <div class="card-header card-header-tabs card-header-primary">
            <div class="nav-tabs-navigation">
              <div class="nav-tabs-wrapper">
                <h4 class="card-title">Election Candidates</h4>
                <ul class="nav nav-tabs" data-tabs="tabs">
                  <li class="nav-item">
                    <a class="nav-link active" @click="createModal = true">
                      <i class="material-icons">source</i> Add Election
                      Candidate
                      <div class="ripple-container"></div>
                    </a>
                  </li>
                </ul>
              </div>
            </div>
          </div>
          <div v-if="allElectionCandidates" class="card-body table-responsive">
            <table class="table table-hover">
              <thead class="text-warning">
                <th>ID</th>
                <th>Name</th>
                <th>Election</th>
                <th>Post</th>
                <th>Action</th>
              </thead>
              <tbody>
                <tr
                  v-if="allElectionCandidates.total > 0 && !tableLoading"
                  v-for="(item, index) in allElectionCandidates.data"
                  :key="'ad' + index"
                >
                  <td>{{ item.id }}</td>
                  <td>{{ item.user ? item.user.name : "N/A" }}</td>
                  <td>{{ item.election ? item.election.name : "N/A" }}</td>
                  <td>
                    {{ item.post ? item.post.name : "N/A" }}
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
                      @on-ok="deleteElectionCandidate(index)"
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
              v-if="!tableLoading && allElectionCandidates.total == 0"
            >
              <p>No data</p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- create modal -->
    <Modal
      title="Add Election Candidate"
      v-model="createModal"
      :footer-hide="true"
      :mask-closable="false"
      :closable="false"
    >
      <form v-on:submit.prevent="addElectionCandidate">
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label class="bmd-label-floating">Election</label>
              <Select
                filterable
                v-model="candidate.election_id"
                @on-change="getNominatedCandidates(candidate.election_id)"
              >
                <Option
                  v-for="(item, index) in allElections"
                  :key="'ec' + index"
                  :value="item.id"
                  >{{ item.name }}</Option
                >
              </Select>
              <p v-if="error.election_id" class="text-danger">
                <Icon type="md-alert" /> {{ error.election_id }}
              </p>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label class="bmd-label-floating d-block">Symbol</label>
              <div v-if="uploadFile" class="demo-upload-list">
                <template v-if="uploadFile.status === 'finished'">
                  <img :src="uploadFile.response.url" />
                  <div class="demo-upload-list-cover">
                    <Icon
                      type="ios-trash-outline"
                      @click.native="handleRemove()"
                    ></Icon>
                  </div>
                </template>
                <template v-else>
                  <Progress
                    v-if="uploadFile.showProgress"
                    :percent="uploadFile.percentage"
                    hide-info
                  ></Progress>
                </template>
              </div>
              <Upload
                v-else
                :show-upload-list="false"
                :on-progress="handleProgress"
                :format="['jpg', 'jpeg', 'png']"
                :max-size="20480"
                :on-format-error="handleFormatError"
                :on-exceeded-size="handleMaxSize"
                type="drag"
                name="file"
                action="/app/upload/file"
                style="display: inline-block; width: 58px"
              >
                <div style="width: 58px; height: 58px; line-height: 58px">
                  <Icon type="ios-camera" size="20"></Icon>
                </div>
              </Upload>
              <p v-if="error.symbol" class="text-danger">
                <Icon type="md-alert" /> {{ error.symbol }}
              </p>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label class="bmd-label-floating">User</label>
              <Select v-model="candidate.user_id" filterable>
                <Option
                  v-for="(item, index) in allNonCandidates"
                  :key="'nc' + index"
                  :value="item.id"
                  >{{ item.name }}</Option
                >
              </Select>
              <p v-if="error.user_id" class="text-danger">
                <Icon type="md-alert" /> {{ error.user_id }}
              </p>
            </div>
          </div>
          <div class="col-md-6 mb-5">
            <div class="form-group">
              <label class="bmd-label-floating">Election Post</label>
              <Select v-model="candidate.election_post_id" filterable>
                <Option
                  v-for="(item, index) in allPosts"
                  :key="'ps' + index"
                  :value="item.id"
                  >{{ item.name }}</Option
                >
              </Select>
              <p v-if="error.election_post_id" class="text-danger">
                <Icon type="md-alert" /> {{ error.election_post_id }}
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
      title="Edit Election Candidate"
      v-model="editModal"
      :footer-hide="true"
      :mask-closable="false"
      :closable="false"
    >
      <form v-on:submit.prevent>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label class="bmd-label-floating">User</label>
              <Select v-model="editData.user_id" filterable>
                <Option
                  v-for="(item, index) in allAlumnies"
                  :key="'nc' + index"
                  :value="item.id"
                >
                  {{ item.name }}</Option>
              </Select>
              <p v-if="error.user_id" class="text-danger">
                <Icon type="md-alert" /> {{ error.user_id }}
              </p>
            </div>
          </div>
          <div class="col-md-6">
            <!-- <div class="form-group">
              <label class="bmd-label-floating d-block">Symbol</label>
              <div v-if="uploadFile" class="demo-upload-list">
                <template v-if="uploadFile.status === 'finished'">
                  <img :src="uploadFile.response.url" />
                  <div class="demo-upload-list-cover">
                    <Icon
                      type="ios-trash-outline"
                      @click.native="handleRemove(item)"
                    ></Icon>
                  </div>
                </template>
                <template v-else>
                  <Progress
                    v-if="uploadFile.showProgress"
                    :percent="uploadFile.percentage"
                    hide-info
                  ></Progress>
                </template>
              </div>
              <Upload
                v-else
                :show-upload-list="false"
                :on-progress="handleProgress"
                :format="['jpg', 'jpeg', 'png']"
                :max-size="20480"
                :on-format-error="handleFormatError"
                :on-exceeded-size="handleMaxSize"
                type="drag"
                name="file"
                action="/app/upload/file"
                style="display: inline-block; width: 58px"
              >
                <div style="width: 58px; height: 58px; line-height: 58px">
                  <Icon type="ios-camera" size="20"></Icon>
                </div>
              </Upload>
              <p v-if="error.symbol" class="text-danger">
                <Icon type="md-alert" /> {{ error.symbol }}
              </p>
            </div> -->
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label class="bmd-label-floating">Election</label>
              <Select v-model="editData.election_id" filterable>
                <Option
                  v-for="(item, index) in allElections"
                  :key="'ec' + index"
                  :value="item.id"
                >{{ item.name }}
                </Option>
              </Select>
              <p v-if="error.election_id" class="text-danger">
                <Icon type="md-alert" /> {{ error.election_id }}
              </p>
            </div>
          </div>
          <div class="col-md-6 mb-5">
            <div class="form-group">
              <label class="bmd-label-floating">Election Post</label>
              <Select v-model="editData.election_post_id" filterable>
                <Option
                  v-for="(item, index) in allPosts"
                  :key="'ps' + index"
                  :value="item.id"
                >{{ item.name }}</Option>
              </Select>
              <p v-if="error.election_post_id" class="text-danger">
                <Icon type="md-alert" /> {{ error.election_post_id }}
              </p>
            </div>
          </div>
        </div>
        <button
          @click="editElectionCandidate"
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
      allElectionCandidates: [],
      allElections: [],
      allPosts: [],
      allNonCandidates: [],
      allAlumnies: [],
      tableLoading: true,
      createModal: false,
      editModal: false,
      modal_loading: false,

      candidate: {
        election_id: null,
        election_post_id: null,
        user_id: null,
        symbol: "",
      },
      error: {
        election_id: false,
        election_post_id: false,
        user_id: false,
        symbol: false,
      },
      editIndex: -1,
      deleteIndex: -1,
      deleteLoading: false,
      editData: {},
      page: 1,
      uploadFile: false,
    };
  },

  computed: {
    ...mapGetters({}),
  },

  methods: {
    clearData() {
      this.candidate = {
        election_id: null,
        election_post_id: null,
        user_id: null,
        symbol: "",
      };
      this.editIndex = -1;
      this.editData = {};
    },

    clearErrorData() {
      this.error = {
        election_id: false,
        election_post_id: false,
        user_id: false,
        symbol: false,
      };
    },

    openEditModal(index) {
      this.clearErrorData();
      this.editModal = true;
      this.editData = _.clone(this.allElectionCandidates.data[index]);
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

    async addElectionCandidate() {
      this.clearErrorData();
      if (this.candidate.user_id == null) {
        this.error.user_id = "Select an user!";
        return false;
      }
      if (!this.uploadFile) {
        this.error.symbol = "Upload a symbol!";
        return false;
      }

      if (this.candidate.election_id == null) {
        this.error.election_id = "Select a election!";
        return false;
      }
      if (this.candidate.election_post_id == null) {
        this.error.election_post_id = "Select a post!";
        return false;
      }
      this.modal_loading = true;
      this.candidate.symbol = this.uploadFile.response.url;
      const res = await this.callApi(
        "post",
        "/app/admin/election/candidate/add/new",
        this.candidate
      );
      if (res.status == 201) {
        let userIndex = this.allNonCandidates.findIndex(
          (i) => i.id == this.candidate.user_id
        );
        let electionIndex = this.allElections.findIndex(
          (i) => i.id == this.candidate.election_id
        );
        let electionPostIndex = this.allPosts.findIndex(
          (i) => i.id == this.candidate.election_post_id
        );

        res.data.user = this.allNonCandidates[userIndex];
        res.data.election = this.allElections[electionIndex];
        res.data.post = this.allPosts[electionPostIndex];

        this.allElectionCandidates.data.push(res.data);
        this.allElectionCandidates.total += 1;
        this.clearData();
      } else if (res.status == 401 && res.data.name) {
        for (let i of res.data.name) this.e(i);
      } else if (res.status == 401 && res.data.election_id) {
        for (let i of res.data.election_id) this.e(i);
      } else {
        this.nswr();
      }
      this.modal_loading = false;
      this.closeModal();
    },

    async editElectionCandidate() {
      if (!this.editData.user_id || this.editData.user_id == null) {
        this.error.user_id = "Select an user!";
        return false;
      }

      if (!this.editData.election_id || this.editData.election_id == null) {
        this.error.election_id = "Select a election!";
        return false;
      }
      if (
        !this.editData.election_post_id ||
        this.editData.election_post_id == null
      ) {
        this.error.election_post_id = "Select a post!";
        return false;
      }
      this.modal_loading = true;
      const res = await this.callApi(
        "post",
        `/app/admin/election/candidate/update/${this.editData.id}`,
        this.editData
      );
      if (res.status == 200) {
        this.allElectionCandidates.data[this.editIndex] = _.clone(
          this.editData
        );
        this.clearData();
      } else {
        this.nswr();
      }
      this.clearData();
      this.closeModal();
    },

    async deleteElectionCandidate(index) {
      this.deleteLoading = true;
      this.deleteIndex = index;
      const res = await this.callApi(
        "post",
        `/app/admin/election/candidate/delete/${this.allElectionCandidates.data[index].id}`
      );
      if (res.status == 200) {
        this.allElectionCandidates.data.splice(index, 1);
        this.allElectionCandidates.total -= 1;
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
        `/app/admin/election/candidate/paginate/all?page=${this.page}`
      );
      if (res.status == 200) {
        this.allElectionCandidates = res.data;
      } else {
        this.nswr();
      }
      this.tableLoading = false;
    },

    handleRemove() {
      this.uploadFile = false;
    },

    handleProgress(res, file) {
      this.uploadFile = file;
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
        desc: "File  " + file.name + " is too large, no more than 20M.",
      });
    },

    async getNominatedCandidates(id) {
      this.allNonCandidates = [];
      this.allPosts = [];
      const [res, post] = await Promise.all([
        this.callApi("get", `/app/admin/election/cadidate/check/user/${id}`),
        this.callApi("get", `/app/admin/election/posts/election/${id}`),
      ]);
      if (res.status == 200 && post.status == 200) {
        this.allNonCandidates = res.data;
        this.allPosts = post.data;
      } else {
        this.nswr();
      }
    },
  },

  async created() {
    this.paginate(1);

    const [res, allAlumnies] = await Promise.all([
      this.callApi("get", "/app/admin/election/get/all"),
      this.callApi("get", "/app/admin/user/get/alumni"),
    ]);
    if (res.status == 200 && allAlumnies.status == 200) {
      this.allElections = res.data;
      this.allAlumnies = allAlumnies.data;
    } else {
      this.nswr();
    }
  },
};
</script>