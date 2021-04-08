<template>
  <div class="container-fluid">
    <div class="row">
      <div class="col-12 col-md-12">
        <div class="card">
          <div class="card-header card-header-tabs card-header-primary">
            <div class="nav-tabs-navigation">
              <div class="nav-tabs-wrapper">
                <h4 class="card-title">Election Posts</h4>
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
                <th>Action</th>
              </thead>
              <tbody>
                <tr
                  v-if="!tableLoading"
                  v-for="(item, index) in allElectionCandidates"
                  :key="'post' + index"
                >
                  <td>{{ item.id }}</td>
                  <td>
                    {{ item.name }}
                  </td>
                  <td>
                    <div
                      @click="
                        $router.push(`/alumni/elections/candidate/${item.id}`)
                      "
                      class="btn btn-primary"
                    >
                      See Candidates
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
              v-if="!tableLoading && allElectionCandidates.total == 0"
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
    };
  },

  methods: {
    async paginate(id) {
      this.tableLoading = true;
      const res = await this.callApi("get", `/app/alumni/election/posts/${id}`);
      if (res.status == 200) {
        this.allElectionCandidates = res.data;
      } else {
        this.nswr();
      }
      this.tableLoading = false;
    },
  },

  async created() {
    if (!this.$route.params.id) return (window.location = "/alumni/elections");
    this.paginate(this.$route.params.id);
  },
};
</script>