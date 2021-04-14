<template>
  <div class="container-fluid">
    <div class="row">
      <div class="col-12 col-md-12">
        <div class="card">
          <div class="card-header card-header-tabs card-header-primary">
            <div class="nav-tabs-navigation">
              <div class="nav-tabs-wrapper">
                <h4 class="card-title">Elections</h4>
              </div>
            </div>
          </div>
          <div v-if="allElections" class="card-body table-responsive">
            <table class="table table-hover">
              <thead class="text-warning">
                <th>ID</th>
                <th>Name</th>
                <th>Date</th>
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
                    <div
                      @click="
                        $router.push(`/alumni/elections/posts/${item.id}`)
                      "
                      class="btn btn-light"
                    >
                      See Posts
                    </div>
                    <div @click="openModal(item)" class="btn btn-primary">
                      Cast Vote
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
              v-if="!tableLoading && allElections.total == 0"
            >
              <p>No data</p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <Modal title="Election Details" v-model="castModal" :footer-hide="true">
      <form
        v-on:submit.prevent="castVote(electionOverview)"
        v-if="!isLoading && electionOverview"
      >
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
              <span> {{ electionOverview.date_and_time | formatDate }}</span>
              <p
                :style="
                  electionOverview.isEnded == 'yes'
                    ? 'color:red'
                    : 'color:green'
                "
              >
                {{ electionOverview.isEnded == "yes" ? "Ended" : "Running" }}
              </p>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-12">
            <div class="form-group">
              <label class="bmd-label-floating">Posts:</label>
              <span
                v-if="
                  electionOverview.posts && electionOverview.posts.length > 0
                "
              >
                <div
                  v-for="(item, index) in electionOverview.posts"
                  :key="'psts' + index"
                >
                  <p>
                    {{ item.name }}
                  </p>
                  <div v-if="item.candidates" class="mt-2">
                    <span
                      v-for="(img, pindex) in item.candidates"
                      :key="'pst' + pindex"
                      :style="' margin: 5px; margin-top: 10px;'"
                    >
                      <img
                        @click="selectIndex(item, pindex)"
                        :src="img.symbol"
                        alt=""
                        :style="
                          pindex == item.selectedIndex
                            ? 'border:2px solid rgb(201 58 243); width: 50px; height: 50px; cursor: pointer;'
                            : 'width: 50px; height: 50px; cursor: pointer;'
                        "
                      />
                      <span v-if="img.user">{{ img.user.name }}</span>
                    </span>
                  </div>
                  <p v-if="item.winner">winner: {{ item.winner.name }}</p>
                  <div
                    v-if="index < electionOverview.posts.length - 1"
                    class="dropdown-divider"
                  ></div>
                </div>
              </span>
            </div>
          </div>
          <!-- <div class="col-6">
            <div class="form-group">
              <label class="bmd-label-floating">Total Vote:</label>
              <span>{{ electionOverview.total_votes_count }}</span>
            </div>
          </div> -->
        </div>
        <button
          v-if="
            !electionOverview.is_voted || electionOverview.is_voted.length == 0
          "
          type="submit"
          class="btn btn-primary pull-right d-flex align-items-center"
          :disabled="modal_loading"
        >
          <template v-if="modal_loading">
            <Loader position="inline" color="white" />
          </template>
          <template v-else>
            <p>Cast Vote</p>
          </template>
        </button>
        <template v-else-if="electionOverview.isEnded == 'yes'">
          <p style="color: red">This election has ended!</p>
        </template>
        <template v-else>
          <p style="color: green">You have already voted!</p>
        </template>
        <div class="clearfix"></div>
      </form>
    </Modal>
  </div>
</template>
<script>
export default {
  data() {
    return {
      allElections: [],
      tableLoading: true,
      castModal: false,
      electionOverview: false,
      isLoading: false,
      modal_loading: false,
    };
  },

  methods: {
    openModal(item) {
      this.castModal = true;
      this.electionOverview = item;
    },

    async castVote(item) {
      let count = 0;

      for (let i of item.posts) {
        console.log("i", i);
        if (i.selectedIndex > -1) {
          count += 1;
        }
      }

      if (count < item.posts.length) {
        return this.e("Please select one candidate symbol of all posts");
      }

      this.modal_loading = true;

      let array = [];
      for (let i of item.posts) {
        array.push(i.candidates[i.selectedIndex].id);
      }
      item.candidateIds = array;
      const res = await this.callApi(
        "post",
        `/app/alumni/election/cast-vote/${item.id}`,
        item
        // { post_id: this.allElectionCandidates[index].post.id }
      );

      if (res.status == 200) {
        this.$router.go();
        this.ns("Your vote has been casted succussfully!");
      } else {
        this.nswr();
      }
      this.modal_loading = false;
    },

    selectIndex(item, index) {
      let array = _.clone(this.allElections.data);
      for (let i of array) {
        if (i.id == item.election_id) {
          for (let j of i.posts) {
            if (j.id == item.id) {
              j.selectedIndex = index;
            }
          }
        }
      }
      this.$set(this.allElections, "data", array);
    },

    async paginate(page) {
      this.tableLoading = true;
      const res = await this.callApi(
        "get",
        `/app/alumni/election/paginate/all?page=${page}`
      );
      if (res.status == 200) {
        this.allElections = res.data;
        for (let i of this.allElections.data) {
          for (let j of i.posts) {
            j.selectedIndex = -1;
          }

          i.isEnded = "no";
          if (new Date().getTime() > new Date(i.date_and_time).getTime()) {
            i.isEnded = "yes";
          }
        }
      } else {
        this.nswr();
      }
      this.tableLoading = false;
    },
  },

  filters: {
    checkDate(value) {
      return new Date().getTime() > new Date(value).getTime()
        ? "Runnig"
        : "Ended";
    },
  },
  async created() {
    this.paginate(1);
  },
};
</script>
