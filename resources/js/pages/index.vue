<template>
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-3">
        <label class="bmd-label">Select Election</label>
        <Select
          class="mb-3"
          v-model="election_id"
          @on-change="getElectionOverview"
        >
          <Option
            v-for="(item, index) in allElections"
            :key="'ec' + index"
            :value="item.id"
            >{{ item.name }}
          </Option>
        </Select>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header card-header-primary">
            <h4 class="card-title">Election Overview</h4>
          </div>

          <div class="card-body">
            <form v-if="!isLoading && electionOverview">
              <div class="row">
                <div class="col-6">
                  <div class="form-group">
                    <label class="bmd-label-floating">Name:</label>
                    <span> {{ electionOverview.name }}</span>
                  </div>
                </div>
                <div class="col-6">
                  <div class="form-group">
                    <label class="bmd-label-floating">Date:</label>
                    <span>
                      {{ electionOverview.date_and_time | formatDate }}</span
                    >
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-6">
                  <div class="form-group">
                    <label class="bmd-label-floating">Posts:</label>
                    <!-- <span>{{ electionOverview.mobile }}</span> -->
                    <span
                      v-if="
                        electionOverview.posts &&
                        electionOverview.posts.length > 0
                      "
                    >
                      <div
                        v-for="(item, index) in electionOverview.posts"
                        :key="'psts' + index"
                      >
                        <p>
                          {{ item.name }}
                        </p>
                        <p v-if="item.winner">winner: {{ item.winner.name }}</p>
                        <div
                          v-if="index < electionOverview.posts.length - 1"
                          class="dropdown-divider"
                        ></div>
                      </div>
                    </span>
                  </div>
                </div>
                <div class="col-6">
                  <div class="form-group">
                    <label class="bmd-label-floating">Total Vote:</label>
                    <span>{{ electionOverview.total_votes_count }}</span>
                  </div>
                </div>
              </div>

              <div class="clearfix"></div>
            </form>

            <div v-else class="text-center d-flex justify-content-center">
              <Loader v-if="isLoading && !electionOverview"></Loader>
              <p v-else>Select a election to see result.</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      allElections: [],
      electionOverview: false,
      isLoading: false,
      election_id: null,
    };
  },

  methods: {
    async getElectionOverview() {
      this.isLoading = true;
      const res = await this.callApi(
        "get",
        `app/user/get/overview/${this.election_id}`
      );
      if (res.status == 200) {
        this.electionOverview = res.data;
      }
      this.isLoading = false;
    },
  },

  async created() {
    const res = await this.callApi("get", "/app/user/get/elections");
    if (res.status == 200) {
      this.allElections = res.data;
    }
  },
};
</script>
