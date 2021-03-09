<template>
  <div class="container-fluid">
    <div class="row">
      <div class="col-12 col-md-12">
        <div class="card">
          <div class="card-header card-header-tabs card-header-primary">
            <div class="nav-tabs-navigation">
              <div class="nav-tabs-wrapper">
                <h4 class="card-title">Election Post</h4>
                <ul class="nav nav-tabs" data-tabs="tabs">
                  <li class="nav-item">
                    <a class="nav-link active" @click="createModal = true">
                      <i class="material-icons">source</i> Add Election Post
                      <div class="ripple-container"></div>
                    </a>
                  </li>
                </ul>
              </div>
            </div>
          </div>
          <div v-if="allElectionPosts" class="card-body table-responsive">
            <table class="table table-hover">
              <thead class="text-warning">
                <th>ID</th>
                <th>Name</th>
                <th>Election</th>
                <th>Action</th>
              </thead>
              <tbody>
                <tr
                  v-if="allElectionPosts.total > 0 && !tableLoading"
                  v-for="(item, index) in allElectionPosts.data"
                  :key="'ad' + index"
                >
                  <td>{{ item.id }}</td>
                  <td>{{ item.name }}</td>
                  <td>{{ item.election ? item.election.name : "N/A" }}</td>
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
                      @on-ok="deleteElectionPost(index)"
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
              v-if="!tableLoading && allElectionPosts.total == 0"
            >
              <p>No data</p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- create modal -->
    <Modal
      title="Add Election Post"
      v-model="createModal"
      :footer-hide="true"
      :mask-closable="false"
      :closable="false"
    >
      <form v-on:submit.prevent="addElectionPost">
        <div class="row">
          <div class="col-12">
            <div class="form-group">
              <label class="bmd-label-floating">Name</label>
              <input v-model="post.name" type="text" class="form-control" />
              <p v-if="error.name" class="text-danger">
                <Icon type="md-alert" /> {{ error.name }}
              </p>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label class="bmd-label-floating">Election</label>
              <Select v-model="post.election_id">
                <Option
                  v-for="(item, index) in allElections"
                  :key="'ps' + index"
                  :value="item.id"
                >
                  {{ item.name }}
                </Option>
              </Select>
              <p v-if="error.election_id" class="text-danger">
                <Icon type="md-alert" /> {{ error.election_id }}
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
      title="Edit Election Post"
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
              <label class="">Election</label>
              <Select v-model="editData.election_id">
                <Option
                  v-for="(item, index) in allElections"
                  :key="'ps' + index"
                  :value="item.id"
                >
                  {{ item.name }}
                </Option>
              </Select>
              <p v-if="error.election_id" class="text-danger">
                <Icon type="md-alert" /> {{ error.election_id }}
              </p>
            </div>
          </div>
        </div>
        <button
          @click="editElectionPost"
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
      allElectionPosts: [],
      allElections: [],
      tableLoading: true,
      createModal: false,
      editModal: false,
      modal_loading: false,

      post: {
        name: "",
        election_id: null,
      },
      error: {
        name: false,
        election_id: false,
      },
      editIndex: -1,
      deleteIndex: -1,
      deleteLoading: false,
      editData: {},
      page: 1,
    };
  },
  computed: {
    ...mapGetters({}),
  },
  methods: {
    clearData() {
      this.post = {
        name: "",
        election_id: null,
      };
      this.editIndex = -1;
      this.editData = {};
    },

    clearErrorData() {
      this.error = {
        name: false,
        election_id: false,
      };
    },

    openEditModal(index) {
      this.clearErrorData();
      this.editModal = true;
      this.editData = _.clone(this.allElectionPosts.data[index]);
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

    async addElectionPost() {
      this.clearErrorData();
      if (this.post.name.trim() == "") {
        this.error.name = "Name is required!";
        return false;
      }

      if (this.post.election_id == null) {
        this.error.election_id = "Select a election!";
        return false;
      }
      this.modal_loading = true;
      const res = await this.callApi(
        "post",
        "/app/admin/election/posts/add/new",
        this.post
      );
      if (res.status == 201) {
        let index = this.allElections.findIndex(
          (i) => i.id == this.post.election_id
        );

        res.data.election = this.allElections[index];

        this.allElectionPosts.data.push(res.data);
        this.allElectionPosts.total += 1;
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

    async editElectionPost() {
      if (!this.editData.name || this.editData.name.trim() == "") {
        this.error.name = "Name is required!";
        return false;
      }

      if (!this.editData.election_id || this.editData.election_id == "") {
        this.error.election_id = "Select a election!";
        return false;
      }
      this.modal_loading = true;
      const res = await this.callApi(
        "post",
        `/app/admin/election/posts/update/${this.editData.id}`,
        this.editData
      );
      if (res.status == 200) {
        this.allElectionPosts.data[this.editIndex] = _.clone(this.editData);
        this.clearData();
      } else {
        this.nswr();
      }
      this.clearData();
      this.closeModal();
    },

    async deleteElectionPost(index) {
      this.deleteLoading = true;
      this.deleteIndex = index;
      const res = await this.callApi(
        "post",
        `/app/admin/election/posts/delete/${this.allElectionPosts.data[index].id}`
      );
      if (res.status == 200) {
        this.allElectionPosts.data.splice(index, 1);
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
        `/app/admin/election/posts/paginate/all?page=${this.page}`
      );
      if (res.status == 200) {
        this.allElectionPosts = res.data;
      } else {
        this.nswr();
      }
      this.tableLoading = false;
    },
  },
  async created() {
    this.paginate(1);

    const res = await this.callApi("get", "/app/admin/election/get/all");
    if (res.status == 200) {
      this.allElections = res.data;
    } else {
      this.swr();
    }
  },
};
</script>