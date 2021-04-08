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
                  <li v-if="!isApplied" class="nav-item">
                    <a class="nav-link active" @click="applyForPost">
                      <i class="material-icons">source</i> Apply For This Post
                      <div class="ripple-container"></div>
                    </a>
                  </li>
                  <li v-else class="nav-item">
                    <a class="nav-link"> You have already applied. </a>
                  </li>
                </ul>
              </div>
            </div>
          </div>
          <div
            v-if="allElectionCandidates.length > 0"
            class="card-body table-responsive"
          >
            <table class="table table-hover">
              <thead class="text-warning">
                <th>ID</th>
                <th>Name</th>
                <th>Symbol</th>
                <th>Election</th>
                <th>Post</th>
                <!-- <th>Action</th> -->
              </thead>
              <tbody>
                <tr
                  v-if="!tableLoading"
                  v-for="(item, index) in allElectionCandidates"
                  :key="'ad' + index"
                >
                  <td>{{ item.id }}</td>
                  <td>
                    {{ item.user ? item.user.name : "N/A" }}
                  </td>
                  <td>
                    <img
                      style="width: 30px; height: 30px"
                      :src="item.symbol"
                      alt=""
                    />
                  </td>
                  <td>{{ item.election ? item.election.name : "N/A" }}</td>
                  <td>
                    {{ item.post ? item.post.name : "N/A" }}
                  </td>
                  <!-- <td>
                    <div
                      v-if="!isVoteCasted"
                      @click="castVote(index)"
                      :disabled="btnLoading && btnIndex == index"
                      class="btn btn-primary"
                    >
                      <Loader
                        color="#fff"
                        position="inline"
                        v-if="btnLoading && btnIndex == index"
                      />
                      <p v-else>Cast</p>
                    </div>
                    <p v-else>Your vote has been casted already</p>
                  </td> -->
                </tr>
              </tbody>
            </table>
            <div class="d-flex justify-content-center" v-if="tableLoading">
              <Loader />
            </div>
            <div
              class="d-flex justify-content-center"
              v-if="!tableLoading && allElectionCandidates.length == 0"
            >
              <p>No data</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import { mapGetters } from "vuex";
export default {
  data() {
    return {
      allElectionCandidates: [],
      tableLoading: true,
      isVoteCasted: false,
      btnLoading: false,
      btnIndex: -1,
      isApplied: false,
    };
  },

  methods: {
    async paginate(id) {
      this.tableLoading = true;
      const res = await this.callApi(
        "get",
        `/app/alumni/election/candidate/${id}`
      );
      if (res.status == 200) {
        this.allElectionCandidates = res.data;
      } else {
        this.nswr();
      }
      this.tableLoading = false;
    },

    async castVote(index) {
      this.btnLoading = true;
      this.btnIndex = index;
      const res = await this.callApi(
        "post",
        `/app/alumni/election/cast-vote/${this.$route.params.id}`,
        { post_id: this.allElectionCandidates[index].post.id }
      );

      if (res.status == 201) {
        this.isVoteCasted = true;
        this.ns("Your vote has been casted succussfully!");
      } else {
        this.nswr();
      }
      this.btnLoading = false;
      this.btnIndex = -1;
    },

    async applyForPost() {
      if (this.isApplied) return;
      this.btnLoading = true;
      const res = await this.callApi(
        "post",
        `/app/alumni/election/candidate/apply/${this.$route.params.id}`
      );
      if ((res.status = 200)) {
        this.isApplied = true;
      }
      this.btnLoading = false;
    },
  },

  async created() {
    if (!this.$route.params.id) return (window.location = "/alumni/elections");
    this.paginate(this.$route.params.id);

    const res = await this.callApi(
      "get",
      `/app/alumni/election/vote-check/${this.$route.params.id}`
    );

    if (res.status == 200) {
      if (res.data.is_vote_casted) {
        this.isVoteCasted = true;
      }
      this.isApplied = res.data.isApplied;
    }
  },
};
</script>